La page Profil vous permet de configurer certains comportements de
Jeedom spécifiques à l’utilisateur : page d’accueil, thèmes de la
version desktop, de la version mobile, des graphiques…​ Elle permet
aussi de changer votre mot de passe.

Vous la retrouvez en haut à droite en cliquant sur le l’icône bonhomme
puis Profil (suivi de votre identifiant).

Thèmes
======

Le panneau thèmes vous permet de régler des paramètres d’interface :

-   **Desktop** : thèmes à utiliser en mode desktop, attention seul le
    thème par défaut est officiellement supporté par Jeedom

-   **Mobile couleur** : permet de choisir la couleur de l’interface
    (ici tout est supporté)

-   **Graphique Desktop** : permet de définir le thème par défaut des
    graphiques en mode desktop

-   **Graphique Mobile** : permet de définir le thème par défaut des
    graphiques en mode mobile

-   **Opacité par des widgets Dashboard** : permet de donner l’opacité
    (entre 0 et 1) des widgets sur le dashboard

-   **Opacité par des widgets Design** : permet de donner l’opacité
    (entre 0 et 1) des widgets sur les designs

-   **Opacité par des widgets Vue** : permet de donner l’opacité (entre
    0 et 1) des widgets sur les vues

-   **Opacité par des widgets Mobile** : permet de donner l’opacité
    (entre 0 et 1) des widgets en mobile

Interface
---------

Vous permet de définir certains comportements de l’interface :

-   **Général**

    -   **Afficher les menus** : indique à Jeedom d’afficher le panneau
        de gauche, lorsqu’il existe, pour rappel ce panneau est
        disponible sur la plupart des pages des plugins, ainsi que la
        page des scénarii, des interactions, des objets…​.

-   **Page par défaut** : page par défaut à afficher lors de la
    connexion en desktop/mobile

-   **Objet par défaut sur le dashboard** : objet à afficher par défaut
    lors de l’arrivée sur le dashboard/mobile

-   **Vue par défaut** : vue à afficher par défaut lors de l’arrivée sur
    le dashboard/mobile

-   **Design par défaut** : design à afficher par défaut lors de
    l’arrivée sur le dashboard/mobile

    -   **Plein écran** : affichage par défaut en plein écran lors de
        l’arrivée sur les designs

-   **Dashboard**

    -   **Déplier le panneau des scénarii** : permet de rendre visible
        par défaut le menu des scénarii (à droite) sur le dashboard

    -   **Déplier le panneau des objets** : permet de rendre visible par
        défaut le menu des objets (à gauche) sur le dashboard

-   **Vue**

    -   **Déplier le panneau des vues** : permet de rendre visible par
        défaut le menu des vues (à gauche) sur les vues

Sécurité
--------

-   **Authentification en 2 étapes** : permet de configurer
    l’authentification en 2 étapes (pour rappel, c’est un code changeant
    toutes les X secondes qui s’affiche sur une application mobile, type
    google authentificator). A noter que la double authentification ne sera demandée que pour les connexions externe. Pour les connexion local le code ne sera donc pas demandé. Important si lors de la configuration de la double authentification vous avez une erreur vérifier que jeedom (voir sur la page santé) et votre téléphone sont bien à la meme heure (1 min de difference suffit pour que ca ne marche pas)

-   **Mot de passe** : permet de changer votre mot de passe (ne pas
    oublier de le retaper en dessous)

-   **Hash de l’utilisateur** : votre clef API d’utilisateur

### Sessions actives

Vous avez ici la liste de vos sessions actuellement connecté, leur ID,
leur IP ainsi que la date de derniere communication. En cliquant sur
"Déconnecter" cela déconnectera l’utilisateur. Attention si il est sur
un péripherique enregistré cela supprimera églagement l’enregistrement.

### Péripherique enregistrés

Vous retrouvez ici la liste de tous les péripheriques enregistré (qui se
connecte sans authentification) à votre Jeedom ainsi que la date de
derniere utilisation. Vous pouvez ici supprimer l’enregistrement d’un
peripherique. Attention cela ne le deconnecte pas mais empechera juste
sa reconnection automatique.

Notifications
-------------

-   **Commande de notification utilisateur** : Commande par défaut pour
    vous joindre (commande de type message)
