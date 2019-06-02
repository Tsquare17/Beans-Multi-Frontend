<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\HTML;

/**
 * Since WordPress force us to use the header.php name to open the document, we add a header-partial.php template for the actual header.
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

HTML\beans_open_markup_e(
	'beans_header',
	'header',
	array(
		'class'     => 'tm-header uk-block',
		'role'      => 'banner',
		'itemscope' => 'itemscope',
		'itemtype'  => 'https://schema.org/WPHeader',
	)
);

	HTML\beans_open_markup_e( 'beans_fixed_wrap[_header]', 'div', 'class=uk-container uk-container-center' );

		/**
		 * Fires in the header.
		 *
		 * @since 1.0.0
		 */
		do_action( 'beans_header' );

	HTML\beans_close_markup_e( 'beans_fixed_wrap[_header]', 'div' );

HTML\beans_close_markup_e( 'beans_header', 'header' );
