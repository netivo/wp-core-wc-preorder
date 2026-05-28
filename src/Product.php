<?php

namespace Netivo\Module\WooCommerce\ProductPreorder;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

/**
 * Class Product
 *
 * This class handles modifications to the post title for products marked as "preorder".
 */
class Product {

	private string $text;
	private string $position;

	/**
	 * Product constructor.
	 *
	 * Initializes filters and actions.
	 */
	public function __construct() {
		$this->text     = get_option( 'nt_preorder_text', '[PREORDER]' );
		$this->position = get_option( 'nt_preorder_position', 'before' );
		$this->init_filters();
		$this->init_actions();
	}

	/**
	 * Initializes filters related to preorder titles.
	 *
	 * @return void
	 */
	protected function init_filters(): void {
		add_filter( 'the_title', [ $this, 'nt_preorder_title' ], 10, 2 );
		add_filter( 'woocommerce_cart_item_name', [ $this, 'nt_preorder_title' ], 10, 2 );
		add_filter( 'woocommerce_order_item_get_name', [ $this, 'nt_preorder_title' ], 10, 2 );
		add_filter( 'posts_clauses', [ $this, 'modify_preorder_posts_clauses' ], 10, 2 );
	}

	/**
	 * Initializes actions related to preorder queries.
	 *
	 * @return void
	 */
	protected function init_actions(): void {
	}

	/**
	 * Modifies the post title to include a preorder text if the post is marked as available for preorder.
	 *
	 * @param string $title The original title of the post.
	 * @param mixed $post The ID of the post.
	 *
	 * @return string The modified post title including the preorder text, if applicable.
	 */
	public function nt_preorder_title( string $title, mixed $post ): string {
		if ( is_a( $post, 'WC_Order_Item_Product' ) ) {
			$post_id = $post->get_product_id();
		} else if ( is_array( $post ) && isset( $post['product_id'] ) ) {
			$post_id = $post['product_id'];
		} else {
			$post_id = $post;
		}
		if ( get_post_meta( $post_id, '_nt_preorder', true ) === 'yes' ) {

			if ( $this->position === 'before' ) {
				$title = $this->text . ' ' . $title;
			} else if ( $this->position === 'after' ) {
				$title = $title . ' ' . $this->text;
			}
		}

		return $title;
	}

	/**
	 * Modifies the product query clauses to filter by preorder meta if search term matches.
	 *
	 * @param array $clauses
	 * @param WP_Query $query
	 *
	 * @return array
	 */
	public function modify_preorder_posts_clauses( array $clauses, WP_Query $query ): array {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'product' ) ) {
			return $clauses;
		}

		$search_query = $query->get( 's' );

		if ( empty( $search_query ) ) {
			return $clauses;
		}

		$search_terms = $this->get_preorder_search_terms();

		if ( in_array( strtolower( trim( $search_query ) ), array_unique( $search_terms ), true ) ) {
			global $wpdb;

			// Join with postmeta to filter by _nt_preorder
			$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS nt_pm ON ({$wpdb->posts}.ID = nt_pm.post_id AND nt_pm.meta_key = '_nt_preorder') ";

			// Force the where clause to only include preorder products and ignore the standard search 's' for where clause
			// We keep 's' in the query object so breadcrumbs and titles work automatically
			$clauses['where'] = " AND nt_pm.meta_value = 'yes' AND {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		}

		return $clauses;
	}

	/**
	 * Returns an array of search terms that should trigger the preorder filter.
	 *
	 * @return array
	 */
	protected function get_preorder_search_terms(): array {
		return [
			'przedsprzedaz',
			'przedsprzedaż',
			'preorder',
			'pre order',
			strtolower( trim( $this->text ) ),
			strtolower( trim( str_replace( [ '[', ']' ], '', $this->text ) ) ),
		];
	}
}