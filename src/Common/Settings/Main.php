<?php

declare( strict_types=1 );

namespace Erap\Common\Settings;

use Erap\PluginData;
use Erap\Common\Utilities\Posts as Posts;

// exit si accès direct.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Main::class ) ) {
	/**
	 * Tous ce qui est en lien avec les paramètres/options du plugin.
	 */
	class Main {

		/**
		 * Nous utilisons les initiales du text domain comme préfixe.
		 *
		 * @var string
		 */
		private $prefix;

		/**
		 * Une instance de Common Utilities Posts.
		 *
		 * @var Posts
		 */
		public $uposts;

		/**
		 * Initialise la classe et ses propriétés.
		 */
		public function __construct() {
			$this->uposts = new Posts();
		}

		/**
		 * Le préfixe de l'option.
		 *
		 * @see get_prefixed_option_key()
		 *
		 * @return string
		 */
		public function get_option_prefix(): string {
			// Un moyen d'identifier où le préfixe fini et la clé unique commence.
			$delimiter = '__';

			if ( empty( $this->prefix ) ) {
				$this->prefix = PluginData::plugin_prefix();
			}

			return $this->prefix . $delimiter;
		}

		/**
		 * Le nom complet de l'option, préfixé de façon constante, dans un format qui fonctionnera 
		 * avec les clés d'objet javascript(d'où les tirets convertis en underscores).
		 *
		 * @param string $key
		 *
		 * @return string
		 */
		public function get_prefixed_option_key( string $key ): string {
			$key = sanitize_key( $this->get_option_prefix() . $key );

			return str_replace( '-', '_', $key );
		}

		/**
		 * URL de la page des paramètres du plugin.
		 *
		 * @return string
		 */
		public function get_main_settings_page_url(): string {
			$url = 'options-general.php?page=' . $this->get_settings_page_slug();

			return admin_url( $url );
		}

		/**
		 * Slug de la page des paramètres du plugin.
		 *
		 * @return string
		 */
		public function get_settings_page_slug(): string {
			return PluginData::plugin_prefix() . '-settings';
		}

		/**
		 * Le texte traductible "Settings".
		 *
		 * @return string
		 */
		public function get_settings_word(): string {
			return esc_html__( 'Options', 'extend-rest-api-post' );
		}

		/**
		 * Définition des options par défaut.
		 *
		 * @return array
		 */
		public function options_default() {
		    return array(
		        'check_post_types' => false,
		    );
		}

		/**
		 * Une simple option de la base de données en "string" avec une valeur par défaut optionnelle.
		 *
		 * @param string $key
		 * @param string $default
		 *
		 * @return string
		 */
		public function get_option_as_string( string $key, string $default = '' ): string {
			$result = $this->get_option( $key, $default );

			return $result;
		}

		/**
		 * Une simple option de la base de données brute avec une valeur par défaut optionnelle.
		 *
		 * @param string $key
		 * @param mixed  $default
		 *
		 * @return mixed
		 */
		public function get_option( string $key, $default = '' ) {
			$all_options = $this->get_all_options();

			// On ne peut pas utiliser empty() car une checkbox non cochée est boolean false, par exemple.
			if ( isset( $all_options[ $key ] ) ) {
				return $all_options[ $key ];
			} else {
				return $default;
			}
		}

		/**
		 * Toutes les options sauvegardées de la base de données.
		 *
		 * @return array
		 */
		public function get_all_options(): array {
			$plugin_options = get_option( $this->get_prefixed_option_key( 'options' ) , $this->options_default() );

			if ( ! empty( $plugin_options ) ) {
				return (array) $plugin_options;
			} else {
				return [];
			}
		}

		/**
		 * Une simple option de la base de données en "array" avec une valeur par défaut optionnelle.
		 *
		 * @param string $key
		 * @param mixed  $default
		 *
		 * @return array
		 */
		public function get_option_as_array( string $key, $default = '' ): array {
			$result = $this->get_option( $key, $default );

			if ( is_string( $result ) ) {
				$result = json_decode( $result, true );
			}

			$result = (array) $result;

			$result = array_keys( $result );

			return $result;
		}

		/**
		 * Suppression de toutes les options de la base de données.
		 *
		 * @see delete_option()
		 *
		 * @return bool
		 */
		public function delete_all_options(): bool {
			return delete_option( $this->get_prefixed_option_key( 'options' ) );
		}

		/**
		 * Toutes les clés d'options avec le préfixe du plugin.
		 *
		 * @param bool $only_if_show_in_rest pour exclure ou pas si 'show_in_rest' = false.
		 *
		 * @return array
		 */
		public function get_all_prefixed_options( bool $only_if_show_in_rest = true ): array {
			$prefix = $this->get_option_prefix();

			$all_settings = get_registered_settings();

			$result = [];

			foreach ( $all_settings as $key => $value ) {
				if ( 0 === strpos( $key, $prefix ) ) {
					if (
						$only_if_show_in_rest
						&& empty( $all_settings[ $key ]['show_in_rest'] )
					) {
						continue;
					}

					$result[] = $key; // seulement les clés des options, pas tous leurs arguments
				}
			}

			return $result;
		}

		/**
		 * Affiche les informations pour la page des options
		 * callback: Rest API Section
		 *
		 * @return void
		 */
		public function callback_section_rest_options() {
    		echo '<p>Ces réglages vous permettent d\'ajouter l\'URL de l\'image à la une, le nom de l\'auteur et l\'URL de son avatar au retour de l\'API REST pour tous les types de post public.</p>';
		}

		/**
		 * Affiche les accordéons
		 * callback: checkbox field
		 *
		 * @return void
		 */
		public function callback_rest_api_fields($callback_args) {

		    // Boucle sur chaque type de post
		    foreach ($this->uposts->get_public_post_types() as $post_type) {
			    $options=$this->get_all_options();
				?>
	            <!-- insertion d'accordéon pour chaque type de post -->
	            <div class="accordion-container">
	                <div class="set">
	                    <a href="#">
	                        <?php
	                            // Récupération du nom du type de post pour afficher en titre
	                            $post_type_obj = get_post_type_object($post_type);
	                            $post_type_singular_name = $post_type_obj->labels->singular_name;
	                            $post_type_capitalized_name = ucwords(strtolower($post_type_singular_name));
	                            if ($post_type == 'page' || $post_type == 'post') {                            
	                                echo '<label class="erap_label"><span class="dashicons '.$post_type_obj->menu_icon.'"></span> ' . $post_type_capitalized_name . '</label>';                                
	                            } else {
	                                echo '<label class="erap_label"><span class="dashicons dashicons-admin-generic"></span> ' . $post_type_capitalized_name . ' (custom)</label>';
	                            }
	                        ?>
	                        <i class="dashicons dashicons-arrow-down-alt2" ></i>
	                    </a>
	                    <div class="content" style="margin: 0; padding: 1em;">
	                        <?php
	                            // Check-box pour le nom de l'auteur
	                            $post_author_name = $post_type . '_author_name';
	                            $checked = '';
	                            if (is_array($options) && array_key_exists($post_author_name, $options)) {
	                                $checked = 'checked';
	                            }
	                            echo '<div class="erap_custom_field"> <input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_author_name . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_author_name . ']" type="checkbox" value="' . $post_author_name . '"' . $checked . '></div> ';
	                            echo '<h4 class="erap_cf_title"> Nom de l\'auteur </h4>';                            

	                            // Check-box pour l'avatar de l'auteur
	                            $post_author_avatar = $post_type . '_author_avatar';
	                            $checked = '';
	                            if (is_array($options) && array_key_exists($post_author_avatar, $options)) {
	                                $checked = 'checked';
	                            }
	                            echo '<div class="erap_custom_field"> <input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_author_avatar . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_author_avatar . ']" type="checkbox" value="' . $post_author_avatar . '"' . $checked . '></div> ';
	                            echo '<h4 class="erap_cf_title"> Avatar de l\'auteur </h4>';                            

	                            // Check-box pour l'image à la une
	                            $post_featured_image = $post_type . '_featured_image';
	                            $checked = '';
	                            if (is_array($options) && array_key_exists($post_featured_image, $options)) {
	                                $checked = 'checked';
	                            }
	                            echo '<div class="erap_custom_field"> <input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_featured_image . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_featured_image . ']" type="checkbox" value="' . $post_featured_image . '"' . $checked . '></div> ';
	                            echo '<h4 class="erap_cf_title"> Image à la une </h4>';
	                        ?>
	                    </div>
	                </div>
	            </div>
	            <?php
	  		}
		}

		/**
		 * Enregistrement des paramètres.
		 *
		 * @link https://developer.wordpress.org/reference/functions/register_setting/
		 * @link https://developer.wordpress.org/rest-api/reference/settings/
		 * @link https://make.wordpress.org/core/2016/10/26/registering-your-settings-in-wordpress-4-7/
		 * @link https://make.wordpress.org/core/2019/10/03/wp-5-3-supports-object-and-array-meta-types-in-the-rest-api/
		 */
		public function register_settings() {
			register_setting(
		        $this->get_prefixed_option_key( 'options' ),
		        $this->get_prefixed_option_key( 'options' )
    		);
		    add_settings_section(
		        'erap_section_rest_options',
		        '',
		        [$this,'callback_section_rest_options'],
		        $this->get_settings_page_slug()
		    );
		    add_settings_field(
		        'check_post_types',
		        '',
		        [$this,'callback_rest_api_fields'],
		        $this->get_settings_page_slug(),
		        'erap_section_rest_options',
		        ['id' => 'check_post_types']
		    );
		}

		public function register_api_settings() {
			$options=$this->get_all_options();

 		   // Boucle sur chaque type de post
		    foreach ($this->uposts->get_public_post_types() as $post_type) {

		        // ajout des données auteur à l'API si les options ont été choisies
		        $post_author_name = $post_type . '_author_name';
		        if (is_array($options) && array_key_exists($post_author_name, $options)) {
		            register_rest_field($post_type,
		                'author_name',
		                array(
		                    'get_callback' => [$this->uposts,'get_author_name'],
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
		                    'get_callback' => [$this->uposts,'get_author_avatar'],
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
		                    'get_callback' => [$this->uposts,'get_featured_image'],
		                    'update_callback' => null,
		                    'schema' => null,
		                )
		            );
		        }
		    }
	    }
	}
}