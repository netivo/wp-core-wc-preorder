<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

/**
 * Class Product
 *
 * Handles the addition of a custom "preorder" option to WooCommerce products
 * and processes saving the associated meta data.
 */
class Product {

	public function __construct() {
		add_filter( 'product_type_options', [ $this, 'add_preorder_option' ], 10, 1 );

		add_action( 'woocommerce_admin_process_product_object', [ $this, 'save_product' ], 10, 1 );
	}

	/**
	 * @param $options array Array of options.
	 *
	 * @return array Modified array of options with the preorder option included.
	 */
	public function add_preorder_option( array $options ): array {
		$options['nt_preorder'] = [
			'id'          => '_nt_preorder',
//			'wrapper_class' => 'show_if_simple show_if_variable show_if_package',
			'label'       => __( 'Przedsprzedaż', 'netivo' ),
			'description' => __( 'Dodaje do tytułu informację o preorderze oraz w opisie datę premiery.', 'netivo' ),
			'default'     => 'no',
		];

		return $options;
	}

	/**
	 * Updates the product's preorder meta data based on the presence of a specific POST parameter.
	 *
	 * @param \WC_Product $product The WooCommerce product being updated.
	 *
	 * @return void
	 */
	public function save_product( \WC_Product $product ): void {
		$product->update_meta_data( '_nt_preorder', ( isset( $_POST['_nt_preorder'] ) ) ? 'yes' : 'no' );
	}
}