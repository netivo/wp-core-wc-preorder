<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Product;
use PHPUnit\Framework\MockObject\MockObject;

class ProductTest extends TestCase {

	protected function tearDown(): void {
		unset( $GLOBALS['post_meta_return'] );
		unset( $GLOBALS['options_return'] );
	}

	public function test_nt_preorder_title_without_preorder() {
		$product = new Product();

		$GLOBALS['post_meta_return'] = 'no';

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( $title, $result );
	}

	public function test_nt_preorder_title_with_preorder_before() {
		$product = new Product();

		$GLOBALS['post_meta_return'] = 'yes';
		$GLOBALS['options_return']   = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'before'
		];

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( '[PREORDER] Sample Product', $result );
	}

	public function test_nt_preorder_title_with_preorder_after() {
		$product = new Product();

		$GLOBALS['post_meta_return'] = 'yes';
		$GLOBALS['options_return']   = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'after'
		];

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( 'Sample Product [PREORDER]', $result );
	}
}
