<?php

namespace Erap\Core;

use Erap\PluginData as PluginData;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( I18n::class ) ) {
	/**
	 * Défini les fonctions d'internationalisation.
	 *
	 * Charge et défini les fichiers d'internationalisation pour que le plugin soit prêt pour la traduction.
	 */
	class I18n {

		/**
		 * Charge le text domain du plugin pour la traduction.
		 *
		 * @link https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#loading-text-domain
		 *
		 * TODO: Retirer si WordPress.org fourni les fichiers de traduction.
		 */
		public function load_plugin_textdomain(): void {
			load_plugin_textdomain(
				PluginData::plugin_text_domain(),
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		}
	}
}
