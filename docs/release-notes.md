# All changes since release 4.6.0

## New core features
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-07-22 10:57:14 | Sync update notification message after each relevant action | [#3410](https://github.com/jeedom/core/pull/3410) |
| 2026-07-20 19:06:23 | Unify plugin install prompts and revamp the add plugin modal | [#3428](https://github.com/jeedom/core/pull/3428) |
| 2026-07-17 12:21:21 | Harmonize buttons and icons on the update page | [#3426](https://github.com/jeedom/core/pull/3426) |
| 2026-07-05 12:36:06 | Recovery use new build-provided product_name for Smart/Atlas | [#3398](https://github.com/jeedom/core/pull/3398) |
| 2026-06-30 15:36:39 | Uncheck default plugins update | [#3406](https://github.com/jeedom/core/pull/3406) |
| 2026-06-29 16:12:13 | Add history retention mode | [#3401](https://github.com/jeedom/core/pull/3401) |
| 2026-06-25 16:40:35 | Update Jeedom logos to new branding | [#3392](https://github.com/jeedom/core/pull/3392) |

## Breaking changes
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-08-17 12:18:51 | Centralize eqLogic remove confirmation and post-delete redirect | [#3415](https://github.com/jeedom/core/pull/3415) |

## Fixes
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-08-27 13:50:54 | Enhance Samba command security with temporary authentication file | [#3475](https://github.com/jeedom/core/pull/3475) |
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

## Others
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-08-18 20:15:41 | Make plugin::byId/isInstalled cache handling and full parameter typing explicit | [#3453](https://github.com/jeedom/core/pull/3453) |
| 2026-08-17 13:33:18 | incorrect translation | [#3463](https://github.com/jeedom/core/pull/3463) |
| 2026-07-22 10:45:14 | Modernize Element.prototype.empty using replaceChildren | [#3417](https://github.com/jeedom/core/pull/3417) |
| 2026-06-27 21:01:28 | Use guard clauses in cmd->addHistoryValue | [#3400](https://github.com/jeedom/core/pull/3400) |

## Documentations
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-07-16 09:18:32 | 2 wording corrections in Objects documentation (object.md) | [#3422](https://github.com/jeedom/core/pull/3422) |

## Developer
| Merge date | Title | PR |
| --- | --- | --- |
| 2026-08-18 08:21:10 | Remove PHP 7.3 workflow from GitHub Actions | [#3465](https://github.com/jeedom/core/pull/3465) |
| 2026-07-30 14:11:05 | Speed up plugin::isInstalled and use it instead of byId for existence checks | [#3446](https://github.com/jeedom/core/pull/3446) |
| 2026-07-01 18:15:21 | chore(deps): bump actions/checkout from 6 to 7 | [#3412](https://github.com/jeedom/core/pull/3412) |
| 2026-07-01 18:14:57 | chore(deps): bump actions/cache from 5 to 6 | [#3411](https://github.com/jeedom/core/pull/3411) |
| 2026-06-26 16:47:36 | Remove V4-stable-update workflow | [#3397](https://github.com/jeedom/core/pull/3397) |

