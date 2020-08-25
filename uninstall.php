<?php
/**
 * Appelé quand le plugin est désinstallé.
 *
 * @package    Extend_REST_API_Post
 */
// die si l'appel ne vient pas de WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Suppression des options de la base de données
delete_option('erap__options');