<?php
/**
 * The Recall Masters Options Class
 *
 * @package Recall Masters
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recall Masters Options.
 */
class Recall_Masters_Options {

	/**
	 * Class Constructor.
	 *
	 * @method __construct
	 */
	public function __construct() {
		// Add actions.
		add_action( 'admin_menu', array( $this, 'recall_masters_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'recall_masters_settings_init' ) );
	}

	/**
	 * Add settings menu
	 *
	 * @method recall_masters_add_admin_menu
	 */
	function recall_masters_add_admin_menu() {
		add_submenu_page(
			'options-general.php',
			'Recall Masters',
			'Recall Masters',
			'manage_options',
			'recall_masters',
			array( $this, 'recall_masters_options_page' )
		);
	}

	/**
	 * Add settings to settings page.
	 *
	 * @method recall_masters_settings_init
	 */
	function recall_masters_settings_init() {
		register_setting( 'plugin_page', 'recall_masters_settings' );
		add_settings_section(
			'recall_masters_plugin_page_section',
			__( 'Plugin Settings', 'recall_masters' ),
			array( $this, 'recall_masters_settings_section_callback' ),
			'plugin_page'
		);
		add_settings_field(
			'recall_masters_text_field_0',
			__( 'Recall Results Page:', 'recall_masters' ),
			array( $this, 'recall_masters_select_field_0_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_1',
			__( 'API Token:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_1_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_6',
			__( 'API User:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_6_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_2',
			__( 'Form Header:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_2_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_3',
			__( 'Form Description:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_3_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_4',
			__( 'Active Recall Note:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_4_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_5',
			__( 'Inactive Recall Note:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_5_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_7',
			__( 'Repair Severity Text (Level 1):', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_7_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_8',
			__( 'Repair Severity Text (Level 2):', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_8_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_9',
			__( 'Repair Severity Text (Level 3):', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_9_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_10',
			__( 'Repair Severity Text (Level 4):', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_10_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
		add_settings_field(
			'recall_masters_text_field_11',
			__( 'Repair Severity Text (Level 5):', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_11_render' ),
			'plugin_page',
			'recall_masters_plugin_page_section'
		);
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_0_render
	 */
	function recall_masters_select_field_0_render() {
		$options = get_option( 'recall_masters_settings' );
		$args = array(
				'selected' => $options['recall_masters_select_field_0'],
				'echo'     => 1,
				'name'     => 'recall_masters_settings[recall_masters_select_field_0]',
		);
		wp_dropdown_pages( $args );
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_1_render
	 */
	function recall_masters_text_field_1_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_1]' value='<?php esc_html_e( $options['recall_masters_text_field_1'], 'recall_masters' ); ?>'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_2_render
	 */
	function recall_masters_text_field_2_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_2]' value='<?php esc_html_e( $options['recall_masters_text_field_2'], 'recall_masters' ); ?>' placeholder='Checking Your Recall Status Is Easy.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_3_render
	 */
	function recall_masters_text_field_3_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_3]' value='<?php esc_html_e( $options['recall_masters_text_field_3'], 'recall_masters' ); ?>' placeholder='Enter your VIN to check your recall status.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_4_render
	 */
	function recall_masters_text_field_4_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_4]' value='<?php esc_html_e( $options['recall_masters_text_field_4'], 'recall_masters' ); ?>' placeholder='There is current recall information available for this vehicle.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_5_render
	 */
	function recall_masters_text_field_5_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_5]' value='<?php esc_html_e( $options['recall_masters_text_field_5'], 'recall_masters' ); ?>' placeholder='There are no current recalls for your vehicle.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_6_render
	 */
	function recall_masters_text_field_6_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_6]' value='<?php esc_html_e( $options['recall_masters_text_field_6'], 'recall_masters' ); ?>' placeholder='Darth Vader'>
		<?php
	}
	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_7_render
	 */
	function recall_masters_text_field_7_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_7]' value='<?php esc_html_e( $options['recall_masters_text_field_7'], 'recall_masters' ); ?>' placeholder='Sticker'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_8_render
	 */
	function recall_masters_text_field_8_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_8]' value='<?php esc_html_e( $options['recall_masters_text_field_8'], 'recall_masters' ); ?>' placeholder='Nuisance'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_9_render
	 */
	function recall_masters_text_field_9_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_9]' value='<?php esc_html_e( $options['recall_masters_text_field_9'], 'recall_masters' ); ?>' placeholder='Programming Only'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_10_render
	 */
	function recall_masters_text_field_10_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_10]' value='<?php esc_html_e( $options['recall_masters_text_field_10'], 'recall_masters' ); ?>' placeholder='No Car Lift'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 *
	 * @method recall_masters_text_field_11_render
	 */
	function recall_masters_text_field_11_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_11]' value='<?php esc_html_e( $options['recall_masters_text_field_11'], 'recall_masters' ); ?>' placeholder='Car Lift'>
		<?php
	}


	/**
	 * [recall_masters_settings_section_callback description]
	 *
	 * @method recall_masters_settings_section_callback
	 */
	function recall_masters_settings_section_callback() {
		esc_html_e( 'Here you will find a few options to set for the Recall Masters plugin, such as the results page, API token, and text verbiage.', 'recall_masters' );
	}

	/**
	 * [recall_masters_options_page description]
	 *
	 * @method recall_masters_options_page
	 */
	function recall_masters_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>Recall Masters</h2>
			<?php
			settings_fields( 'plugin_page' );
			do_settings_sections( 'plugin_page' );
			submit_button();
			?>
		</form>
		<?php
	}

} // End Class
