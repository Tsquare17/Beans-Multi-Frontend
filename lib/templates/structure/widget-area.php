<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Frontend\Init as Frontend;
use Beans\Framework\API\Widgets;
use Beans\Framework\API\HTML;

/**
 * Echo the widget area and widget loop structural markup. It also calls the widget area and widget loop
 * action hooks.
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

// This includes everything added to wp hooks before the widgets.
echo Widgets\beans_get_widget_area( 'before_widgets' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Widget area has to be echoed.

	// phpcs:disable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
	if ( 'grid' === Widgets\beans_get_widget_area( 'beans_type' ) ) {
		HTML\beans_open_markup_e(
			'beans_widget_area_grid' . Widgets\_beans_widget_area_subfilters(),
			'div',
			array(
				'class'                           => Frontend::get('grid_container_class'),
				Frontend::get('data_grid_margin') => '',
			)
		);
	}

	if ( 'offcanvas' === Widgets\beans_get_widget_area( 'beans_type' ) ) {

		HTML\beans_open_markup_e(
			'beans_widget_area_offcanvas_wrap' . Widgets\_beans_widget_area_subfilters(),
			'div',
			array(
				'id'    => Widgets\beans_get_widget_area( 'id' ), // Automatically escaped.
				'class' => Frontend::get('offcanvas'),
			)
		);

			HTML\beans_open_markup_e( 'beans_widget_area_offcanvas_bar' . Widgets\_beans_widget_area_subfilters(), 'div', array( 'class' => Frontend::get('offcanvas_bar') ) );
	}

		// Widgets.
		if ( Widgets\beans_have_widgets() ) :

			/**
			 * Fires before widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'beans_before_widgets_loop' );

				while ( Widgets\beans_have_widgets() ) :
					Widgets\beans_setup_widget();

					if ( 'grid' === Widgets\beans_get_widget_area( 'beans_type' ) ) {
						HTML\beans_open_markup_e( 'beans_widget_grid' . Widgets\_beans_widget_subfilters(), 'div', Frontend::get('grid_class_attribute') );
					}

						HTML\beans_open_markup_e( 'beans_widget_panel' . Widgets\_beans_widget_subfilters(), 'div', Frontend::get('row_class_attribute') );

							/**
							 * Fires in each widget panel structural HTML.
							 *
							 * @since 1.0.0
							 */
							do_action( 'beans_widget' );

						HTML\beans_close_markup_e( 'beans_widget_panel' . Widgets\_beans_widget_subfilters(), 'div' );

					if ( 'grid' === Widgets\beans_get_widget_area( 'beans_type' ) ) {
						HTML\beans_close_markup_e( 'beans_widget_grid' . Widgets\_beans_widget_subfilters(), 'div' );
					}
				endwhile;

			/**
			 * Fires after the widgets loop.
			 *
			 * This hook only fires if widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'beans_after_widgets_loop' );
		else :

			/**
			 * Fires if no widgets exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'beans_no_widget' );
		endif;

	if ( 'offcanvas' === Widgets\beans_get_widget_area( 'beans_type' ) ) {

			HTML\beans_close_markup_e( 'beans_widget_area_offcanvas_bar' . Widgets\_beans_widget_area_subfilters(), 'div' );

		HTML\beans_close_markup_e( 'beans_widget_area_offcanvas_wrap' . Widgets\_beans_widget_area_subfilters(), 'div' );
	}

	if ( 'grid' === Widgets\beans_get_widget_area( 'beans_type' ) ) {
		HTML\beans_close_markup_e( 'beans_widget_area_grid' . Widgets\_beans_widget_area_subfilters(), 'div' );
	}

// This includes everything added to wp hooks after the widgets.
echo Widgets\beans_get_widget_area( 'after_widgets' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Widget area has to be echoed.

// phpcs:enable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
