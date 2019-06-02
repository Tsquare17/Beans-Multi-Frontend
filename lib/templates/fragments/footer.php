<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Actions;
use Beans\Framework\API\HTML;

/**
 * Echo footer fragments.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'beans_footer', __NAMESPACE__ . '\beans_footer_content' );
/**
 * Echo the footer content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_footer_content() {
	HTML\beans_open_markup_e( 'beans_footer_credit', 'div', array( 'class' => 'uk-clearfix uk-text-small uk-text-muted' ) );

		HTML\beans_open_markup_e( 'beans_footer_credit_left', 'span', array( 'class' => 'uk-align-medium-left uk-margin-small-bottom' ) );

			HTML\beans_output_e(
				'beans_footer_credit_text',
				sprintf(
					// translators: Footer credits. Date followed by the name of the website.
					__( '&#x000A9; %1$s - %2$s. All rights reserved.', 'beans' ),
					date( 'Y' ),
					get_bloginfo( 'name' )
				)
			);

		HTML\beans_close_markup_e( 'beans_footer_credit_left', 'span' );

		$framework_link = HTML\beans_open_markup(
			'beans_footer_credit_framework_link',
			'a',
			array(
				'href' => 'https://www.getbeans.io', // Automatically escaped.
				'rel'  => 'nofollow',
			)
		);

			$framework_link .= HTML\beans_output( 'beans_footer_credit_framework_link_text', 'Beans' );

		$framework_link .= HTML\beans_close_markup( 'beans_footer_credit_framework_link', 'a' );

		HTML\beans_open_markup_e( 'beans_footer_credit_right', 'span', array( 'class' => 'uk-align-medium-right uk-margin-bottom-remove' ) );

			HTML\beans_output_e(
				'beans_footer_credit_right_text',
				sprintf(
					// translators: Link to the Beans website.
					__( '%1$s theme for WordPress.', 'beans' ),
					$framework_link
				)
			);

		HTML\beans_close_markup_e( 'beans_footer_credit_right', 'span' );

	HTML\beans_close_markup_e( 'beans_footer_credit', 'div' );
}

Actions\beans_add_smart_action( 'wp_footer', __NAMESPACE__ . '\beans_replace_nojs_class' );
/**
 * Print inline JavaScript in the footer to replace the 'no-js' class with 'js'.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_replace_nojs_class() {
	?><script type="text/javascript">
		(function() {
			document.body.className = document.body.className.replace('no-js','js');
		}());
	</script>
	<?php
}
