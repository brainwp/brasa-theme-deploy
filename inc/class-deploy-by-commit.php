<?php
/**
 * Use the PHP for https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       Brasa GitHub Theme Deploy
 * Plugin URI:        @TODO
 * Description:       @TODO
 * Version:           1.0.0
 * Author:            @TODO
 * Author URI:        @TODO
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */
// Include plugin options
class Brasa_Force_Deploy_By_Commit extends Brasa_Update_Deploy_File {
	/**
	 * Construct class
	 * @return boolean
	 */
	public function __construct () {
		add_action( 'wp_ajax_brasa_deploy_by_commit', array( $this, 'deploy' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ), 10, 1 );
	}
	/**
	 * Add scripts
	 */
	public function add_scripts() {
		$current_screen = get_current_screen();
		if ( 'tools_page_brasa_theme_deploy' != $current_screen->base ) {
			return;
		}
		$url = plugins_url( 'admin/assets/js/', dirname(__FILE__) );
		wp_enqueue_script( 'brasa-theme-deploy-adminjs',  $url . 'admin.js', array(), null, true );
	}
	/**
	 * Download & extract theme tar.gz file from GitHub
	 * @return type
	 */
    protected function extract_file() {
    	wp_verify_nonce( $_REQUEST[ 'nonce' ], 'deploy_by_hash' );
    	$this->options = get_option( 'brasa_theme_deploy_settings' );
    	if ( ! isset( $this->options[ 'brasa_deploy_repository' ] ) ) {
    		return false;
    	}
    	$hash = esc_textarea( $_REQUEST[ 'hash' ] );
    	if ( ! $this->commit_exists( $hash ) ) {
    		return false;
    	}
    	$repository_name = explode( '/', $this->options[ 'brasa_deploy_repository' ] );
    	$repository_name = $repository_name[ 1 ];
		$folder = get_template_directory();
		$folder = explode( '/', $folder );
		unset( $folder[ count( $folder ) - 1 ] );
		$folder = implode( '/', $folder );
		$download_url = sprintf( 'http://github.com/%s/archive/%s.tar.gz', $this->options[ 'brasa_deploy_repository'], $hash );
		exec( "cd $folder && wget $download_url" );

		$file_format = sprintf( '%s.tar.gz', $hash );
		$file_check = $folder . '/' . $file_format;
		if ( ! file_exists( $file_check ) ) {
			$this->options[ 'error' ] = $download_url;
			return false;
		}
		$template_folder = get_template_directory();
		exec( "rm -rf $template_folder" );
		exec( "cd $folder && tar -zxvf $file_format" );
		$folder_format = sprintf( '%s-%s', $repository_name, $hash );
		rename( $folder . '/' . $folder_format, $template_folder );
		unlink( $folder . '/' . $file_format );
		return true;
	}
	/**
	 * Deploy last commit from GitHub repo
	 * @return boolean
	 */
	public function deploy() {
		if ( $this->extract_file() ) {
			echo '<div class="notice notice-success is-dismissible" style="margin:0;">';
        	echo '<p>';
        	_e( 'Done!', 'brasa-theme-deploy' );
        	echo '</p>';
        	echo '</div>';
		} else {
			echo '<div class="notice notice-error is-dismissible" style="margin:0;">';
        	echo '<p>';
        	if ( isset( $this->options[ 'error' ] ) ) {
        		echo $this->options[ 'error' ];
        	} else {
        		_e( 'An error has occurred!', 'brasa-theme-deploy' );
        	}
        	echo '</p>';
        	echo '</div>';
		}
		echo '<br>';
		wp_die();
	}
}
