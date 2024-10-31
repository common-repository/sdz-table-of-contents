<?php
/*
Plugin Name: SDZ - Table of Contents
Plugin URI: https://sergioalarconfelipe.com/wordpress/sdz-table-contain/
Description: Shortcode to insert a contain table in post and pages
Version: 1.1.3
Author: Sergio Alarcón Felipe
Author URI: https://sergioalarconfelipe.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( "Bye bye" );

define( 'SDZTC_PATH', plugin_dir_path( __FILE__ ) );
define( 'SDZTC_NAME', 'SDZ - Table of Contents' );
define( 'SDZTC_VERSION', '1.1.3' );

define( 'SDZTC_CSS_NAME', 'sdztc_table_contain_style' );
define( 'SDZTC_CSS_VERSION', SDZTC_VERSION );

define( 'SDZTC_JS_NAME', 'sdztc_table_contain_js' );
define( 'SDZTC_JS_VERSION', SDZTC_VERSION );

include( SDZTC_PATH . 'includes/functions.php' );

// Logic - Start
function sdztc_shortcode_table_content( $atts = [], $content = null, $tag = '' ) {
	$atts = shortcode_atts( 
		[
			'title' => null,
			'link_color' => null,
			'hide_button' => true,
			'text_show' => 'Show',
			'text_hide' => 'Hide',
		], 
		$atts
	);
	$atts[ 'hide_button' ] = filter_var( $atts[ 'hide_button' ], FILTER_VALIDATE_BOOLEAN );
	
	$html = get_the_content();
	try{
		$htmlModify = sdztc_modifyCode( $html );
		$containTable = sdztc_getContainTable( $htmlModify, $atts );
		
		$data = sdztc_getHeaders( $htmlModify );
		if( !sizeof( $data ) ) {
			return null;
		}

		// send text to the button JS
		/*
		wp_localize_script( SDZTC_JS_NAME, SDZTC_JS_NAME, 
			array( 
				'text_show' => _( $atts[ 'text_show' ] ),
				'text_hide' => _( $atts[ 'text_hide' ] ),
			) 
		);
		*/

		return $containTable;
	}
	catch( Exception $e ) {
		return null;
	}
}

function sdztc_filter_the_content_id_to_headers( $html ) {
	try{
		$html = do_shortcode( $html );
		$html = sdztc_modifyCode( $html );
	}
	catch( Exception $e ) {}
	
	return $html;
}

// Logic - End

// Base - Start
function sdztc_init_plugin() {
	wp_register_style( SDZTC_CSS_NAME, plugins_url( '/css/sdztc_table_contain.css', __FILE__ ), false, SDZTC_CSS_VERSION, 'all' );
	wp_enqueue_script( SDZTC_JS_NAME, plugins_url( '/js/sdztc_table_contain.js', __FILE__ ), false, SDZTC_JS_VERSION, 'all' );
	
	add_shortcode( 'table_content', 'sdztc_shortcode_table_content' );
	add_filter( 'the_content', 'sdztc_filter_the_content_id_to_headers' );
}
add_action( 'init', 'sdztc_init_plugin' );

function sdztc_enqueue_style(){
	wp_enqueue_style( SDZTC_CSS_NAME );
	wp_enqueue_script( SDZTC_JS_NAME );
}
add_action( 'wp_enqueue_scripts', 'sdztc_enqueue_style' );
// Base - End
?>
