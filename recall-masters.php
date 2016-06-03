<?php
/**
 * Plugin Name:       Recall Masters
 * Plugin URI:        http://digitalcliq.com
 * Description:       A WordPress plugin that checks the RecallMasters API. Built for DigitalCLIQ.
 * Version:           1.0.6
 * Author:            Brian C. Welch
 * Author URI:        http://briancwelch.com
 * Requires at least: 4.0
 * Tested up to:      4.5
 * License:           MIT
 *
 * Text Domain:       recall_masters
 * Domain Path:       /languages/
 *
 * @package           Recall masters
 * @category          Plugin
 * @author            Brian C. Welch
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Definitions.
if ( ! defined( 'RECALL_MASTERS_VER' ) ) { define( 'RECALL_MASTERS_FILE', '1.0.6' ); }
if ( ! defined( 'RECALL_MASTERS_FILE' ) ) { define( 'RECALL_MASTERS_FILE', __FILE__ ); }
if ( ! defined( 'RECALL_MASTERS_PATH' ) ) { define( 'RECALL_MASTERS_PATH', dirname( __FILE__ ) ); }
if ( ! defined( 'RECALL_MASTERS_URL' ) ) { define( 'RECALL_MASTERS_URL', plugin_dir_url( __FILE__ ) ); }

/**
 * Register the recall_masters() function.
 */
function recall_masters() {
	return Recall_Masters::get_instance();
}

// Recall Masters Global.
$GLOBALS['recall_masters'] = recall_masters();
global $recall_masters;


/**
 * The Recall Masters Core Class.
 */
class Recall_Masters {

	/**
	 * Instance.
	 *
	 * @var [type]
	 */
	private static $instance;

	/**
	 * Singleton.
	 *
	 * @method get_instance
	 * @return [type]       [description]
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Class Constructor.
	 *
	 * @method __construct
	 */
	public function __construct() {

		$this->includes();

		// Class Instantiation.
		$this->recall_masters_options     = new Recall_Masters_Options();
		$this->recall_masters_scripts     = new Recall_Masters_Scripts();
		$this->recall_masters_shortcodes  = new Recall_Masters_Shortcodes();
		$this->recall_masters_tinymce     = new Recall_Masters_TinyMCE();

		// Add Actions.
		add_action( 'plugins_loaded', array( $this, 'translations' ) );
	}

	/**
	 * Includes.
	 *
	 * @method includes
	 */
	function includes() {
		// Includes.
		require_once( RECALL_MASTERS_PATH . '/classes/class-options.php' );
		require_once( RECALL_MASTERS_PATH . '/classes/class-scripts.php' );
		require_once( RECALL_MASTERS_PATH . '/classes/class-shortcodes.php' );
		require_once( RECALL_MASTERS_PATH . '/classes/class-tinymce.php' );
	}

	/**
	 * Load Plugin Translations.
	 *
	 * @method translations
	 */
	function translations() {
		load_plugin_textdomain(
			'recall_masters',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}
} // End class.
