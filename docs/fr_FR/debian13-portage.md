# Portage Debian 13 : validation du cœur Jeedom

Base : branche officielle `develop`, version du cœur 4.6.2, commit
`d50e9d17872bac22993ce94dcac7b0cbfe97d436`.
Ce travail complète les adaptations Debian 13 déjà présentes dans cette branche.

## Modifications

- Résoudre les commandes `mariadb`, `mariadb-dump` et `mariadb-check`, avec repli
  vers les noms MySQL pour les installations plus anciennes.
- Partager les arguments de connexion entre sauvegarde et restauration, en
  préservant les espaces et apostrophes dans les valeurs.
- Arrêter une sauvegarde si le contrôle des tables ou l'export SQL échoue.
- Contrôler l'extraction et préparer l'import avant de supprimer les tables ;
  signaler un échec d'import au lieu d'afficher une restauration réussie.
- Détecter les services MariaDB et Apache par leurs unités systemd, et vérifier
  leur activation avant la surveillance.
- Utiliser les commandes MariaDB natives dans l'initialisation Docker ; propager
  les erreurs et arrêter proprement la base en traitant les signaux Docker
  pendant l’attente du processus cron. Ne plus supprimer les journaux
  InnoDB pour tenter de contourner un échec de démarrage.
- Écrire le réglage `vm.swappiness` dans `/etc/sysctl.d/99-jeedom.conf`, lu au
  démarrage de Debian 13 ; ne pas modifier le noyau hôte pendant un build Docker.
- Installer les dépendances par groupes avec contrôle du résultat ; utiliser
  `plocate` et installer `python3-venv` dans l'image Docker.
- Donner un nom à la suite PHPUnit pour que sa configuration soit acceptée par
  les versions récentes de PHPUnit.

Le test existant du calcul de scénario compare maintenant le résultat calculé
à la valeur attendue, au lieu de comparer uniquement la donnée de test.

## Tests reproductibles

Tests indépendants d'une base de données :

```sh
php tests/compatibility/databaseCliTest.php
bash tests/compatibility/dockerServicesTest.sh
```

Test complet, sur une installation de test jetable uniquement :

```sh
bash tests/compatibility/databaseRoundTrip.sh
```

Ce dernier test crée une table et une configuration dédiées, lance les scripts
réels de sauvegarde et restauration, puis vérifie des valeurs UTF-8 et nettoie
ses données. Les journaux et l'archive de sauvegarde sont conservés pour examen.
Le test exige que les scripts de Jeedom aient accès à leurs outils système ;
il s'exécute comme administrateur dans la VM ou le conteneur de test.

Le workflow Docker exécute ces contrôles sur Bookworm et Trixie. Le workflow CI
exécute les tests des clients en PHP 7.4, 8.2 et 8.4.

## Installation depuis ces sources modifiées

Copier les sources dans le répertoire prévu pour Jeedom puis exécuter les étapes
1 à 5 et 7 à 12 du script d'installation, en passant `-v develop`. L'étape 6
retélécharge les sources officielles et écraserait les modifications locales :
elle est donc omise lors de cette installation depuis une copie locale.
L'étape 9 initialise la base de données : cette procédure est destinée à une
nouvelle installation de test, pas à une instance existante.

Les plugins, matériels USB et scénarios d'une installation personnelle nécessitent
une validation avec leurs propres versions et données. Ces essais du cœur ne
constituent pas une déclaration de support officiel Debian 13 par Jeedom.
