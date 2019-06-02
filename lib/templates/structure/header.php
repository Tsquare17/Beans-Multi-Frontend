<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\Accessibility as A11Y;
use Beans\Framework\API\Layout;
use Beans\Framework\API\Utilities;
use Beans\Framework\API\HTML;

/**
 * Despite its name, this template echos between the opening HTML markup and the opening primary markup.
 *
 * This template must be called using get_header().
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

HTML\beans_output_e( 'beans_doctype', '<!DOCTYPE html>' );

HTML\beans_open_markup_e( 'beans_html', 'html', str_replace( ' ', '&', str_replace( '"', '', Utilities\beans_render_function( 'language_attributes' ) ) ) );

	HTML\beans_open_markup_e( 'beans_head', 'head' );

		/**
		 * Fires in the head.
		 *
		 * This hook fires in the head HTML section, not in wp_header().
		 *
		 * @since 1.0.0
		 */
		do_action( 'beans_head' );

		wp_head();

	HTML\beans_close_markup_e( 'beans_head', 'head' );

	HTML\beans_open_markup_e(
		'beans_body',
		'body',
		array(
			'class'     => implode( ' ', get_body_class( 'uk-form no-js' ) ),
			'itemscope' => 'itemscope',
			'itemtype'  => 'https://schema.org/WebPage',

		)
	);

		A11Y\beans_build_skip_links();

		HTML\beans_open_markup_e( 'beans_site', 'div', array( 'class' => 'tm-site' ) );

			HTML\beans_open_markup_e( 'beans_main', 'main', array( 'class' => 'tm-main uk-block' ) );

				HTML\beans_open_markup_e( 'beans_fixed_wrap[_main]', 'div', 'class=uk-container uk-container-center' );

					HTML\beans_open_markup_e(
						'beans_main_grid',
						'div',
						array(
							'class'               => 'uk-grid',
							'data-uk-grid-margin' => '',
						)
					);

						HTML\beans_open_markup_e( 'beans_primary', 'div', array( 'class' => 'tm-primary ' . Layout\beans_get_layout_class( 'content' ) ) );
