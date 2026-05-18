# All changes since release 4.5.3

## New core features
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-05-11 08:56:25 | Feat: add while block in scenario | [#3234](https://github.com/jeedom/core/pull/3234) |
| 2026-05-09 16:16:05 | Unify legacy time widgets into standard widgets | [#3332](https://github.com/jeedom/core/pull/3332) |
| 2026-04-19 15:18:24 | Add healthcheck in the docker container | [#2998](https://github.com/jeedom/core/pull/2998) |

## Breaking changes
no entries

## Fixes
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-05-18 17:27:23 | Code improvements and behavior fixes (PHPStan level 1) | [#3260](https://github.com/jeedom/core/pull/3260) |
| 2026-05-18 15:41:29 | Fix jeedom update via api | [#3352](https://github.com/jeedom/core/pull/3352) |
| 2026-05-18 15:40:15 | fix: expected type object, get string ($now) | [#3353](https://github.com/jeedom/core/pull/3353) |
| 2026-05-18 09:22:48 | fix test expression result | [#3349](https://github.com/jeedom/core/pull/3349) |
| 2026-05-12 15:01:09 | Fix & improvement: add safe guard in scenario in "for" loop, "wait" & "sleep" actions | [#3341](https://github.com/jeedom/core/pull/3341) |
| 2026-05-12 10:50:34 | fix - time limit on php jeeplugin | [#3343](https://github.com/jeedom/core/pull/3343) |
| 2026-05-12 09:39:52 | fix: remove Access-Control-Allow-Credentials with Allow-Origin: * | [#3277](https://github.com/jeedom/core/pull/3277) |
| 2026-05-09 14:46:51 | fix: resolve TDZ ReferenceError for 'wg' in widgets.js | [#3333](https://github.com/jeedom/core/pull/3333) |
| 2026-05-09 10:37:37 | fix: add force version update option during upgrade | [#3330](https://github.com/jeedom/core/pull/3330) |
| 2026-05-09 08:50:04 | Fix/chunk log | [#3316](https://github.com/jeedom/core/pull/3316) |
| 2026-05-08 11:44:36 | fix(desktop/js): correct 11 DOM and logic bugs (dashboard, object, plan, replace, eqAnalyse, administration, update) | [#3318](https://github.com/jeedom/core/pull/3318) |
| 2026-05-04 10:02:20 | Fix timeline folder field visibility on scenario page load | [#3305](https://github.com/jeedom/core/pull/3305) |
| 2026-04-25 08:00:07 | Fix: translations string concatenation | [#3289](https://github.com/jeedom/core/pull/3289) |
| 2026-04-21 11:24:20 | fix: prevent a possible SQL injection in archive process | [#3268](https://github.com/jeedom/core/pull/3268) |
| 2026-04-20 11:11:26 | Fix: scenario expression execute without scenario | [#3228](https://github.com/jeedom/core/pull/3228) |
| 2026-04-20 11:03:03 | Fix undefined  variable in AJAX remove after market→repo migration | [#3118](https://github.com/jeedom/core/pull/3118) |
| 2026-04-19 20:34:27 | Rename count variable to count_functionality | [#3273](https://github.com/jeedom/core/pull/3273) |
| 2026-04-19 08:25:34 | fix: prevent a possible SQL injection in setComponentOrder | [#3267](https://github.com/jeedom/core/pull/3267) |
| 2026-04-17 15:14:11 | fix: display_name field (ui) | [#3262](https://github.com/jeedom/core/pull/3262) |
| 2026-04-17 14:01:55 | fix: scenario self execute mixing tags | [#3255](https://github.com/jeedom/core/pull/3255) |
| 2026-04-17 09:35:39 | fix: Suppression des arguments superflus dans les appels de méthodes (PHPStan lvl 1) | [#3258](https://github.com/jeedom/core/pull/3258) |
| 2026-04-17 09:35:16 | fix: Correction des méthodes statiques et du nommage de classes (PHPStan lvl 1) | [#3259](https://github.com/jeedom/core/pull/3259) |
| 2026-04-16 13:05:53 | Fix: issue displaying history if grouping type is set | [#3242](https://github.com/jeedom/core/pull/3242) |
| 2026-04-03 08:46:58 | fix: randText function | [#3197](https://github.com/jeedom/core/pull/3197) |
| 2026-04-02 14:13:11 | fix: resolve 3 bugs in proxy configuration in jsonrpcClient | [#3238](https://github.com/jeedom/core/pull/3238) |
| 2026-03-31 20:51:42 | fix: graphUpdate function in history class | [#3178](https://github.com/jeedom/core/pull/3178) |

## Others
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-05-18 14:30:21 | Refactor error handling to use \Throwable instead of Exception and Error | [#3347](https://github.com/jeedom/core/pull/3347) |
| 2026-05-18 14:21:29 | Refactor & code Cleanup in scenario | [#3346](https://github.com/jeedom/core/pull/3346) |
| 2026-05-12 10:37:46 | refactor(dom.ui): seen(), unseen() and toggle() use hidden class consistently | [#3342](https://github.com/jeedom/core/pull/3342) |
| 2026-05-11 09:08:24 | Update translations for 'while' block terminology | [#3339](https://github.com/jeedom/core/pull/3339) |
| 2026-05-09 19:12:01 | Chore: secure parameter typehints | [#3317](https://github.com/jeedom/core/pull/3317) |
| 2026-05-08 15:06:43 | refactor(desktop/js): replace var with const/let in display modules (dashboard, widgets, plan, plan3d, eqAnalyse) | [#3320](https://github.com/jeedom/core/pull/3320) |
| 2026-05-06 15:22:08 | Optimize Jeedom core version retrieval by caching the result | [#3315](https://github.com/jeedom/core/pull/3315) |
| 2026-05-06 10:00:20 | Refactor var to const/let in equipment and commands (2/4) | [#3301](https://github.com/jeedom/core/pull/3301) |
| 2026-05-06 09:19:39 | Refactor var to const/let in admin/system + log search multi-term (4/4) | [#3303](https://github.com/jeedom/core/pull/3303) |
| 2026-05-05 17:04:02 | Refactor var to const/let in core JS utilities (1/4) | [#3300](https://github.com/jeedom/core/pull/3300) |
| 2026-05-05 17:02:29 | Refactor var to const/let in UI rendering files (3/4) | [#3302](https://github.com/jeedom/core/pull/3302) |
| 2026-05-05 14:28:06 | add log in plugin autoloader | [#3276](https://github.com/jeedom/core/pull/3276) |
| 2026-04-13 08:16:53 | feat: improve "do not remove log" feature | [#3245](https://github.com/jeedom/core/pull/3245) |
| 2026-04-08 09:16:23 | chore: Correction *aucun* script | [#3246](https://github.com/jeedom/core/pull/3246) |
| 2026-04-04 07:56:05 | chore: typo Securité | [#3241](https://github.com/jeedom/core/pull/3241) |

## Documentations
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-05-13 10:00:53 | docs: refactor Core widgets  and document scenario block execution time limits | [#3345](https://github.com/jeedom/core/pull/3345) |
| 2026-04-20 11:02:18 | Auto-generated draft of release notes / changelog in develop | [#3278](https://github.com/jeedom/core/pull/3278) |

## Developer
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-05-13 09:49:57 | feat: change docker image building workflow into matrix | [#3172](https://github.com/jeedom/core/pull/3172) |
| 2026-05-09 17:30:24 | chore: translate PHPStan workflow PR body to English | [#3336](https://github.com/jeedom/core/pull/3336) |
| 2026-05-09 07:43:23 | chore: ignore .claude settings directory | [#3329](https://github.com/jeedom/core/pull/3329) |
| 2026-05-05 10:21:10 | Bump peter-evans/create-pull-request from 5 to 8 | [#3307](https://github.com/jeedom/core/pull/3307) |
| 2026-05-05 10:20:50 | Bump actions/github-script from 7 to 9 | [#3308](https://github.com/jeedom/core/pull/3308) |
| 2026-05-05 10:20:32 | Bump actions/checkout from 2 to 6 | [#3309](https://github.com/jeedom/core/pull/3309) |
| 2026-04-25 11:58:25 | Increase PR list limit to 1000 iso 30 by default | [#3296](https://github.com/jeedom/core/pull/3296) |
| 2026-04-24 17:12:16 | don't use core translations | [#3294](https://github.com/jeedom/core/pull/3294) |
| 2026-04-24 15:04:14 | Bump docker/setup-qemu-action from 2 to 4 | [#3279](https://github.com/jeedom/core/pull/3279) |
| 2026-04-24 15:03:36 | Bump docker/login-action from 2 to 4 | [#3280](https://github.com/jeedom/core/pull/3280) |
| 2026-04-24 15:03:23 | Bump docker/build-push-action from 3 to 7 | [#3281](https://github.com/jeedom/core/pull/3281) |
| 2026-04-24 14:54:09 | Bump docker/setup-buildx-action from 2 to 4 | [#3282](https://github.com/jeedom/core/pull/3282) |
| 2026-04-24 14:03:50 | remove french translations from others languages file | [#3290](https://github.com/jeedom/core/pull/3290) |
| 2026-04-20 11:38:39 | Bump actions/cache from 3 to 5 | [#3283](https://github.com/jeedom/core/pull/3283) |
| 2026-04-20 11:29:10 | add [skip ci] in commit message to try to avoid execution of others WF | [#3287](https://github.com/jeedom/core/pull/3287) |
| 2026-04-20 11:01:32 | add dependabot only for github-actions at first | [#3239](https://github.com/jeedom/core/pull/3239) |
| 2026-04-17 21:11:17 | new workflow for translations | [#3251](https://github.com/jeedom/core/pull/3251) |
| 2026-04-17 12:26:56 | Fix PHPStan workflow on branch deletion | [#3263](https://github.com/jeedom/core/pull/3263) |
| 2026-04-16 22:32:24 | Use Composer dependency for PHPStan | [#3256](https://github.com/jeedom/core/pull/3256) |
| 2026-04-03 15:27:29 | Update GitHub workflows to use 'develop' branch instead of 'alpha' and 'beta' | [#3240](https://github.com/jeedom/core/pull/3240) |

