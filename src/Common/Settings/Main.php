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
		 * Le texte traductible "Options".
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
		 * Toutes les options sauvegardées par ce plugin dans la base de données.
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
		 * @see register_settings()
		 *
		 * @return void
		 */
		public function callback_section_rest_options() {
    		echo '<p>'._e('Ces réglages vous permettent d\'ajouter l\'URL de l\'image à la une, le nom de l\'auteur et l\'URL de son avatar au retour de l\'API REST pour tous les types de post public.', 'extend-rest-api-post').'</p>';
		}

		/**
		 * Affiche les accordéons
		 * callback: checkbox field
		 *
		 * @see register_settings()
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
	                            $post_type_capitalized_name = ucwords(strtolower($post_type->labels->singular_name));
	                            if ($post_type->_builtin) {                            
	                                echo '<label class="erap_label"><span class="dashicons '.$post_type->menu_icon.'"></span> ' . $post_type_capitalized_name . '</label>';                                
	                            } else {
	                                echo '<label class="erap_label"><span class="dashicons dashicons-admin-generic"></span> ' . $post_type_capitalized_name . ' (custom)</label>';
	                            }
	                        ?>
	                        <i class="dashicons dashicons-arrow-down-alt2" ></i>
	                    </a>
	                    <div class="content">
	                        <?php
	                            // Check-box pour le nom de l'auteur
	                            $post_author_name = $post_type->name . '_author_name';
	                            $checked = '';
	                            if (is_array($options) && array_key_exists($post_author_name, $options)) {
	                                $checked = 'checked';
	                            }
	                            echo '<div class="erap_custom_field"> <label class="switch"><input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_author_name . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_author_name . ']" type="checkbox" value="' . $post_author_name . '"' . $checked . '><span class="slider round"></span></label></div> ';
	                            echo '<h4 class="erap_cf_title">'._e( 'Nom de l\'auteur', 'extend-rest-api-post' ).'</h4>';                            

	                            // Check-box pour l'avatar de l'auteur
	                            $post_author_avatar = $post_type->name . '_author_avatar';
	                            $checked = '';
	                            if (is_array($options) && array_key_exists($post_author_avatar, $options)) {
	                                $checked = 'checked';
	                            }
	                            echo '<div class="erap_custom_field"> <label class="switch"><input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_author_avatar . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_author_avatar . ']" type="checkbox" value="' . $post_author_avatar . '"' . $checked . '><span class="slider round"></span></label></div> ';
	                            echo '<h4 class="erap_cf_title">'. _e( 'Avatar de l\'auteur', 'extend-rest-api-post' ).'</h4>';                            

		                        // Check-box pour l'image à la une si nécessaire
	                            if (post_type_supports($post_type->name,'thumbnail')){
		                            $post_featured_image = $post_type->name . '_featured_image';
		                            $checked = '';
		                            if (is_array($options) && array_key_exists($post_featured_image, $options)) {
		                                $checked = 'checked';
		                            }
		                            echo '<div class="erap_custom_field"> <label class="switch"><input id="'.$this->get_prefixed_option_key( 'options' ).'_' . $post_featured_image . '" name="'.$this->get_prefixed_option_key( 'options' ).'[' . $post_featured_image . ']" type="checkbox" value="' . $post_featured_image . '"' . $checked . '><span class="slider round"></span></label></div> ';
		                            echo '<h4 class="erap_cf_title">'. _e( 'Image à la une', 'extend-rest-api-post' ).'</h4>';
		                        }
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
		 * Ajoute une ligne dans wp_options avec option_name = erap__options et les paramètres dans option_value
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

		/**
		 * Extension de l'API
		 *
		 * Ajoute pour chaque type de post les infos sélectionnées
		 *
		 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
		 */
		 public function register_api_settings() {
			$options=$this->get_all_options();

 		   // Boucle sur chaque type de post
		    foreach ($this->uposts->get_public_post_types() as $post_type) {

		        // ajout des données auteur à l'API si les options ont été choisies
		        $post_author_name = $post_type->name . '_author_name';
		        if (is_array($options) && array_key_exists($post_author_name, $options)) {
		            register_rest_field($post_type->name,
		                'author_name',
		                array(
		                    'get_callback' => [$this->uposts,'get_author_name'],
		                    'update_callback' => null,
		                    'schema' => null,
		                )
		            );
		        }
		       $post_author_avatar = $post_type->name . '_author_avatar';
		        if (is_array($options) && array_key_exists($post_author_avatar, $options)) {
		            register_rest_field($post_type->name,
		                'author_avatar',
		                array(
		                    'get_callback' => [$this->uposts,'get_author_avatar'],
		                    'update_callback' => null,
		                    'schema' => null,
		                )
		            );
		        }

		        // ajout de l'image à la une à l'API si l'option a été choisie
		        $post_featured_image = $post_type->name . '_featured_image';
		        if (is_array($options) && array_key_exists($post_featured_image, $options)) {
		            register_rest_field($post_type->name,
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
