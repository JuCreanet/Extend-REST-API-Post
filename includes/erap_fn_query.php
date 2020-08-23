<?php
/* fonctions communes */

function erap_get_post_types() {

    // Récupération de tous les types de post
    $args = array(
        'public' => true, // seulement les types de post publiques
        '_builtin' => false, // sans les types de post natifs
    );

    // génération de la liste
    $post_types = get_post_types($args, 'names');

    // ajout des articles et des pages
    $post_types['post'] = 'post';
    $post_types['page'] = 'page';
    return $post_types;
}

// options par défaut
function erap_options_default() {
    return array(
        'check_post_types' => false,
    );
}

// récupérations des options dans la base de données
$options = get_option('erap_options', erap_options_default());