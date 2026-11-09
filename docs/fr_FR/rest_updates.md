# Plugins et mises à jour dans Core UI

La page de gestion utilise un inventaire unique. Le contrôleur `PluginsController`
assemble les plugins installés, leurs informations de suivi et une carte Core toujours
présente. Il appelle les classes natives et ne contient aucun accès SQL direct.
Un plugin sans ligne dans `update` reste visible, avec un état inconnu. Une ligne
orpheline n’ajoute pas de fausse carte.

| Route | Usage |
| --- | --- |
| `GET /api/v1/plugins/management` | Plugins, Core, date de vérification et dernière opération |
| `POST /api/v1/updates/check` | Vérification asynchrone du Core et des plugins, sans corps |
| `POST /api/v1/updates/operations` | Mise à jour des identifiants `ids` sélectionnés |
| `GET /api/v1/updates/operations/latest` | Suivi reprenable sans conserver d’URL ou de stockage navigateur |
| `GET /api/v1/logs/update` | Journal natif partagé, lu uniquement à la demande |

Ces routes sont réservées aux administrateurs. Les écritures vérifient aussi le
mode démo et le jeton CSRF des sessions. Les deux POST d’opération exigent une
`Idempotency-Key` : une réponse perdue peut être redemandée sans relancer le travail.
Une autre sélection, un autre type d’opération ou un autre acteur avec la même clé
donne un conflit. La dernière opération est conservée dans la configuration de la box.

`updateOperation` exécute le travail ; cette classe n’assemble pas l’inventaire.
Son worker CLI indépendant reste vivant pendant l’arrêt/redémarrage de la queue par
le programme natif de mise à jour. Le Core passe **avant** les plugins. Il utilise
le programme natif avec sauvegarde, puis chaque plugin sélectionné passe par
`update::doUpdate()` avec sa source et sa branche configurées. Les paquets du système
ne sont pas vérifiés ni mis à jour par ces routes.

Un verrou partagé avec les installations et mutations REST des plugins interdit
les opérations concurrentes. Les exclusions et l’identité de chaque composant sont
revérifiées dans le worker, ainsi que les droits de l’acteur. Un échec arrête les
éléments restants et conserve les succès déjà obtenus. Une tâche perdue devient
`failed`, jamais `succeeded`. La fermeture de la page n’annule pas le travail.

Le champ `progress` expose les jalons `[PROGRESS][n]` du programme natif uniquement
pendant l’étape Core. Ce pourcentage décrit cette étape, et non tout le lot.
Les plugins ne fournissent pas de pourcentage individuel : leur suivi utilise les
états `queued/running/succeeded/failed/skipped` et leur rang dans la sélection.

Validation isolée des routes, sans télécharger ni installer de plugin réel :

```sh
php tests/rest/plugins.php
php tests/rest/updates.php
```
