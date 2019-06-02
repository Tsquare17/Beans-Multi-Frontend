<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Widgets;
use Beans\Framework\API\Actions;

/**
 * Echo widget areas.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'beans_sidebar_primary', __NAMESPACE__ . '\beans_widget_area_sidebar_primary' );
/**
 * Echo primary sidebar widget area.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_area_sidebar_primary() {
	echo Widgets\beans_get_widget_area_output( 'sidebar_primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
}

Actions\beans_add_smart_action( 'beans_sidebar_secondary', __NAMESPACE__ . '\beans_widget_area_sidebar_secondary' );
/**
 * Echo secondary sidebar widget area.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_area_sidebar_secondary() {
	echo Widgets\beans_get_widget_area_output( 'sidebar_secondary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
}

Actions\beans_add_smart_action( 'beans_site_after_markup', __NAMESPACE__ . '\beans_widget_area_offcanvas_menu' );
/**
 * Echo off-canvas widget area.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_area_offcanvas_menu() {

	if ( ! current_theme_supports( 'offcanvas-menu' ) ) {
		return;
	}

	echo Widgets\beans_get_widget_area_output( 'offcanvas_menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
}
