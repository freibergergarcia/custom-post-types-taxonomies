<?php

declare(strict_types=1);

namespace Custom_PTT\Tests\Unit\Unit\Admin;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Admin\Admin_Menu;
use WP_Mock;
use Mockery;

/**
 * Class Admin_Menu_Test
 *
 * @package Custom_PTT\Tests\Unit
 * @since 0.1.0-alpha
 */
class Admin_Menu_Test extends TestCase {

	/**
	 * Set up the test environment.
	 *
	 * @since 0.1.0-alpha
	 */
	protected function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();

		// Mock the admin_url function
		WP_Mock::userFunction(
			'admin_url',
			array(
				'return' => 'http://example.com/wp-admin/',
			)
		);

		// Mock the Taxonomy_List_Table class and its methods
		$mock = Mockery::mock( 'overload:Custom_PTT\Admin\Taxonomy_List_Table' );
		$mock->shouldReceive( 'prepare_items' )->once();
		$mock->shouldReceive( 'display' )->once();
	}

	/**
	 * Tear down the test environment.
	 *
	 * @since 0.1.0-alpha
	 */
	protected function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}
}
