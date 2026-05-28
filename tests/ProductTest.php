<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Product;
use PHPUnit\Framework\MockObject\MockObject;

class ProductTest extends TestCase {

	protected function tearDown(): void {
		unset( $GLOBALS['post_meta_return'] );
		unset( $GLOBALS['options_return'] );
		unset( $GLOBALS['is_post_type_archive_return'] );
		unset( $GLOBALS['wpdb'] );
	}

	public function test_nt_preorder_title_without_preorder() {
		$product = new Product();

		$GLOBALS['post_meta_return'] = 'no';

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( $title, $result );
	}

	public function test_nt_preorder_title_with_preorder_before() {
		$GLOBALS['post_meta_return'] = 'yes';
		$GLOBALS['options_return']   = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'before'
		];

		$product = new Product();

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( '[PREORDER] Sample Product', $result );
	}

	public function test_nt_preorder_title_with_preorder_after() {
		$GLOBALS['post_meta_return'] = 'yes';
		$GLOBALS['options_return']   = [
			'nt_preorder_text'     => '[PREORDER]',
			'nt_preorder_position' => 'after'
		];

		$product = new Product();

		$title  = 'Sample Product';
		$result = $product->nt_preorder_title( $title, 123 );

		$this->assertEquals( 'Sample Product [PREORDER]', $result );
	}

	public function test_filter_preorder_posts_search_clears_matching_search_clause() {
		$GLOBALS['is_post_type_archive_return'] = true;

		$product = new Product();
		$query   = new \WP_Query( [ 's' => 'preorder' ] );

		$this->assertSame( '', $product->filter_preorder_posts_search( " AND ((wp_posts.post_title LIKE '%preorder%'))", $query ) );
	}

	public function test_modify_preorder_posts_clauses_adds_preorder_only_filter() {
		$GLOBALS['is_post_type_archive_return'] = true;
		$GLOBALS['wpdb']                        = new class() {
			public string $postmeta = 'wp_postmeta';
			public string $posts = 'wp_posts';
		};

		$product = new Product();
		$query   = new \WP_Query( [ 's' => 'preorder' ] );
		$clauses = [
			'join'  => '',
			'where' => " AND wp_posts.post_type = 'product' ",
		];

		$result = $product->modify_preorder_posts_clauses( $clauses, $query );

		$this->assertStringContainsString( 'INNER JOIN wp_postmeta AS nt_preorder_pm', $result['join'] );
		$this->assertStringContainsString( "AND nt_preorder_pm.meta_value = 'yes'", $result['where'] );
		$this->assertStringNotContainsString( "OR nt_preorder_pm.meta_value = 'yes'", $result['where'] );
	}
}
