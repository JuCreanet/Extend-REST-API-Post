<?php

namespace Erap\Core;

use Erap\Admin as Admin;
use Erap\Common\Settings as Settings;
use Erap\PluginData as PluginData;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Init::class ) ) {
	/**
	 * La classe du coeur du plugin.
	 * Defini l'internationalisation, les hooks spécifiques à l'admin, et les hooks pour les parties communes du site.
	 */
	class Init {

		/**
		 * Le loader   responsable de maintenir et enregistrer tous les hooks dont se sert le plugin
		 *
		 * @var      Loader $loader Maintient et enregistre tous les hooks pour le plugin.
		 */
		protected $loader;

		/**
		 * Initialise et defini les fonctionnalités du coeur du plugin.
		 */
		public function __construct() {
			$this->load_dependencies();
			$this->set_locale();
			$this->define_common_hooks();
			$this->define_admin_hooks();
		}

		/**
		 * Charge les dépendances requises suivantes.
		 *
		 * - Loader - gère les hooks du plugin.
		 * - I18n - Defini la fonctionnalité d'internationalisation.
		 * - Admin - Défini tous les hooks de l'admin.
		 * - Common - Défini tous les hooks communs à l'admin et au site.
		 */
		private function load_dependencies(): void {
			$this->loader = new Loader();
		}

		/**
		 * Defini la locale de ce plugin pour l'internationalisation.
		 *
		 * Utilise la classe I18n pour définir le domaine et enregistrer le hook dans WordPress.
		 */
		private function set_locale(): void {
			$i18n = new I18n();

			$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Enregistre tous les hooks utilisés à la fois dans l'admin et la partie publique par le plugin.
		 */
		private function define_common_hooks(): void {
			// Les champs des options ne doivent pas être derrière un `is_admin()`, car c'est trop tard.
			$settings = new Settings\Main();

			/**
			 * Si on n'utilise pas le hook 'rest_api_init', on n'aura pas accès au types de post custom
			 * même si 'show_in_rest' = true. C'est aussi pourquoi l'enregistrement des paramètres
			 * ne peut pas être fait après une vérification si is_admin().
			 */
			$this->loader->add_action( 'admin_init', $settings, 'register_settings' );
			$this->loader->add_action( 'rest_api_init', $settings, 'register_api_settings' );
		}

		/**
		 * Enregistre tous les hooks utilisés dans l'admin par le plugin.
		 * Fonctionne également avec Ajax.
		 */
		private function define_admin_hooks(): void {

			$settings = new Admin\Settings\Main();

			// Liens action du Plugin
			$this->loader->add_filter(
				'plugin_action_links_' . PluginData::plugin_basename(),
				$settings,
				'customize_action_links'
			);

			// Admin menu
			$this->loader->add_action( 'admin_menu', $settings, 'add_plugin_admin_menu' );
		}


		/**
		 * Lancer le loader pour exécuter tous les hooks avec WordPress.
		 */
		public function run(): void {
			$this->loader->run();
		}

		/**
		 * La référence à la classe qui gère les hooks et le plugin.
		 *
		 * @return Loader gère les hooks du plugin.
		 */
		public function get_loader(): Loader {
			return $this->loader;
		}
	}
}
