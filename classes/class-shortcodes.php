<?php
/**
 * The Recall Masters Shortcodes Class
 *
 * @package Recall Masters
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recall Masters Shortcodes.
 */
class Recall_Masters_Shortcodes {

	/**
	 * Class Constructor.
	 *
	 * @method __construct
	 */
	public function __construct() {
		// Add shortcodes.
		add_shortcode( 'recall_form',    array( $this, 'recall_form_shortcode' ) );
		add_shortcode( 'recall_results', array( $this, 'recall_results_shortcode' ) );
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
		$options = get_option( 'recall_masters_settings' );
		$url = 'https://app.recallmasters.com/api/v1/lookup/' . $vin . '/?format=json&user=' . $options['recall_masters_text_field_6'] . '';

		$headers = array(
			'timeout'   => 5,
			'sslverify' => true,
			'headers'   => array(
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
								<div class="panel-footer">
									<span class="label label-primary"><?php esc_html_e( 'OEM Code', 'recall_masters' ); ?>: <?php esc_html_e( $recall['oem_id'], 'recall_masters' ); ?></span> / <span class="label label-primary"><?php esc_html_e( 'NHTSA Code', 'recall_masters' ); ?>: <?php esc_html_e( $recall['nhtsa_id'], 'recall_masters' ); ?></span>
								</div>
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
