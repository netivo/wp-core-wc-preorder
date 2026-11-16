<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Admin;

use Netivo\Module\WooCommerce\ProductPreorder\Product as PreorderProduct;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

/**
 * Makes products with the preorder checkbox checked but a release date that has
 * already passed visible in the admin. The shop silently stops showing them as
 * preorders, which otherwise looks like a bug to the shop owner.
 */
class ExpiredPreorders {

	public const QUERY_VAR = 'nt_preorder_expired';

	public function __construct() {
		add_action( 'admin_notices', [ $this, 'render_notices' ] );
		add_action( 'pre_get_posts', [ $this, 'filter_product_list' ] );
	}

	/**
	 * Meta query matching products with an expired preorder.
	 *
	 * @return array
	 */
	public static function get_meta_query(): array {
		return [
			'relation' => 'AND',
			[
				'key'   => '_nt_preorder',
				'value' => 'yes',
			],
			[
				'key'     => '_nt_preorder_date',
				'value'   => PreorderProduct::get_today(),
				'compare' => '<=',
				'type'    => 'DATE',
			],
		];
	}

	public function render_notices(): void {
		if ( ! function_exists( 'get_current_screen' ) || ! current_user_can( 'edit_products' ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( empty( $screen ) || 'product' !== $screen->post_type ) {
			return;
		}

		if ( 'product' === $screen->base ) {
			$this->render_single_product_notice();
		} elseif ( 'edit' === $screen->base ) {
			$this->render_product_list_notice();
		}
	}

	protected function render_single_product_notice(): void {
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( empty( $post_id ) || ! PreorderProduct::is_preorder_expired( $post_id ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html(
				sprintf(
				/* translators: %s: release date */
					__( 'Przedsprzedaż jest zaznaczona, ale data premiery (%s) już minęła, więc sklep pokazuje ten produkt jako zwykły. Zmień datę premiery albo odznacz przedsprzedaż.', 'netivo' ),
					get_post_meta( $post_id, '_nt_preorder_date', true )
				)
			)
		);
	}

	protected function render_product_list_notice(): void {
		if ( ! empty( $_GET[ self::QUERY_VAR ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$query = new WP_Query( [
			'post_type'              => 'product',
			'post_status'            => 'any',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'meta_query'             => self::get_meta_query(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		] );

		if ( empty( $query->found_posts ) ) {
			return;
		}

		$url = add_query_arg( [ 'post_type' => 'product', self::QUERY_VAR => 1 ], admin_url( 'edit.php' ) );

		printf(
			'<div class="notice notice-warning"><p>%s <a href="%s">%s</a></p></div>',
			esc_html(
				sprintf(
				/* translators: %d: number of products */
					_n(
						'%d produkt ma zaznaczoną przedsprzedaż, ale jego data premiery już minęła – sklep nie pokazuje go jako przedsprzedaż.',
						'%d produktów ma zaznaczoną przedsprzedaż, ale ich data premiery już minęła – sklep nie pokazuje ich jako przedsprzedaż.',
						(int) $query->found_posts,
						'netivo'
					),
					(int) $query->found_posts
				)
			),
			esc_url( $url ),
			esc_html__( 'Pokaż produkty', 'netivo' )
		);
	}

	/**
	 * Limits the admin product list to expired preorders when requested from the notice.
	 *
	 * @param WP_Query $query The query.
	 *
	 * @return void
	 */
	public function filter_product_list( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() || 'product' !== $query->get( 'post_type' ) ) {
			return;
		}

		if ( empty( $_GET[ self::QUERY_VAR ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$meta_query = $query->get( 'meta_query' );
		$meta_query = is_array( $meta_query ) ? $meta_query : [];

		$meta_query[] = self::get_meta_query();

		$query->set( 'meta_query', $meta_query );
	}
}
