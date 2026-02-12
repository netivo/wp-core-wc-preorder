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
	}

	/**
	 * Modifies the post title to include a preorder text if the post is marked as available for preorder.
	 *
	 * @param string $title The original title of the post.
	 * @param int $post_id The ID of the post.
	 *
	 * @return string The modified post title including the preorder text, if applicable.
	 */
	public function nt_preorder_title( string $title, int $post_id ): string {
		if ( get_post_meta( $post_id, '_nt_preorder', true ) == 'yes' ) {
			$text     = get_option( 'nt_preorder_text' );
			$position = get_option( 'nt_preorder_position' );

			if ( $position == 'before' ) {
				$title = $text . ' ' . $title;
			} else if ( $position == 'after' ) {
				$title = $title . ' ' . $text;
			}
		}

		return $title;
	}
}