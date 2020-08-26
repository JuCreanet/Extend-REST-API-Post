<?php

declare( strict_types=1 );

namespace Erap\Admin\Settings;

use Erap\PluginData as PluginData;
use Erap\Common\Settings\Main as Common_Settings;
use WP_Screen;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Main::class ) ) {
	/**
	 * Réglages spécifiques à l'admin.
	 */
	class Main {


		/**
		 * L'instance de settings de Common.
		 *
		 * @var Common_Settings
		 */
		private $settings;

		/**
		 * Initialise la classe et défini ses propriétés.
		 */
		public function __construct() {
			$this->settings = new Common_Settings();
		}

		/**
		 * Ajout d'un lien vers les réglages sur la page de gestion des plugins.
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		public function customize_action_links( array $links ): array {
			$link_to_settings_page = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $this->settings->get_main_settings_page_url() ),
				$this->settings->get_settings_word()
			);

			$custom_action_links = [
				$link_to_settings_page,
			];

			return array_merge( $custom_action_links, $links );
		}

		/**
		 * Ajout de la page des réglages au menu de l'admin.
		 */
		public function add_plugin_admin_menu(): void {
			$hook_suffix = add_options_page(
				PluginData::get_plugin_display_name(),
				PluginData::get_plugin_display_name(),
				'manage_options',
				$this->settings->get_settings_page_slug(),
				[ $this, 'settings_page' ]
			);

			if ( ! empty( $hook_suffix ) ) {
				add_action( "admin_print_scripts-{$hook_suffix}", [ $this, 'enqueue_settings_page_assets' ] );
			}
		}

		/**
		 * Enregistrement des scripts pour la page d'interface du plugin.
		 */
		public function enqueue_settings_page_assets() {
			// CSS pour la page des options.
			wp_enqueue_style(
				PluginData::get_asset_handle( 'admin-settings' ),
				PluginData::plugin_dir_url() . 'assets/css/style.css',
				[
					'wp-components',
				],
				PluginData::plugin_version(),
				'all'
			);

			// JS pour la page des options.
			wp_enqueue_script(
				PluginData::get_asset_handle( 'admin-settings' ),
				PluginData::plugin_dir_url() . 'assets/js/main.js',
				[
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'jquery',
				],
				PluginData::plugin_version(),
				true
			);
		}

		/**
		 * l'ID de la page des options
		 *
		 * @see \get_plugin_page_hookname()
		 *
		 * @return string
		 */
		public function get_settings_page_id(): string {
			return 'settings_page_' . $this->settings->get_settings_page_slug();
		}

		/**
		 * Détecte si on est sur la page des options.
		 *
		 * @return bool
		 */
		public function is_our_settings_page(): bool {
			$current_screen = get_current_screen();

			if (
				$current_screen instanceof WP_Screen
				&& $this->get_settings_page_id() === $current_screen->base
			) {
				return true;
			}

			return false;
		}


		/**
		 * Affiche le HTML pour la page des options.
		 *
		 * @link https://developer.wordpress.org/reference/functions/settings_fields/
		 * @link https://developer.wordpress.org/reference/functions/do_settings_sections/
		 * @link https://developer.wordpress.org/reference/functions/submit_button/
		 * 
		 */
		public function settings_page(): void {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'Vous n\'avez pas les permissions suffisantes pour accéder à cette page.', 'extend-rest-api-post' ) );
			}
			?>
			<div class="wrap" id="<?php echo PluginData::plugin_prefix();?>">
			<h1><?php echo PluginData::get_plugin_display_name(); ?></h1>
			<form action="options.php" method="post">
			<?php
                settings_fields($this->settings->get_prefixed_option_key( 'options' ));
                do_settings_sections($this->settings->get_settings_page_slug());
                submit_button();
            ?>
			</form>
			</div>
			<?php
		}
	}
}
