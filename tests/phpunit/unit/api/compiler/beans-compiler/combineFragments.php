<?php
/**
 * Tests for the combine_fragments() method of _Beans_Compiler.
 *
 * @package Beans\Framework\Tests\Unit\API\Compiler
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Unit\API\Compiler;

use Beans\Framework\Tests\Unit\API\Compiler\Includes\Compiler_Test_Case;
use Brain\Monkey;
use org\bovigo\vfs\vfsStream;

require_once dirname( __DIR__ ) . '/includes/class-compiler-test-case.php';

/**
 * Class Tests_BeansCompiler_CombineFragments
 *
 * @package Beans\Framework\Tests\Unit\API\Compiler
 * @group   api
 * @group   api-compiler
 */
class Tests_BeansCompiler_CombineFragments extends Compiler_Test_Case {

	/**
	 * The CSS content.
	 *
	 * @var string
	 */
	protected $css;

	/**
	 * The Less content.
	 *
	 * @var string
	 */
	protected $less;

	/**
	 * The jQuery content.
	 *
	 * @var string
	 */
	protected $jquery;

	/**
	 * The JavaScript content.
	 *
	 * @var string
	 */
	protected $js;

	/**
	 * Set up the tests.
	 */
	protected function setUp() {
		parent::setUp();

		$fixtures     = $this->mock_filesystem->getChild( 'fixtures' );
		$this->css    = $fixtures->getChild( 'style.css' )->getContent();
		$this->less   = $fixtures->getChild( 'variables.less' )->getContent() . $fixtures->getChild( 'test.less' )->getContent();
		$this->jquery = $fixtures->getChild( 'jquery.test.js' )->getContent();
		$this->js     = $fixtures->getChild( 'my-game-clock.js' )->getContent();
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return an empty string when there are no fragments to combine.
	 */
	public function test_should_return_empty_string_when_no_fragments() {
		$compiler = $this->create_compiler( [] );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( '', $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return an empty string when the fragment does not exist.
	 */
	public function test_should_return_empty_string_when_fragment_does_not_exist() {
		$fragment = vfsStream::url( 'compiled/fixtures/' ) . 'invalid-file.js';
		$compiler = $this->create_compiler(
			[
				'id'           => 'test-script',
				'type'         => 'script',
				'fragments'    => [ $fragment ],
				'dependencies' => [ 'javascript' ],
				'in_footer'    => true,
				'minify_js'    => true,
			]
		);

		// Set up the mocks.
		Monkey\Functions\when( 'beans_url_to_path' )->returnArg();
		Monkey\Functions\when( 'wp_remote_get' )->justReturn();
		Monkey\Functions\when( 'is_wp_error' )->justReturn( true );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( '', $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should compile the LESS fragments and return the compiled CSS.
	 */
	public function test_should_compile_less_and_return_css() {
		$compiler = $this->create_compiler(
			[
				'id'        => 'test',
				'type'      => 'style',
				'format'    => 'less',
				'fragments' => [
					vfsStream::url( 'compiled/fixtures/variables.less' ),
					vfsStream::url( 'compiled/fixtures/test.less' ),
				],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( true );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$expected_css = <<<EOB
body {
  background-color: #fff;
  color: #000;
  font-size: 18px;
}

EOB;
		$this->assertSame( $expected_css, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return minified, compiled CSS from the Less combined fragments.
	 */
	public function test_should_return_minified_compiled_css() {
		$compiler = $this->create_compiler(
			[
				'id'        => 'test',
				'type'      => 'style',
				'format'    => 'less',
				'fragments' => [
					vfsStream::url( 'compiled/fixtures/variables.less' ),
					vfsStream::url( 'compiled/fixtures/test.less' ),
				],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( false );
		$this->mock_filesystem_for_fragments( $compiler );

		$expected_css = <<<EOB
body{background-color:#fff;color:#000;font-size:18px}
EOB;

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $expected_css, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return the original jQuery when site is not in development mode,
	 * but "minify_js" is disabled.
	 */
	public function test_should_return_original_jquery_when_minify_js_disabled() {
		$compiler = $this->create_compiler(
			[
				'id'           => 'test',
				'type'         => 'script',
				'minify_js'    => false,
				'fragments'    => [
					vfsStream::url( 'compiled/fixtures/jquery.test.js' ),
				],
				'dependencies' => [ 'jquery' ],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( false );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->jquery, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return the original jQuery when "minify_js" is enabled,
	 * but the site is in development mode.
	 */
	public function test_should_always_return_original_jquery_when_in_dev_mode() {
		$compiler = $this->create_compiler(
			[
				'id'           => 'test',
				'type'         => 'script',
				'minify_js'    => true,
				'fragments'    => [
					vfsStream::url( 'compiled/fixtures/jquery.test.js' ),
				],
				'dependencies' => [ 'jquery' ],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( true );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->jquery, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return minified jQuery.
	 */
	public function test_should_return_minified_jquery() {
		$compiler = $this->create_compiler(
			[
				'id'           => 'test',
				'type'         => 'script',
				'minify_js'    => true,
				'fragments'    => [
					vfsStream::url( 'compiled/fixtures/jquery.test.js' ),
				],
				'dependencies' => [ 'jquery' ],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( false );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->get_compiled_jquery(), $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return the original JavaScript when site is not in development
	 * mode, but "minify_js" is disabled.
	 */
	public function test_should_return_original_js_when_minify_js_disabled() {
		$compiler = $this->create_compiler(
			[
				'id'        => 'test',
				'type'      => 'script',
				'minify_js' => false,
				'fragments' => [
					vfsStream::url( 'compiled/fixtures/my-game-clock.js' ),
				],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( false );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->js, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return the original JavaScript when "minify_js" is enabled,
	 * but the site is in development mode.
	 */
	public function test_should_always_return_original_js_when_in_dev_mode() {
		$compiler = $this->create_compiler(
			[
				'id'        => 'test',
				'type'      => 'script',
				'minify_js' => true,
				'fragments' => [
					vfsStream::url( 'compiled/fixtures/my-game-clock.js' ),
				],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( true );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->js, $compiler->compiled_content );
	}

	/**
	 * Test _Beans_Compiler::combine_fragments() should return minified JavaScript.
	 */
	public function test_should_return_minified_javascript() {
		$compiler = $this->create_compiler(
			[
				'id'        => 'test',
				'type'      => 'script',
				'minify_js' => true,
				'fragments' => [
					vfsStream::url( 'compiled/fixtures/my-game-clock.js' ),
				],
			]
		);

		// Set up the mocks.
		Monkey\Functions\expect( 'beans_url_to_path' )->never();
		Monkey\Functions\expect( 'wp_remote_get' )->never();
		Monkey\Functions\expect( '_beans_is_compiler_dev_mode' )->once()->andReturn( false );
		$this->mock_filesystem_for_fragments( $compiler );

		// Run the test.
		$compiler->combine_fragments();
		$this->assertSame( $this->get_compiled_js(), $compiler->compiled_content );
	}
}
