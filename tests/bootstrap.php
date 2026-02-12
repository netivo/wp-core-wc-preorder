<?php

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() );
}

/**
 * Mocking WordPress and WooCommerce functions
 */
if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		return true;
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'is_admin' ) ) {
	function is_admin() {
		return false;
	}
}

if ( ! function_exists( 'get_post_meta' ) ) {
	function get_post_meta( $post_id, $key = '', $single = false ) {
		return $GLOBALS['post_meta_return'] ?? '';
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		return $GLOBALS['options_return'][ $option ] ?? $default;
	}
}

if ( ! class_exists( 'WC_Product' ) ) {
	class WC_Product {
		public function update_meta_data( $key, $value ) {
		}
	}
}

require_once __DIR__ . '/../vendor/autoload.php';
