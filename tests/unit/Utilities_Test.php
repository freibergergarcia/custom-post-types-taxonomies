<?php
/**
 * Class Utilities_Test
 *
 * @package Custom_PTT\Tests
 *
 * @since 0.1.0-alpha
 */

namespace Custom_PTT\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Custom_PTT\Utilities;

class Utilities_Test extends TestCase {

	use Utilities;

	/**
	 * Test format_snake_case_to_title_case method.
	 *
	 * @dataProvider data_provider_for_test_format_snake_case_to_title_case
	 *
	 * @param string $input    The snake_case string to be formatted.
	 * @param string $expected The expected Title Case string.
	 *
	 * @since 0.1.0-alpha
	 */
	public function test_format_snake_case_to_title_case( string $input, string $expected ) {
		$this->assertSame( $expected, $this->format_snake_case_to_title_case( $input ) );
	}

	/**
	 * Data provider for test_format_snake_case_to_title_case.
	 *
	 * @return array
	 *
	 * @since 0.1.0-alpha
	 */
	public function data_provider_for_test_format_snake_case_to_title_case(): array {
		return array(
			array( 'snake_case', 'Snake Case' ),
			array( 'another_snake_case', 'Another Snake Case' ),
			array( 'yet_another_snake_case', 'Yet Another Snake Case' ),
		);
	}
}
