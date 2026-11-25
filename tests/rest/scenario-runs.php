<?php

declare(strict_types=1);

use Jeedom\Api\Rest\ScenarioRuns;

require_once dirname(__DIR__, 2) . '/core/class/scenarioTrace.class.php';
require_once dirname(__DIR__, 2) . '/core/api/rest/src/ScenarioRuns.php';

final class log
{
    public static string $directory;

    public static function getPathToLog(string $name): string
    {
        return self::$directory . '/' . $name;
    }

    public static function exception(Throwable $error): string
    {
        return $error->getMessage();
    }
}

function expectSame($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' : ' . var_export($actual, true));
    }
}

$GLOBALS['JEEDOM_SCLOG_TEXT'] = [
    'start' => ['txt' => '-- Début :'],
    'finishOk' => ['txt' => 'Fin correcte du scénario'],
    'startSubTask' => ['txt' => 'Lancement sous tâche'],
    'endSubTask' => ['txt' => 'Fin sous tâche'],
];

log::$directory = sys_get_temp_dir() . '/jeedom-scenario-runs-' . bin2hex(random_bytes(8));
mkdir(log::$directory);
try {
    expectSame([], ScenarioRuns::read(1), 'Aucun lancement avant le premier log');
    $one = str_repeat('a', 32);
    $two = str_repeat('b', 32);
    $trace = new scenarioTrace(1, $one);
    $concurrent = new scenarioTrace(1, $two);
    $trace->start('Manuel', 'rev1');
    $concurrent->start('Programmation', 'rev1');
    $scheduled = $trace->within('block:12', function () use ($trace, $concurrent) {
        $trace->line('Planification du bloc', false);
        $concurrent->within('block:12', function () use ($concurrent) {
            $concurrent->line('Autre lancement', false);
        });
        $concurrent->finish();
        return $trace->context();
    });
    $trace->line('Fin du lancement principal', false);
    $trace->finish();

    $worker = new scenarioTrace(1, $scheduled['runId'], $scheduled['step']);
    $worker->within('part:5', function () use ($worker) {
        try {
            $worker->within('expression:8', function () { throw new RuntimeException('Commande indisponible'); });
        } catch (RuntimeException $error) {
            $worker->exception($error);
        }
        $worker->within('expression:8', function () use ($worker) { $worker->line('Deuxième passage', false); });
    });
    $detail = ScenarioRuns::read(1, $one);
    expectSame(['block:12', 'part:5', 'expression:8', 'expression:8'], array_column($detail['steps'], 'key'), 'Boucle et travail différé');
    expectSame($detail['steps'][0]['id'], $detail['steps'][1]['parent'], 'Le travail différé est rattaché au bloc qui l’a programmé');
    expectSame(true, $detail['steps'][2]['id'] !== $detail['steps'][3]['id'], 'Identité distincte des passages');
    expectSame('error', $detail['run']['status'], 'Une erreur différée change le résultat du lancement');
    expectSame([false, false, true, false], array_column($detail['lines'], 'error'), 'Niveau d’erreur et séparation des lancements');
    expectSame($detail['steps'][2]['id'], $detail['lines'][2]['step'], 'Erreur portée par l’action concernée');
    expectSame('finished', ScenarioRuns::read(1, $two)['run']['status'], 'Le lancement concurrent reste indépendant');

    file_put_contents(scenarioTrace::path(1),
        "[2026-01-01 09:00:00][SCENARIO] -- Début : Manuel.\n" .
        "[2026-01-01 09:00:01][SCENARIO] Fin correcte du scénario\n" .
        "[2026-01-01 09:00:02][SCENARIO] -- Début : Lancement A.\n" .
        "[2026-01-01 09:00:02][SCENARIO] -- Début : Lancement B.\n" .
        "[2026-01-01 09:00:02][SCENARIO] Fin correcte du scénario\n" .
        "[2026-01-01 09:00:03][SCENARIO] Fin correcte du scénario\n"
    );
    file_put_contents(scenarioTrace::path(1, true), '{"run":"fragment"', FILE_APPEND);
    $history = ScenarioRuns::read(1);
    expectSame(3, count($history), 'Un seul ancien lancement identifiable et deux traces complètes');
    expectSame(null, $history[2]['revision'], 'L’ancien lancement est textuel');
    expectSame('Manuel', $history[2]['trigger'], 'Déclencheur extrait du texte');
    $legacy = ScenarioRuns::read(1, $history[2]['id']);
    expectSame(2, count($legacy['lines']), 'Journal de l’ancien lancement');
    expectSame([], $legacy['steps'], 'Aucune association à des blocs pour un ancien journal');
} finally {
    scenarioTrace::clear(1);
    rmdir(log::$directory . '/scenarioLog');
    rmdir(log::$directory);
}
echo "Historique des scénarios : OK\n";
