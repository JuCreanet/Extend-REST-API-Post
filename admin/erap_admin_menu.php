<?php

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Ajout d'un onglet dans le sous-menu Réglages
function erap_add_sublevel_menu()
{
    add_submenu_page(
        'options-general.php',
        'Extend REST API Post',
        'Extend REST API Post',
        'manage_options',
        'erap',
        'erap_display_settings_page'
    );
}
add_action('admin_menu', 'erap_add_sublevel_menu');
