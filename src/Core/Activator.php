<?php

namespace Erap\Core;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Activator::class ) ) {
	/**
	 * Appelé à l'activation du plugin
	 *
	 * Cette classe défini tous les codes à effectuer pendant l'activation du plugin.
	 * pour l'utiliser ajouter 'register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Activator', 'activate' ] );'
	 * dans le fichier php principal du plugin.
	 *
	 **/
	class Activator {

		/**
		 * Description
		 */
		public static function activate() {
		}
	}
}
