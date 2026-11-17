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

    private const MAX_DATA_BYTES = 8 * 1024 * 1024;
    private const MAX_PDF_BYTES = 25 * 1024 * 1024;
    private const MAX_HISTORY_READS = 64;
    private const MAX_IMAGE_BYTES = 5 * 1024 * 1024;

    /** @var array<int, cmd|null> */
    private array $commands = [];
    /** @var array<int, string|null> */
    private array $values = [];
    private array $histories = [];

    private function __construct(private user $user, private DateTimeImmutable $now) {
    }

    public static function render(reportTemplate $template, user $user): string {
        if (!function_exists('proc_open') || !is_executable('/usr/bin/python3') || !is_executable('/usr/bin/timeout')) {
            throw new RuntimeException('Moteur PDF indisponible.', self::UNAVAILABLE);
        }
        $renderer = new self($user, new DateTimeImmutable());
        $document = $renderer->document($template);
        return self::writePdf($document);
    }

    private function document(reportTemplate $template): array {
        $layout = $template->getLayoutDocument();
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

    private function command(int $id): ?cmd {
        if (!array_key_exists($id, $this->commands)) {
            $command = cmd::byId($id);
            $this->commands[$id] = $command instanceof cmd && $command->getType() === 'info'
                && is_object($command->getEqLogic()) && $command->hasRight($this->user) ? $command : null;
        }
        return $this->commands[$id];
    }

    private function value(stdClass $options): array {
        $id = $options->cmdId;
        $command = $this->command($id);
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
        $unit = null;
        $series = [];
        foreach ($options->series as $entry) {
            $command = $this->command($entry->cmdId);
            if ($command === null || $command->getSubType() !== 'numeric' || !(int) $command->getIsHistorized()) {
                continue;
            }
            $commandUnit = trim((string) $command->getUnite());
            if ($unit !== null && $commandUnit !== $unit) {
                throw new DomainException(sprintf('Page %s, bloc %s : les séries doivent utiliser la même unité.', $pageId, $blockId));
            }
            $unit = $commandUnit;
            $series[] = [
                'data' => $this->history($entry->cmdId, $start, $end),
                'label' => ($entry->label ?? '') !== '' ? $entry->label : $command->getName(),
                'color' => $entry->color ?? null,
            ];
        }

        $from = $start->getTimestamp();
        $to = $end->getTimestamp();
        $labels = [];
        for ($index = 0; $index <= 4; $index++) {
            $time = $from + (int) (($to - $from) * $index / 4);
            $labels[] = $start->setTimestamp($time)->format($to - $from <= 90000 ? 'H:i' : ($to - $from <= 366 * 86400 ? 'd/m' : 'm/Y'));
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

    private function history(int $id, DateTimeImmutable $start, DateTimeImmutable $end): string {
        $key = $id . ':' . $start->getTimestamp() . ':' . $end->getTimestamp();
        if (isset($this->histories[$key])) {
            return $key;
        }
        if (count($this->histories) >= self::MAX_HISTORY_READS) {
            throw new DomainException('Le rapport utilise plus de 64 combinaisons de commandes et de périodes.');
        }
        $days = (int) $start->diff($end)->days;
        [$grouping, $gap] = match (true) {
            $days <= 1 => ['avg::minute', 90],
            $days <= 60 => ['avg::hour', 5400],
            $days <= 730 => ['avg::day', 36 * 3600],
            $days <= 36525 => ['avg::month', 45 * 86400],
            default => ['avg::year', 550 * 86400],
        };
        $points = [];
        foreach (history::all($id, $start->format('Y-m-d H:i:s'), $end->modify('-1 second')->format('Y-m-d H:i:s'), $grouping) as $point) {
            $time = new DateTimeImmutable($point->getDatetime(), $start->getTimezone());
            $value = $point->getValue();
            $points[] = [$time->getTimestamp(), is_numeric($value) && is_finite((float) $value) ? (float) $value : null];
        }
        $this->histories[$key] = ['gap' => $gap, 'points' => $points];
        return $key;
    }

    private static function writePdf(array $document): string {
        $data = json_encode($document, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
        if (strlen($data) > self::MAX_DATA_BYTES) {
            throw new DomainException('Les données du rapport dépassent la taille maximale.');
        }
        $directory = sys_get_temp_dir() . '/jeedom-report-' . bin2hex(random_bytes(16));
        if (!mkdir($directory, 0700)) {
            throw new RuntimeException('Impossible de créer le dossier temporaire du rapport.');
        }
        $input = $directory . '/input.json';
        $output = $directory . '/report.pdf';
        $error = $directory . '/error';
        try {
            if (file_put_contents($input, $data) !== strlen($data)) {
                throw new RuntimeException('Impossible de préparer les données du rapport.');
            }
            $script = dirname(__DIR__, 2) . '/resources/report/render.py';
            $process = proc_open(
                ['/usr/bin/timeout', '--kill-after=2s', '20s', '/usr/bin/python3', '-I', $script, $input, $output],
                [0 => ['file', '/dev/null', 'r'], 1 => ['file', '/dev/null', 'w'], 2 => ['file', $error, 'w']],
                $pipes
            );
            if ($process === false) {
                throw new RuntimeException('Impossible de lancer le moteur PDF.', self::UNAVAILABLE);
            }
            $status = proc_close($process);
            if ($status === 65) {
                throw new DomainException(trim((string) file_get_contents($error, false, null, 0, 1024)));
            }
            if ($status !== 0) {
                $message = trim((string) file_get_contents($error, false, null, 0, 2048));
                throw new RuntimeException('Erreur du moteur PDF (' . $status . ') ' . $message, $status === 137 ? self::TIMEOUT : $status);
            }
            $pdf = is_file($output) ? file_get_contents($output, false, null, 0, self::MAX_PDF_BYTES + 1) : false;
            if ($pdf === false || !str_starts_with($pdf, '%PDF-')) {
                throw new RuntimeException('Le moteur PDF n’a produit aucun document valide.');
            }
            if (strlen($pdf) > self::MAX_PDF_BYTES) {
                throw new DomainException('Le PDF dépasse 25 Mio.');
            }
            return $pdf;
        } finally {
            foreach ([$input, $output, $error] as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
}
