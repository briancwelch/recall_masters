<?php
/**
 * Plugin Name:       Recall Masters
 * Plugin URI:        http://digitalcliq.com
 * Description:       A WordPress plugin that checks the RecallMasters API.	Built for DigitalCLIQ.
 * Version:           1.0.1
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

define( 'RECALL_MASTERS_VER', '1.0.1' );

/**
 * The Recall Masters Core Class.
 */
class Recall_Masters {

	/**
	 * [$instance description]
	 *
	 * @var [type]
	 */
	private static $instance;

	/**
	 * Singleton
	 *
	 * @return self::$instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( ! defined( 'RECALL_MASTERS_FILE' ) ) { define( 'RECALL_MASTERS_FILE', __FILE__ ); }
		if ( ! defined( 'RECALL_MASTERS_PATH' ) ) { define( 'RECALL_MASTERS_PATH', dirname( __FILE__ ) ); }
		if ( ! defined( 'RECALL_MASTERS_URL' ) ) { define( 'RECALL_MASTERS_URL', plugin_dir_url( __FILE__ ) ); }

		// Add actions.
		add_action( 'plugins_loaded', array( $this, 'translations' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'admin_menu',	array( $this, 'recall_masters_add_admin_menu' ) );
		add_action( 'admin_init',	array( $this, 'recall_masters_settings_init' ) );

		// Add filters.
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_form_button' ) );
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_results_button' ) );
		add_filter( 'mce_buttons', array( $this, 'register_tinymce_form_button' ) );
		add_filter( 'mce_buttons', array( $this, 'register_tinymce_results_button' ) );

		// Add shortcodes.
		add_shortcode( 'recall_form', array( $this, 'recall_form_shortcode' ) );
		add_shortcode( 'recall_results', array( $this, 'recall_results_shortcode' ) );

	}

	/**
	 * Load Plugin Text Domain
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

	/**
	 * Register scripts and stylesheets.
	 *
	 * @method scripts
	 */
	function scripts() {
		// CSS.
		wp_enqueue_style(
			'recall_strap',
			'//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
			array(),
			time(),
			null
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
			'recall_strap',
			'//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js',
			array( 'jquery' ),
			time(),
			true
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

	/**
	 * Load the TinyMCE Plugin
	 *
	 * @method add_tinymce_form_button
	 * @param	[type] $plugin_array [description].
	 */
	function add_tinymce_form_button( $plugin_array ) {
		$plugin_array['recall_masters_form'] = plugins_url( '/assets/js/recall_form.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Load the TinyMCE Plugin
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
		register_setting( 'pluginPage', 'recall_masters_settings' );
		add_settings_section(
			'recall_masters_pluginPage_section',
			__( 'Plugin Settings', 'recall_masters' ),
			array( $this, 'recall_masters_settings_section_callback' ),
			'pluginPage'
		);
		add_settings_field(
			'recall_masters_text_field_0',
			__( 'Recall Results Page:', 'recall_masters' ),
			array( $this, 'recall_masters_select_field_0_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
		);
		add_settings_field(
			'recall_masters_text_field_1',
			__( 'API Token:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_1_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
		);
		add_settings_field(
			'recall_masters_text_field_2',
			__( 'Form Header:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_2_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
		);
		add_settings_field(
			'recall_masters_text_field_3',
			__( 'Form Description:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_3_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
		);
		add_settings_field(
			'recall_masters_text_field_4',
			__( 'Active Recall Note:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_4_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
		);
		add_settings_field(
			'recall_masters_text_field_5',
			__( 'Inactive Recall Note:', 'recall_masters' ),
			array( $this, 'recall_masters_text_field_5_render' ),
			'pluginPage',
			'recall_masters_pluginPage_section'
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
	 * @method recall_masters_text_field_1_render
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
	 * @method recall_masters_text_field_1_render
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
	 * @method recall_masters_text_field_1_render
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
	 * @method recall_masters_text_field_1_render
	 */
	function recall_masters_text_field_5_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_5]' value='<?php esc_html_e( $options['recall_masters_text_field_5'], 'recall_masters' ); ?>' placeholder='There are no current recalls for your vehicle.'>
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
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
		</form>
		<?php
	}

	/**
	 * Render form shortcode.
	 *
	 * @method recall_form_shortcode
	 * @return [type] [description]
	 */
	function recall_form_shortcode() {
		ob_start();
		$options = get_option( 'recall_masters_settings' );
		$permalink = get_permalink( $options['recall_masters_select_field_0'] );
		?>
		<h3><?php esc_html_e( $options['recall_masters_text_field_2'], 'recall_masters' ); ?></h3>
		<p><?php esc_html_e( $options['recall_masters_text_field_3'], 'recall_masters' ); ?></p>
		<form id="recall-form" class="form-inline" action="<?php esc_html_e( $permalink, 'recall_masters' ); ?>" method="post">
			<div class="form-group">
				<label class="control-label" for="vin"><?php esc_html_e( 'Vehicle Identification Number', 'recall_masters' ); ?> (VIN):</label>
				<input type="text" class="form-control" id="vin" name="vin" maxlength="17">
				<input type="hidden" name="action" value="recall_form">
			</div>
			<button type="submit" class="btn btn-primary vin-button"><?php esc_html_e( 'Check Recall Status', 'recall_masters' ); ?></button>
		</form>
		<div class="clearfix"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render results shortcode.
	 *
	 * @method recall_results_shortcode
	 * @return [type] [description]
	 */
	function recall_results_shortcode() {
		ob_start();

		$vin = wp_unslash( $_POST['vin'] );
		$url = 'https://app.recallmasters.com/api/v1/lookup/' . $vin . '';
		$options = get_option( 'recall_masters_settings' );

		$headers = array(
			'timeout'	 => 5,
			'sslverify' => true,
			'headers'	 => array(
				'Authorization' => 'Token ' . $options['recall_masters_text_field_1'] . '',
			),
		);

		$response = wp_remote_get( $url, $headers );

		if ( is_wp_error( $response ) ) {
			esc_html_e( $response->get_error_message(), 'recall_masters' );
		}

		$recall_data_array = json_decode( wp_remote_retrieve_body( $response ), true );

		?>
		<div class="well">
			<!--<pre><?php //print_r( $recall_data_array ); ?></pre>-->
			<table class="table table-condensed table-hover">
				 <thead>
						<tr>
							 <th><?php esc_html_e( 'Vehicle Identification Number', 'recall_masters' ); ?>(VIN)</th>
							 <th><?php esc_html_e( 'Year', 'recall_masters' ); ?></th>
							 <th><?php esc_html_e( 'Make', 'recall_masters' ); ?></th>
							 <th><?php esc_html_e( 'Model', 'recall_masters' ); ?></th>
						</tr>
				 </thead>
				 <tbody>
						<tr>
							 <td><?php esc_html_e( $recall_data_array['vin'], 'recall_masters' ); ?></td>
							 <td><?php esc_html_e( $recall_data_array['model_year'], 'recall_masters' ); ?></td>
							 <td><?php esc_html_e( $recall_data_array['make'], 'recall_masters' );?></td>
							 <td><?php esc_html_e( $recall_data_array['model_name'], 'recall_masters' );?></td>
						</tr>
				 </tbody>
			</table>
			<div class="clearfix"></div>
			<?php
			if ( $recall_data_array['recall_count'] >= 1 ) { ?>
				<div class="alert alert-danger" role="alert"><?php esc_html_e( $options['recall_masters_text_field_4'], 'recall_masters' ); ?></div>
				<?php
				foreach ( $recall_data_array['recalls'] as $recall ) {
						?>
						<div class="panel panel-default">
							<div class="panel-heading"><h3><?php esc_html_e( 'Recall', 'recall_masters' ); ?> - <?php esc_html_e( $recall['name'] , 'recall_masters' ); ?></h3></div>
								<div class="panel-body">
									<h3><?php esc_html_e( 'Description', 'recall_masters' ); ?></h3><?php esc_html_e( $recall['description'], 'recall_masters' ); ?>
									<hr />
									<h4><?php esc_html_e( 'Remedy', 'recall_masters' ); ?></h4>
									<p><?php esc_html_e( $recall['remedy'], 'recall_masters' ); ?></p>
									<h4><?php esc_html_e( 'Repair Information', 'recall_masters' ); ?></h4>
									<p><?php esc_html_e( 'Parts Availability', 'recall_masters' ); ?>: <?php esc_html_e( $recall['parts_available'], 'recall_masters' ); ?></p>
									<p><?php esc_html_e( 'Repair Difficulty (1-5)', 'recall_masters' ); ?>: <?php esc_html_e( $recall['labor_difficulty'], 'recall_masters' ); ?></p>
									<p><?php esc_html_e( 'Estimated Repair Time', 'recall_masters' ); ?>: <?php esc_html_e( $recall['labor_max'], 'recall_masters' ); ?> <?php esc_html_e( 'hour(s)', 'recall_masters' ); ?></p>
								</div>
								<div class="panel-footer"><span class="label label-primary"><?php esc_html_e( 'OEM Code', 'recall_masters' ); ?>: <?php esc_html_e( $recall['oem_id'], 'recall_masters' ); ?></span> / <span class="label label-primary"><?php esc_html_e( 'NHTSA Code', 'recall_masters' ); ?>: <?php esc_html_e( $recall['nhtsa_id'], 'recall_masters' ); ?></span></div>
						</div>
						<?php
				}
			?>
			<?php } else { ?>
				<div class="alert alert-success" role="alert"><?php esc_html_e( $options['recall_masters_text_field_5'], 'recall_masters' ); ?></div>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}
} // End Class

// Instantiate the Recall Masters Class.
Recall_Masters::get_instance();
