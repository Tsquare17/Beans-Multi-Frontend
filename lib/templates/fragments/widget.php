<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Widgets;
use Beans\Framework\API\Actions;
use Beans\Framework\API\HTML;
use Beans\Framework\API\Utilities;
use Beans\Framework\API\Filters;

/**
 * Echo widget fragments.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'beans_widget', __NAMESPACE__ . '\beans_widget_badge', 5 );
/**
 * Echo widget badge.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_badge() {

	if ( ! Widgets\beans_get_widget( 'badge' ) ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_widget_badge' . Widgets\_beans_widget_subfilters(), 'div', 'class=uk-panel-badge uk-badge' );

		echo Widgets\beans_widget_shortcodes( Widgets\beans_get_widget( 'badge_content' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	HTML\beans_close_markup_e( 'beans_widget_badge' . Widgets\_beans_widget_subfilters(), 'div' );
}

Actions\beans_add_smart_action( 'beans_widget', __NAMESPACE__ . '\beans_widget_title' );
/**
 * Echo widget title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_title() {
	$title = Widgets\beans_get_widget( 'title' );

	if ( ! $title || ! Widgets\beans_get_widget( 'show_title' ) ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_widget_title' . Widgets\_beans_widget_subfilters(), 'h3', 'class=uk-panel-title' );

		HTML\beans_output_e( 'beans_widget_title_text', $title );

	HTML\beans_close_markup_e( 'beans_widget_title' . Widgets\_beans_widget_subfilters(), 'h3' );
}

Actions\beans_add_smart_action( 'beans_widget', __NAMESPACE__ . '\beans_widget_content', 15 );
/**
 * Echo widget content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_widget_content() {
	HTML\beans_open_markup_e( 'beans_widget_content' . Widgets\_beans_widget_subfilters(), 'div' );

		HTML\beans_output_e( 'beans_widget_content' . Widgets\_beans_widget_subfilters(), Widgets\beans_get_widget( 'content' ) );

	HTML\beans_close_markup_e( 'beans_widget_content' . Widgets\_beans_widget_subfilters(), 'div' );
}

Actions\beans_add_smart_action( 'beans_no_widget', __NAMESPACE__ . '\beans_no_widget' );
/**
 * Echo no widget content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_no_widget() {

	// Only apply this notice to sidebar_primary and sidebar_secondary.
	if ( ! in_array( Widgets\beans_get_widget_area( 'id' ), array( 'sidebar_primary', 'sidebar_secondary' ), true ) ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_no_widget_notice', 'p', array( 'class' => 'uk-alert uk-alert-warning' ) );

		HTML\beans_output_e(
			'beans_no_widget_notice_text',
			// translators: Name of the widget area.
			sprintf( esc_html__( '%s does not have any widget assigned!', 'beans' ), Widgets\beans_get_widget_area( 'name' ) )
		);

	HTML\beans_close_markup_e( 'beans_no_widget_notice', 'p' );
}

Filters\beans_add_filter( 'beans_widget_content_rss_output', __NAMESPACE__ . '\beans_widget_rss_content' );
/**
 * Modify RSS widget content.
 *
 * @since 1.0.0
 *
 * @return string The RSS widget content.
 */
function beans_widget_rss_content() {
	$options = Widgets\beans_get_widget( 'options' );

	return '<p><a class="uk-button" href="' . Utilities\beans_get( 'url', $options ) . '" target="_blank">' . esc_html__( 'Read feed', 'beans' ) . '</a><p>';
}

Filters\beans_add_filter( 'beans_widget_content_attributes', __NAMESPACE__ . '\beans_modify_widget_content_attributes' );
/**
 * Modify core widgets content attributes, so they use the default UIKit styling.
 *
 * @since 1.0.0
 *
 * @param array $attributes The current widget attributes.
 *
 * @return array The modified widget attributes.
 */
function beans_modify_widget_content_attributes( $attributes ) {
	$type = Widgets\beans_get_widget( 'type' );

	$target = array(
		'archives',
		'categories',
		'links',
		'meta',
		'pages',
		'recent-posts',
		'recent-comments',
	);

	$current_class = isset( $attributes['class'] ) ? $attributes['class'] . ' ' : '';

	if ( in_array( Widgets\beans_get_widget( 'type' ), $target, true ) ) {
		$attributes['class'] = $current_class . 'uk-list'; // Automatically escaped.
	}

	if ( 'calendar' === $type ) {
		$attributes['class'] = $current_class . 'uk-table uk-table-condensed'; // Automatically escaped.
	}

	return $attributes;
}

Filters\beans_add_filter( 'beans_widget_content_categories_output', __NAMESPACE__ . '\beans_modify_widget_count' );
Filters\beans_add_filter( 'beans_widget_content_archives_output', __NAMESPACE__ . '\beans_modify_widget_count' );
/**
 * Modify widget count.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function beans_modify_widget_count( $content ) {
	$count = HTML\beans_output( 'beans_widget_count', '$1' );

	if ( true === Utilities\beans_get( 'dropdown', Widgets\beans_get_widget( 'options' ) ) ) {
		$output = $count;
	} else {
		$output  = HTML\beans_open_markup( 'beans_widget_count', 'span', 'class=tm-count' );
		$output .= $count;
		$output .= HTML\beans_close_markup( 'beans_widget_count', 'span' );
	}

	// Keep closing tag to avoid overwriting the inline JavaScript.
	return preg_replace( '#>((\s|&nbsp;)\((.*)\))#', '>' . $output, $content );
}

Filters\beans_add_filter( 'beans_widget_content_categories_output', __NAMESPACE__ . '\beans_remove_widget_dropdown_label' );
Filters\beans_add_filter( 'beans_widget_content_archives_output', __NAMESPACE__ . '\beans_remove_widget_dropdown_label' );
/**
 * Modify widget dropdown label.
 *
 * @since 1.0.0
 *
 * @param string $content The widget content.
 *
 * @return string The modified widget content.
 */
function beans_remove_widget_dropdown_label( $content ) {
	return preg_replace( '#<label([^>]*)class="screen-reader-text"(.*?)>(.*?)</label>#', '', $content );
}
