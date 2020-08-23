<?php

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Enregistrement des paramètres et ajout des sections et champs du pluggin
function erap_register_settings()
{
    register_setting(
        'erap_options',
        'erap_options',
        'erap_callback_validate_options'
    );
    add_settings_section(
        'erap_section_rest_options',
        '',
        'erap_callback_section_rest_options',
        'erap'
    );
    add_settings_field(
        'check_post_types',
        '',
        'erap_callback_rest_api_fields',
        'erap',
        'erap_section_rest_options',
        ['id' => 'check_post_types']
    );
}
add_action('admin_init', 'erap_register_settings');
