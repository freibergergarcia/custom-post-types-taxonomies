<?php

declare( strict_types=1 );

namespace Custom_PTT\Tests\Unit;

use Custom_PTT\Plugin;
use DI\Container;
use WP_UnitTestCase;

class Plugin_Test extends WP_UnitTestCase {

	private Plugin $plugin;
	private Container $container;

	public function setUp(): void {
		parent::setUp();

		$this->container = $this->createMock( Container::class );
		$this->plugin    = new Plugin( $this->container );
	}

	public function tearDown(): void {
		parent::tearDown();
	}
}
