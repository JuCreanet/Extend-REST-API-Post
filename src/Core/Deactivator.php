<?php

namespace Erap\Core;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Deactivator::class ) ) {
	/**
	 * Appelé à la désactivation du plugin
	 *
	 * Cette classe défini tous les codes à effectuer pendant la désactivation du plugin.
	 * pour l'utiliser ajouter 'register_deactivation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Deactivator', 'deactivate' ] );'
	 * dans extend_rest_api_post.php
	 *
	 **/
	class Deactivator {

		/**
		 * Description.
		 */
		public static function deactivate() {
		}
	}
}
