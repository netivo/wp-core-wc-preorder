<?php

namespace Netivo\Module\WooCommerce\ProductPreorder\Test;

use PHPUnit\Framework\TestCase;
use Netivo\Module\WooCommerce\ProductPreorder\Module;

class ModuleTest extends TestCase {

	public function test_get_instance_returns_singleton() {
		$instance1 = Module::get_instance();
		$instance2 = Module::get_instance();

		$this->assertInstanceOf( Module::class, $instance1 );
		$this->assertSame( $instance1, $instance2 );
	}

	public function test_get_module_path() {
		$path = Module::get_module_path();
		$this->assertNotEmpty( $path );
		$this->assertDirectoryExists( $path );
		$this->assertStringContainsString( 'wp-core-preorder', $path );
	}
}
