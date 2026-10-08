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

- **Commande de notification utilisateur** : Commande par défaut pour vous joindre (commande de type message). Cette commande doit être renseignée pour que la procédure automatique en cas de mot de passe perdu puisse aboutir.

## Onglet Sécurité

- **Authentification en 2 étapes** : permet de configurer l’authentification en deux étapes. Un code de vérification temporaire est généré par une application d’authentification sur votre appareil mobile. La double authentification est demandée uniquement lors des connexions externes ; elle n’est pas requise pour les connexions locales.

  **Important :** en cas d’erreur lors de la configuration, vérifiez que l’horloge de Jeedom et celle de votre téléphone sont synchronisées. Un décalage d’une minute peut empêcher la validation du code.

- **Mot de passe** : permet de modifier votre mot de passe. Saisissez-le également dans le champ de confirmation.

- **Hash de l’utilisateur** : Votre clef API d’utilisateur.

### Sessions actives

Vous avez ici la liste de vos sessions actuellement connectées, leur ID, leur IP ainsi que la date de dernière communication. En cliquant sur "Déconnecter" cela déconnectera l’utilisateur. Attention si il est sur un périphérique enregistré, cela supprimera également l’enregistrement.

### Périphériques enregistrés

Vous retrouvez ici la liste de tous les périphériques enregistrés (qui se connectent sans authentification) à votre Jeedom ainsi que la date de dernière utilisation.
Vous pouvez ici supprimer l’enregistrement d’un périphérique. Attention cela ne le déconnecte pas mais empêchera juste sa reconnexion automatique.
