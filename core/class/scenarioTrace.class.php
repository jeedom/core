<?php

/** Trace d'exécution structurée, conservée avec les journaux de scénario. */
final class scenarioTrace {
	private int $scenarioId;
	private string $runId;
	private ?string $step;
	private string $worker;
	private int $sequence = 0;
	private ?int $reported = null;

	public function __construct(int $scenarioId, string $runId, ?string $parent = null) {
		$this->scenarioId = $scenarioId;
		$this->runId = $runId;
		$this->step = $parent;
		$this->worker = getmypid() . '-' . uniqid();
	}

	public static function path(int $scenarioId, bool $trace = false): string {
		return log::getPathToLog('scenarioLog/scenario' . $scenarioId . ($trace ? '.jsonl' : '.log'));
	}

	public static function clear(int $scenarioId): void {
		foreach ([self::path($scenarioId), self::path($scenarioId, true)] as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}

	public function context(): array {
		return ['runId' => $this->runId, 'step' => $this->step];
	}

	public function start(string $trigger, string $revision): void {
		$this->write('start', ['trigger' => $trigger, 'revision' => $revision]);
	}

	public function finish(): void {
		$this->write('finish');
		$this->reported = null;
	}

	public function within(string $key, callable $execute) {
		$this->reported = null;
		$parent = $this->step;
		$this->step = $this->worker . '-' . ++$this->sequence;
		$this->write('step', ['id' => $this->step, 'parent' => $parent, 'key' => $key]);
		try {
			return $execute();
		} catch (Throwable $error) {
			$this->exception($error);
			throw $error;
		} finally {
			$this->step = $parent;
		}
	}

	public function exception(Throwable $error): void {
		if ($this->reported !== spl_object_id($error)) {
			$this->reported = spl_object_id($error);
			$this->line('[' . date('Y-m-d H:i:s') . '][SCENARIO] ' . log::exception($error), true);
		}
	}

	public function line(string $text, bool $error): void {
		$this->write('log', ['step' => $this->step, 'text' => $text, 'error' => $error]);
	}

	private function write(string $type, array $data = []): void {
		$file = self::path($this->scenarioId, true);
		// L'écriture de la trace ne doit jamais interrompre le moteur.
		if (!is_dir(dirname($file)) && !@mkdir(dirname($file), 0775, true) && !is_dir(dirname($file))) {
			return;
		}
		$line = json_encode(['run' => $this->runId, 'at' => microtime(true), 'type' => $type] + $data, JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
		if ($line !== false) {
			@file_put_contents($file, $line . "\n", FILE_APPEND | LOCK_EX);
		}
	}
}
