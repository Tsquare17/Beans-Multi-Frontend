<?php
/**
 * The base Test Case for Beans' Compiler API integration tests.
 *
 * @package Beans\Framework\Tests\Integration\API\Compiler\Includes
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Integration\API\Compiler\Includes;

use Beans\Framework\Tests\Integration\Test_Case;
use Mockery;
use org\bovigo\vfs\vfsStream;

/**
 * Abstract Class Base_Test_Case
 *
 * @package Beans\Framework\Tests\Integration\API\Compiler\Includes
 */
abstract class Base_Test_Case extends Test_Case {

	/**
	 * Path to the compiled files' directory.
	 *
	 * @var string
	 */
	protected $compiled_dir;

	/**
	 * Instance of vfsStreamDirectory to mock the filesystem.
	 *
	 * @var vfsStreamDirectory
	 */
	protected $mock_filesystem;

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp() {
		parent::setUp();

		$this->set_up_virtual_filesystem();
		$this->compiled_dir = vfsStream::url( 'compiled' );

		// Set the Uploads directory to our virtual filesystem.
		add_filter(
			'upload_dir',
			function( array $uploads_dir ) {
				$uploads_dir['path']    = $this->compiled_dir . $uploads_dir['subdir'];
				$uploads_dir['url']     = str_replace( 'wp-content/uploads', 'compiled', $uploads_dir['url'] );
				$uploads_dir['basedir'] = $this->compiled_dir;
				$uploads_dir['baseurl'] = str_replace( 'wp-content/uploads', 'compiled', $uploads_dir['baseurl'] );

				return $uploads_dir;
			}
		);
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	public function tearDown() {
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Set up the virtual filesystem.
	 */
	protected function set_up_virtual_filesystem() {
		$this->mock_filesystem = vfsStream::setup(
			'compiled',
			0755,
			$this->get_virtual_structure()
		);
	}

	/**
	 * Get the virtual filesystem's structure.
	 */
	protected function get_virtual_structure() {
		return [
			'beans' => [
				'compiler'       => [
					'index.php' => '',
				],
				'admin-compiler' => [
					'index.php' => '',
				],
			],
		];
	}

	/**
	 * Set Development Mode.
	 *
	 * @since 1.5.0
	 *
	 * @param bool $is_enabled Optional. When true, turns on development mode. Default is false.
	 *
	 * @return void
	 */
	protected function set_dev_mode( $is_enabled = false ) {
		update_option( 'beans_dev_mode', $is_enabled );
	}
}
