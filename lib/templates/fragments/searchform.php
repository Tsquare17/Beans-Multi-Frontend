<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Actions;
use Beans\Framework\API\HTML;

/**
 * Modify the search from.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

// Filter.
Actions\beans_add_smart_action( 'get_search_form', __NAMESPACE__ . '\beans_search_form' );
/**
 * Modify the search form.
 *
 * @since 1.0.0
 *
 * @return string The form.
 */
function beans_search_form() {
	$output = HTML\beans_open_markup(
		'beans_search_form',
		'form',
		array(
			'class'  => 'uk-form uk-form-icon uk-form-icon-flip uk-width-1-1',
			'method' => 'get',
			'action' => esc_url( home_url( '/' ) ),
			'role'   => 'search',
		)
	);

		$output .= HTML\beans_selfclose_markup(
			'beans_search_form_input',
			'input',
			array(
				'class'       => 'uk-width-1-1',
				'type'        => 'search',
				'placeholder' => __( 'Search', 'beans' ), // Automatically escaped.
				'value'       => esc_attr( get_search_query() ),
				'name'        => 's',
			)
		);

		$output .= HTML\beans_open_markup(
			'beans_search_form_input_icon',
			'span',
			array(
				'class'       => 'uk-icon-search',
				'aria-hidden' => 'true',
			)
		);

		$output .= HTML\beans_close_markup( 'beans_search_form_input_icon', 'span' );

	$output .= HTML\beans_close_markup( 'beans_search_form', 'form' );

	return $output;
}
