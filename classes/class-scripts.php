<?php
/**
 * The Recall Masters Scripts Class
 *
 * @package Recall Masters
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recall Masters Scripts.
 */
class Recall_Masters_Scripts {

	/**
	 * Class Constructor.
	 *
	 * @method __construct
	 */
	public function __construct() {
		// Add actions.
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Register scripts and stylesheets.
	 *
	 * @method scripts
	 */
	function scripts() {
		// CSS.
		wp_enqueue_style(
			'recall_strap',                                                     // Name of the stylesheet. Should be unique.
			'//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',  // Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
			array(),                                                            // An array of registered stylesheet handles this stylesheet depends on.
			time(),                                                             // String specifying stylesheet version number.  Set to php time() for caching.
			null                                                                // The media for which this stylesheet has been defined.
		);
		wp_enqueue_style(
			'form_validation',
			RECALL_MASTERS_URL . 'assets/css/formValidation.min.css',
			array(),
			time(),
			null
		);
		wp_enqueue_style(
			'recall_masters',
			RECALL_MASTERS_URL . 'assets/css/app.css',
			array(),
			time(),
			null
		);

		// JS.
		wp_enqueue_script(
			'recall_strap',                                                   // Name of the stylesheet. Should be unique.
			'//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',  // Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
			array( 'jquery' ),                                                // An array of registered script handles this script depends on.
			time(),                                                           // String specifying script version number.  Set to php time() for caching.
			true                                                              // Whether to enqueue the script before </body> instead of in the <head>.
		);
		wp_enqueue_script(
			'form_validation',
			RECALL_MASTERS_URL . 'assets/js/formValidation.min.js',
			array( 'jquery' ),
			time(),
			true
		);
		wp_enqueue_script(
			'form_validation_popular',
			RECALL_MASTERS_URL . 'assets/js/formValidation.popular.min.js',
			array( 'jquery' ),
			time(),
			true
		);
		wp_enqueue_script(
			'bootstrap_framework',
			RECALL_MASTERS_URL . 'assets/js/bootstrap.js',
			array( 'jquery' ),
			time(),
			true
		);
		wp_enqueue_script(
			'recall_masters',
			RECALL_MASTERS_URL . 'assets/js/recall_masters.js',
			array( 'jquery' ),
			time(),
			true
		);
	}
} // End Class
