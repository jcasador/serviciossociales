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

<?php if ( is_active_sidebar( 'headertop' ) ) : ?>

	<!-- ******************* The Footer Full-width Widget Area ******************* -->

		<div class="" id="wrapper-header-top">

			<div class="<?php echo esc_attr( $container ); ?>" id="header-top-content" tabindex="-1">

				<div class="row">

					<?php dynamic_sidebar( 'headertop' ); ?>

				</div>

			</div>

		</div><!-- #wrapper-footer-full -->
	
	<?php
endif;
