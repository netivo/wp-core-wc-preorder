<?php

namespace Netivo\Module\WooCommerce\ProductPreorder;

use Netivo\Module\WooCommerce\ProductPreorder\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

/**
 * Represents the primary module functionality, including initialization
 * and access to the module directory path.
 */
class Module {

	/**
	 * Retrieves the absolute path to the module directory.
	 *
	 * @return false|string|null Returns the absolute path to the module directory if it exists,
	 *                           null if the file does not exist, or false on failure.
	 */
	public static function get_module_path(): false|string|null {
		$file = realpath( __DIR__ . '/../' );
		if ( file_exists( $file ) ) {
			return $file;
		}

		return null;
	}

	protected static ?self $instance = null;

	/**
	 * Retrieves the singleton instance of the class.
	 *
	 * @return self Returns the single instance of the class.
	 */
	public static function get_instance(): self {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		new Product();
		if ( is_admin() ) {
			new Panel();
		}
	}
}