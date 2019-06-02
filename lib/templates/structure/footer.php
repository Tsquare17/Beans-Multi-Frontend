<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\HTML;

/**
 * Despite its name, this template echos between the closing primary markup and the closing HTML markup.
 *
 * This template must be called using get_footer().
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

						HTML\beans_close_markup_e( 'beans_primary', 'div' );

					HTML\beans_close_markup_e( 'beans_main_grid', 'div' );

				HTML\beans_close_markup_e( 'beans_fixed_wrap[_main]', 'div' );

			HTML\beans_close_markup_e( 'beans_main', 'main' );

		HTML\beans_close_markup_e( 'beans_site', 'div' );

		wp_footer();

	HTML\beans_close_markup_e( 'beans_body', 'body' );

HTML\beans_close_markup_e( 'beans_html', 'html' );
