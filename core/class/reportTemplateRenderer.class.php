<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/../php/core.inc.php';

class reportTemplateRenderer {
    public const UNAVAILABLE = 69;
    public const TIMEOUT = 124;

    private const INVALID_LAYOUT = 65;
    private const MAX_DATA_BYTES = 8 * 1024 * 1024;
    private const MAX_PDF_BYTES = 25 * 1024 * 1024;
    private const MAX_HISTORY_READS = 64;
    private const MAX_IMAGE_BYTES = 5 * 1024 * 1024;

    /** @var array<int, cmd> */
    private array $commands = [];
    /** @var array<int, string|null> */
    private array $values = [];
    private array $histories = [];

    private function __construct(private user $user, private DateTimeImmutable $now) {
    }

    /** @return resource Flux PDF positionné au début. Son appelant en prend la responsabilité. */
    public static function render(reportTemplate $template, user $user) {
        if (!function_exists('proc_open') || !is_executable('/usr/bin/python3') || !is_executable('/usr/bin/timeout')) {
            throw new RuntimeException('Moteur PDF indisponible.', self::UNAVAILABLE);
        }
        $renderer = new self($user, new DateTimeImmutable());
        return self::writePdf($renderer->document($template));
    }

    private function document(reportTemplate $template): array {
        $layout = $template->getLayoutDocument();
        $this->loadCommands($layout->pages);
        $images = dirname(__DIR__, 2) . '/data/reportTemplates/' . $template->getId() . '/images';
        foreach ($layout->pages as $page) {
            foreach ($page->blocks as $block) {
                switch ($block->type) {
                    case 'value':
                        $block->data = $this->value($block->options);
                        break;
                    case 'chart':
                        $block->data = $this->chart($block->options, $page->id, $block->id);
                        break;
                    case 'image':
                        $path = $images . '/' . $block->options->imageId;
                        $size = realpath($path) === $path && is_file($path) ? filesize($path) : false;
                        $block->data = $size !== false && $size <= self::MAX_IMAGE_BYTES ? $path : null;
                        break;
                }
            }
        }
        return ['name' => $template->getName(), 'pages' => $layout->pages, 'histories' => (object) $this->histories];
    }

    private function loadCommands(array $pages): void {
        $ids = [];
        foreach ($pages as $page) {
            foreach ($page->blocks as $block) {
                if ($block->type === 'value') {
                    $ids[(int) $block->options->cmdId] = true;
                } elseif ($block->type === 'chart') {
                    foreach ($block->options->series as $entry) {
                        $ids[(int) $entry->cmdId] = true;
                    }
                }
            }
        }
        $commands = array_filter(cmd::byIds(array_keys($ids)) ?? [], static fn (cmd $cmd) => $cmd->getType() === 'info');
        $equipmentIds = array_unique(array_map(static fn (cmd $cmd) => $cmd->getEqLogic_id(), $commands));
        $equipment = [];
        foreach (eqLogic::byIds($equipmentIds) as $eqLogic) {
            $equipment[$eqLogic->getId()] = $eqLogic;
        }
        foreach ($commands as $command) {
            $eqLogic = $equipment[$command->getEqLogic_id()] ?? null;
            if ($eqLogic === null) {
                continue;
            }
            $command->setEqLogic($eqLogic);
            if ($command->hasRight($this->user)) {
                $this->commands[$command->getId()] = $command;
            }
        }
    }

    private function value(stdClass $options): array {
        $id = $options->cmdId;
        $command = $this->commands[$id] ?? null;
        if (!array_key_exists($id, $this->values)) {
            $value = $command?->execCmd();
            $text = is_bool($value) ? (string) (int) $value : (is_scalar($value) ? (string) $value : '');
            $this->values[$id] = $text === '' ? null : mb_strcut($text, 0, 4096, 'UTF-8');
        }
        return [
            'label' => ($options->label ?? '') !== '' ? $options->label : ($command?->getName() ?? '#' . $id),
            'value' => $this->values[$id],
            'unit' => ($options->showUnit ?? true) ? (string) $command?->getUnite() : '',
        ];
    }

    private function chart(stdClass $options, string $pageId, string $blockId): array {
        [$start, $end] = $this->period($options->period);
        if ($start >= $end) {
            throw new DomainException(sprintf('Page %s, bloc %s : période vide dans le fuseau de Jeedom.', $pageId, $blockId));
        }
        $unit = null;
        $series = [];
        foreach ($options->series as $entry) {
            $command = $this->commands[$entry->cmdId] ?? null;
            if ($command === null || $command->getSubType() !== 'numeric' || !(int) $command->getIsHistorized()) {
                continue;
            }
            $commandUnit = trim((string) $command->getUnite());
            if ($unit !== null && $commandUnit !== $unit) {
                throw new DomainException(sprintf('Page %s, bloc %s : les séries doivent utiliser la même unité.', $pageId, $blockId));
            }
            $unit = $commandUnit;
            $series[] = [
                'data' => $this->history($command, $start, $end),
                'label' => ($entry->label ?? '') !== '' ? $entry->label : $command->getName(),
                'color' => $entry->color ?? null,
            ];
        }

        $from = $start->getTimestamp();
        $to = $end->getTimestamp();
        $duration = $to - $from;
        $format = match (true) {
            $duration <= 90000 => 'H:i',
            $duration <= 366 * 86400 => 'd/m',
            default => 'm/Y',
        };
        $labels = [];
        for ($index = 0; $index <= 4; $index++) {
            $labels[] = $start->setTimestamp($from + intdiv($duration * $index, 4))->format($format);
        }
        return ['start' => $from, 'end' => $to, 'labels' => $labels, 'series' => $series, 'unit' => $unit ?? ''];
    }

    private function period(stdClass $period): array {
        $today = $this->now->setTime(0, 0);
        if ($period->type === 'fixed') {
            $zone = $today->getTimezone();
            return [new DateTimeImmutable($period->start, $zone), new DateTimeImmutable($period->end, $zone)];
        }
        $tomorrow = $today->modify('+1 day');
        $month = $today->modify('first day of this month');
        return match ($period->preset) {
            'today' => [$today, $tomorrow],
            'yesterday' => [$today->modify('-1 day'), $today],
            'last7Days' => [$today->modify('-6 days'), $tomorrow],
            'last30Days' => [$today->modify('-29 days'), $tomorrow],
            'thisMonth' => [$month, $month->modify('+1 month')],
            'previousMonth' => [$month->modify('-1 month'), $month],
        };
    }

    private function history(cmd $command, DateTimeImmutable $start, DateTimeImmutable $end): string {
        $key = $command->getId() . ':' . $start->getTimestamp() . ':' . $end->getTimestamp();
        if (isset($this->histories[$key])) {
            return $key;
        }
        if (count($this->histories) >= self::MAX_HISTORY_READS) {
            throw new DomainException('Le rapport utilise plus de 64 combinaisons de commandes et de périodes.');
        }
        $days = (int) $start->diff($end)->days;
        $grouping = match (true) {
            $days <= 1 => 'avg::minute',
            $days <= 60 => 'avg::hour',
            $days <= 730 => 'avg::day',
            $days <= 36525 => 'avg::month',
            default => 'avg::year',
        };
        $points = [];
        $lastSecond = $end->setTimestamp($end->getTimestamp() - 1);
        foreach ($command->getHistory($start->format('Y-m-d H:i:s'), $lastSecond->format('Y-m-d H:i:s'), $grouping) as $point) {
            $time = new DateTimeImmutable($point->getDatetime(), $start->getTimezone());
            $value = $point->getValue();
            $points[] = [$time->getTimestamp(), is_numeric($value) && is_finite((float) $value) ? (float) $value : null];
        }
        $this->histories[$key] = ['points' => $points];
        return $key;
    }

    /** @return resource */
    private static function writePdf(array $document) {
        $data = json_encode($document, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
        if (strlen($data) > self::MAX_DATA_BYTES) {
            throw new DomainException('Les données du rapport dépassent la taille maximale.');
        }
        $input = tmpfile();
        $pdf = tmpfile();
        $errors = tmpfile();
        $ready = false;
        try {
            if ($input === false || $pdf === false || $errors === false || fwrite($input, $data) !== strlen($data)) {
                throw new RuntimeException('Impossible de préparer les fichiers temporaires du rapport.');
            }
            rewind($input);

            // Des fichiers temporaires évitent de bloquer sur des pipes et permettent d'envoyer le PDF en flux.
            $script = dirname(__DIR__, 2) . '/resources/report/render.py';
            $process = proc_open(
                ['/usr/bin/timeout', '--kill-after=2s', '20s', '/usr/bin/python3', '-I', $script, (string) self::MAX_PDF_BYTES],
                [0 => $input, 1 => $pdf, 2 => $errors],
                $pipes
            );
            if ($process === false) {
                throw new RuntimeException('Impossible de lancer le moteur PDF.', self::UNAVAILABLE);
            }
            $status = proc_close($process);
            if ($status !== 0) {
                rewind($errors);
                $message = trim((string) fread($errors, 2048));
                if ($status === self::INVALID_LAYOUT && $message !== '') {
                    throw new DomainException($message);
                }
                throw new RuntimeException('Erreur du moteur PDF (' . $status . ') ' . $message, $status === 137 ? self::TIMEOUT : $status);
            }

            rewind($pdf);
            if (fread($pdf, 5) !== '%PDF-') {
                throw new RuntimeException('Le moteur PDF n’a produit aucun document valide.');
            }
            rewind($pdf);
            $ready = true;
            return $pdf;
        } finally {
            foreach ([$input, $errors, $ready ? null : $pdf] as $stream) {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }
        }
    }
}
