<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\HTML;

/**
 * Since WordPress force us to use the footer.php name to close the document, we add a footer-partial.php template for the actual footer.
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

HTML\beans_open_markup_e(
	'beans_footer',
	'footer',
	array(
		'class'     => 'tm-footer uk-block',
		'role'      => 'contentinfo',
		'itemscope' => 'itemscope',
		'itemtype'  => 'https://schema.org/WPFooter',
	)
);

	HTML\beans_open_markup_e( 'beans_fixed_wrap[_footer]', 'div', 'class=uk-container uk-container-center' );

		/**
		 * Fires in the footer.
		 *
		 * This hook fires in the footer HTML section, not in wp_footer().
		 *
		 * @since 1.0.0
		 */
		do_action( 'beans_footer' );

	HTML\beans_close_markup_e( 'beans_fixed_wrap[_footer]', 'div' );

HTML\beans_close_markup_e( 'beans_footer', 'footer' );
