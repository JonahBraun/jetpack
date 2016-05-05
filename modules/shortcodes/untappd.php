<?php
/**
 * Untappd Shortcodes
 * @author kraftbj
 *
 * [untappd-menu location="123" theme="123"]
 * @since  4.1.0
 * @param location int Location ID for the Untappd venue. Required.
 * @param theme    int Theme ID for the Untappd menu. Required.
 */

class Jetpack_Untappd {

	function __construct() {
		add_action( 'init', array( $this, 'action_init' ) );
	}

	function action_init() {
		add_shortcode( 'untappd-menu', array( $this, 'menu_shortcode' ) );
	}

	/**
	 * [untappd-menu] shortcode.
	 *
	 */
	static function menu_shortcode( $atts, $content = '' ) {
		// Let's bail if we don't have location or theme.
		if ( ! isset( $atts['location'] ) || ! isset( $atts['theme'] ) ) {
			if ( current_user_can( 'edit_posts') ){
				return __( 'No location or theme ID provided in the untappd-menu shortcode.', 'jetpack' );
			}
			return;
		}

		// Let's apply some defaults.
		$atts = shortcode_atts( array(
			'location' => '',
			'theme'    => '',
		), $atts, 'untappd-menu' );

		// We're going to clean the user input.
		$atts = self::santize_atts( $atts );

		static $untappd_menu = 1;

		$html  = '<div id="menus-container-untappd-' . $untappd_menu . '"></div>';
		$html .= '<script type="text/javascript">' . PHP_EOL;
		$html .= '!function(e, t) { console.log("getscript: ", e);' . PHP_EOL;
		$html .= 'var n = document.createElement("script"), o = document.getElementsByTagName("script")[0];' . PHP_EOL;
		$html .= 'n.async = 1, o.parentNode.insertBefore(n, o), n.onload = n.onreadystatechange = function(e, o) {' . PHP_EOL;
		$html .= '(o || !n.readyState || /loaded|complete/.test(n.readyState)) && (n.onload = n.onreadystatechange = null, n = void 0, o || t && t())' . PHP_EOL;
		$html .= '}, n.src = e' . PHP_EOL;
		$html .= '}("https://business.untappd.com/locations/' . $atts['location'] . '/themes/' . $atts['theme'] . '/js", function() {' . PHP_EOL;
		$html .= 'EmbedMenu( "menu-container-untappd-' . $untappd_menu . '" )});' . PHP_EOL;
		$html .= '</script>';

		$untappd_menu++;

		return $html;
	}

	/**
	 * Santize the atts
	 *
	 * @return array
	 */
	static function santize_atts( $atts = null ) {
		if ( ! is_array( $atts ) ){
			return;
		}

		foreach ( $atts as $k => $v ){
			$atts['k'] = intval( $v );
		}

		return $atts;
	}
}

new Jetpack_Untappd();