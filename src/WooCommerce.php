<?php

namespace Netivo\Module\WooCommerce\ProductPreorder;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	exit;
}

class WooCommerce {

	public function __construct() {
		add_filter( 'the_title', [ $this, 'nt_preorder_title' ], 10, 2 );
	}

	public function nt_preorder_title( $title, $post_id ) {
		if( get_post_meta( $post_id, '_nt_preorder', true ) == 'yes' ) {
			$title = $title . ' ' . __( '[PRZEDSPRZEDAŻ]', 'netivo' );
		}

		return $title;
	}
}