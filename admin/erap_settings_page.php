<?php 

// exit si accès direct
if (!defined('ABSPATH')) {
    exit;
}

// fonction callback de add_submenu_page pour afficher les sections des paramètres et les champs
function erap_display_settings_page() {
    // On vérifie si l'utilisateur à les droits nécessaires
    if (!current_user_can('manage_options')) {
        return;
    }
?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
			<?php
                settings_fields('erap_options');
                do_settings_sections('erap');
                submit_button();
            ?>
		</form>
	</div>
	<?php
}