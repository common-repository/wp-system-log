<?php
/**
 * This file is used for system enquiry.
 *
 * @package Wp_System_Log
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>

<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding the HTTPS/HTTP running on your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-security-log-tbl">
		<table class="form-table">
			<tbody>
				<tr>
				<th>Secure connection (HTTPS):<th>
					<td>
<?php
if ( is_ssl() ) {
	echo '<span class="wpsl-security">Your store is using HTTPS.</span>';
} else {
	echo '<span class="wpsl-security">Your store is not using HTTPS.</span>
	<a class="wpsl-security-ssl" href="https://docs.woocommerce.com/document/ssl-and-https/" target="_blank">Learn more about HTTPS and SSL Certificates</a>
	';
}
?>
					</td>
					<td><?php if ( is_ssl() ) { ?>
						<span class="dashicons dashicons-yes"></span>
						<?php
						} else {
							?>
							<span class="dashicons dashicons-no"></span></td><?php } ?>
</tr>

			</tbody>
		</table>
	</div>
</div>
