<?php
/**
 * This file is used for system enquiry.
 *
 * @package Wp_System_Log
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wpdb;
$database = $wpdb->get_results( 'SHOW TABLE STATUS' );
?>

<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding the tables running on your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-database-log-tbl">
		<table class="form-table">
			<tbody>
				<?php foreach ( $database as $mytable ) { ?>
					<tr>
						<th> <?php echo esc_html( $mytable->Name ); ?></th>
							<td><?php echo esc_html( 'Version ' . $mytable->Version . ' + ' . 'Engine ' . $mytable->Engine ); ?></td>
						</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
