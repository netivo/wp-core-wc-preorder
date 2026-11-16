<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Product;

class PreorderDateTest extends TestCase {

	protected function tearDown(): void {
		unset( $GLOBALS['post_meta_return'] );
		unset( $GLOBALS['options_return'] );
		unset( $GLOBALS['post_meta_map'] );
	}

	public function test_nt_preorder_title_with_future_date() {
		$product = new Product();

		$GLOBALS['post_meta_map']  = [
			'_nt_preorder'      => 'yes',
			'_nt_preorder_date' => '2026-12-31' // Future date relative to 2026-06-11
		];
		$GLOBALS['options_return'] = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'before'
		];

		$title  = 'Future Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( '[PREORDER] Future Product', $result );
	}

	public function test_nt_preorder_title_with_past_date() {
		$product = new Product();

		$GLOBALS['post_meta_map']  = [
			'_nt_preorder'      => 'yes',
			'_nt_preorder_date' => '2026-01-01' // Past date relative to 2026-06-11
		];
		$GLOBALS['options_return'] = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'before'
		];

		$title  = 'Past Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( 'Past Product', $result );
	}

	public function test_nt_preorder_title_without_date() {
		$product = new Product();

		$GLOBALS['post_meta_map']  = [
			'_nt_preorder'      => 'yes',
			'_nt_preorder_date' => ''
		];
		$GLOBALS['options_return'] = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'before'
		];

		$title  = 'No Date Product';
		$result = $product->nt_preorder_title( $title, 123 );

		// If no date is provided, it should still show preorder title (default behavior)
		$this->assertEquals( '[PREORDER] No Date Product', $result );
	}
}
