<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test\Admin;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Admin\Product;
use WC_Product;

class ProductTest extends TestCase {

	public function test_add_preorder_option() {
		$admin_product = new Product();
		$options       = [
			'virtual' => [ 'label' => 'Virtual' ]
		];

		$result = $admin_product->add_preorder_option( $options );

		$this->assertArrayHasKey( 'nt_preorder', $result );
		$this->assertEquals( '_nt_preorder', $result['nt_preorder']['id'] );
		$this->assertEquals( 'Przedsprzedaż', $result['nt_preorder']['label'] );
	}

	public function test_save_product() {
		$admin_product = new Product();

		/** @var WC_Product|\PHPUnit\Framework\MockObject\MockObject $product */
		$product = $this->createMock( WC_Product::class );

		$_POST['_nt_preorder'] = 'yes';

		$product->expects( $this->once() )
		        ->method( 'update_meta_data' )
		        ->with( '_nt_preorder', 'yes' );

		$admin_product->save_product( $product );

		unset( $_POST['_nt_preorder'] );
	}

	public function test_save_product_no_preorder() {
		$admin_product = new Product();

		/** @var WC_Product|\PHPUnit\Framework\MockObject\MockObject $product */
		$product = $this->createMock( WC_Product::class );

		if ( isset( $_POST['_nt_preorder'] ) ) {
			unset( $_POST['_nt_preorder'] );
		}

		$product->expects( $this->once() )
		        ->method( 'update_meta_data' )
		        ->with( '_nt_preorder', 'no' );

		$admin_product->save_product( $product );
	}
}
