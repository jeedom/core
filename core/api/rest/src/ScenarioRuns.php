<?php

declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Lit les traces récentes sans prendre le verrou du moteur pendant une exécution. */
final class ScenarioRuns
{
    private const MAX_READ = 8 * 1024 * 1024;
    private const MAX_RUNS = 50;

    public static function read(int $scenarioId, ?string $runId = null): array
    {
        $runs = [];
        $steps = [];
        $lines = [];
        $firstTrace = INF;

        foreach (self::lines(\scenarioTrace::path($scenarioId, true)) as $line) {
            $event = json_decode($line, true);
            if (!isset($event['run'], $event['at'], $event['type'])) {
                continue;
            }
            $id = $event['run'];
            if ($event['type'] === 'start') {
                $firstTrace = min($firstTrace, floor($event['at']));
                $runs[$id] = [
                    'id' => $id,
                    'startedAt' => date(DATE_ATOM, (int) $event['at']),
                    'durationMs' => null,
                    'trigger' => $event['trigger'],
                    'status' => 'incomplete',
                    'revision' => $event['revision'],
                    'started' => $event['at'],
                ];
            } elseif (isset($runs[$id])) {
                if ($event['type'] === 'finish') {
                    $runs[$id]['durationMs'] = max(0, (int) round(($event['at'] - $runs[$id]['started']) * 1000));
                    if ($runs[$id]['status'] !== 'error') {
                        $runs[$id]['status'] = 'finished';
                    }
                } elseif ($event['type'] === 'log' && $event['error']) {
                    $runs[$id]['status'] = 'error';
                }
            } else {
                // La rotation du journal peut laisser la fin d'un lancement.
                continue;
            }

            if ($id === $runId) {
                if ($event['type'] === 'step') {
                    $steps[] = ['id' => $event['id'], 'parent' => $event['parent'], 'key' => $event['key']];
                } elseif ($event['type'] === 'log') {
                    $lines[] = ['step' => $event['step'], 'text' => $event['text'], 'error' => $event['error']];
                }
            }
        }

        if ($runId === null || str_starts_with($runId, 'legacy-')) {
            foreach (self::legacy($scenarioId, $firstTrace) as [$run, $log]) {
                $runs[$run['id']] = $run;
                if ($run['id'] === $runId) {
                    $lines = array_map(static fn ($text) => ['step' => null, 'text' => $text, 'error' => false], $log);
                }
            }
        }
        if ($runId !== null) {
            return isset($runs[$runId]) ? ['run' => self::summary($runs[$runId]), 'steps' => $steps, 'lines' => $lines] : [];
        }
        uasort($runs, static fn ($a, $b) => $b['started'] <=> $a['started']);
        return array_values(array_map(self::summary(...), array_slice($runs, 0, self::MAX_RUNS)));
    }

    private static function summary(array $run): array
    {
        unset($run['started']);
        return $run;
    }

    private static function lines(string $path): iterable
    {
        $file = @fopen($path, 'rb');
        if ($file === false) {
            return;
        }
        try {
            $size = (int) fstat($file)['size'];
            $start = max(0, $size - self::MAX_READ);
            if ($start > 0 && fseek($file, $start - 1) === 0 && fgetc($file) !== "\n") {
                fgets($file);
            }
            while (ftell($file) < $size && ($line = fgets($file)) !== false && str_ends_with($line, "\n")) {
                yield rtrim($line, "\r\n");
            }
        } finally {
            fclose($file);
        }
    }

    /** Les anciens journaux ne donnent un lancement fiable qu'entre un début et une fin sans chevauchement. */
    private static function legacy(int $scenarioId, float $before): iterable
    {
        $messages = $GLOBALS['JEEDOM_SCLOG_TEXT'];
        $start = $messages['start']['txt'];
        $finish = $messages['finishOk']['txt'];
        $depth = 0;
        $ambiguous = false;
        $started = false;
        $log = [];
        foreach (self::lines(\scenarioTrace::path($scenarioId)) as $line) {
            if ($line === '------------------------------------') {
                $depth = 0;
                $log = [];
                continue;
            }
            $message = preg_match('/^\[([^\]]+)\]\[SCENARIO\] (.*)$/D', $line, $parts) ? $parts[2] : '';
            if (str_starts_with($message, $start)) {
                ++$depth;
                $ambiguous = $depth > 1;
                if ($ambiguous) {
                    continue;
                }
                $started = strtotime($parts[1]);
                $trigger = trim(substr($message, strlen($start)));
                if (($tagsAt = strpos($trigger, ' Tags : ')) !== false) {
                    $tags = json_decode(substr($trigger, $tagsAt + 8), true);
                    $trigger = $tags['#trigger_message#'] ?? $tags['trigger_message'] ?? substr($trigger, 0, $tagsAt);
                }
                $trigger = trim($trigger, " .\t");
                $log = [];
            } elseif ($depth > 0 && (str_starts_with($message, $messages['startSubTask']['txt']) || str_starts_with($message, $messages['endSubTask']['txt']))) {
                $ambiguous = true;
            }
            if ($depth > 0 && !$ambiguous) {
                $log[] = mb_convert_encoding($line, 'UTF-8', 'UTF-8');
            }
            if ($message === $finish && $depth > 0 && --$depth === 0 && !$ambiguous && $started !== false && $started < $before) {
                $ended = strtotime($parts[1]);
                yield [[
                    'id' => 'legacy-' . hash('sha256', implode("\n", $log)),
                    'startedAt' => date(DATE_ATOM, $started),
                    'durationMs' => $ended === false ? null : max(0, ($ended - $started) * 1000),
                    'trigger' => $trigger,
                    'status' => 'unknown',
                    'revision' => null,
                    'started' => $started,
                ], $log];
            }
        }
    }
}
