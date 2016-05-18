<?php
/**
 * Plugin Name:       Recall Masters
 * Plugin URI:        http://digitalcliq.com
 * Description:       A WordPress plugin that checks the RecallMasters API.  Built for DigitalCLIQ.
 * Version:           1.0.0
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

define( 'RECALL_MASTERS_VER', '1.0.0' );

/**
 * The Recall Masters Core Class.
 */
class Recall_Masters {

	/**
	 * [$instance description]
	 * @var [type]
	 */
	private static $instance;

	/**
	 * Singleton
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
		add_action( 'admin_menu',  array( $this, 'recall_masters_add_admin_menu' ) );
		add_action( 'admin_init',  array( $this, 'recall_masters_settings_init' ) );

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
   * @method translations
   * @return [type]       [description]
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
   * @method scripts
   * @return [type]  [description]
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
	}

  /**
   * Load the TinyMCE Plugin
   * @method add_tinymce_form_button
   * @param  [type]             $plugin_array [description]
   */
	function add_tinymce_form_button( $plugin_array ) {
		$plugin_array['recall_masters_form'] = plugins_url( '/assets/js/recall_form.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * Load the TinyMCE Plugin
	 * @method add_tinymce_results_button
	 * @param  [type]             $plugin_array [description]
	 */
	function add_tinymce_results_button( $plugin_array ) {
		$plugin_array['recall_masters_results'] = plugins_url( '/assets/js/recall_results.js', __FILE__ );
		return $plugin_array;
	}

  /**
   * Add the TinyMCE Form Button to the editor.
   * @method register_tinymce_form_button
   * @param  [type]                  $buttons [description]
   * @return [type]                           [description]
   */
	function register_tinymce_form_button( $buttons ) {
		array_push( $buttons, 'recall_form' );
		return $buttons;
	}

	/**
	 * Add the TinyMCE Results Button to the editor.
	 * @method register_tinymce_results_button
	 * @param  [type]                  $buttons [description]
	 * @return [type]                           [description]
	 */
	function register_tinymce_results_button( $buttons ) {
		array_push( $buttons, 'recall_results' );
		return $buttons;
	}

	/**
	 * Add settings menu
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
	 * @method recall_masters_settings_init
	 * @return [type] [description]
	 */
	function recall_masters_settings_init() {
		register_setting( 'pluginPage', 'recall_masters_settings' );
		add_settings_section(
			'recall_masters_pluginPage_section',
			__( 'Recall Results Plugin Settings', 'recall_masters' ),
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
	 * @method recall_masters_text_field_0_render
	 * @return [type] [description]
	 */
	function recall_masters_select_field_0_render() {
		$options = get_option( 'recall_masters_settings' );
		$args = array(
		    'selected'              => $options['recall_masters_select_field_0'],
		    'echo'                  => 1,
		    'name'                  => 'recall_masters_settings[recall_masters_select_field_0]',
		);
		wp_dropdown_pages( $args );
	}

	/**
	 * Add input box to settings page.
	 * @method recall_masters_text_field_1_render
	 * @return [type] [description]
	 */
	function recall_masters_text_field_1_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_1]' value='<?php echo $options['recall_masters_text_field_1']; ?>'>
		<?php
	}


	/**
	 * Add input box to settings page.
	 * @method recall_masters_text_field_1_render
	 * @return [type] [description]
	 */
	function recall_masters_text_field_2_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_2]' value='<?php echo $options['recall_masters_text_field_2']; ?>' placeholder='Checking Your Recall Status Is Easy.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 * @method recall_masters_text_field_1_render
	 * @return [type] [description]
	 */
	function recall_masters_text_field_3_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_3]' value='<?php echo $options['recall_masters_text_field_3']; ?>' placeholder='Enter your VIN to check your recall status.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 * @method recall_masters_text_field_1_render
	 * @return [type] [description]
	 */
	function recall_masters_text_field_4_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_4]' value='<?php echo $options['recall_masters_text_field_4']; ?>' placeholder='There is current recall information available for this vehicle.'>
		<?php
	}

	/**
	 * Add input box to settings page.
	 * @method recall_masters_text_field_1_render
	 * @return [type] [description]
	 */
	function recall_masters_text_field_5_render() {
		$options = get_option( 'recall_masters_settings' );
		?>
		<input type='text' size='75' name='recall_masters_settings[recall_masters_text_field_5]' value='<?php echo $options['recall_masters_text_field_5']; ?>' placeholder='There are no current recalls for your vehicle.'>
		<?php
	}




	/**
	 * [recall_masters_settings_section_callback description]
	 * @method recall_masters_settings_section_callback
	 * @return [type] [description]
	 */
	function recall_masters_settings_section_callback() {
		echo __( 'Here you will find a few options to set for the Recall Masters plugin, such as the results page, API token, and text verbiage.', 'recall_masters' );
	}

	/**
	 * [recall_masters_options_page description]
	 * @method recall_masters_options_page
	 * @return [type] [description]
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
	 * @method recall_form_shortcode
	 * @return [type] [description]
	 */
	function recall_form_shortcode() {
		ob_start();
		$options = get_option( 'recall_masters_settings' );
		$permalink = get_permalink( $options['recall_masters_select_field_0'] );
		?>
		<h3><?php echo $options['recall_masters_text_field_2']; ?></h3>
		<p><?php echo $options['recall_masters_text_field_3']; ?></p>
		<form class="form-inline recall_form" action="<?php echo $permalink; ?>" method="post">
			<div class="form-group">
				<label for="vin"><?php _e( 'Vehicle Identification Number', 'recall_masters' ); ?> (VIN):</label>
				<input type="text" class="form-control" id="vin" name="vin" placeholder="">
				<input type="hidden" name="action" value="recall_form">
			</div>
			<button type="submit" class="btn btn-primary"><?php _e( 'Check Recall Status', 'recall_masters' ); ?></button>
		</form>
		<div class="clearfix"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render results shortcode.
	 * @method recall_results_shortcode
	 * @return [type] [description]
	 */
	function recall_results_shortcode() {
		ob_start();

		$vin = $_POST['vin'];
		$url = 'https://app.recallmasters.com/api/v1/lookup/' . $vin . '';
		$options = get_option( 'recall_masters_settings' );

		$headers = array(
			'timeout'   => 5,
			'sslverify' => true,
			'headers'   => array(
				'Authorization' => 'Token b88fdb6013de1b10701d2502abc24319fa3ef0bf',
			),
		);

		$response = wp_remote_get( $url, $headers );

		if ( is_wp_error( $response ) ) {
			echo $response->get_error_message();
		}

		$recall_data_array = json_decode( wp_remote_retrieve_body( $response ), true );

		?>
		<div class="well">
			<table class="table table-condensed table-hover">
				 <thead>
						<tr>
							 <th><?php _e( 'Vehicle Identification Number', 'recall_masters' ); ?>(VIN)</th>
							 <th><?php _e( 'Year', 'recall_masters' ); ?></th>
							 <th><?php _e( 'Make', 'recall_masters' ); ?></th>
							 <th><?php _e( 'Model', 'recall_masters' ); ?></th>
						</tr>
				 </thead>
				 <tbody>
						<tr>
							 <td><?php echo $recall_data_array['vin']; ?></td>
							 <td><?php echo $recall_data_array['model_year']; ?></td>
							 <td><?php echo $recall_data_array['make'];?></td>
							 <td><?php echo $recall_data_array['model_name'];?></td>
						</tr>
				 </tbody>
			</table>
			<div class="clearfix"></div>
			<?php
			if ( $recall_data_array['recall_count'] >= 1 ) { ?>
				<div class="alert alert-danger" role="alert"><?php echo $options['recall_masters_text_field_4']; ?></div>
				<?php
					foreach ( $recall_data_array['recalls'] as $recall ) {

						switch ( $recall['risk_rank'] ) {
							case '1':
								$label_class = 'label-success';
								break;
							case '2':
								$label_class = 'label-info';
								break;
							case '3':
								$label_class = 'label-primary';
								break;
							case '4':
								$label_class = 'label-warning';
								break;
							case '5':
								$label_class = 'label-danger';
								break;
							default:
								$label_class = 'label-default';
						}
						?>
						<div class="panel panel-default">
							<div class="panel-heading"><h3><?php _e( 'Recall', 'recall_masters' ); ?></h3></div>
								<div class="panel-body">
									<p><?php echo $recall['name']; ?></p>
									<p><span class="label label-default"><?php _e( 'OEM Code', 'recall_masters' ); ?>:</span> <?php echo $recall['oem_id']; ?></p>
									<p><span class="label label-default"><?php _e( 'NHTSA Code', 'recall_masters' ); ?>:</span> <?php echo $recall['nhtsa_id']; ?></p>
									<p><span class="label label-default"><?php _e( 'Description', 'recall_masters' ); ?>:</span> <?php echo $recall['description']; ?></p>
									<p><span class="label <?php echo $label_class; ?>"><?php _e( 'Severity', 'recall_masters' ); ?>:</span> <?php echo $recall['risk_rank']; ?></p>
									<hr />
									<h4>Remedy:</h4>
									<p><?php echo $recall['remedy']; ?></p>
								</div>
						</div>
						<?php
					}
				 ?>
			<?php } else { ?>
				<div class="alert alert-success" role="alert"><?php echo $options['recall_masters_text_field_5']; ?></div>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}

} // End Class

// Instantiate the Recall Masters Class.
Recall_Masters::get_instance();
