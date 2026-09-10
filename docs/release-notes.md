# All changes since release 4.6.0

## New core features
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-09-08 08:58:08 | Feature: add audit log | [#3493](https://github.com/jeedom/core/pull/3493) |
| 2026-09-04 21:34:21 | Add Debian 13 Trixie compatibility and raises minimum required to Debian 12 | [#3483](https://github.com/jeedom/core/pull/3483) |
| 2026-09-04 21:28:50 | Update Highstock/Highcharts to 12.6.0 | [#3457](https://github.com/jeedom/core/pull/3457) |
| 2026-08-27 13:50:54 | Enhance Samba command security with temporary authentication file | [#3475](https://github.com/jeedom/core/pull/3475) |
| 2026-07-22 10:57:14 | Sync update notification message after each relevant action | [#3410](https://github.com/jeedom/core/pull/3410) |
| 2026-07-20 19:06:23 | Unify plugin install prompts and revamp the add plugin modal | [#3428](https://github.com/jeedom/core/pull/3428) |
| 2026-07-17 12:21:21 | Harmonize buttons and icons on the update page | [#3426](https://github.com/jeedom/core/pull/3426) |
| 2026-07-05 12:36:06 | Recovery use new build-provided product_name for Smart/Atlas | [#3398](https://github.com/jeedom/core/pull/3398) |
| 2026-06-30 15:36:39 | Uncheck default plugins update | [#3406](https://github.com/jeedom/core/pull/3406) |
| 2026-06-29 16:12:13 | Add history retention mode | [#3401](https://github.com/jeedom/core/pull/3401) |
| 2026-06-25 16:40:35 | Update Jeedom logos to new branding | [#3392](https://github.com/jeedom/core/pull/3392) |
| 2026-06-19 12:09:45 | Update display.php | [#3379](https://github.com/jeedom/core/pull/3379) |

## Breaking changes
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-09-01 08:12:25 | migrate password sha512 to php native | [#3476](https://github.com/jeedom/core/pull/3476) |
| 2026-09-01 08:05:52 | add option to avoid infinite save loops in eqLogic import | [#3471](https://github.com/jeedom/core/pull/3471) |
| 2026-08-17 12:18:51 | Centralize eqLogic remove confirmation and post-delete redirect | [#3415](https://github.com/jeedom/core/pull/3415) |
| 2026-06-23 11:57:10 | chore: remove InfluxDB integration | [#3306](https://github.com/jeedom/core/pull/3306) |

## Fixes
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-09-10 08:24:24 | Remove session_id from registeredDevice options in toArray method | [#3507](https://github.com/jeedom/core/pull/3507) |
| 2026-09-09 18:00:41 | Review user_connect & audit log fine tuning | [#3504](https://github.com/jeedom/core/pull/3504) |
| 2026-09-08 09:56:11 | Refactor user input handling: add validation and sanitization methods for login and password | [#3500](https://github.com/jeedom/core/pull/3500) |
| 2026-09-07 10:06:59 | Rework health() diagnostics and fix inconsistent checks | [#3491](https://github.com/jeedom/core/pull/3491) |
| 2026-09-07 09:59:54 | Fix shared cmd::update value mutation breaking live chart updates | [#3452](https://github.com/jeedom/core/pull/3452) |
| 2026-09-07 09:56:36 | Fix: Scenario triggers failing when trailing spaces are present | [#3494](https://github.com/jeedom/core/pull/3494) |
| 2026-09-07 09:56:13 | Fix autoload always loading a plugin's main class file instead of a dedicated one | [#3444](https://github.com/jeedom/core/pull/3444) |
| 2026-09-07 09:54:16 | Prevent setAxisScales from overriding an active chart zoom | [#3451](https://github.com/jeedom/core/pull/3451) |
| 2026-09-07 09:50:02 | Fix NaN comparisons in DataTables column sorting | [#3413](https://github.com/jeedom/core/pull/3413) |
| 2026-09-07 09:43:33 | Fire a change event on ispin spinners and guard against double init | [#3436](https://github.com/jeedom/core/pull/3436) |
| 2026-09-07 09:41:00 | Remove useless floatval() cast in history::getHistoryFromCalcul | [#3399](https://github.com/jeedom/core/pull/3399) |
| 2026-09-07 09:39:32 | Fix setTags() reference error in jeeApi & plan3d | [#3408](https://github.com/jeedom/core/pull/3408) |
| 2026-09-07 09:37:06 | add duplicate login check on user update | [#3486](https://github.com/jeedom/core/pull/3486) |
| 2026-09-07 09:36:05 | Fix icon selector search crashing and not filtering object background images | [#3477](https://github.com/jeedom/core/pull/3477) |
| 2026-09-07 09:31:27 | Fix login page layout | [#3469](https://github.com/jeedom/core/pull/3469) |
| 2026-09-04 16:12:23 | Security fix: prevent password hash and 2FA secret leakage in user data responses | [#3482](https://github.com/jeedom/core/pull/3482) |
| 2026-09-02 10:52:02 | Fix history charts on views/designs never showing the continuation-to-now dash | [#3458](https://github.com/jeedom/core/pull/3458) |
| 2026-08-31 14:59:18 | Refactor user hash regeneration to avoid double save side effects | [#3470](https://github.com/jeedom/core/pull/3470) |
| 2026-08-20 13:27:53 | Simplify OpenVPN and virtual plugin auto-install checks | [#3454](https://github.com/jeedom/core/pull/3454) |
| 2026-08-18 20:06:11 | Fix stale dashed tail not clearing on live history chart update | [#3456](https://github.com/jeedom/core/pull/3456) |
| 2026-08-15 17:41:40 | Restore unreachable error handling when plugin::byId can't find a plugin | [#3455](https://github.com/jeedom/core/pull/3455) |
| 2026-07-30 14:10:24 | Fix real-time graph updates: skip inconsistent cases and rescale the Y axis | [#3437](https://github.com/jeedom/core/pull/3437) |
| 2026-07-30 12:27:42 | Remove stale mistranslated German "Batterie" translation | [#3448](https://github.com/jeedom/core/pull/3448) |
| 2026-07-27 17:04:17 | Fix widget size (zoom) breaking grid snap and containment in the design editor | [#3441](https://github.com/jeedom/core/pull/3441) |
| 2026-07-27 17:02:48 | Fix resize stop callback not firing | [#3439](https://github.com/jeedom/core/pull/3439) |
| 2026-07-27 16:35:09 | Sanitize dynamic tooltip content before rendering it as HTML | [#3440](https://github.com/jeedom/core/pull/3440) |
| 2026-07-24 14:08:25 | Fix transformations and pie chart handling on real-time history graph updates | [#3434](https://github.com/jeedom/core/pull/3434) |
| 2026-07-22 18:33:00 | Fire real click/change events when toggling checkboxes via the context menu | [#3427](https://github.com/jeedom/core/pull/3427) |
| 2026-07-22 10:55:38 | Scope clearToasts to the dialog being closed | [#3414](https://github.com/jeedom/core/pull/3414) |
| 2026-07-22 10:52:07 | Fix setTags() reference error in interactQuery | [#3407](https://github.com/jeedom/core/pull/3407) |
| 2026-07-22 10:51:13 | Fix plugin custom events never reaching native addEventListener listeners | [#3416](https://github.com/jeedom/core/pull/3416) |
| 2026-07-22 10:44:28 | Always place the cancel button before confirm in jeeDialog footers | [#3423](https://github.com/jeedom/core/pull/3423) |
| 2026-07-22 10:43:46 | Avoid config pollution when a plugin lookup fails on a non-existent id | [#3421](https://github.com/jeedom/core/pull/3421) |
| 2026-07-17 16:29:00 | Fix broken translations on the update page | [#3431](https://github.com/jeedom/core/pull/3431) |
| 2026-07-16 09:33:46 | Harmonize wording of selection modal titles | [#3424](https://github.com/jeedom/core/pull/3424) |
| 2026-06-30 11:29:16 | Prevent recording uppercase status in update database | [#3405](https://github.com/jeedom/core/pull/3405) |
| 2026-06-25 13:40:05 | fix in case pdo hydratation set timeout to null | [#3393](https://github.com/jeedom/core/pull/3393) |
| 2026-06-25 13:39:19 | fix in case pdo hydratation set timeout to null | [#3395](https://github.com/jeedom/core/pull/3395) |
| 2026-06-23 11:10:53 | Fix missing translations in cmd.configure modal | [#3384](https://github.com/jeedom/core/pull/3384) |

## Others
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-09-10 09:59:13 | Chore: Update PHP version checks to require PHP 7.4 or higher | [#3509](https://github.com/jeedom/core/pull/3509) |
| 2026-09-07 09:48:19 | Chore: clean useless filter | [#3450](https://github.com/jeedom/core/pull/3450) |
| 2026-09-07 09:45:59 | Remove dead widget plugin template lookup | [#3447](https://github.com/jeedom/core/pull/3447) |
| 2026-09-04 21:38:24 | Fix hardcoded result property and incorrect options variable | [#3487](https://github.com/jeedom/core/pull/3487) |
| 2026-08-18 20:15:41 | Make plugin::byId/isInstalled cache handling and full parameter typing explicit | [#3453](https://github.com/jeedom/core/pull/3453) |
| 2026-08-17 13:33:18 | incorrect translation | [#3463](https://github.com/jeedom/core/pull/3463) |
| 2026-07-30 14:11:05 | Speed up plugin::isInstalled and use it instead of byId for existence checks | [#3446](https://github.com/jeedom/core/pull/3446) |
| 2026-07-22 10:45:14 | Modernize Element.prototype.empty using replaceChildren | [#3417](https://github.com/jeedom/core/pull/3417) |
| 2026-06-27 21:01:28 | Use guard clauses in cmd->addHistoryValue | [#3400](https://github.com/jeedom/core/pull/3400) |
| 2026-06-23 11:59:28 | patch: remove nginx support | [#3376](https://github.com/jeedom/core/pull/3376) |

## Documentations
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-07-16 09:18:32 | 2 wording corrections in Objects documentation (object.md) | [#3422](https://github.com/jeedom/core/pull/3422) |

## Developer
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-09-07 09:36:53 | Drop bullseye from CI and Docker Hub build matrices | [#3492](https://github.com/jeedom/core/pull/3492) |
| 2026-09-01 10:51:55 | Refactor draft release notes workflow to remove tag date retrieval and improve PR fetching logic | [#3481](https://github.com/jeedom/core/pull/3481) |
| 2026-09-01 08:55:47 | Merge back to develop hotfix 4.6.1 | [#3480](https://github.com/jeedom/core/pull/3480) |
| 2026-08-18 08:21:10 | Remove PHP 7.3 workflow from GitHub Actions | [#3465](https://github.com/jeedom/core/pull/3465) |
| 2026-07-01 18:15:21 | chore(deps): bump actions/checkout from 6 to 7 | [#3412](https://github.com/jeedom/core/pull/3412) |
| 2026-07-01 18:14:57 | chore(deps): bump actions/cache from 5 to 6 | [#3411](https://github.com/jeedom/core/pull/3411) |
| 2026-06-26 16:47:36 | Remove V4-stable-update workflow | [#3397](https://github.com/jeedom/core/pull/3397) |
| 2026-06-23 14:25:02 | chore: merge back release to develop | [#3386](https://github.com/jeedom/core/pull/3386) |

