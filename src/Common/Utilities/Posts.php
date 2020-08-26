<?php

namespace Erap\Common\Utilities;

use WP_Post;
use WP_Query;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Posts::class ) ) {
	/**
	 * Fonctionnalités communes
	 *
	 */
	class Posts {

		/**
		 * fonction pour récupérer le nom de l'auteur
		 *
		 * @return string 	nom de l'auteur
		 */
		public function get_author_name()
		{
		    $user_info = get_user_meta(get_the_author_meta('ID'));
		    $author_name = implode($user_info['nickname']);
		    return $author_name;
		}

		/**
		 * fonction pour récupérer l'avatar de l'auteur
		 *
		 * @return string 	URL de l'avatar de l'auteur
		 */
		public function get_author_avatar()
		{
		    $user_info = get_user_meta(get_the_author_meta('ID'));
		    $author_avatar = get_avatar_url(get_the_author_meta('ID'));
		    return $author_avatar;
		}
		/**
		 * fonction pour récupérer l'image à la une
		 *
		 * @param null|int|WP_Post $candidate  Post ID ou object, `null` pour le post global.
		 *
		 * @return object 	liste des différentes URL de l'image à la une
		 */
		public function get_featured_image($candidate = null)
		{
		    $post=get_post( $candidate );;
		    $featured_image = (object) ['size_thumbnail' => get_the_post_thumbnail_url($post->ID, 'thumbnail'),
		        'size_medium' => get_the_post_thumbnail_url($post->ID, 'medium'),
		        'size_large' => get_the_post_thumbnail_url($post->ID, 'large'),
		        'size_full' => get_the_post_thumbnail_url($post->ID, 'full'),
		    ];
		    return $featured_image ? $featured_image : '';
		}

		/**
		 * Récupération de tous les types de post trié par ordre alphabétique
		 *
		 * @see get_post_types()
		 *
		 * @return array
		 */
		public function get_public_post_types() {
			$result = get_post_types( [ 'public' => true ], 'object' );

			uasort(
				$result, function ( $a, $b ) {
				return strcmp( $a->label, $b->label );
			}
			);

			return $result;
		}
	}
}
