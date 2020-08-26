=== Extend REST API Post ===

Contributors: Julia Galindo
Tags: REST API, Admin UI, composer
Requires at least: 5.2
Tested up to: 5.5
Requires PHP: 7.1.0
Stable tag: 1.2.0
License: GPL version 3 or any later version
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Extension de l'API REST pour les posts

== Description ==

Extend REST API Post est un plugin WordPress qui ajoute une interface au tableau de bord pour pouvoir ajouter le nom de l'auteur, l'URL de son avatar et les URL de l'image à la une dans le retour de l'API REST pour chaque type de post publique.
Le plugin est développé en objet et utilise autoload avec des namespaces PHP. Les dépendances sont gérées avec composer. 
Une notification de mise à jour apparait dans le tableau de bord avec mise à jour en un clic. (nécessite de relancer `composer install` après la maj)
Pour compiler le css (en mode minifié), lancer dans la console `npm run sass` et enregistrer les modifications dans assets/sass/main.scss
Pour générer le fichier .pot, lancer dans la console `npm run pot`

== Installation ==

1.  Télécharger le ZIP du code
2.  Se connecter au tableau de bord de WordPress et se rendre dans le menu extensions
3.  Cliquer sur ajouter puis téléverser une extension
4.  Glisser le ZIP ou le sélectionner dans l'arborescence
5.  Lancer `composer install` en ligne de commande
6.  Activer le plugin
7.  Accéder à l'interface par le menu du tableau de bord sous >> Réglages >> Extend REST API Post

== Changelog ==

= 1.2.0 =
* 26 août 2020
* Notification mise à jour

= 1.1.1 =
* 25 août 2020
* Internationalisation

= 1.1.0 =
* 25 août 2020
* SASS

= 1.0.0 =
* 24 août 2020
* version OOP

= 0.1.0 =
* 23 août 2020
* Code initial

