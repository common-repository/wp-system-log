<?php
/**
 * This file is used for system enquiry.
 *
 * @package Wp_System_Log
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$post_types = get_post_types( array( 'public' => true ), 'objects' );
?>

<div class="wpsl-server-log">
	<p class="description"><?php esc_attr_e( 'This tab will let you all the details regarding the post type count running on your site.', 'wp-system-log' ); ?></p>
	<div class="wpsl-post-log-tbl">
		<table class="form-table">
			<tbody>
				<?php foreach ( $post_types as $post_type ) { ?>
					<tr>
						<th> <?php echo esc_html( $post_type->name ); ?></th>
						<?php
						$count_post = wp_count_posts( $post_type->name );
						$count      = 0;
						foreach ( $count_post as $postfield ) {
							$count += (int) $postfield;
						}
						?>
						<td><?php echo esc_html( $count ); ?></td>
						</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
