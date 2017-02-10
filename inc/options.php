<?php
add_action( 'admin_menu', 'brasa_theme_deploy_add_admin_menu' );
add_action( 'admin_init', 'brasa_theme_deploy_settings_init' );


function brasa_theme_deploy_add_admin_menu(  ) {

	add_submenu_page( 'tools.php', 'Brasa Theme Deploy', 'Brasa Theme Deploy', 'manage_options', 'brasa_theme_deploy', 'brasa_theme_deploy_options_page' );

}


function brasa_theme_deploy_settings_init(  ) {

	register_setting( 'pluginPage', 'brasa_theme_deploy_settings' );

	add_settings_section(
		'brasa_theme_deploy_pluginPage_section',
		__( 'Brasa Theme Deploy - Push to Deploy', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'brasa_deploy_secret',
		__( 'GitHub Secret', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_text_field_0_render',
		'pluginPage',
		'brasa_theme_deploy_pluginPage_section'
	);

	add_settings_field(
		'brasa_deploy_repository',
		__( 'GitHub Repository: user/repo (Example: wpbrasil/odin)', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_text_field_3_render',
		'pluginPage',
		'brasa_theme_deploy_pluginPage_section'
	);

	add_settings_field(
		'brasa_deploy_branch',
		__( 'Branch', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_text_field_1_render',
		'pluginPage',
		'brasa_theme_deploy_pluginPage_section'
	);

	add_settings_section(
		'brasa_theme_deploy_pluginPage_section_force_commit',
		__( 'Brasa Theme Deploy - Force Update by commmit hash', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'brasa_deploy_force_hash',
		__( 'Commit Hash', 'brasa-theme-deploy' ),
		'brasa_deploy_force_hash',
		'pluginPage',
		'brasa_theme_deploy_pluginPage_section_force_commit'
	);


}


function brasa_deploy_force_hash(  ) {

	?>
	<input type='text' name='brasa_deploy_force_hash' data-nonce="<?php echo wp_create_nonce( 'deploy_by_hash');?>">
	<div class="clear"></div><!-- .clear -->
	<div id="brasa-deploy-status"></div><!-- #brasa-deploy-status -->
	<br>
	<button class="button button-primary" id="brasa-deploy-by-commit" data-load="<?php esc_html_e( 'Loading..', 'odin' );?>">
		<?php _e( 'Deploy!', 'odin' );?>
	</button>
	<?php

}


function brasa_theme_deploy_text_field_0_render(  ) {

	$options = get_option( 'brasa_theme_deploy_settings' );
	?>
	<input type='text' name='brasa_theme_deploy_settings[brasa_deploy_secret]' value='<?php echo $options['brasa_deploy_secret']; ?>'>
	<?php

}


function brasa_theme_deploy_text_field_1_render(  ) {

	$options = get_option( 'brasa_theme_deploy_settings' );
	?>
	<input type='text' name='brasa_theme_deploy_settings[brasa_deploy_branch]' value='<?php echo $options['brasa_deploy_branch']; ?>'>
	<div class="clear"></div><!-- .clear -->
	<?php
	submit_button();
}
function brasa_theme_deploy_text_field_3_render(  ) {

	$options = get_option( 'brasa_theme_deploy_settings' );
	?>
	<input type='text' name='brasa_theme_deploy_settings[brasa_deploy_repository]' value='<?php echo $options['brasa_deploy_repository']; ?>'>
	<?php
}


function brasa_theme_deploy_settings_section_callback(  ) {
}


function brasa_theme_deploy_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		?>

	</form>
	<?php

}

?>
