La page Profil vous permet de configurer certains comportements de
Jeedom spécifiques à l’utilisateur : page d’accueil, thèmes de la
version desktop, de la version mobile, des graphiques…​ Elle permet
aussi de changer votre mot de passe.

A encontrar la parte superior derecha hacer clic en el icono del hombre
y Perfil (seguido de su identificación).

Thèmes
======

El panel de temas le permite ajustar los parámetros de la interfaz:

-   **Desktop** : thèmes à utiliser en mode desktop, attention seul le
    tema por defecto es apoyado oficialmente por Jeedom

-   ** ** móvil de color: elegir el color de la interfaz
    (Aquí todo es compatible)

-   ** ** gráficos de escritorio: Establecer el tema por defecto
    la moda de escritorio gráficos

-   **móviles** Gráfico: establecer el tema predeterminado
    gráficos en modo móvil

-   ** ** La opacidad de los widgets del Dashboard: para dar opacidad
    (Entre 0 y 1) de los widgets en el tablero de instrumentos

-   **La opacidad Diseño de los widgets**: dar opacidad
    (Entre 0 y 1) de widgets en diseños

-   **La opacidad de los widgets Vista**: para dar opacidad (entre
    0 y 1) de widgets en vistas

-   **La opacidad de los widgets móviles**: permite dar opacidad
    (Entre 0 y 1) de widgets móviles

Interface
---------

Se puede definir ciertos comportamientos de la interfaz:

-   **General**

    -   ** ** Muestra los menús dice panel de visualización Jeedom
        la izquierda, en su caso, para devolver este panel es
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

Notificaciones
-------------

-   **Commande de notification utilisateur** : Commande par défaut pour
    vous joindre (commande de type message)
