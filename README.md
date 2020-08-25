# Extend REST API Post

Extend REST API Post est un plugin WordPress qui ajoute une interface au tableau de bord pour pouvoir ajouter le nom de l'auteur, l'URL de son avatar et les URL de l'image à la une dans le retour de l'API REST pour chaque type de post publique.
Le plugin est développé en objet et utilise autoload avec des namespaces PHP. Les dépendances sont gérées avec composer.
Pour compiler le css (en mode minifié), lancer dans la console `npm run sass` et enregistrer les modifications dans assets/sass/main.scss


## Installation

1.	Télécharger le ZIP du code
1.	Se connecter au tableau de bord de WordPress et se rendre dans le menu extensions
1.	Cliquer sur ajouter puis téléverser une extension
1.  Glisser le ZIP ou le sélectionner dans l'arborescence
1. 	Lancer `composer install` en ligne de commande
1.  Activer le plugin

## Usage

L'accès à l'interface se fait par le menu du tableau de bord sous >> Réglages >> Extend REST API Post

## License

Extend REST API Post est distribué sous licence GNU GPL3.

### 1.1.1
* Internationalisation

### 1.1.0
* SASS

### 1.0.0
* Version OOP

### 0.1.0
* Initial release

