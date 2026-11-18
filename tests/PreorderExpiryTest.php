<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test;

use Netivo\Module\WooCommerce\ProductPreorder\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PreorderExpiryTest extends TestCase {

	protected function setUp(): void {
		$GLOBALS['current_time_return'] = '2026-11-16';
	}

	protected function tearDown(): void {
		unset( $GLOBALS['post_meta_map'], $GLOBALS['post_meta_return'], $GLOBALS['current_time_return'] );
	}

	private function meta( string $preorder, string $date ): void {
		$GLOBALS['post_meta_map']    = [ '_nt_preorder' => $preorder, '_nt_preorder_date' => $date ];
		$GLOBALS['post_meta_return'] = '';
	}

	public static function states(): array {
		// preorder, date, expected active, expected expired
		return [
			'checked, future date'      => [ 'yes', '2026-12-30', true, false ],
			'checked, release tomorrow' => [ 'yes', '2026-11-17', true, false ],
			'checked, release today'    => [ 'yes', '2026-11-16', false, true ],
			'checked, past date'        => [ 'yes', '2026-10-31', false, true ],
			'checked, no date'          => [ 'yes', '', true, false ],
			'checked, malformed date'   => [ 'yes', '31.10.2026', true, false ],
			'unchecked, past date'      => [ 'no', '2026-10-31', false, false ],
			'unchecked, future date'    => [ 'no', '2026-12-30', false, false ],
		];
	}

	#[DataProvider( 'states' )]
	public function test_preorder_state( string $preorder, string $date, bool $active, bool $expired ): void {
		$this->meta( $preorder, $date );

		$this->assertSame( $active, Product::is_preorder_active( 123 ) );
		$this->assertSame( $expired, Product::is_preorder_expired( 123 ) );
		$this->assertSame( $active, ( new Product() )->should_show_preorder_title( 123 ) );
	}

	public function test_today_uses_site_timezone(): void {
		$GLOBALS['current_time_return'] = '2026-10-31';
		$this->meta( 'yes', '2026-10-31' );

		$this->assertTrue( Product::is_preorder_expired( 123 ) );
	}

	public function test_render_preorder_date_excludes_date_from_translation(): void {
		$this->meta( 'yes', '2026-12-07' );

		ob_start();
		( new Product() )->render_preorder_date();
		$html = ob_get_clean();

		$this->assertSame(
			'<p class="nt-preorder-date">Data premiery: <time class="notranslate" translate="no" datetime="2026-12-07">2026-12-07</time></p>',
			$html
		);
	}

	public function test_render_preorder_date_hidden_when_release_passed(): void {
		$this->meta( 'yes', '2026-10-31' );

		ob_start();
		( new Product() )->render_preorder_date();

		$this->assertSame( '', ob_get_clean() );
	}

	public function test_is_valid_date(): void {
		$this->assertTrue( Product::is_valid_date( '2026-12-30' ) );
		$this->assertFalse( Product::is_valid_date( '2026-02-30' ) );
		$this->assertFalse( Product::is_valid_date( '2026-1-5' ) );
		$this->assertFalse( Product::is_valid_date( '30.12.2026' ) );
		$this->assertFalse( Product::is_valid_date( '' ) );
	}
}
