# Changelog Jeedom V4.6

## 4.6.0

### Nouvelles fonctionnalités

- Ajout d'un bloc "Tant que" dans les scénarios ([Détails](https://github.com/jeedom/core/pull/3234){:target="_blank"})
- Nouvelle méthode de traduction de l'interface ([Détails](https://github.com/jeedom/core/pull/3251){:target="_blank"})
- Unification des anciens widgets horaires *(`timeXxxx`)* vers les widgets standards avec paramètre `time` correspondant ([Détails](https://github.com/jeedom/core/pull/3332){:target="_blank"})
- [Avancé] Ajout d'un `healthcheck` pour les installations sous Docker ([Détails](https://github.com/jeedom/core/pull/2998){:target="_blank"})

### Correctifs

- Correction de la mise à jour automatique des graphiques ([Détails](https://github.com/jeedom/core/pull/3178){:target="_blank"})
- Correction de la fonction mathématique `randText` ([Détails](https://github.com/jeedom/core/pull/3197){:target="_blank"})
- Fiabilisation de l'utilisation des actions spécifiques en dehors d'un scénario ([Détails](https://github.com/jeedom/core/pull/3228){:target="_blank"})
- Correction de la sélection de plage de dates *(Zoom)* avec groupement dans l'historique ([Détails](https://github.com/jeedom/core/pull/3242){:target="_blank"})
- Meilleure gestion du nettoyage des logs de plugins ([Détails](https://github.com/jeedom/core/pull/3245){:target="_blank"})
- Correction du passage de tags lors de l’exécution d'un scénario sur lui-même ([Détails](https://github.com/jeedom/core/pull/3255){:target="_blank"})
- Protection contre les injections de commandes dans l'API TTS ([Détails](https://github.com/jeedom/core/pull/3261){:target="_blank"})
- Protection contre les injections SQL dans la gestion des vues ([Détails](https://github.com/jeedom/core/pull/3267){:target="_blank"})
- Protection contre les injections SQL dans l'archivage des historiques ([Détails](https://github.com/jeedom/core/pull/3268){:target="_blank"})
- Correction de la visibilité du champ dossier de timeline dans les scénarios ([Détails](https://github.com/jeedom/core/pull/3305){:target="_blank"})
- Correction d'un bug pouvant vider aléatoirement les logs des scénarios ([Détails](https://github.com/jeedom/core/pull/3316){:target="_blank"})
- Harmonisation de la durée maximale d'exécution des blocs de scénarios "Boucle" et "Tant que" et des actions "Attendre" et "Pause" *(1 heure maximum)* ([Détails](https://github.com/jeedom/core/pull/3341){:target="_blank"})
- Suppression des avertissements non justifiés du testeur d'expression ([Détails](https://github.com/jeedom/core/pull/3349){:target="_blank"})
- Correction de l'affichage des unités dans la liste des commandes ([Détails](https://github.com/jeedom/core/pull/3362){:target="_blank"})
- Correction des boutons d'accès au changelog du core dans le centre de mise à jour ([Détails](https://github.com/jeedom/core/pull/3368){:target="_blank"})
- [Avancé] Correction de bugs dans la configuration du proxy ([Détails](https://github.com/jeedom/core/pull/3238){:target="_blank"})
- [Avancé] Correction des mises à jour via API ([Détails](https://github.com/jeedom/core/pull/3352){:target="_blank"})
- [Divers] Très nombreuses optimisations et corrections de code aussi bien au niveau de l'interface (`Javascript`) que du fonctionnement du core (`PHP`)

### Documentations

- Génération automatique des notes de version au fil des intégrations ([Détails](https://github.com/jeedom/core/pull/3278){:target="_blank"})
- Mise à jour de la documentation des scénarios avec le bloc "Tant que" et la durée maximale d'exécution ([Détails](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- Documentation des widgets entièrement réécrite et enrichie ([Détails](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- [Développeurs] Ajout de PHPDoc dans les fichiers de classe ([Détails](https://github.com/jeedom/core/pull/3365){:target="_blank"})

>**INFORMATION**
>
>Cette version introduit également une nouvelle organisation dans le développement de Jeedom, reposant dorénavant sur 3 branches principales : `develop` *(intégration continue)* → `release` *(prochaine stable)* → `master` *(stable)*. Les anciennes branches `alpha`, `beta` et `V4-stable` seront supprimées prochainement.\
>Les documentations [Bêta-test de Jeedom](https://doc.jeedom.com/fr_FR/beta/){:target="_blank"}, [Contribuer à la documentation](https://doc.jeedom.com/fr_FR/contribute/doc){:target="_blank"} et [Contribuer au core ou aux plugins](https://doc.jeedom.com/fr_FR/contribute/core){:target="_blank"} ont été réécrites en conséquence.
