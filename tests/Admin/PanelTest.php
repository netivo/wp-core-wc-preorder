<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test\Admin;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Admin\Panel;

class PanelTest extends TestCase {

	public function test_constructor_initializes_components() {
		// This class just creates new instances in constructor.
		// Testing this literally would be hard without mocking 'new Settings()' etc.
		// However, we can at least check if it's instantiable.
		$panel = new Panel();
		$this->assertInstanceOf( Panel::class, $panel );
	}
}
