<?php

namespace Erap;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( PluginData::class ) ) {
	/**
	 * Les informations basiques du plugin comme son nom, l'emplacement des fichiers etc.
	 */
	class PluginData {

		/**
		 * La version du plugin.
		 *
		 * @TODO à mettre à jour avec le readme.txt + entête du plugin.
		 *
		 * @return string
		 */
		public static function plugin_version(): string {
			return '1.2.0';
		}

		/**
		 * La version minimum de PHP requise pour le plugin.
		 *
		 * doit correspondre avec celle dans composer.json `"require": { "php":...`
		 *
		 * @link https://wordpress.org/about/requirements/
		 * @link https://en.wikipedia.org/wiki/PHP#Release_history
		 *
		 * @return string
		 */
		public static function required_min_php_version(): string {
			return '7.1.0';
		}

		/**
		 * Text domain du plugin.
		 *
		 * doit correspondre au répertoire principal du plugin et au fichier PHP principal.
		 *
		 * @return string
		 */
		public static function plugin_text_domain(): string {
			return 'extend-rest-api-post';
		}

		/**
		 * Préfixe du plugin.
		 *
		 * @return string
		 */
		public static function plugin_prefix(): string {
			return 'erap';
		}

		/**
		 * Prefix des handle pour les style/script. (Doit être unique!)
		 *
		 * Pour être consistant tout en étant unique.
		 *
		 * @param string $handle
		 *
		 * @return string
		 */
		public static function get_asset_handle( string $handle ): string {
			return self::plugin_prefix() . '-' . $handle;
		}

		/**
		 * Le text domain du plugin avec des underscores au lieu des tirets.
		 *
		 * Utilisé pour sauvegarder les options. Utile aussi pour créer des noms de hook avec namespace, des classes, des URLs, etc.
		 *
		 * @return string 'extend_rest_api_post'
		 */
		public static function plugin_text_domain_underscores(): string {
			return str_replace( '-', '_', self::plugin_text_domain() );
		}

		/**
		 * Le nom à afficher du plugin.
		 *
		 * Utile pour les titres par exemple.
		 *
		 * @return string
		 */
		public static function get_plugin_display_name(): string {
			return esc_html_x( 'Extend REST API Post', 'Nom du plugin à afficher', 'extend-rest-api-post' );
		}

		/**
		 * Chemin du répertoire du plugin, relatf à l'emplacement de ce fichier.
		 *
		 * Ce fichier devrait être dans `/src` et nous voulons le niveau supérieur.
		 * Example: /app/public/wp-content/plugins/extend-rest-api-post/
		 *
		 * @return string
		 */
		public static function plugin_dir_path(): string {
			return trailingslashit( realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' ) );
		}

		/**
		 * L'URL du répertoire du plugin.
		 *
		 * Example: https://example.com/wp-content/plugins/extend-rest-api-post/
		 *
		 * @return string
		 */
		public static function plugin_dir_url(): string {
			return plugin_dir_url( self::main_plugin_file() );
		}

		/**
		 * Basename du plugin.
		 *
		 * @return string 'extend-rest-api-post/extend-rest-api-post.php'
		 */
		public static function plugin_basename(): string {
			return plugin_basename( self::main_plugin_file() );
		}

		/**
		 * Le répertoire du plugin relatif à l'emplacement de ce fichier.
		 *
		 * Ce fichier devrait être dans `/src` et nous voulons le niveau supérieur.
		 * Example: /app/public/wp-content/plugins/
		 *
		 * @return string
		 */
		public static function all_plugins_dir(): string {
			return trailingslashit( realpath( self::plugin_dir_path() . '..' ) );
		}

		/**
		 * Le fichier principal du plugin.
		 *
		 * ATTENTION: Assume que le fichier existe - donc ne faites pas d'erreur!!!
		 *
		 * @return string
		 */
		private static function main_plugin_file(): string {
			return self::plugin_dir_path() . self::plugin_text_domain() . '.php';
		}

	}
}
