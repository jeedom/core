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

class reportTemplate {
    private const MAX_LAYOUT_BYTES = 1048576;
    private const PAGE_WIDTH = 210;
    private const PAGE_HEIGHT = 297;
    private const RELATIVE_PERIODS = ['today', 'yesterday', 'last7Days', 'last30Days', 'thisMonth', 'previousMonth'];

    private $id;
    private $name;
    private $layout = '{"schemaVersion":1,"pages":[{"id":"page-1","blocks":[]}]}';
    private $revision = 1;
    private $created_at;
    private $updated_at;

    /** @return array[] */
    public static function summaries(): array {
        return DB::Prepare('SELECT id, name, revision, created_at, updated_at
            FROM `reportTemplate` ORDER BY name, id', [], DB::FETCH_TYPE_ALL);
    }

    public static function byId(int $_id): ?self {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . ' FROM `reportTemplate` WHERE id = :id';
        $template = DB::Prepare($sql, ['id' => $_id], DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
        return $template instanceof self ? $template : null;
    }

    public function setName(mixed $_name): void {
        if (!is_string($_name) || trim($_name) === '' || mb_strlen(trim($_name)) > 127) {
            throw new InvalidArgumentException(__('Un nom de 1 à 127 caractères est attendu.', __FILE__));
        }
        $this->name = trim($_name);
    }

    public function setLayout(mixed $_layout): void {
        $layout = self::checkObject($_layout, ['schemaVersion', 'pages'], 'layout');
        if (($layout->schemaVersion ?? null) !== 1) {
            self::invalid('layout.schemaVersion', 'La version du format doit être 1.');
        }
        $pages = self::checkList($layout->pages ?? null, 1, 50, 'layout.pages');
        $pageIds = [];
        $blockIds = [];
        foreach ($pages as $pageIndex => $value) {
            $field = 'layout.pages.' . $pageIndex;
            $page = self::checkObject($value, ['id', 'name', 'blocks'], $field);
            self::checkId($page, $pageIds, $field);
            self::checkText($page, 'name', 127, $field);
            foreach (self::checkList($page->blocks ?? null, 0, 100, $field . '.blocks') as $blockIndex => $block) {
                self::checkBlock($block, $blockIds, $field . '.blocks.' . $blockIndex);
            }
        }
        $encoded = json_encode($layout, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
        if (strlen($encoded) > self::MAX_LAYOUT_BYTES) {
            self::invalid('layout', 'La composition dépasse 1 Mio.');
        }
        $this->layout = $encoded;
    }

    /** Retourne false si la révision attendue n'est plus celle de la base. */
    public function save(?int $_expectedRevision = null): bool {
        $this->setName($this->name);
        $id = $this->getId();
        if ($id === null) {
            $this->created_at = $this->updated_at = gmdate('Y-m-d H:i:s');
            $this->revision = 1;
            return DB::save($this);
        }
        if ($_expectedRevision !== null && $_expectedRevision !== $this->getRevision()) {
            return false;
        }

        $revision = $this->getRevision();
        $updatedAt = $this->updated_at;
        DB::beginTransaction();
        try {
            $current = DB::Prepare('SELECT revision FROM `reportTemplate` WHERE id = :id FOR UPDATE', ['id' => $id]);
            if (!is_array($current)) {
                throw new OutOfBoundsException(__('Modèle de rapport inconnu.', __FILE__));
            }
            if ((int) $current['revision'] !== $revision) {
                DB::rollBack();
                return false;
            }
            $this->revision = $revision + 1;
            $this->updated_at = gmdate('Y-m-d H:i:s');
            DB::save($this);
            DB::commit();
            return true;
        } catch (Throwable $error) {
            $this->revision = $revision;
            $this->updated_at = $updatedAt;
            if (DB::getConnection()->inTransaction()) {
                DB::rollBack();
            }
            throw $error;
        }
    }

    public function remove(): void {
        $id = $this->getId();
        if ($id !== null) {
            reportTemplateArchive::removeTemplate($this, $id);
        }
    }

    public function getId(): ?int {
        return $this->id === null ? null : (int) $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getRevision(): int {
        return (int) $this->revision;
    }

    public function getLayoutDocument(): stdClass {
        return json_decode($this->layout, false, 64, JSON_THROW_ON_ERROR);
    }

    public function getCreated_at(): string {
        return $this->created_at;
    }

    public function getUpdated_at(): string {
        return $this->updated_at;
    }

    private static function checkBlock(mixed $value, array &$ids, string $path): void {
        $block = self::checkObject($value, ['id', 'type', 'x', 'y', 'width', 'height', 'options'], $path);
        self::checkId($block, $ids, $path);
        $x = self::checkNumber($block, 'x', 0, self::PAGE_WIDTH, $path);
        $y = self::checkNumber($block, 'y', 0, self::PAGE_HEIGHT, $path);
        $width = self::checkNumber($block, 'width', 0.1, self::PAGE_WIDTH, $path);
        $height = self::checkNumber($block, 'height', 0.1, self::PAGE_HEIGHT, $path);
        if ($x + $width > self::PAGE_WIDTH + 1e-9 || $y + $height > self::PAGE_HEIGHT + 1e-9) {
            self::invalid($path, 'Le bloc doit tenir dans la page A4.');
        }

        $options = $block->options ?? null;
        $optionsPath = $path . '.options';
        switch ($block->type ?? null) {
            case 'text':
                $options = self::checkObject($options, ['text', 'fontSize', 'color', 'align'], $optionsPath);
                self::checkText($options, 'text', 20000, $optionsPath, true);
                if (property_exists($options, 'fontSize')) {
                    self::checkNumber($options, 'fontSize', 1, 200, $optionsPath);
                }
                self::checkColor($options, $optionsPath);
                if (property_exists($options, 'align') && !in_array($options->align, ['left', 'center', 'right'], true)) {
                    self::invalid($optionsPath . '.align', 'Alignement inconnu.');
                }
                break;
            case 'image':
                $options = self::checkObject($options, ['imageId'], $optionsPath);
                if (!is_string($options->imageId ?? null) || !preg_match('/\A[a-f0-9]{32}\z/', $options->imageId)) {
                    self::invalid($optionsPath . '.imageId', 'Identifiant d’image invalide.');
                }
                break;
            case 'value':
                $options = self::checkObject($options, ['cmdId', 'label', 'showUnit'], $optionsPath);
                self::checkCommand($options, $optionsPath);
                self::checkText($options, 'label', 127, $optionsPath);
                if (property_exists($options, 'showUnit') && !is_bool($options->showUnit)) {
                    self::invalid($optionsPath . '.showUnit', 'Un booléen est attendu.');
                }
                break;
            case 'chart':
                self::checkChart($options, $optionsPath);
                break;
            default:
                self::invalid($path . '.type', 'Type de bloc inconnu.');
        }
    }

    private static function checkChart(mixed $value, string $path): void {
        $options = self::checkObject($value, ['title', 'series', 'period'], $path);
        self::checkText($options, 'title', 127, $path);
        $series = self::checkList($options->series ?? null, 1, 10, $path . '.series');
        foreach ($series as $index => $item) {
            $seriesPath = $path . '.series.' . $index;
            $item = self::checkObject($item, ['cmdId', 'label', 'color'], $seriesPath);
            self::checkCommand($item, $seriesPath);
            self::checkText($item, 'label', 127, $seriesPath);
            self::checkColor($item, $seriesPath);
        }

        $periodPath = $path . '.period';
        $period = self::checkObject($options->period ?? null, ['type', 'preset', 'start', 'end'], $periodPath);
        if (($period->type ?? null) === 'relative') {
            self::checkObject($period, ['type', 'preset'], $periodPath);
            if (!in_array($period->preset ?? null, self::RELATIVE_PERIODS, true)) {
                self::invalid($periodPath . '.preset', 'Période relative inconnue.');
            }
        } elseif (($period->type ?? null) === 'fixed') {
            self::checkObject($period, ['type', 'start', 'end'], $periodPath);
            $start = self::checkDate($period->start ?? null, $periodPath . '.start');
            $end = self::checkDate($period->end ?? null, $periodPath . '.end');
            if ($start >= $end) {
                self::invalid($periodPath . '.end', 'La date de fin doit être postérieure à la date de début.');
            }
        } else {
            self::invalid($periodPath . '.type', 'Type de période inconnu.');
        }
    }

    private static function checkObject(mixed $value, array $fields, string $path): stdClass {
        if (!$value instanceof stdClass) {
            self::invalid($path, 'Un objet JSON est attendu.');
        }
        $unknown = array_diff(array_keys(get_object_vars($value)), $fields);
        if ($unknown !== []) {
            self::invalid($path . '.' . reset($unknown), 'Champ inconnu.');
        }
        return $value;
    }

    private static function checkList(mixed $value, int $min, int $max, string $path): array {
        if (!is_array($value) || !array_is_list($value) || count($value) < $min || count($value) > $max) {
            self::invalid($path, 'Une liste de ' . $min . ' à ' . $max . ' éléments est attendue.');
        }
        return $value;
    }

    private static function checkId(stdClass $value, array &$ids, string $path): void {
        $id = $value->id ?? null;
        if (!is_string($id) || !preg_match('/\A[A-Za-z0-9_-]{1,64}\z/', $id) || isset($ids[$id])) {
            self::invalid($path . '.id', 'Un identifiant unique de 1 à 64 caractères est attendu.');
        }
        $ids[$id] = true;
    }

    private static function checkNumber(stdClass $value, string $field, float $min, float $max, string $path): float {
        $number = $value->$field ?? null;
        if ((!is_int($number) && !is_float($number)) || !is_finite($number) || $number < $min || $number > $max) {
            self::invalid($path . '.' . $field, 'Un nombre compris entre ' . $min . ' et ' . $max . ' est attendu.');
        }
        return $number;
    }

    private static function checkText(stdClass $value, string $field, int $max, string $path, bool $required = false): void {
        if (!$required && !property_exists($value, $field)) {
            return;
        }
        if (!is_string($value->$field ?? null) || mb_strlen($value->$field) > $max) {
            self::invalid($path . '.' . $field, 'Un texte de ' . $max . ' caractères au plus est attendu.');
        }
    }

    private static function checkColor(stdClass $value, string $path): void {
        if (property_exists($value, 'color') && (!is_string($value->color) || !preg_match('/\A#[a-fA-F0-9]{6}\z/', $value->color))) {
            self::invalid($path . '.color', 'Une couleur au format #RRGGBB est attendue.');
        }
    }

    private static function checkCommand(stdClass $value, string $path): void {
        if (!is_int($value->cmdId ?? null) || $value->cmdId < 1 || $value->cmdId > 2147483647) {
            self::invalid($path . '.cmdId', 'Un identifiant de commande positif est attendu.');
        }
    }

    private static function checkDate(mixed $value, string $path): string {
        if (!is_string($value) || !preg_match('/\A([0-9]{4})-([0-9]{2})-([0-9]{2})\z/', $value, $parts)
            || !checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])) {
            self::invalid($path, 'Une date valide au format AAAA-MM-JJ est attendue.');
        }
        return $value;
    }

    private static function invalid(string $path, string $message): never {
        throw new InvalidArgumentException($path . ' : ' . $message);
    }
}
