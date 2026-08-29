#!/usr/bin/env node
/**
 * ESLint baseline checker for Jeedom core.
 *
 * Runs ESLint on JS sources, compares results against eslint-baseline.json,
 * and fails if a new violation appears (i.e. a new {file, ruleId, message}
 * tuple not present in the baseline). Allows existing violations to
 * disappear (e.g. when a fix lands).
 *
 * Usage:
 *   node .github/scripts/eslint-baseline-check.js           # check
 *   node .github/scripts/eslint-baseline-check.js --update  # regenerate baseline
 */

const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..', '..');
const BASELINE_PATH = path.join(ROOT, 'eslint-baseline.json');
const TARGETS = ['core/js', 'desktop/js'];

function runEslint() {
  const eslintBin = path.join(ROOT, 'node_modules', '.bin', 'eslint');
  const result = spawnSync(eslintBin, ['-f', 'json', ...TARGETS], {
    cwd: ROOT,
    encoding: 'utf8',
    maxBuffer: 50 * 1024 * 1024
  });
  if (result.error) {
    console.error('Failed to spawn ESLint:', result.error);
    process.exit(2);
  }
  // ESLint exits non-zero when it finds errors; that's fine, we still parse stdout.
  if (!result.stdout) {
    console.error('ESLint produced no output. stderr:', result.stderr);
    process.exit(2);
  }
  return JSON.parse(result.stdout);
}

function normalize(report) {
  const entries = [];
  for (const file of report) {
    const rel = path.relative(ROOT, file.filePath).replace(/\\/g, '/');
    for (const msg of file.messages || []) {
      if (msg.severity !== 2) continue;
      entries.push({
        file: rel,
        ruleId: msg.ruleId,
        message: msg.message
      });
    }
  }
  entries.sort((a, b) =>
    (a.file + (a.ruleId || '') + a.message).localeCompare(
      b.file + (b.ruleId || '') + b.message
    )
  );
  return entries;
}

function tupleKey(e) {
  return `${e.file}|${e.ruleId || ''}|${e.message}`;
}

function loadBaseline() {
  if (!fs.existsSync(BASELINE_PATH)) return [];
  return JSON.parse(fs.readFileSync(BASELINE_PATH, 'utf8'));
}

function main() {
  const update = process.argv.includes('--update');
  const current = normalize(runEslint());

  if (update) {
    fs.writeFileSync(BASELINE_PATH, JSON.stringify(current, null, 2) + '\n');
    console.log(`Baseline updated: ${current.length} entries written to eslint-baseline.json`);
    return;
  }

  const baseline = loadBaseline();
  const baselineKeys = new Set(baseline.map(tupleKey));
  const currentKeys = new Set(current.map(tupleKey));

  const added = current.filter(e => !baselineKeys.has(tupleKey(e)));
  const removed = baseline.filter(e => !currentKeys.has(tupleKey(e)));

  console.log(`Baseline: ${baseline.length} entries`);
  console.log(`Current:  ${current.length} entries`);
  console.log(`Removed:  ${removed.length} entries (fixes welcome)`);
  console.log(`Added:    ${added.length} entries`);

  if (added.length > 0) {
    console.error('\nNew ESLint violations not present in baseline:');
    for (const e of added) {
      console.error(`  ${e.file}: [${e.ruleId}] ${e.message}`);
    }
    console.error(
      '\nFix these violations or, if intentional, regenerate the baseline:\n' +
      '  npm run lint:baseline'
    );
    process.exit(1);
  }

  console.log('\nNo new violations. ');
}

main();
