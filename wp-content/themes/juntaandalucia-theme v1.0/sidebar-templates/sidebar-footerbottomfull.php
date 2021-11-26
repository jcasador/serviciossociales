<?php
/**
 * Sidebar setup for footer full
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

?>

<?php if ( is_active_sidebar( 'footerbottomfull' ) ) : ?>

	<!-- ******************* The Footer Full-width Widget Area ******************* -->
	<footer id="footerbottom">
		<div class="wrapper" id="wrapper-footer-bottom-full">

			<div class="<?php echo esc_attr( $container ); ?>" id="footer-bottom-full-content" tabindex="-1">

				<div class="row">

					<?php dynamic_sidebar( 'footerbottomfull' ); ?>

				</div>

			</div>

		</div><!-- #wrapper-footer-full -->
</footer>
	<?php
endif;
