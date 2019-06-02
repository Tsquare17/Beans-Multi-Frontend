<?php
/**
 * Tests for beans_render_function_array()
 *
 * @package Beans\Framework\Tests\Unit\API\Utilities
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Unit\API\Utilities;

use Beans\Framework\Tests\Unit\Test_Case;

/**
 * Class Tests_BeansRenderFunctionArray
 *
 * @package Beans\Framework\Tests\API\Utilities
 * @group   api
 * @group   api-utilities
 */
class Tests_BeansRenderFunctionArray extends Test_Case {

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp() {
		parent::setUp();

		require_once BEANS_TESTS_LIB_DIR . 'api/utilities/functions.php';
	}

	/**
	 * Test beans_render_function_array() should bail out when receiving a non-callable.
	 */
	public function test_should_bail_when_noncallable() {
		$this->assertEmpty( beans_render_function_array( 'this-callback-does-not-exist' ) );
	}

	/**
	 * Test beans_render_function_array() should work when there are no arguments.
	 */
	public function test_should_work_when_no_arguments() {
		$this->assertEquals(
			'You called me!',
			beans_render_function_array(
				function () {
					echo 'You called me!';
				}
			)
		);
	}

	/**
	 * Test beans_render_function_array() should work with arguments.
	 */
	public function test_should_work_with_arguments() {

		$callback = function ( $foo, $bar, $baz ) {
			echo "{$foo} {$bar} {$baz}"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Not testing escaping functionality.
		};
		$this->assertSame(
			'foo bar baz',
			beans_render_function_array( $callback, [ 'foo', 'bar', 'baz' ] )
		);

		$callback = function ( $array, $baz ) {
			$this->assertCount( 2, $array );
			echo join( ' ', $array ) . ' ' . $baz; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Not testing escaping functionality.
		};
		$this->assertSame(
			'foo bar baz',
			beans_render_function_array( $callback, [ [ 'foo', 'bar' ], 'baz' ] )
		);

		$callback = function ( $array1, $array2 ) {
			$this->assertCount( 2, $array1 );
			$this->assertArrayHasKey( 'bar', $array1 );
			$this->assertCount( 1, $array2 );
			$this->assertArrayHasKey( 'baz', $array2 );
			echo $array1['foo']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Not testing escaping functionality.
		};
		$this->assertSame(
			'oof',
			beans_render_function_array(
				$callback,
				[
					[
						'foo' => 'oof',
						'bar' => 'rab',
					],
					[ 'baz' => 'zab' ],
				]
			)
		);
	}
}
