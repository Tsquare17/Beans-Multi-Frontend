<?php
/**
 * Tests for the register_metabox() method of _Beans_Post_Meta.
 *
 * @package Beans\Framework\Tests\Unit\API\Post_Meta
 *
 * @since 1.5.0
 */

namespace Beans\Framework\Tests\Unit\API\Post_Meta;

use Beans\Framework\Tests\Unit\API\Post_Meta\Includes\Post_Meta_Test_Case;
use _Beans_Post_Meta;
use Brain\Monkey;

require_once dirname( __DIR__ ) . '/includes/class-post-meta-test-case.php';

/**
 * Class Tests_BeansPostMeta_RegisterMetabox
 *
 * @package Beans\Framework\Tests\Unit\API\Post_Meta
 * @group   api
 * @group   api-post-meta
 */
class Tests_BeansPostMeta_RegisterMetabox extends Post_Meta_Test_Case {

	/**
	 * Test _Beans_Post_Meta::register_metabox() should register an appropriate metabox.
	 */
	public function test_register_metabox_should_register_metabox() {
		$post_meta = new _Beans_Post_Meta( 'beans', [ 'title' => 'Post Options' ] );

		$wp_meta_boxes['post']['normal']['high']['1'] = [
			'id'            => 1,
			'title'         => 'Post Options',
			'callback'      => [ $this, 'render_metabox_content' ],
			'callback_args' => null,
		];

		Monkey\Functions\expect( 'add_meta_box' )
			->once()
			->with( 'beans', 'Post Options', [ $post_meta, 'render_metabox_content' ], 'post', 'normal', 'high' )
			->andReturn( $wp_meta_boxes['post']['normal']['high']['1'] );

		$post_meta->register_metabox( 'post' );

		$this->assertEquals(
			$wp_meta_boxes['post']['normal']['high'][1],
			[
				'id'            => 1,
				'title'         => 'Post Options',
				'callback'      => [ $this, 'render_metabox_content' ],
				'callback_args' => null,
			]
		);
	}
}
