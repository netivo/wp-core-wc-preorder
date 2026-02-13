<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

class Importer {

	public function __construct() {
		add_filter( 'woocommerce_csv_product_import_mapping_options', [ $this, 'add_column_to_importer' ] );
		add_filter( 'woocommerce_csv_product_import_mapping_default_columns', [
			$this,
			'add_column_to_mapping_screen'
		] );
		add_filter( 'woocommerce_product_import_pre_insert_product_object', [ $this, 'process_import' ] );

		add_filter( 'woocommerce_product_export_column_names', [ $this, 'add_export_column' ] );
		add_filter( 'woocommerce_product_export_product_default_columns', [ $this, 'add_export_column' ] );
		add_filter( 'woocommerce_product_export_product_column_custom_column', [ $this, 'add_export_data' ] );
	}

	protected function add_column_to_importer( array $options ): array {
		$options['nt_preorder'] = 'Przedsprzedaż';

		return $options;
	}

	protected function add_column_to_mapping_screen( array $columns ): array {
		$columns['nt_preorder'] = 'Przedsprzedaż';

		return $columns;
	}

	protected function process_import( $product, $data ): array {
		if ( ! empty( $data['nt_preorder'] ) ) {
			$preorder    = $data['nt_preorder'];
			$true_values = [ 'yes', '1', 'true', 'tak' ];

			if ( in_array( strtolower( $preorder ), $true_values ) ) {
				$preorder = 'yes';
			} else {
				$preorder = 'no';
			}

			$product->update_meta_data( '_nt_preorder', $preorder );
		}

		return $product;
	}

	protected function add_export_column( array $columns ): array {
		$columns['nt_preorder'] = 'Przedsprzedaż';

		return $columns;
	}

	protected function add_export_data( $value, $product ): string {
		return $product->get_meta( '_nt_preorder', true, 'edit' );
	}
}