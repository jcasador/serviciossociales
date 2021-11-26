<?php


// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'understrap_change_logo_class' );

if ( ! function_exists( 'understrap_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return string
	 */
	function understrap_change_logo_class( $html ) {
		$logoimg = get_theme_mod( 'custom_logo' );
		$logo = wp_get_attachment_image_src( $logoimg , 'full' );

		$logo = get_theme_mod( 'custom_logo' );
		$image = wp_get_attachment_image_src( $logo , 'full' );
		$logo = "https://www.juntadeandalucia.es/themes/custom/terra/logo-junta.png";
		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link logo-organismo logo-domain" style="background-image:url('.$logo.' );"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}
