<?php

namespace Beans\Framework\Render;

use Beans\Framework\API\Actions;
use Beans\Framework\API\Template;

/**
 * Loads Beans fragments.
 *
 * @package Beans\Framework\Render
 *
 * @since   1.0.0
 */

// Filter.
Actions\beans_add_smart_action( 'template_redirect', __NAMESPACE__ . '\beans_load_global_fragments', 1 );
/**
 * Load global fragments and dynamic views.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_load_global_fragments() {
	Template\beans_load_fragment_file( 'breadcrumb' );
	Template\beans_load_fragment_file( 'footer' );
	Template\beans_load_fragment_file( 'header' );
	Template\beans_load_fragment_file( 'menu' );
	Template\beans_load_fragment_file( 'post-shortcodes' );
	Template\beans_load_fragment_file( 'post' );
	Template\beans_load_fragment_file( 'widget-area' );
	Template\beans_load_fragment_file( 'embed' );
	Template\beans_load_fragment_file( 'deprecated' );
}

// Filter.
Actions\beans_add_smart_action( 'comments_template', __NAMESPACE__ . '\beans_load_comments_fragment' );
/**
 * Load comments fragments.
 *
 * The comments fragments only loads if comments are active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @param string $template The template filename.
 *
 * @return string The template filename.
 */
function beans_load_comments_fragment( $template ) {

	if ( empty( $template ) ) {
		return;
	}

	Template\beans_load_fragment_file( 'comments' );

	return $template;
}

Actions\beans_add_smart_action( 'dynamic_sidebar_before', __NAMESPACE__ . '\beans_load_widget_fragment', -1 );
/**
 * Load widget fragments.
 *
 * The widget fragments only loads if a sidebar is active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function beans_load_widget_fragment() {
	return Template\beans_load_fragment_file( 'widget' );
}

Actions\beans_add_smart_action( 'pre_get_search_form', __NAMESPACE__ . '\beans_load_search_form_fragment' );
/**
 * Load search form fragments.
 *
 * The search form fragments only loads if search is active to prevent unnecessary memory usage.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function beans_load_search_form_fragment() {
	return Template\beans_load_fragment_file( 'searchform' );
}
