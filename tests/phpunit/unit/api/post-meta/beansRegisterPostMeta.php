<?php
/**
 * Tests for beans_register_post_meta()
 *
 * @package Beans\Framework\Tests\Unit\API\Post_Meta
 *
 * @since   1.5.0
 */

namespace Beans\Framework\Tests\Unit\API\Post_Meta;

use Beans\Framework\Tests\Unit\API\Post_Meta\Includes\Post_Meta_Test_Case;
use Brain\Monkey;

require_once dirname( __FILE__ ) . '/includes/class-post-meta-test-case.php';

/**
 * Class Tests_BeansRegisterPostMeta
 *
 * @package Beans\Framework\Tests\Unit\API\Post_Meta
 * @group   api
 * @group   api-post-meta
 */
class Tests_BeansRegisterPostMeta extends Post_Meta_Test_Case {

	/**
	 * Test beans_register_post_meta() should return false when no fields.
	 */
	public function test_should_return_false_when_no_fields() {
		$this->assertFalse( beans_register_post_meta( [], true, 'beans' ) );
	}

	/**
	 * Test beans_register_post_meta() should return false when conditions are false.
	 */
	public function test_should_return_false_when_conditions_are_false() {
		Monkey\Functions\when( '_beans_pre_standardize_fields' )->returnArg();
		Monkey\Functions\expect( '_beans_is_post_meta_conditions' )->once()->andReturn( false );

		$this->assertFalse(
			beans_register_post_meta(
				[
					[
						'id'    => 'field_id',
						'type'  => 'radio',
						'label' => 'Field Label',
					],
				],
				false,
				'beans'
			)
		);
	}

	/**
	 * Test beans_register_post_meta should return false when not is_admin().
	 */
	public function test_should_return_false_when_not_is_admin() {
		Monkey\Functions\when( '_beans_pre_standardize_fields' )->returnArg();
		Monkey\Functions\expect( '_beans_is_post_meta_conditions' )->once()->andReturn( true );
		Monkey\Functions\expect( 'is_admin' )->once()->andReturn( false );

		$this->assertFalse(
			beans_register_post_meta(
				[
					[
						'id'    => 'field_id',
						'type'  => 'radio',
						'label' => 'Field Label',
					],
				],
				true,
				'beans'
			)
		);
	}

	/**
	 * Test beans_register_post_meta() should return false when fields cannot be registered.
	 */
	public function test_should_return_false_when_fields_cannot_be_registered() {
		Monkey\Functions\when( '_beans_pre_standardize_fields' )->returnArg();
		Monkey\Functions\expect( '_beans_is_post_meta_conditions' )->once()->andReturn( true );
		Monkey\Functions\expect( 'is_admin' )->once()->andReturn( true );
		Monkey\Functions\expect( 'beans_register_fields' )
			->once()
			->with( [ 'unregisterable' ], 'post_meta', 'beans' )
			->andReturn( false );

		$this->assertFalse( beans_register_post_meta( [ 'unregisterable' ], true, 'beans' ) );
	}

	/**
	 * Test beans_register_post_meta() should return true when fields are successfully registered.
	 */
	public function test_should_return_true_when_fields_successfully_registered() {
		Monkey\Functions\when( '_beans_pre_standardize_fields' )->returnArg();
		Monkey\Functions\expect( '_beans_is_post_meta_conditions' )->once()->andReturn( true );
		Monkey\Functions\expect( 'is_admin' )->once()->andReturn( true );
		Monkey\Functions\expect( 'beans_register_fields' )
			->once()
			->with( [ 'field_id', 'radio', 'Field Label' ], 'post_meta', 'beans' )
			->andReturn( true );

		$this->assertTrue( beans_register_post_meta( [ 'field_id', 'radio', 'Field Label' ], true, 'beans' ) );
	}
}
