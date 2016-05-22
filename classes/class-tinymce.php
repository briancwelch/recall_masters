<?php
/**
 * The Recall Masters TinyMCE Class
 *
 * @package Recall Masters
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recall Masters TinyMCE.
 */
class Recall_Masters_TinyMCE {

	/**
	 * Class Constructor.
	 *
	 * @method __construct
	 */
	public function __construct() {
		// Add filters.
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_form_button' ) );
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_results_button' ) );
		add_filter( 'mce_buttons',          array( $this, 'register_tinymce_form_button' ) );
		add_filter( 'mce_buttons',          array( $this, 'register_tinymce_results_button' ) );
	}

	/**
	 * Load the TinyMCE Recall Form plugin.
	 *
	 * @method add_tinymce_form_button
	 * @param	[type] $plugin_array [description].
	 */
	function add_tinymce_form_button( $plugin_array ) {
		$plugin_array['recall_masters_form'] = plugins_url( '/assets/js/recall_form.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Load the TinyMCE Recall Results plugin.
	 *
	 * @method add_tinymce_results_button
	 * @param	[type] $plugin_array [description].
	 */
	function add_tinymce_results_button( $plugin_array ) {
		$plugin_array['recall_masters_results'] = plugins_url( '/assets/js/recall_results.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Add the TinyMCE Form Button to the editor.
	 *
	 * @method register_tinymce_form_button
	 * @param	[type] $buttons [description].
	 * @return [type] [description]
	 */
	function register_tinymce_form_button( $buttons ) {
		array_push( $buttons, 'recall_form' );
		return $buttons;
	}

	/**
	 * Add the TinyMCE Results Button to the editor.
	 *
	 * @method register_tinymce_results_button
	 * @param	[type] $buttons [description].
	 * @return [type] [description]
	 */
	function register_tinymce_results_button( $buttons ) {
		array_push( $buttons, 'recall_results' );
		return $buttons;
	}
} // End Class
