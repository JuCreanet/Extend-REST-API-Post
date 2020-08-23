<?php
/*
Plugin Name:  Extend REST API Post
Plugin URI:  https://github.com/JuCreanet/Extend-REST-API-Post
Description:  Ajoute une page au tableau de bord pour pouvoir ajouter le nom de l'auteur, l'URL de son avatar et l'URL de l'image à la une dans le retour de l'API REST pour chaque type de post publique
Version:      0.1.0
Author:       Julia Galindo
Author URI:   https://www.creanet.fr/
Text Domain:  erap
Domain Path:  /languages
License:      GPL2

Extend REST API Post is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Extend REST API Post is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Custom REST API Generator. If not, see License URI:
https://www.gnu.org/licenses/gpl-2.0.html
*/

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// import des scripts nécessaires
add_action('admin_enqueue_scripts', 'erap_enqueue_scripts');
function erap_enqueue_scripts()
{
    wp_enqueue_style('erap_fa', plugin_dir_url(__FILE__) . 'includes/css/fontawesome-free-5.6.1-web/css/all.css', array(), '5.6.1');
    wp_enqueue_style('erap_css', plugin_dir_url(__FILE__) . 'includes/css/style.css', array(), '1.0');
    wp_enqueue_script('erap_js', plugin_dir_url(__FILE__) . 'includes/js/main.js', array('jquery'), '1.0', true);
}
require_once ABSPATH . "wp-includes/pluggable.php";
require_once plugin_dir_path(__FILE__) . 'includes/erap_fn_query.php';
if (is_admin()) {
    // inclusion des dépendances php
    require_once plugin_dir_path(__FILE__) . 'admin/erap_admin_menu.php';
    require_once plugin_dir_path(__FILE__) . 'admin/erap_settings_page.php';
    require_once plugin_dir_path(__FILE__) . 'admin/erap_settings_register.php';
    require_once plugin_dir_path(__FILE__) . 'admin/erap_settings_callback.php';
}
require_once plugin_dir_path(__FILE__) . 'admin/erap_settings_api_function.php';
