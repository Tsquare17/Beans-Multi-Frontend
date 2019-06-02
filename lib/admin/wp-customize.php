<?php

namespace Beans\Framework\Admin;

use Beans\Framework\API\Actions;
use Beans\Framework\API\Layout;
use Beans\Framework\API\WP_Customize;

/**
 * Add Beans options to the WordPress Customizer.
 *
 * @package Beans\Framework\Admin
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'customize_preview_init', 'beans_do_enqueue_wp_customize_assets' );
/**
 * Enqueue Beans assets for the WordPress Customizer.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_do_enqueue_wp_customize_assets() {
	wp_enqueue_script(
		'beans-wp-customize-preview',
		BEANS_ADMIN_JS_URL . 'wp-customize-preview.js',
		array(
			'jquery',
			'customize-preview',
		),
		BEANS_VERSION,
		true
	);
}

Actions\beans_add_smart_action( 'customize_register', 'beans_do_register_wp_customize_options' );
/**
 * Add Beans options to the WordPress Customizer.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_do_register_wp_customize_options() {
	$fields = array(
		array(
			'id'    => 'beans_logo_image',
			'label' => __( 'Logo Image', 'beans' ),
			'type'  => 'WP_Customize_Image_Control',
		),
	);

	WP_Customize\beans_register_wp_customize_options( $fields, 'title_tagline', array( 'title' => __( 'Branding', 'beans' ) ) );

	// Get layout option without default for the count.
	$options = Layout\beans_get_layouts_for_options();

	// Only show the layout options if more than two layouts are registered.
	if ( count( $options ) > 2 ) {
		$fields = array(
			array(
				'id'      => 'beans_layout',
				'label'   => __( 'Default Layout', 'beans' ),
				'type'    => 'radio',
				'default' => Layout\beans_get_default_layout(),
				'options' => $options,
			),
		);

		WP_Customize\beans_register_wp_customize_options(
			$fields,
			'beans_layout',
			array(
				'title'    => __( 'Default Layout', 'beans' ),
				'priority' => 1000,
			)
		);
	}

	$fields = array(
		array(
			'id'          => 'beans_viewport_width_group',
			'label'       => __( 'Viewport Width - for Previewing Only', 'beans' ),
			'description' => __( 'Slide left or right to change the viewport width. Publishing will not change the width of your website.', 'beans' ),
			'type'        => 'group',
			'transport'   => 'postMessage',
			'fields'      => array(
				array(
					'id'      => 'beans_enable_viewport_width',
					'label'   => __( 'Enable to change the viewport width.', 'beans' ),
					'type'    => 'activation',
					'default' => false,
				),
				array(
					'id'       => 'beans_viewport_width',
					'type'     => 'slider',
					'default'  => 1000,
					'min'      => 300,
					'max'      => 2500,
					'interval' => 10,
					'unit'     => 'px',
				),
			),
		),
		array(
			'id'          => 'beans_viewport_height_group',
			'label'       => __( 'Viewport Height - for Previewing Only', 'beans' ),
			'description' => __( 'Slide left or right to change the viewport height. Publishing will not change the height of your website.', 'beans' ),
			'type'        => 'group',
			'transport'   => 'postMessage',
			'fields'      => array(
				array(
					'id'      => 'beans_enable_viewport_height',
					'label'   => __( 'Enable to change the viewport height.', 'beans' ),
					'type'    => 'activation',
					'default' => false,
				),
				array(
					'id'       => 'beans_viewport_height',
					'type'     => 'slider',
					'default'  => 1000,
					'min'      => 300,
					'max'      => 2500,
					'interval' => 10,
					'unit'     => 'px',
				),
			),
		),
	);

	WP_Customize\beans_register_wp_customize_options(
		$fields,
		'beans_preview',
		array(
			'title'    => __( 'Preview Tools', 'beans' ),
			'priority' => 1010,
		)
	);
}
