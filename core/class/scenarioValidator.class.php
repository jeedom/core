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
	private ?array $userMethods = null;

	private function __construct(private readonly user $user, private readonly bool $submittedElements) {
	}

	/** @return list<array{field:string, reference:string, message:string}> */
	public static function validate(scenario $scenario, array $changes, user $user): array {
		$validator = new self($user, array_key_exists('elements', $changes));
		$stored = $scenario->getElement();
		if ($validator->submittedElements) {
			foreach ($stored as $element) {
				foreach ($element->getAllId() as $kind => $ids) {
					$validator->owned[$kind] += array_fill_keys($ids, true);
				}
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
		if (in_array($mode, ['schedule', 'all'], true)) {
			$schedules = array_filter((array) ($changes['schedule'] ?? $scenario->getSchedule()), static fn ($date) => trim((string) $date) !== '');
			if ($schedules === []) {
				throw self::invalid('schedule', 'Une programmation est requise pour ce mode.');
			}
			foreach ($schedules as $index => $date) {
				$validator->schedule(trim((string) $date), "schedule.$index");
			}
		}
		if ($mode !== 'schedule') {
			foreach (($changes['trigger'] ?? $scenario->getTrigger()) as $index => $trigger) {
				$validator->trigger(trim((string) $trigger), "trigger.$index");
			}
		}
		foreach (($changes['elements'] ?? array_map(static fn ($block) => $block->getAjaxElement(), $stored)) as $index => $element) {
			$validator->element($element, "elements.$index");
		}
		return $validator->warnings;
	}

	private function schedule(string $value, string $field): void {
		if (\Cron\CronExpression::isValidExpression(checkAndFixCron($value))) {
			return;
		}
		// cronIsDue accepte aussi une heure Gi ou une expression qui produit cette heure.
		if (ctype_digit($value)) {
			if (strlen($value) <= 4 && (int) $value < 2400 && (int) $value % 100 < 60) {
				return;
			}
		} elseif (preg_match('/[#(0-9]/', $value)) {
			$this->condition($value, $field, []);
			foreach (self::references($value) as $reference) {
				$this->requireCommand($reference, 'info', $field);
			}
			return;
		}
		throw self::invalid($field, 'Une programmation cron, une heure ou une expression horaire est attendue.');
	}

	private function trigger(string $trigger, string $field): void {
		if ($trigger === '') {
			return;
		}
		$references = self::references($trigger);
		foreach ($references as $reference) {
			$this->requireCommand($reference, 'info', $field);
		}
		// Même notation avec ou sans # que scenario::testTrigger(), y compris si
		// le déclencheur est suivi d'une comparaison.
		$condition = preg_replace('/#(variable|genericType)\((.*?)\)#/s', '$1($2)', $trigger);
		if ($references !== [] || preg_match('/(?:variable|genericType)\(/', $condition)) {
			$this->condition($condition, $field);
			return;
		}
		// Les plugins choisissent leur nom d'événement (espaces et accents compris).
		if (preg_match('/\A#[^#]+#\z/u', $trigger) || preg_match('/\A[\pL_][\pL\pN _.:\/-]*\z/u', $trigger)) {
			return;
		}
		if (preg_match('/#[^#]+#/', $trigger)) {
			$this->condition($trigger, $field);
			return;
		}
		throw self::invalid($field, 'Déclencheur invalide.');
	}

	private function element(mixed $element, string $field, bool $enabled = true): void {
		$type = is_array($element) ? ($element['type'] ?? null) : null;
		if (!is_string($type) || !isset(self::PARTS[$type]) || !is_array($element['subElements'] ?? null) || !array_is_list($element['subElements'])) {
			throw self::invalid($field, 'Type de bloc ou liste de sous-blocs invalide.');
		}
		$this->checkId($element, 'element', $field);
		$parts = self::PARTS[$type];
		// Le moteur vérifie enable sur le sous-bloc principal avant toute branche.
		foreach ($element['subElements'] as $part) {
			if (($part['type'] ?? null) === $type && ($part['options']['enable'] ?? 1) == 0) {
				$enabled = false;
			}
		}
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
				$this->expression($expression, $types, "$partField.expressions.$n", $part['id'] ?? null, $partType, $enabled);
			}
		}
		unset($parts['else']);
		if (array_diff_key($parts, $seen) !== []) {
			throw self::invalid($field, 'Sous-bloc obligatoire manquant.');
		}
	}

	private function expression(mixed $expression, array $types, string $field, mixed $parent, string $partType, bool $enabled): void {
		if (!is_array($expression) || !in_array($expression['type'] ?? null, $types, true)) {
			throw self::invalid($field, 'Type d’expression invalide dans ce sous-bloc.');
		}
		$this->checkId($expression, 'expression', $field, $parent);
		$enabled = $enabled && ($expression['options']['enable'] ?? 1) != 0;
		if ($expression['type'] === 'element') {
			$this->element($expression['element'] ?? null, "$field.element", $enabled);
			return;
		}
		$value = $expression['expression'] ?? '';
		$options = $expression['options'] ?? [];
		if ((!is_string($value) && !is_int($value)) || !is_array($options)) {
			throw self::invalid($field, 'Expression ou options invalides.');
		}
		$value = (string) $value;
		if ($expression['type'] === 'code') {
			try {
				token_get_all('<?php ' . $value, TOKEN_PARSE);
			} catch (ParseError $error) {
				throw self::invalid("$field.expression", 'Syntaxe PHP invalide à la ligne ' . $error->getLine() . ' du bloc.');
			}
			return;
		}
		try {
			if ($expression['type'] === 'action') {
				$this->action(trim($value), $options, $field);
			} elseif ($expression['type'] === 'condition') {
				$this->text($value, "$field.expression");
				if ($partType !== 'in' || !is_numeric(str_replace(',', '.', $value))) {
					$this->condition($value, "$field.expression");
				}
			}
		} catch (scenarioValidationException $error) {
			if ($enabled) {
				throw $error;
			}
			$this->warnings[] = ['field' => $error->field, 'reference' => $value, 'message' => $error->getMessage()];
		}
	}

	private function checkId(array $item, string $kind, string $field, mixed $parent = null): void {
		$id = $item['id'] ?? '';
		// Pour les blocs relus en base, les liens parent/enfant viennent du Core lui-même.
		if (!$this->submittedElements || $id === '') {
			return;
		}
		if ((!is_int($id) && !is_string($id)) || !isset($this->owned[$kind][$id]) || isset($this->seen[$kind][$id])) {
			throw self::invalid("$field.id", 'Identifiant inconnu, répété ou appartenant à un autre scénario.');
		}
		$this->seen[$kind][$id] = true;
		if ($kind === 'element') {
			return;
		}
		$original = $kind === 'subelement' ? scenarioSubElement::byId($id)->getScenarioElement_id() : scenarioExpression::byId($id)->getScenarioSubElement_id();
		if ((string) $original === (string) $parent) {
			return;
		}
		// saveAjaxElement recrée une expression déplacée lorsque son ancien parent est fourni.
		if ($kind === 'expression' && (string) $original === (string) ($item['scenarioSubElement_id'] ?? '')) {
			return;
		}
		throw self::invalid("$field.id", 'Identifiant rattaché à un autre bloc.');
	}

	private function action(string $name, array $options, string $field): void {
		if (!in_array($name, self::ACTIONS, true)) {
			if (!preg_match('/^(?:#[0-9]+#|[0-9]+|#\[.+\]\[.+\]\[.+\]#)$/sD', $name)) {
				$this->userFunction($name, "$field.expression");
			} else {
				$this->requireCommand($name, 'action', "$field.expression");
			}
		}
		switch ($name) {
			case 'scenario':
			case 'remove_inat':
				$id = $options['scenario_id'] ?? '';
				if ($name === 'scenario' || !in_array($id, ['', null, 0, '0'], true) && !$this->runtimeTarget($id, "$field.options.scenario_id")) {
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
				if (!in_array($object, ['', null, 'all', '0', 0, '-1', -1], true) && !$this->runtimeTarget($object, "$field.options.object")) {
					$target = is_string($object) && !is_numeric($object) && !str_contains($object, '#') ? jeeObject::byName($object) : null;
					if (!$target instanceof jeeObject) {
						$target = $this->target($object, jeeObject::class, 'object', "$field.options.object");
					}
					if (!$target->hasRight('r', $this->user)) {
						throw self::invalid("$field.options.object", 'Objet inaccessible.');
					}
				}
				unset($options['object']);
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
				if (!$this->runtimeTarget($options['plan_id'] ?? null, "$field.options.plan_id")) {
					$this->target($options['plan_id'] ?? null, planHeader::class, '', "$field.options.plan_id");
				}
				unset($options['plan_id']);
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
							if (!$this->runtimeTarget($options['view_id'] ?? null, "$field.options.view_id")) {
								$this->target($options['view_id'] ?? null, view::class, '', "$field.options.view_id");
							}
							unset($options['view_id']);
							break;
						case 'plan':
							if (!$this->runtimeTarget($options['plan_id'] ?? null, "$field.options.plan_id")) {
								$this->target($options['plan_id'] ?? null, planHeader::class, '', "$field.options.plan_id");
							}
							unset($options['plan_id']);
							break;
					}
				}
				break;
		}
		foreach ($options as $key => $value) {
			$this->text($value, "$field.options.$key");
		}
	}

	private function userFunction(string $expression, string $field): void {
		if (!preg_match('/\A([a-zA-Z_\x80-\xff][\w\x80-\xff]*)(?:\(.*\))?\z/s', $expression, $match)) {
			throw self::invalid($field, 'Action de scénario inconnue.');
		}
		$name = $match[1];
		if (class_exists('userFunction', false)) {
			if (method_exists('userFunction', $name) && is_callable(['userFunction', $name])) {
				return;
			}
		} else {
			$this->userMethods ??= self::readUserMethods(__DIR__ . '/../../data/php/user.function.class.php');
			if (isset($this->userMethods['names'][strtolower($name)])) {
				return;
			}
			if (!$this->userMethods['complete']) {
				$this->warnings[] = ['field' => $field, 'reference' => $name, 'message' => 'Fonction utilisateur à vérifier au chargement de userFunction.'];
				return;
			}
		}
		throw self::invalid($field, 'Action de scénario inconnue.');
	}

	/** Lit les déclarations sans inclure le PHP local. Les traits, parents et classes
	 * fournis par un autre fichier ne peuvent pas tous être résolus statiquement. */
	private static function readUserMethods(string $file): array {
		$result = ['names' => [], 'complete' => !is_file($file)];
		if ($result['complete'] || ($source = @file_get_contents($file)) === false) {
			return $result;
		}
		$tokens = array_values(array_filter(PhpToken::tokenize($source), static fn ($t) => !$t->isIgnorable()));
		$start = null;
		foreach ($tokens as $i => $token) {
			if ($token->id === T_NAMESPACE) {
				return $result;
			}
			if ($token->id === T_CLASS && strcasecmp($tokens[$i + 1]->text ?? '', 'userFunction') === 0) {
				if ($start !== null || ($tokens[$i + 2]->text ?? '') !== '{') {
					return $result;
				}
				$start = $i + 3;
			}
		}
		if ($start === null) {
			return $result;
		}
		$result['complete'] = true;
		$depth = 1;
		for ($i = $start; isset($tokens[$i]); $i++) {
			$token = $tokens[$i];
			if ($token->is([ord('{'), T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES])) {
				$depth++;
			} elseif ($token->id === ord('}')) {
				if (--$depth === 0) {
					break;
				}
			} elseif ($depth === 1 && $token->id === T_USE) {
				$result['complete'] = false;
			} elseif ($depth === 1 && $token->id === T_FUNCTION) {
				$index = $i + 1;
				if (($tokens[$index]->text ?? '') === '&') {
					$index++;
				}
				$modifiers = [];
				for ($j = $i - 1; $j >= 0 && $tokens[$j]->is([T_PUBLIC, T_STATIC, T_FINAL, T_PRIVATE, T_PROTECTED, T_ABSTRACT]); $j--) {
					$modifiers[] = $tokens[$j]->id;
				}
				if (isset($tokens[$index]) && in_array(T_STATIC, $modifiers, true)
					&& !array_intersect($modifiers, [T_PRIVATE, T_PROTECTED, T_ABSTRACT])) {
					$result['names'][strtolower($tokens[$index]->text)] = true;
				}
			}
		}
		return $result;
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

	/** Options passées par setTags avant la recherche de la cible. Les références
	 * numériques fournissent la valeur d'une commande info, pas son identifiant. */
	private function runtimeTarget(mixed $value, string $field): bool {
		if (!is_string($value)) {
			return false;
		}
		$value = (string) jeedom::fromHumanReadable($value);
		if (preg_match('/\A#(?:object|scenario|eqLogic)\d+#\z/', $value)) {
			return false;
		}
		if (!preg_match('/#[^#\[\]\r\n]+#|\b[a-zA-Z][a-zA-Z1-9_]*\(/', $value)) {
			return false;
		}
		$this->text($value, $field);
		return true;
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
		$references = $numeric[0];
		foreach (array_unique($human[0]) as $reference) {
			// Un scénario et une commande partagent la notation à trois noms.
			if (!preg_match('/^#scenario[0-9]+#$/D', (string) jeedom::fromHumanReadable($reference))) {
				$references[] = $reference;
			}
		}
		return array_unique($references);
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

	private function condition(string $value, string $field, ?array $names = null): void {
		if (trim($value) === '') {
			return;
		}
		// Représente les tags et les fonctions évaluées par le moteur par des valeurs
		// fictives. Le parseur vérifie la syntaxe sans lire d'équipement ni évaluer de code.
		$value = preg_replace(cmd::HUMAN_REFERENCE_PATTERN, '1', $value);
		// Dans une chaîne déjà délimitée, un # peut aussi être du texte (couleur, motif…).
		$tagPattern = <<<'REGEX'
~(?:"(?:[^"\\]++|\\.)*"|'(?:[^'\\]++|\\.)*')(*SKIP)(*F)|##[^#\r\n]+##|#[^#\r\n]+#~s
REGEX;
		$value = preg_replace($tagPattern, '1', $value);
		$value = normalizeJeedomExpression($value);
		$language = new \Symfony\Component\ExpressionLanguage\ExpressionLanguage();
		preg_match_all('/\b([a-zA-Z_][a-zA-Z_0-9]*)\s*\(/', $value, $calls);
		foreach (array_unique($calls[1]) as $function) {
			if (!in_array($function, ['constant', 'enum'], true)) {
				$language->register($function, static fn () => '1', static fn () => 1);
			}
		}
		try {
			$language->lint($value, $names);
		} catch (\Symfony\Component\ExpressionLanguage\SyntaxError) {
			// setTags lit les arguments Jeedom comme du texte (ex. « first day of this month »).
			// Contrôler les opérateurs autour des appels sans évaluer leurs arguments.
			$mask = '~\b(?!(?:not|and|or|xor|in|matches)\b)[a-zA-Z][a-zA-Z1-9_]*(\((?:[^()]++|(?1))*\))~i';
			$outer = preg_replace($mask, '1', $value);
			if ($outer !== null) {
				$outer = str_replace('""', '"', $outer);
				if ($outer !== $value) {
					try {
						$language->lint($outer, $names);
						return;
					} catch (\Symfony\Component\ExpressionLanguage\SyntaxError) {
					}
				}
			}
			throw self::invalid($field, 'Syntaxe de l’expression invalide.');
		}
	}

	private static function invalid(string $field, string $message): scenarioValidationException {
		return new scenarioValidationException($field, $message);
	}
}
