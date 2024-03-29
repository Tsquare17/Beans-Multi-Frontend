<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\Layout;
use Beans\Framework\API\HTML;

/**
 * Echo the primary sidebar structural markup. It also calls the primary sidebar action hooks.
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 * @since   1.5.0 Added ID and tabindex for skip links.
 */

HTML\beans_open_markup_e(
	'beans_sidebar_primary',
	'aside',
	array(
		'class'     => 'tm-secondary ' . Layout\beans_get_layout_class( 'sidebar_primary' ), // Automatically escaped.
		'id'        => 'beans-primary-sidebar',
		'role'      => 'complementary',
		'itemscope' => 'itemscope',
		'itemtype'  => 'https://schema.org/WPSideBar',
		'tabindex'  => '-1',
	)
);

	/**
	 * Fires in the primary sidebar.
	 *
	 * @since 1.0.0
	 */
	do_action( 'beans_sidebar_primary' );

HTML\beans_close_markup_e( 'beans_sidebar_primary', 'aside' );
