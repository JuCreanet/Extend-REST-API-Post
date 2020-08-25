<?php

namespace Erap;

use Erap\Core as Core;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Bootstrap::class ) ) {
	/**
	 * Le fichier qui fait tourner le plugin.
	 */
	class Bootstrap {

		/**
		 * Lance l'exécution du plugin.
		 *
		 * Comme tous dans le plugin est enregistrer via des hooks, lancer le plugin à partir de ce fichier n'affecte pas le cycle de vie de la page
		 *
		 * Retourne une copie de l'objet de l'app pour que des développeurs tiers puissent intéragir avec les hooks du plugin.
		 *
		 * @return Bootstrap|null
		 */
		public function init(): ?self {
			$plugin = new self();

			if ( $plugin->is_ready() ) {
				$core = new Core\Init();
				$core->run();

				return $plugin;
			} else {
				return null;
			}
		}

		/**
		 * Affiche un message en cas de mauvaise version de PHP.
		 */
		public function notice_old_php_version(): void {
			$help_link = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', 'https://wordpress.org/about/requirements/' );

			$message = sprintf(
				// traductions: 1: nom à afficher du plugin, 2: version minimum de PHP requise, 3: version courante de PHP, lien aide
				__( '%1$s requires at least PHP version %2$s in order to work. You have version %3$s. Please see %4$s for more information.', 'extend-rest-api-post' ),
				'<strong>' . PluginData::get_plugin_display_name() . '</strong>',
				'<strong>' . PluginData::required_min_php_version() . '</strong>',
				'<strong>' . PHP_VERSION . '</strong>',
				$help_link
			);

			$this->do_admin_notice( $message );
		}

		/**
		 * Affiche une notice wp-admin.
		 *
		 * @param string $message
		 * @param string $type
		 */
		public function do_admin_notice( string $message, string $type = 'error' ): void {
			$class = sprintf( '%s %s', $type, sanitize_html_class( PluginData::plugin_text_domain() ) );

			printf( '<div class="%s"><p>%s</p></div>', $class, $message );
		}


		/**
		 * Vérifie que nous avons tout ce qui est requis.
		 *
		 * @return bool
		 */
		public function is_ready(): bool {
			$success = true;

			if ( version_compare( PHP_VERSION, PluginData::required_min_php_version(), '<' ) ) {
				add_action( 'admin_notices', [ $this, 'notice_old_php_version' ] );
				$success = false;
			}
			return $success;
		}
	}
}
