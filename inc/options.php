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
		__( 'Brasa Theme Deploy', 'brasa-theme-deploy' ),
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
		'brasa_deploy_branch',
		__( 'Branch', 'brasa-theme-deploy' ),
		'brasa_theme_deploy_text_field_1_render',
		'pluginPage',
		'brasa_theme_deploy_pluginPage_section'
	);


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
	<?php

}


function brasa_theme_deploy_settings_section_callback(  ) {

	_e( 'Deploy any WordPress theme hosted on GitHub', 'brasa-theme-deploy' );

}


function brasa_theme_deploy_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

?>
