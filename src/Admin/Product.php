<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

class Product {

	public function __construct() {
		add_filter( 'product_type_options', [ $this, 'add_preorder_option' ], 10, 1 );

		add_action( 'woocommerce_admin_process_product_object', [ $this, 'save_product' ], 10, 1 );
	}

	public function add_preorder_option( $options ) {
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
	 * @param $product \WC_Product
	 *
	 * @return void
	 */
	public function save_product( \WC_Product $product ): void {
		$product->update_meta_data( '_nt_preorder', ( isset( $_POST['_nt_preorder'] ) ) ? 'yes' : 'no' );
	}
}