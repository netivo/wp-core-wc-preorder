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

if ( ! function_exists( 'is_post_type_archive' ) ) {
	function is_post_type_archive( $post_type = '' ) {
		return $GLOBALS['is_post_type_archive_return'] ?? false;
	}
}

if ( ! function_exists( 'get_post_meta' ) ) {
	function get_post_meta( $post_id, $key = '', $single = false ) {
		if ( isset( $GLOBALS['post_meta_map'][ $key ] ) ) {
			return $GLOBALS['post_meta_map'][ $key ];
		}

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

if ( ! class_exists( 'WP_Query' ) ) {
	class WP_Query {
		private array $query_vars;
		private bool $main_query;

		public function __construct( array $query_vars = [], bool $main_query = true ) {
			$this->query_vars = $query_vars;
			$this->main_query = $main_query;
		}

		public function is_main_query(): bool {
			return $this->main_query;
		}

		public function get( string $key ) {
			return $this->query_vars[ $key ] ?? null;
		}
	}
}

require_once __DIR__ . '/../vendor/autoload.php';
