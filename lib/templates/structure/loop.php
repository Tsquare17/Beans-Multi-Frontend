<?php

namespace Beans\Framework\Templates\Structure;

use Beans\Framework\API\HTML;

/**
 * Echo the posts loop structural markup. It also calls the loop action hooks.
 *
 * @package Beans\Framework\Templates\Structure
 *
 * @since   1.0.0
 */

/**
 * Fires before the loop.
 *
 * This hook fires even if no post exists.
 *
 * @since 1.0.0
 */
do_action( 'beans_before_loop' );
	// phpcs:disable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
	if ( have_posts() && ! is_404() ) :

		/**
		 * Fires before posts loop.
		 *
		 * This hook fires if posts exist.
		 *
		 * @since 1.0.0
		 */
		do_action( 'beans_before_posts_loop' );

		while ( have_posts() ) :
			the_post();

			// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Variable called in a function scope.
			$article_attributes = array(
				'id'        => get_the_ID(), // Automatically escaped.
				'class'     => implode( ' ', get_post_class( array( 'uk-article', current_theme_supports( 'beans-default-styling' ) ? 'uk-panel-box' : null) ) ), // Automatically escaped.
				'itemscope' => 'itemscope',
				'itemtype'  => 'https://schema.org/CreativeWork',
			);

			// Blog specifc attributes.
			if ( 'post' === get_post_type() ) {

				$article_attributes['itemtype'] = 'https://schema.org/BlogPosting';

				// Only add to blogPost attribute to the main query.
				if ( is_main_query() && ! is_search() ) {
					$article_attributes['itemprop'] = 'blogPost';
				}
			}
			// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

			HTML\beans_open_markup_e( 'beans_post', 'article', $article_attributes );

				HTML\beans_open_markup_e( 'beans_post_header', 'header' );

					/**
					 * Fires in the post header.
					 *
					 * @since 1.0.0
					 */
					do_action( 'beans_post_header' );

				HTML\beans_close_markup_e( 'beans_post_header', 'header' );

				HTML\beans_open_markup_e( 'beans_post_body', 'div', array( 'itemprop' => 'articleBody' ) );

					/**
					 * Fires in the post body.
					 *
					 * @since 1.0.0
					 */
					do_action( 'beans_post_body' );

				HTML\beans_close_markup_e( 'beans_post_body', 'div' );

			HTML\beans_close_markup_e( 'beans_post', 'article' );
		endwhile;

		/**
		 * Fires after the posts loop.
		 *
		 * This hook fires if posts exist.
		 *
		 * @since 1.0.0
		 */
		do_action( 'beans_after_posts_loop' );
	else :

			/**
			 * Fires if no posts exist.
			 *
			 * @since 1.0.0
			 */
			do_action( 'beans_no_post' );
	endif;

/**
 * Fires after the loop.
 *
 * This hook fires even if no post exists.
 *
 * @since 1.0.0
 */
	do_action( 'beans_after_loop' );
// phpcs:enable Generic.WhiteSpace.ScopeIndent -- Code structure mirrors HTML markup.
