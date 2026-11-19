<?php
declare(strict_types=1);

final class scenarioValidationException extends DomainException {
	public function __construct(public readonly string $field, string $message) {
		parent::__construct($message);
	}
}

/** Contrôle une définition sans exécuter les expressions ni modifier les blocs enregistrés. */
final class scenarioValidator {
	private const PARTS = [
		'if' => ['if' => 'condition', 'then' => 'action', 'else' => 'action'],
		'for' => ['for' => 'condition', 'do' => 'action'],
		'while' => ['while' => 'condition', 'do' => 'action'],
		'at' => ['at' => 'condition', 'do' => 'action'],
		'in' => ['in' => 'condition', 'do' => 'action'],
		'action' => ['action' => 'action'],
		'code' => ['code' => 'action'],
		'comment' => ['comment' => 'comment'],
	];
	private const ACTIONS = [
		'icon', 'wait', 'sleep', 'stop', 'log', 'event', 'message', 'alert', 'popup', 'setColoredIcon',
		'equipment', 'equipement', 'gotodesign', 'changeTheme', 'scenario', 'variable', 'genericType',
		'delete_variable', 'ask', 'jeedom_poweroff', 'jeedom_reboot', 'scenario_return', 'remove_inat',
		'exportHistory', 'report', 'tag', 'generateReport',
	];

	private array $owned = ['element' => [], 'subelement' => [], 'expression' => []];
	private array $seen = [];
	private array $warnings = [];
	private array $commands = [];

	private function __construct(private readonly user $user) {
	}

	/** @return list<array{field:string, reference:string, message:string}> */
	public static function validate(scenario $scenario, array $changes, user $user): array {
		$validator = new self($user);
		$stored = $scenario->getElement();
		foreach ($stored as $element) {
			foreach ($element->getAllId() as $kind => $ids) {
				$validator->owned[$kind] += array_fill_keys($ids, true);
			}
		}
		$objectId = array_key_exists('object_id', $changes) ? $changes['object_id'] : $scenario->getObject_id();
		if ($objectId !== null && $objectId !== '') {
			$object = jeeObject::byId($objectId);
			if (!$object instanceof jeeObject || !$object->hasRight('r', $user)) {
				throw self::invalid('object_id', 'Objet inconnu ou inaccessible.');
			}
		}
		$mode = $changes['mode'] ?? $scenario->getMode();
		$schedule = $changes['schedule'] ?? $scenario->getSchedule();
		$schedules = array_values(array_filter((array) $schedule, static fn ($date) => trim((string) $date) !== ''));
		if ($schedules === [] && in_array($mode, ['schedule', 'all'], true)) {
			throw self::invalid('schedule', 'Une programmation est requise pour ce mode.');
		}
		foreach ($schedules as $index => $date) {
			if (!\Cron\CronExpression::isValidExpression(checkAndFixCron($date))) {
				throw self::invalid("schedule.$index", 'Expression cron invalide.');
			}
		}
		foreach (($changes['trigger'] ?? $scenario->getTrigger()) as $index => $trigger) {
			$validator->trigger(trim((string) $trigger), "trigger.$index");
		}
		foreach (($changes['elements'] ?? array_map(static fn ($block) => $block->getAjaxElement(), $stored)) as $index => $element) {
			$validator->element($element, "elements.$index");
		}
		return $validator->warnings;
	}

	private function trigger(string $trigger, string $field): void {
		if ($trigger === '') {
			return;
		}
		$references = self::references($trigger);
		foreach ($references as $reference) {
			$this->requireCommand($reference, 'info', $field);
		}
		if (preg_match('/^#(?:variable|genericType)\(.+\)#$/sD', $trigger)
			|| preg_match('/^#[a-zA-Z_][a-zA-Z_0-9.:-]*#$/D', $trigger)
			|| ($references === [] && preg_match('/^[a-zA-Z_][a-zA-Z_0-9.:-]*$/D', $trigger))) {
			// Le moteur accepte aussi des événements déclarés par les plugins.
			return;
		}
		if ($references === []) {
			throw self::invalid($field, 'Déclencheur invalide.');
		}
		$this->condition($trigger, $field);
	}

	private function element(mixed $element, string $field): void {
		$type = is_array($element) ? ($element['type'] ?? null) : null;
		if (!is_string($type) || !isset(self::PARTS[$type]) || !is_array($element['subElements'] ?? null) || !array_is_list($element['subElements'])) {
			throw self::invalid($field, 'Type de bloc ou liste de sous-blocs invalide.');
		}
		$this->checkId($element, 'element', $field);
		$parts = self::PARTS[$type];
		$seen = [];
		foreach ($element['subElements'] as $index => $part) {
			$partField = "$field.subElements.$index";
			$partType = is_array($part) ? ($part['type'] ?? null) : null;
			if (!is_string($partType) || !isset($parts[$partType]) || isset($seen[$partType])
				|| ($part['subtype'] ?? null) !== $parts[$partType]
				|| !is_array($part['expressions'] ?? null) || !array_is_list($part['expressions'])) {
				throw self::invalid($partField, 'Sous-bloc invalide pour ce type de bloc.');
			}
			$seen[$partType] = true;
			$this->checkId($part, 'subelement', $partField, $element['id'] ?? null);
			$types = match ($partType) {
				'code', 'comment' => [$partType],
				default => $parts[$partType] === 'condition' ? ['condition'] : ['action', 'element'],
			};
			foreach ($part['expressions'] as $n => $expression) {
				$this->expression($expression, $types, "$partField.expressions.$n", $part['id'] ?? null);
			}
		}
		unset($parts['else']);
		if (array_diff_key($parts, $seen) !== []) {
			throw self::invalid($field, 'Sous-bloc obligatoire manquant.');
		}
	}

	private function expression(mixed $expression, array $types, string $field, mixed $parent): void {
		if (!is_array($expression) || !in_array($expression['type'] ?? null, $types, true)) {
			throw self::invalid($field, 'Type d’expression invalide dans ce sous-bloc.');
		}
		$this->checkId($expression, 'expression', $field, $parent);
		if ($expression['type'] === 'element') {
			$this->element($expression['element'] ?? null, "$field.element");
			return;
		}
		$value = $expression['expression'] ?? '';
		$options = $expression['options'] ?? [];
		if ((!is_string($value) && !is_int($value)) || !is_array($options)) {
			throw self::invalid($field, 'Expression ou options invalides.');
		}
		$value = (string) $value;
		switch ($expression['type']) {
			case 'code':
				try {
					token_get_all("<?php function __jeedom_scenario_check() {\n" . $value . "\n}", TOKEN_PARSE);
				} catch (ParseError $error) {
					throw self::invalid("$field.expression", 'Syntaxe PHP invalide à la ligne ' . max(1, $error->getLine() - 1) . ' du bloc.');
				}
				break;
			case 'action':
				$this->action(trim($value), $options, $field);
				break;
			case 'condition':
				$this->condition($value, "$field.expression");
				$this->text($value, "$field.expression");
				break;
		}
	}

	private function checkId(array $item, string $kind, string $field, mixed $parent = null): void {
		$id = $item['id'] ?? '';
		if ($id === '') {
			return;
		}
		if ((!is_int($id) && !is_string($id)) || !isset($this->owned[$kind][$id]) || isset($this->seen[$kind][$id])) {
			throw self::invalid("$field.id", 'Identifiant inconnu, répété ou appartenant à un autre scénario.');
		}
		$this->seen[$kind][$id] = true;
		if ($kind === 'subelement' && (string) scenarioSubElement::byId($id)->getScenarioElement_id() !== (string) $parent
			|| $kind === 'expression' && (string) scenarioExpression::byId($id)->getScenarioSubElement_id() !== (string) $parent) {
			throw self::invalid("$field.id", 'Identifiant rattaché à un autre bloc.');
		}
	}

	private function action(string $name, array $options, string $field): void {
		if (!in_array($name, self::ACTIONS, true)) {
			if (!preg_match('/^(?:#[0-9]+#|[0-9]+|#\[.+\]\[.+\]\[.+\]#)$/sD', $name)) {
				// Les fonctions utilisateur déjà chargées font partie des actions du moteur.
				$function = explode('(', $name, 2)[0];
				if (!class_exists('userFunction', false) || !is_callable(['userFunction', $function])) {
					throw self::invalid("$field.expression", 'Action de scénario inconnue.');
				}
			} else {
				$this->requireCommand($name, 'action', "$field.expression");
			}
		}
		switch ($name) {
			case 'scenario':
			case 'remove_inat':
				$id = $options['scenario_id'] ?? '';
				if ($name === 'scenario' || (string) $id !== '' && (string) $id !== '0') {
					$target = $this->target($id, scenario::class, 'scenario', "$field.options.scenario_id");
					$right = $name === 'scenario' && in_array($options['action'] ?? null, ['start', 'startsync', 'stop'], true) ? 'x' : 'w';
					if (!$target->hasRight($right, $this->user)) {
						throw self::invalid("$field.options.scenario_id", 'Scénario inaccessible.');
					}
				}
				unset($options['scenario_id']);
				break;
			case 'equipment':
			case 'equipement':
				$equipment = $this->target($options['eqLogic'] ?? null, eqLogic::class, 'eqLogic', "$field.options.eqLogic");
				if (!$equipment->hasRight('w', $this->user)) {
					throw self::invalid("$field.options.eqLogic", 'Équipement inaccessible.');
				}
				unset($options['eqLogic']);
				break;
			case 'genericType':
				$object = $options['object'] ?? '';
				if (!in_array((string) $object, ['', 'all', '0', '-1'], true)) {
					$target = $this->target($object, jeeObject::class, 'object', "$field.options.object");
					if (!$target->hasRight('r', $this->user)) {
						throw self::invalid("$field.options.object", 'Objet inaccessible.');
					}
				}
				break;
			case 'event':
			case 'ask':
				$command = $options['cmd'] ?? null;
				if ($name !== 'ask' || !is_string($command) || !str_contains($command, '#profile#')) {
					$this->requireCommand($command, $name === 'event' ? 'info' : 'action', "$field.options.cmd");
				}
				unset($options['cmd']);
				break;
			case 'gotodesign':
				$this->target($options['plan_id'] ?? null, planHeader::class, '', "$field.options.plan_id");
				break;
			case 'report':
			case 'generateReport':
			case 'exportHistory':
				$this->commandList($options['cmd'] ?? '', 'action', "$field.options.cmd", $name !== 'exportHistory');
				unset($options['cmd']);
				if ($name === 'exportHistory') {
					$this->commandList($options['cmd_export'] ?? '', 'info', "$field.options.cmd_export", false);
					unset($options['cmd_export']);
				}
				if ($name === 'generateReport') {
					$this->target($options['report_id'] ?? null, reportTemplate::class, '', "$field.options.report_id");
				} elseif ($name === 'report') {
					switch ($options['type'] ?? null) {
						case 'view':
							$this->target($options['view_id'] ?? null, view::class, '', "$field.options.view_id");
							break;
						case 'plan':
							$this->target($options['plan_id'] ?? null, planHeader::class, '', "$field.options.plan_id");
							break;
					}
				}
				break;
		}
		foreach ($options as $key => $value) {
			$this->text($value, "$field.options.$key");
		}
	}

	private function commandList(mixed $value, string $type, string $field, bool $optional): void {
		if ($optional && ($value === '' || $value === null)) {
			return;
		}
		if (!is_string($value) && !is_int($value)) {
			throw self::invalid($field, 'Liste de commandes invalide.');
		}
		foreach (explode('&&', (string) $value) as $reference) {
			$this->requireCommand(trim($reference), $type, $field);
		}
	}

	private function target(mixed $value, string $class, string $prefix, string $field): object {
		$reference = is_string($value) || is_int($value) ? (string) jeedom::fromHumanReadable($value) : '';
		$pattern = $prefix === '' ? '/^([0-9]+)$/D' : '/^(?:#' . $prefix . '([0-9]+)#|([0-9]+))$/D';
		$target = preg_match($pattern, $reference, $id, PREG_UNMATCHED_AS_NULL) ? $class::byId($id[1] ?? $id[2]) : null;
		if (!$target instanceof $class) {
			throw self::invalid($field, 'Référence inconnue ou incompatible.');
		}
		return $target;
	}

	private function requireCommand(mixed $reference, string $type, string $field): void {
		if (!$this->readableCommand($reference, $type)) {
			throw self::invalid($field, 'Commande ' . $type . ' inconnue, incompatible ou inaccessible : ' . (is_scalar($reference) ? $reference : ''));
		}
	}

	private function readableCommand(mixed $reference, string $type): bool {
		if (!is_string($reference) && !is_int($reference)) {
			return false;
		}
		$reference = (string) cmd::humanReadableToCmd(trim((string) $reference));
		if (!preg_match('/^(?:#([0-9]+)#|([0-9]+))$/D', $reference, $id, PREG_UNMATCHED_AS_NULL)) {
			return false;
		}
		$id = $id[1] ?? $id[2];
		if (!array_key_exists($id, $this->commands)) {
			$this->commands[$id] = cmd::byId($id);
		}
		$command = $this->commands[$id];
		return $command instanceof cmd && $command->getType() === $type && $command->getEqLogic() instanceof eqLogic && $command->hasRight($this->user);
	}

	private static function references(string $value): array {
		preg_match_all(cmd::ID_REFERENCE_PATTERN, $value, $numeric);
		preg_match_all(cmd::HUMAN_REFERENCE_PATTERN, $value, $human);
		return array_unique(array_merge($numeric[0], $human[0]));
	}

	private function text(mixed $value, string $field): void {
		if (is_array($value)) {
			foreach ($value as $key => $part) {
				$this->text($part, "$field.$key");
			}
		} elseif (is_string($value)) {
			foreach (self::references($value) as $reference) {
				if (!$this->readableCommand($reference, 'info')) {
					$this->warnings[] = ['field' => $field, 'reference' => $reference, 'message' => 'Commande info inconnue, incompatible ou inaccessible.'];
				}
			}
		}
	}

	private function condition(string $value, string $field): void {
		if (trim($value) === '') {
			return;
		}
		// Représente les tags et les fonctions évaluées par le moteur par des valeurs
		// fictives. Le parseur vérifie la syntaxe sans lire d'équipement ni évaluer de code.
		$value = preg_replace(cmd::HUMAN_REFERENCE_PATTERN, '1', $value);
		$value = preg_replace('/#[^#\r\n]+#/', '1', $value);
		$value = str_ireplace([' et ', ' and ', ' ou ', ' or ', ' xor '], [' && ', ' && ', ' || ', ' || ', ' ^ '], $value);
		$value = preg_replace('/(?<![=!<>])=(?!=)/', '==', $value);
		$language = new \Symfony\Component\ExpressionLanguage\ExpressionLanguage();
		preg_match_all('/\b([a-zA-Z_][a-zA-Z_0-9]*)\s*\(/', $value, $calls);
		foreach (array_unique($calls[1]) as $function) {
			if (!in_array($function, ['constant', 'enum'], true)) {
				$language->register($function, static fn () => '1', static fn () => 1);
			}
		}
		try {
			$language->lint($value, null);
		} catch (\Symfony\Component\ExpressionLanguage\SyntaxError) {
			throw self::invalid($field, 'Syntaxe de l’expression invalide.');
		}
	}

	private static function invalid(string $field, string $message): scenarioValidationException {
		return new scenarioValidationException($field, $message);
	}
}
