<?php

/********************************
 * erap_settings_api_function.php
 ********************************/

// fonction callback pour récupérer le nom de l'auteur
function erap_get_author_name()
{
    $user_info = get_user_meta(get_the_author_meta('ID'));
    $author_name = implode($user_info['nickname']);
    return $author_name;
}
// fonction callback pour récupérer l'avatar de l'auteur
function erap_get_author_avatar()
{
    $user_info = get_user_meta(get_the_author_meta('ID'));
    $author_avatar = get_avatar_url(get_the_author_meta('ID'));
    return $author_avatar;
}
// fonction callback pour récupérer l'image à la une
function erap_get_featured_image()
{
    global $post;
    $featured_image = (object) ['size_thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
        'size_medium' => get_the_post_thumbnail_url($post->ID, 'medium'),
        'size_large' => get_the_post_thumbnail_url($post->ID, 'large'),
        'size_full' => get_the_post_thumbnail_url($post->ID, 'full'),
    ];
    return $featured_image ? $featured_image : '';
}

add_action('rest_api_init', function () {
    global $options;
    $post_types = erap_get_post_types();

    // Boucle sur chaque type de post
    foreach ($post_types as $post_type) {

        // ajout des données auteur à l'API si les options ont été choisies
        $post_author_name = $post_type . '_author_name';
        if (is_array($options) && array_key_exists($post_author_name, $options)) {
            register_rest_field($post_type,
                'author_name',
                array(
                    'get_callback' => 'erap_get_author_name',
                    'update_callback' => null,
                    'schema' => null,
                )
            );
        }
       $post_author_avatar = $post_type . '_author_avatar';
        if (is_array($options) && array_key_exists($post_author_avatar, $options)) {
            register_rest_field($post_type,
                'author_avatar',
                array(
                    'get_callback' => 'erap_get_author_avatar',
                    'update_callback' => null,
                    'schema' => null,
                )
            );
        }

        // ajout de l'image à la une à l'API si l'option a été choisie
        $post_featured_image = $post_type . '_featured_image';
        if (is_array($options) && array_key_exists($post_featured_image, $options)) {
            register_rest_field($post_type,
                'featured_image',
                array(
                    'get_callback' => 'erap_get_featured_image',
                    'update_callback' => null,
                    'schema' => null,
                )
            );
        }
    }
});