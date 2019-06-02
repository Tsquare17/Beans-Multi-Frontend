<?php

namespace Beans\Framework\Render;

use Beans\Framework\API\Widgets;
use Beans\Framework\API\Actions;

/**
 * Registers the Beans default widget areas.
 *
 * @package Beans\Framework\Render
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'widgets_init', __NAMESPACE__ . '\beans_do_register_widget_areas', 5 );
/**
 * Register Beans's default widget areas.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_do_register_widget_areas() {
	// Keep primary sidebar first for default widget asignment.
	Widgets\beans_register_widget_area(
		array(
			'name' => __( 'Sidebar Primary', 'beans' ),
			'id'   => 'sidebar_primary',
		)
	);

	Widgets\beans_register_widget_area(
		array(
			'name' => __( 'Sidebar Secondary', 'beans' ),
			'id'   => 'sidebar_secondary',
		)
	);

	if ( current_theme_supports( 'offcanvas-menu' ) ) {
		Widgets\beans_register_widget_area(
			array(
				'name'       => __( 'Off-Canvas Menu', 'beans' ),
				'id'         => 'offcanvas_menu',
				'beans_type' => 'offcanvas',
			)
		);
	}
}

/**
 * Call register sidebar.
 *
 * Because the WordPress.org checker doesn't understand that we are using register_sidebar properly,
 * we have to add this useless call which only has to be declared once.
 *
 * @since 1.0.0
 * @ignore
 * @access private
 */
add_action( 'widgets_init', 'Beans\Framework\API\Widgets\beans_register_widget_area' );
