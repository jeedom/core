# Jeedom V4.6 Changelog

## 4.6.1

- Fixed an issue with editing a scenario whose timeout is *null* in the database

## 4.6.0

### New Features

- Adding a "While" block to scenarios ([Details](https://github.com/jeedom/core/pull/3234){:target="_blank"})
- New method for translating the interface ([Details](https://github.com/jeedom/core/pull/3251){:target="_blank"})
- Consolidation of the old time-based widgets *(`timeXxxx`)* to standard widgets with parameters `time` corresponding ([Details](https://github.com/jeedom/core/pull/3332){:target="_blank"})
- [Advanced] Adding a `healthcheck` for Docker installations ([Details](https://github.com/jeedom/core/pull/2998){:target="_blank"})

### Patches

- Fixed the automatic update of graphs ([Details](https://github.com/jeedom/core/pull/3178){:target="_blank"})
- Correction of the mathematical function `randText` ([Details](https://github.com/jeedom/core/pull/3197){:target="_blank"})
- Improving the reliability of using specific actions outside of a scenario ([Details](https://github.com/jeedom/core/pull/3228){:target="_blank"})
- Fixed date range selection *(Zoom)* with grouping in the history ([Details](https://github.com/jeedom/core/pull/3242){:target="_blank"})
- Improved management of plugin log cleanup ([Details](https://github.com/jeedom/core/pull/3245){:target="_blank"})
- Fixed the issue with tag passing when running a scenario on itself ([Details](https://github.com/jeedom/core/pull/3255){:target="_blank"})
- Protection against command injections in the TTS API ([Details](https://github.com/jeedom/core/pull/3261){:target="_blank"})
- Protection against SQL injection in view management ([Details](https://github.com/jeedom/core/pull/3267){:target="_blank"})
- Protection against SQL injections in historical data archiving ([Details](https://github.com/jeedom/core/pull/3268){:target="_blank"})
- Fixed the visibility of the timeline folder field in scenarios ([Details](https://github.com/jeedom/core/pull/3305){:target="_blank"})
- Fixed a bug that could randomly clear scenario logs ([Details](https://github.com/jeedom/core/pull/3316){:target="_blank"})
- Standardization of the maximum execution time for "Loop" and "While" scenario blocks and "Wait" and "Pause" actions *(1 hour maximum)* ([Details](https://github.com/jeedom/core/pull/3341){:target="_blank"})
- Removal of unjustified warnings from the expression checker ([Details](https://github.com/jeedom/core/pull/3349){:target="_blank"})
- Fixed the display of units in the command list ([Details](https://github.com/jeedom/core/pull/3362){:target="_blank"})
- Fixed the buttons for accessing the core Changelog in the update center ([Details](https://github.com/jeedom/core/pull/3368){:target="_blank"})
- [Advanced] Bug fixes in the proxy configuration ([Details](https://github.com/jeedom/core/pull/3238){:target="_blank"})
- [Advanced] Fixing updates via API ([Details](https://github.com/jeedom/core/pull/3352){:target="_blank"})
- [Miscellaneous] Numerous optimizations and code fixes, both in the interface (`Javascript`) as well as the operation of the core (`PHP`)

### Documentation

- Automatic generation of release notes as integrations occur ([Details](https://github.com/jeedom/core/pull/3278){:target="_blank"})
- Update to the scenario documentation regarding the "While" block and the maximum execution time ([Details](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- Widget documentation completely rewritten and expanded ([Details](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- [Developers] Adding PHPDoc to class files ([Details](https://github.com/jeedom/core/pull/3365){:target="_blank"})

>**INFORMATION**
>
>This release also introduces a new organizational structure for Jeedom development, which is now based on three main Branches: `develop` *(continuous integration)* → `release` *(next stable release)* → `master` *(stable)*. Older branches `alpha`, `beta` and `V4-stable` will be removed soon.\
>Documentation [Jeedom Beta Test](https://doc.jeedom.com/contribute/en_US/beta){:target="_blank"}, [Contribute to the documentation](https://doc.jeedom.com/contribute/en_US/doc){:target="_blank"} and [Contribute to the core or to plugins](https://doc.jeedom.com/contribute/en_US/core){:target="_blank"} have been rewritten accordingly.
