<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

use Netivo\Module\WooCommerce\ProductPreorder\Product as PreorderProduct;

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
		add_action( 'woocommerce_product_options_pricing', [ $this, 'render_preorder_date_input' ], 5 );
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
			'description' => __( 'Dodaje do tytułu informację o preorderze oraz w opisie datę premiery. Od dnia premiery oznaczenie przestaje się wyświetlać, nawet jeśli opcja pozostaje zaznaczona.', 'netivo' ),
			'default'     => 'no',
		];

		return $options;
	}

	public function render_preorder_date_input(): void {
		$date_input_html_pattern = '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])';
		$post_id                 = (int) get_the_ID();
		$preorder_date           = get_post_meta( $post_id, '_nt_preorder_date', true );

		echo '<p class="form-field">
					<label for="_nt_preorder_date">' . esc_html__( 'Data premiery', 'netivo' ) . '</label>
					<input type="text" class="short" name="_nt_preorder_date" id="_nt_preorder_date" value="' . esc_attr( $preorder_date ) . '" placeholder="YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( $date_input_html_pattern ) . '" />
					<span class="description">' . esc_html__( 'Przedsprzedaż jest oznaczana do dnia poprzedzającego datę premiery. Jeśli premiera się przesuwa, zaktualizuj datę.', 'netivo' ) . '</span>
				</p>';

		if ( PreorderProduct::is_preorder_expired( $post_id ) ) {
			echo '<p class="form-field nt-preorder-expired" style="color:#d63638;">'
			     . esc_html__( 'Data premiery minęła – sklep nie oznacza już tego produktu jako przedsprzedaż (brak dopisku w tytule i daty premiery), mimo że opcja „Przedsprzedaż” jest zaznaczona.', 'netivo' )
			     . '</p>';
		}
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

		$preorder_date = isset( $_POST['_nt_preorder_date'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['_nt_preorder_date'] ) ) ) : '';

		if ( '' === $preorder_date ) {
			$product->delete_meta_data( '_nt_preorder_date' );
		} elseif ( PreorderProduct::is_valid_date( $preorder_date ) ) {
			$product->update_meta_data( '_nt_preorder_date', $preorder_date );
		}
		// A malformed date is ignored, so the previously saved date stays untouched.
	}
}
