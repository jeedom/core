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

class display {
    public const DASHBOARD = 'dashboard';
    public const DASHBOARD_ID = 0;

    private $id;
    private $target_type;
    private $target_id;
    private $context;
    private $context_id;
    private $template;
    private $template_parameters;
    private $sort_order;
    private $visible;

    /** @return self[] */
    public static function byDashboard(): array {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
            FROM `display` WHERE context = :context AND context_id = :context_id
            ORDER BY target_type, target_id';
        return DB::Prepare($sql, self::dashboardContext(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    /** @param array<int, array> $commands Réglages indexés par identifiant de commande. */
    public static function replaceDashboardTile(int $eqLogicId, ?array $equipment, array $commands): void {
        DB::beginTransaction();
        try {
            // Sérialise les écritures d'une tuile, y compris avant sa première personnalisation.
            if (!DB::Prepare('SELECT id FROM eqLogic WHERE id = :id FOR UPDATE', ['id' => $eqLogicId])) {
                throw new OutOfBoundsException('Équipement inconnu.');
            }
            $ids = DB::Prepare('SELECT id FROM cmd WHERE eqLogic_id = :id FOR UPDATE',
                ['id' => $eqLogicId], DB::FETCH_TYPE_ALL, PDO::FETCH_COLUMN);
            if (array_diff(array_keys($commands), $ids) !== []) {
                throw new DomainException('Chaque commande doit appartenir à cet équipement.');
            }

            $sql = 'SELECT ' . DB::buildField(__CLASS__, 'd') . '
                FROM `display` d LEFT JOIN cmd c ON d.target_type = \'cmd\' AND c.id = d.target_id
                WHERE d.context = :context AND d.context_id = :context_id
                AND ((d.target_type = \'eqLogic\' AND d.target_id = :eqLogicId) OR c.eqLogic_id = :cmdEqLogicId)';
            $rows = DB::Prepare($sql, self::dashboardContext() + ['eqLogicId' => $eqLogicId, 'cmdEqLogicId' => $eqLogicId],
                DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);

            $settings = ['eqLogic' => [$eqLogicId => $equipment], 'cmd' => $commands];
            foreach ($rows as $row) {
                $type = $row->getTargetType();
                $id = $row->getTargetId();
                $row->applySettings($settings[$type][$id] ?? null);
                unset($settings[$type][$id]);
            }
            foreach ($settings as $type => $targets) {
                foreach ($targets as $id => $values) {
                    $row = new self();
                    $row->target_type = $type;
                    $row->target_id = $id;
                    $row->context = self::DASHBOARD;
                    $row->context_id = self::DASHBOARD_ID;
                    $row->applySettings($values);
                }
            }
            DB::commit();
        } catch (Throwable $error) {
            if (DB::getConnection()->inTransaction()) {
                DB::rollBack();
            }
            throw $error;
        }
    }

    public static function removeByTarget(string $type, int $id): void {
        DB::Prepare('DELETE FROM `display` WHERE target_type = :type AND target_id = :id', ['type' => $type, 'id' => $id]);
    }

    /**
     * Réglages des éléments de design, indexés par identifiant d'élément (plan).
     * @param list<int> $planIds
     * @return array<int, self>
     */
    public static function byPlanIds(array $planIds): array {
        $ids = array_values(array_unique(array_map('intval', $planIds)));
        if ($ids === []) {
            return [];
        }
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . ' FROM `display`
            WHERE context = \'design\' AND context_id IN (' . implode(',', $ids) . ')';
        $rows = [];
        foreach ((array) DB::Prepare($sql, [], DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__) as $row) {
            $rows[(int) $row->context_id] = $row;
        }
        return $rows;
    }

    /** Remplace le réglage d'un élément de design ; null ou réglage vide le supprime. */
    public static function replaceForPlan(int $planId, string $targetType, int $targetId, ?array $settings): void {
        $row = self::byPlanIds([$planId])[$planId] ?? null;
        if ($row === null) {
            $row = new self();
            $row->context = 'design';
            $row->context_id = $planId;
        }
        $row->target_type = $targetType;
        $row->target_id = $targetId;
        $row->applySettings($settings);
    }

    public function getTemplate(): ?string {
        return $this->template;
    }

    public function getTemplateParameters(): stdClass {
        return $this->template_parameters === null
            ? new stdClass() : json_decode($this->template_parameters, false, 64, JSON_THROW_ON_ERROR);
    }

    public static function removeByPlanId(int $id): void {
        DB::Prepare('DELETE FROM `display` WHERE context = :context AND context_id = :id', ['context' => 'design', 'id' => $id]);
    }

    public function getTargetType(): string {
        return $this->target_type;
    }

    public function getTargetId(): int {
        return (int) $this->target_id;
    }

    public function toArray(): array {
        return [
            'id' => (int) $this->id,
            'target_type' => $this->getTargetType(),
            'target_id' => $this->getTargetId(),
            'context' => $this->context,
            'context_id' => (int) $this->context_id,
            'template' => $this->template,
            'template_parameters' => $this->template_parameters === null
                ? new stdClass() : json_decode($this->template_parameters, false, 64, JSON_THROW_ON_ERROR),
            'sort_order' => $this->sort_order === null ? null : (int) $this->sort_order,
            'visible' => $this->visible === null ? null : (bool) $this->visible,
        ];
    }

    private static function dashboardContext(): array {
        return ['context' => self::DASHBOARD, 'context_id' => self::DASHBOARD_ID];
    }

    private function applySettings(?array $settings): void {
        $parameters = $settings['template_parameters'] ?? new stdClass();
        $values = [
            'template' => $settings['template'] ?? null,
            'template_parameters' => (array) $parameters === [] ? null
                : json_encode($parameters, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION),
            'sort_order' => $settings['sort_order'] ?? null,
            'visible' => isset($settings['visible']) ? (int) $settings['visible'] : null,
        ];
        if ($values === array_fill_keys(array_keys($values), null)) {
            if ($this->id !== null) {
                DB::remove($this);
            }
            return;
        }
        $changed = $this->id === null;
        foreach ($values as $field => $value) {
            $changed = $changed || $this->$field !== $value;
            $this->$field = $value;
        }
        if ($changed) {
            DB::save($this);
        }
    }
}
