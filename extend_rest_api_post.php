<?php
/*
* Le fichier bootstrap du  plugin
*
* Ce fichier est lu par WordPress pour générer les informations du plugin dans le tableau de bord
* Il inclut également toutes les dépendances utilisées par le plugin,
* et défini une fonction pour lancer le plugin.
*
* Plugin Name:  Extend REST API Post
* Plugin URI:  https://github.com/JuCreanet/Extend-REST-API-Post
* Description:  Ajoute une page au tableau de bord pour pouvoir ajouter le nom de l'auteur, l'URL de son avatar
et les URL de l'image à la une dans le retour de l'API REST pour chaque type de post publique
* Version:      1.2.0
* Author:       Julia Galindo
* Author URI:   https://www.creanet.fr/
* Text Domain:  extend-rest-api-post
* Domain Path:  /languages
* License:      GPL3
*
*/
namespace Erap;

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// vérification de mise à jour disponible
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/JuCreanet/Extend-REST-API-Post',
	__FILE__,
	'extend-rest-api-post'
);

// Autoload via Composer
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

// On lance l'exécution du plugin
( new Bootstrap() )->init();
