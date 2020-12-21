# Préférences
**Réglages → Préférences**

La page Préférences vous permet de configurer certains comportements de Jeedom spécifiques à l’utilisateur.

## Onglet Préférences

### Interface

Définit certains comportements de l’interface

- **Page par défaut** : Page à afficher par défaut lors de la connexion en desktop ou mobile.
- **Objet par défaut** : Objet à afficher par défaut lors de l’arrivée sur le Dashboard / mobile.

- **Vue par défaut** : Vue à afficher par défaut lors de l’arrivée sur le Dashboard / mobile.
- **Déplier le panneau des vues** : Permet de rendre visible par défaut le menu des vues (à gauche) sur les vues.

- **Design par défaut** : Design à afficher par défaut lors de l’arrivée sur le Dashboard / mobile.
- **Design Plein écran** : Affichage par défaut en plein écran lors de l’arrivée sur les designs.

- **Design 3D par défaut** : Design 3D à afficher par défaut lors de l’arrivée sur le Dashboard / mobile.
- **Design 3D Plein écran** : Affichage par défaut en plein écran lors de l’arrivée sur les designs 3D.

### Notifications

- **Commande de notification utilisateur** : Commande par défaut pour vous joindre (commande de type message).

## Onglet Sécurité

- **Authentification en 2 étapes** : permet de configurer l’authentification en 2 étapes (pour rappel, c’est un code changeant toutes les X secondes qui s’affiche sur une application mobile, type *google authentificator*). A noter que la double authentification ne sera demandée que pour les connexions externes Pour les connexions locales le code ne sera donc pas demandé.

  **Important** si lors de la configuration de la double authentification vous avez une erreur, il faut vérifier que Jeedom (voir sur la page santé) et votre téléphone sont bien à la même heure (1 min de différence suffit pour que ça ne marche pas).

- **Mot de passe** : Permet de changer votre mot de passe (ne pas oublier de le retaper en dessous).

- **Hash de l’utilisateur** : Votre clef API d’utilisateur.

### Sessions actives

Vous avez ici la liste de vos sessions actuellement connectées, leur ID, leur IP ainsi que la date de dernière communication. En cliquant sur "Déconnecter" cela déconnectera l’utilisateur. Attention si il est sur un périphérique enregistré, cela supprimera également l’enregistrement.

### Périphériques enregistrés

Vous retrouvez ici la liste de tous les périphériques enregistrés (qui se connectent sans authentification) à votre Jeedom ainsi que la date de dernière utilisation.
Vous pouvez ici supprimer l’enregistrement d’un périphérique. Attention cela ne le déconnecte pas mais empêchera juste sa reconnexion automatique.
