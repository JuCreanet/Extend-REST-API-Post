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
* Description:  Ajoute une page au tableau de bord pour pouvoir étendre l'API REST
* Version:      1.2.1
* Author:       Julia Galindo
* Author URI:   https://www.objectifseo.fr/
* Text Domain:  extend-rest-api-post
* Domain Path:  /languages
* License:      GPL3
*
*/
namespace Erap;

// Vérification de nouvelle version disponible => création alerte et lien mise à jour en 1 clic
// On échappe la classe car elle n'appartient pas au namespace
require 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
$erapUpdateChecker = \Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/JuCreanet/Extend-REST-API-Post/',
	__FILE__,
	'extend-rest-api-post'
);

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Autoload via Composer
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

// On lance l'exécution du plugin
( new Bootstrap() )->init();
