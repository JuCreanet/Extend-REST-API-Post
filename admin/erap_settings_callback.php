<?php 

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// callback: Rest API Section
function erap_callback_section_rest_options() {
    echo '<p>Ces réglages vous permettent d\'ajouter l\'URL de l\'image à la une, le nom de l\'auteur et l\'URL de son avatar au retour de l\'API REST pour tous les types de post public.</p>';
}

// callback: checkbox field
function erap_callback_rest_api_fields($callback_args) {

    // Boucle sur chaque type de post
    $post_types = erap_get_post_types();
    foreach ($post_types as $post_type) {
        global $options;
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
                                echo '<label style="margin: 1em;"> ' . $post_type_capitalized_name . '</label>';                                
                            } else {
                                echo '<label style="margin: 1em;"> ' . $post_type_capitalized_name . ' (custom)</label>';
                            }
                        ?>
                        <i class="fa fa-plus" ></i>
                    </a>
                    <div class="content" style="margin: 0; padding: 1em;">
                        <?php
                            // Check-box pour le nom de l'auteur
                            $post_author_name = $post_type . '_author_name';
                            $checked = '';
                            if (is_array($options) && array_key_exists($post_author_name, $options)) {
                                $checked = 'checked';
                            }
                            echo '<div class="erap_custom_field" style="display: inline-block; margin-top: 1em;"> <input id="erap_options_' . $post_author_name . '" name="erap_options[' . $post_author_name . ']" type="checkbox" value="' . $post_author_name . '"' . $checked . '></div> ';
                            echo '<h4 class="erap_cf_title" style="margin: 0; display: inline-block;"> Nom de l\'auteur </h4>';                            

                            // Check-box pour l'avatar de l'auteur
                            $post_author_avatar = $post_type . '_author_avatar';
                            $checked = '';
                            if (is_array($options) && array_key_exists($post_author_avatar, $options)) {
                                $checked = 'checked';
                            }
                            echo '<div class="erap_custom_field" style="display: inline-block; margin-top: 1em;"> <input id="erap_options_' . $post_author_avatar . '" name="erap_options[' . $post_author_avatar . ']" type="checkbox" value="' . $post_author_avatar . '"' . $checked . '></div> ';
                            echo '<h4 class="erap_cf_title" style="margin: 0; display: inline-block;"> Avatar de l\'auteur </h4>';                            

                            // Check-box pour l'image à la une
                            $post_featured_image = $post_type . '_featured_image';
                            $checked = '';
                            if (is_array($options) && array_key_exists($post_featured_image, $options)) {
                                $checked = 'checked';
                            }
                            echo '<div class="erap_custom_field" style="display: inline-block; margin-top: 1em;"> <input id="erap_options_' . $post_featured_image . '" name="erap_options[' . $post_featured_image . ']" type="checkbox" value="' . $post_featured_image . '"' . $checked . '></div> ';
                            echo '<h4 class="erap_cf_title" style="margin: 0; display: inline-block;"> Image à la une </h4>';
                        ?>
                    </div>
                </div>
            </div>
            <?php
    }
}
