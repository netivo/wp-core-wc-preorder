<?php

namespace Netivo\Module\WooCommerce\ProductPreorder;

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

	public function __construct() {
		add_filter( 'the_title', [ $this, 'nt_preorder_title' ], 10, 2 );
		add_filter( 'woocommerce_cart_item_name', [ $this, 'nt_preorder_title' ], 10, 2 );
		add_filter( 'woocommerce_order_item_get_name', [ $this, 'nt_preorder_title' ], 10, 2 );
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
		if ( get_post_meta( $post_id, '_nt_preorder', true ) == 'yes' ) {
			$text     = get_option( 'nt_preorder_text', '[PREORDER]' );
			$position = get_option( 'nt_preorder_position', 'before' );

			if ( $position == 'before' ) {
				$title = $text . ' ' . $title;
			} else if ( $position == 'after' ) {
				$title = $title . ' ' . $text;
			}
		}

		return $title;
	}
}