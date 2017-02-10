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
 * Version:           1.1
 * Author:            @TODO
 * Author URI:        @TODO
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */
// Include plugin options
require 'inc/options.php';
// Include deploy by commit hash class
require 'inc/class-deploy-by-commit.php';

class Brasa_Update_Deploy_File {
	/**
	* Instance of this class.
	*
	* @var object
	*/
	protected static $instance = null;

	/**
	 * Options page array
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Download & extract theme tar.gz file from GitHub
	 * @return type
	 */
    protected function extract_file() {
		$folder = get_template_directory();
		$folder = explode( '/', $folder );
		unset( $folder[ count( $folder ) - 1 ] );
		$folder = implode( '/', $folder );
		$download_url = sprintf( '%s/archive/%s.tar.gz', $this->data['repository']['html_url'], $this->data[ 'after' ] );
		exec( "cd $folder && wget $download_url" );

		$file_format = sprintf( '%s.tar.gz', $this->data[ 'after' ] );
		$file_check = $folder . '/' . $file_format;
		if ( ! file_exists( $file_check ) ) {
			return false;
		}
		$template_folder = get_template_directory();
		exec( "rm -rf $template_folder" );
		exec( "cd $folder && tar -zxvf $file_format" );
		$folder_format = sprintf( '%s-%s', $this->data['repository']['name'], $this->data[ 'after' ] );
		rename( $folder . '/' . $folder_format, $template_folder );
		unlink( $folder . '/' . $file_format );
		return true;
	}
	/**
	 * Check if a commit exist by hash
	 * @param string $hash
	 * @return boolean
	 */
	public function commit_exists( $hash ) {
    	if ( ! isset( $this->options[ 'brasa_deploy_repository' ] ) ) {
    		return false;
    	}
		$response = wp_remote_get( sprintf( 'https://api.github.com/repos/%s/git/commits/%s', $this->options[ 'brasa_deploy_repository' ], $hash ) );
		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === intval( $response_code ) ) {
			return true;
		} elseif ( 403 == intval( $response_code ) ) {
			$this->options[ 'error' ] = __( 'Error: API rate limit exceeded for your server IP', 'odin' );
		} else {
			$this->options[ 'error' ] = __( 'Error: This commit does not exist', 'odin' );
			return false;
		}
	}
	/**
	 * Construct class
	 * @return boolean
	 */
	public function __construct () {
		add_action( 'wp_ajax_nopriv_brasa_deploy', array( $this, 'deploy' ) );
	}
	/**
	 * Validate secret key from GitHub
	 * @return boolean
	 */
	private function validate_secret() {
		list ( $algo, $signature ) = explode( '=', $_SERVER['HTTP_X_HUB_SIGNATURE'] );
		//var_dump( explode( '=', $_SERVER['HTTP_X_HUB_SIGNATURE'] ) );
        if ( $algo !== 'sha1' ) {
            // see https://developer.github.com/webhooks/securing/
            return false;
        }
        if ( ! isset( $this->options[ 'brasa_deploy_secret' ] ) ) {
        	return false;
        }
        //var_dump( $HTTP_RAW_POST_DATA );
        $payloadhash = hash_hmac( $algo, file_get_contents('php://input'), $this->options[ 'brasa_deploy_secret' ] );
        if ( $payloadhash == $signature ) {
        	return true;
        }
        return false;
	}
	/**
	 * Deploy last commit from GitHub repo
	 * @return boolean
	 */
	public function deploy() {
		$this->options = get_option( 'brasa_theme_deploy_settings', false );
		if ( ! $this->options ) {
			wp_die( 'false 0' );
		}
		$this->data = json_decode( file_get_contents( 'php://input' ), true );
		if ( $this->validate_secret() === false ) {
			return false;
		}
		$branch = $this->options[ 'brasa_deploy_branch' ];
		$branch_pos = strpos( $this->data[ 'ref' ], $branch );
        if ( ! $branch || $branch_pos === false ) {
        	wp_die( 'false 2' );
        }

		if ( $this->extract_file() ) {
			wp_die( 'true' );
		}
		wp_die( 'false 3' );
	}
	/**
	 * Get class instance
	 * @return object
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
/**
 * Instance plugin classes
 */
function brasa_theme_deploy_load_classes() {
	new Brasa_Update_Deploy_File();
	new Brasa_Force_Deploy_By_Commit();
}
add_action( 'plugins_loaded', 'brasa_theme_deploy_load_classes' );
