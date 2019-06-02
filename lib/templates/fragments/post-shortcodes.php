<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Actions;
use Beans\Framework\API\HTML;

/**
 * Add post shortcodes.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'beans_post_meta_date', __NAMESPACE__ . '\beans_post_meta_date_shortcode' );
/**
 * Echo post meta date shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_date_shortcode() {
	HTML\beans_output_e( 'beans_post_meta_date_prefix', esc_html__( 'Posted on ', 'beans' ) );

	HTML\beans_open_markup_e(
		'beans_post_meta_date',
		'time',
		array(
			'datetime' => get_the_time( 'c' ),
			'itemprop' => 'datePublished',
		)
	);

		HTML\beans_output_e( 'beans_post_meta_date_text', get_the_time( get_option( 'date_format' ) ) );

	HTML\beans_close_markup_e( 'beans_post_meta_date', 'time' );
}

Actions\beans_add_smart_action( 'beans_post_meta_author', __NAMESPACE__ . '\beans_post_meta_author_shortcode' );
/**
 * Echo post meta author shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_author_shortcode() {
	HTML\beans_output_e( 'beans_post_meta_author_prefix', esc_html__( 'By ', 'beans' ) );

	HTML\beans_open_markup_e(
		'beans_post_meta_author',
		'a',
		array(
			'href'      => get_author_posts_url( get_the_author_meta( 'ID' ) ), // Automatically escaped.
			'rel'       => 'author',
			'itemprop'  => 'author',
			'itemscope' => '',
			'itemtype'  => 'https://schema.org/Person',
		)
	);

		HTML\beans_output_e( 'beans_post_meta_author_text', get_the_author() );

		HTML\beans_selfclose_markup_e(
			'beans_post_meta_author_name_meta',
			'meta',
			array(
				'itemprop' => 'name',
				'content'  => get_the_author(), // Automatically escaped.
			)
		);

	HTML\beans_close_markup_e( 'beans_post_meta_author', 'a' );
}

Actions\beans_add_smart_action( 'beans_post_meta_comments', __NAMESPACE__ . '\beans_post_meta_comments_shortcode' );
/**
 * Echo post meta comments shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_comments_shortcode() {

	if ( post_password_required() || ! comments_open() ) {
		return;
	}

	global $post;
	$comments_number = (int) get_comments_number( $post->ID );

	if ( $comments_number < 1 ) {
		$comment_text = HTML\beans_output( 'beans_post_meta_empty_comment_text', esc_html__( 'Leave a comment', 'beans' ) );
	} elseif ( 1 === $comments_number ) {
		$comment_text = HTML\beans_output( 'beans_post_meta_comments_text_singular', esc_html__( '1 comment', 'beans' ) );
	} else {
		$comment_text = HTML\beans_output(
			'beans_post_meta_comments_text_plural',
			// translators: %s: Number of comments. Plural.
			esc_html__( '%s comments', 'beans' )
		);
	}

	HTML\beans_open_markup_e( 'beans_post_meta_comments', 'a', array( 'href' => get_comments_link() ) ); // Automatically escaped.

		printf( $comment_text, (int) get_comments_number( $post->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaping handled prior to this printf.

	HTML\beans_close_markup_e( 'beans_post_meta_comments', 'a' );
}

Actions\beans_add_smart_action( 'beans_post_meta_tags', __NAMESPACE__ . '\beans_post_meta_tags_shortcode' );
/**
 * Echo post meta tags shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_tags_shortcode() {
	$tags = get_the_tag_list( null, ', ' );

	if ( ! $tags || is_wp_error( $tags ) ) {
		return;
	}

	printf( '%1$s%2$s', HTML\beans_output( 'beans_post_meta_tags_prefix', esc_html__( 'Tagged with: ', 'beans' ) ), $tags ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Tags are escaped by WordPress.
}

Actions\beans_add_smart_action( 'beans_post_meta_categories', __NAMESPACE__ . '\beans_post_meta_categories_shortcode' );
/**
 * Echo post meta categories shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_categories_shortcode() {
	$categories = get_the_category_list( ', ' );

	if ( ! $categories || is_wp_error( $categories ) ) {
		return;
	}

	printf( '%1$s%2$s', HTML\beans_output( 'beans_post_meta_categories_prefix', esc_html__( 'Filed under: ', 'beans' ) ), $categories ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Categories are escaped by WordPress.
}
