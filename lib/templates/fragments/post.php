<?php

namespace Beans\Framework\Templates\Fragments;

use Beans\Framework\API\Actions;
use Beans\Framework\API\Image;
use Beans\Framework\API\Utilities;
use Beans\Framework\API\HTML;

/**
 * Echo post fragments.
 *
 * @package Beans\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'beans_post_header', __NAMESPACE__ . '\beans_post_title' );
/**
 * Echo post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_title() {
	$title     = HTML\beans_output( 'beans_post_title_text', get_the_title() );
	$title_tag = 'h1';

	if ( empty( $title ) ) {
		return;
	}

	if ( ! is_singular() ) {
		$title_link = HTML\beans_open_markup(
			'beans_post_title_link',
			'a',
			array(
				'href'  => get_permalink(), // Automatically escaped.
				'title' => the_title_attribute( 'echo=0' ),
				'rel'   => 'bookmark',
			)
		);

		$title_link .= $title;
		$title_link .= HTML\beans_close_markup( 'beans_post_title_link', 'a' );

		$title     = $title_link;
		$title_tag = 'h2';
	}

	HTML\beans_open_markup_e(
		'beans_post_title',
		$title_tag,
		array(
			'class'    => 'uk-article-title',
			'itemprop' => 'headline',
		)
	);

		echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.

	HTML\beans_close_markup_e( 'beans_post_title', $title_tag );
}

Actions\beans_add_smart_action( 'beans_before_loop', __NAMESPACE__ . '\beans_post_search_title' );
/**
 * Echo search post title.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_search_title() {

	if ( ! is_search() ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_search_title', 'h1', array( 'class' => 'uk-article-title' ) );

		printf( '%1$s%2$s', HTML\beans_output( 'beans_search_title_text', esc_html__( 'Search results for: ', 'beans' ) ), get_search_query() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Each placeholder is escaped.

	HTML\beans_close_markup_e( 'beans_search_title', 'h1' );
}


Actions\beans_add_smart_action( 'beans_before_loop', __NAMESPACE__ . '\beans_post_archive_title' );
/**
 * Echo archive post title.
 *
 * @since 1.4.0
 *
 * @return void
 */
function beans_post_archive_title() {

	if ( ! is_archive() ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_archive_title', 'h1', array( 'class' => 'uk-article-title' ) );

		HTML\beans_output_e( 'beans_archive_title_text', get_the_archive_title() );

	HTML\beans_close_markup_e( 'beans_archive_title', 'h1' );
}

Actions\beans_add_smart_action( 'beans_post_header', __NAMESPACE__ . '\beans_post_meta', 15 );
/**
 * Echo post meta.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta() {

	/**
	 * Filter whether {@see beans_post_meta()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'beans_pre_post_meta', 'post' !== get_post_type() ) ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_post_meta', 'ul', array( 'class' => 'uk-article-meta uk-subnav uk-subnav-line' ) );

		/**
		 * Filter the post meta actions and order.
		 *
		 * A do_action( "beans_post_meta_{$array_key}" ) is called for each array key set. Array values are used to set the priority of
		 * each actions. The array ordered using asort();
		 *
		 * @since 1.0.0
		 *
		 * @param array $fragments An array of fragment files.
		 */
		$meta_items = apply_filters(
			'beans_post_meta_items',
			array(
				'date'     => 10,
				'author'   => 20,
				'comments' => 30,
			)
		);

		asort( $meta_items );

	foreach ( $meta_items as $meta => $priority ) {

		$content = Utilities\beans_render_function( '\do_action', "beans_post_meta_$meta" );

		if ( ! $content ) {
			continue;
		}

		HTML\beans_open_markup_e( "beans_post_meta_item[_{$meta}]", 'li' );

			HTML\beans_output_e( "beans_post_meta_item_{$meta}_text", $content );

		HTML\beans_close_markup_e( "beans_post_meta_item[_{$meta}]", 'li' );
	}

	HTML\beans_close_markup_e( 'beans_post_meta', 'ul' );
}

Actions\beans_add_smart_action( 'beans_post_body', __NAMESPACE__ . '\beans_post_image', 5 );
/**
 * Echo post image.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function beans_post_image() {

	if ( ! has_post_thumbnail() || ! current_theme_supports( 'post-thumbnails' ) ) {
		return false;
	}

	global $post;

	/**
	 * Filter whether Beans should handle the image edition (resize) or let WP do so.
	 *
	 * @since 1.2.5
	 *
	 * @param bool $edit True to use Beans Image API to handle the image edition (resize), false to let {@link https://codex.wordpress.org/Function_Reference/the_post_thumbnail the_post_thumbnail()} taking care of it. Default true.
	 */
	$edit = apply_filters( 'beans_post_image_edit', true );

	if ( $edit ) {

		/**
		 * Filter the arguments used by {@see beans_edit_image()} to edit the post image.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|array $edit_args Arguments used by {@see beans_edit_image()}. Set to false to use WordPress
		 *                              large size.
		 */
		$edit_args = apply_filters(
			'beans_edit_post_image_args',
			array(
				'resize' => array( 800, false ),
			)
		);

		if ( empty( $edit_args ) ) {
			$image = Image\beans_get_post_attachment( $post->ID, 'large' );
		} else {
			$image = Image\beans_edit_post_attachment( $post->ID, $edit_args );
		}

		/**
		 * Filter the arguments used by {@see beans_edit_image()} to edit the post small image.
		 *
		 * The small image is only used for screens equal or smaller than the image width set, which is 480px by default.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|array $edit_args Arguments used by {@see beans_edit_image()}. Set to false to use WordPress
		 *                              small size.
		 */
		$edit_small_args = apply_filters(
			'beans_edit_post_image_small_args',
			array(
				'resize' => array( 480, false ),
			)
		);

		if ( empty( $edit_small_args ) ) {
			$image_small = Image\beans_get_post_attachment( $post->ID, 'thumbnail' );
		} else {
			$image_small = Image\beans_edit_post_attachment( $post->ID, $edit_small_args );
		}
	}

	HTML\beans_open_markup_e( 'beans_post_image', 'div', array( 'class' => 'tm-article-image' ) );

	if ( ! is_singular() ) {
		HTML\beans_open_markup_e(
			'beans_post_image_link',
			'a',
			array(
				'href'  => get_permalink(), // Automatically escaped.
				'title' => the_title_attribute( 'echo=0' ),
			)
		);
	}

			HTML\beans_open_markup_e( 'beans_post_image_item_wrap', 'picture' );

	if ( $edit ) {
		HTML\beans_selfclose_markup_e(
			'beans_post_image_small_item',
			'source',
			array(
				'media'  => '(max-width: ' . $image_small->width . 'px)',
				'srcset' => esc_url( $image_small->src ),
			),
			$image_small
		);

		HTML\beans_selfclose_markup_e(
			'beans_post_image_item',
			'img',
			array(
				'width'    => $image->width,
				'height'   => $image->height,
				'src'      => $image->src, // Automatically escaped.
				'alt'      => $image->alt, // Automatically escaped.
				'itemprop' => 'image',
			),
			$image
		);
	} else {
		// Beans API isn't available, use wp_get_attachment_image_attributes filter instead.
		the_post_thumbnail();
	}

			HTML\beans_close_markup_e( 'beans_post_image_item_wrap', 'picture' );

	if ( ! is_singular() ) {
		HTML\beans_close_markup_e( 'beans_post_image_link', 'a' );
	}

	HTML\beans_close_markup_e( 'beans_post_image', 'div' );
}

Actions\beans_add_smart_action( 'beans_post_body', __NAMESPACE__ . '\beans_post_content' );
/**
 * Echo post content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_content() {
	global $post;

	HTML\beans_open_markup_e(
		'beans_post_content',
		'div',
		array(
			'class'    => 'tm-article-content',
			'itemprop' => 'text',
		)
	);

		the_content();

	if ( is_singular() && 'open' === get_option( 'default_ping_status' ) && post_type_supports( $post->post_type, 'trackbacks' ) ) {
		echo '<!--';
		trackback_rdf();
		echo '-->' . "\n";
	}

	HTML\beans_close_markup_e( 'beans_post_content', 'div' );
}

// Filter.
Actions\beans_add_smart_action( 'the_content_more_link', __NAMESPACE__ . '\beans_post_more_link' );
/**
 * Modify post "more link".
 *
 * @since 1.0.0
 *
 * @return string The modified "more link".
 */
function beans_post_more_link() {

	$output = HTML\beans_open_markup(
		'beans_post_more_link',
		'a',
		array(
			'href'  => get_permalink(), // Automatically escaped.
			'class' => 'more-link',
		)
	);

		$output .= HTML\beans_output( 'beans_post_more_link_text', esc_html__( 'Continue reading', 'beans' ) );

		$output .= HTML\beans_open_markup(
			'beans_next_icon[_more_link]',
			'span',
			array(
				'class'       => 'uk-icon-angle-double-right uk-margin-small-left',
				'aria-hidden' => 'true',
			)
		);
		$output .= HTML\beans_close_markup( 'beans_next_icon[_more_link]', 'span' );

	$output .= HTML\beans_close_markup( 'beans_post_more_link', 'a' );

	return $output;
}

Actions\beans_add_smart_action( 'beans_post_body', __NAMESPACE__ . '\beans_post_content_navigation', 20 );
/**
 * Echo post content navigation.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_content_navigation() {
	echo wp_link_pages(
		array(
			'before' => HTML\beans_open_markup( 'beans_post_content_navigation', 'p', array( 'class' => 'uk-text-bold' ) ) . HTML\beans_output( 'beans_post_content_navigation_text', __( 'Pages:', 'beans' ) ),
			'after'  => HTML\beans_close_markup( 'beans_post_content_navigation', 'p' ),
			'echo'   => false,
		)
	);
}

Actions\beans_add_smart_action( 'beans_post_body', __NAMESPACE__ . '\beans_post_meta_categories', 25 );
/**
 * Echo post meta categories.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_post_meta_categories() {
	$categories = Utilities\beans_render_function( 'do_shortcode', '[' . __NAMESPACE__ . 'beans_post_meta_categories]' );

	if ( ! $categories ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_post_meta_categories', 'span', array( 'class' => 'uk-text-small uk-text-muted uk-clearfix' ) );

		echo $categories; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode's callback handles the escaping. See beans_post_meta_categories_shortcode().

	HTML\beans_close_markup_e( 'beans_post_meta_categories', 'span' );
}

Actions\beans_add_smart_action( 'beans_post_body', __NAMESPACE__ . '\beans_post_meta_tags', 30 );
/**
 * Echo post meta tags.
 *
 * @since 1.0.0
 */
function beans_post_meta_tags() {
	$tags = Utilities\beans_render_function( 'do_shortcode', '[' . __NAMESPACE__ . 'beans_post_meta_tags]' );

	if ( ! $tags ) {
		return;
	}

	HTML\beans_open_markup_e( 'beans_post_meta_tags', 'span', array( 'class' => 'uk-text-small uk-text-muted uk-clearfix' ) );

		echo $tags; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode's callback handles the escaping. See beans_post_meta_tags_shortcode().

	HTML\beans_close_markup_e( 'beans_post_meta_tags', 'span' );
}

// Filter.
Actions\beans_add_smart_action( 'previous_post_link', __NAMESPACE__ . '\beans_previous_post_link', 10, 4 );
/**
 * Modify post "previous link".
 *
 * @since 1.0.0
 *
 * @param string $output "Next link" output.
 * @param string $format Link output format.
 * @param string $link Link permalink format.
 * @param int    $post Post ID.

 * @return string The modified "previous link".
 */
function beans_previous_post_link( $output, $format, $link, $post ) {
	// Using $link won't apply wp filters, so rather strip tags the $output.
	$text = strip_tags( $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- We want to strip the tags and keep the content.

	$output = HTML\beans_open_markup(
		'beans_previous_link[_post_navigation]',
		'a',
		array(
			'href'  => get_permalink( $post ), // Automatically escaped.
			'rel'   => 'previous',
			'title' => $post->post_title, // Automatically escaped.
		)
	);

		$output .= HTML\beans_open_markup(
			'beans_previous_icon[_post_navigation]',
			'span',
			array(
				'class'       => 'uk-icon-angle-double-left uk-margin-small-right',
				'aria-hidden' => 'true',
			)
		);

		$output .= HTML\beans_close_markup( 'beans_previous_icon[_post_navigation]', 'span' );

		$output .= HTML\beans_output( 'beans_previous_text[_post_navigation]', $text );

	$output .= HTML\beans_close_markup( 'beans_previous_link[_post_navigation]', 'a' );

	return $output;
}

// Filter.
Actions\beans_add_smart_action( 'next_post_link', __NAMESPACE__ . '\beans_next_post_link', 10, 4 );
/**
 * Modify post "next link".
 *
 * @since 1.0.0
 *
 * @param string $output "Next link" output.
 * @param string $format Link output format.
 * @param string $link Link permalink format.
 * @param int    $post Post ID.
 *
 * @return string The modified "next link".
 */
function beans_next_post_link( $output, $format, $link, $post ) {
	// Using $link won't apply WP filters, so rather strip tags the $output.
	$text = strip_tags( $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- We want to strip the tags and keep the content.

	$output = HTML\beans_open_markup(
		'beans_next_link[_post_navigation]',
		'a',
		array(
			'href'  => get_permalink( $post ), // Automatically escaped.
			'rel'   => 'next',
			'title' => $post->post_title, // Automatically escaped.
		)
	);

		$output .= HTML\beans_output( 'beans_next_text[_post_navigation]', $text );

		$output .= HTML\beans_open_markup(
			'beans_next_icon[_post_navigation]',
			'span',
			array(
				'class'       => 'uk-icon-angle-double-right uk-margin-small-left',
				'aria-hidden' => 'true',
			)
		);

		$output .= HTML\beans_close_markup( 'beans_next_icon[_post_navigation]', 'span' );

	$output .= HTML\beans_close_markup( 'beans_next_link[_post_navigation]', 'a' );

	return $output;
}

Actions\beans_add_smart_action( 'beans_post_after_markup', __NAMESPACE__ . '\beans_post_navigation' );
/**
 * Echo post navigation.
 *
 * @since 1.0.0
 *
 * @return void
 *
 * phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact -- Layout mirrors HTML markup
 */
function beans_post_navigation() {

	/**
	 * Filter whether {@see beans_post_navigation()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'beans_pre_post_navigation', ! is_singular( 'post' ) ) ) {
		return;
	}

	$previous = is_attachment() ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	HTML\beans_open_markup_e(
		'beans_post_navigation_nav_container',
		'nav',
		array(
			'role'       => 'navigation',
			'aria-label' => __( 'Pagination Navigation', 'beans' ), // Attributes are automatically escaped.
		)
	);

		HTML\beans_open_markup_e(
			'beans_post_navigation',
			'ul',
			array(
				'class' => 'uk-pagination',
			)
		);

		if ( $previous ) {
			HTML\beans_open_markup_e( 'beans_post_navigation_item[_previous]', 'li', array( 'class' => 'uk-pagination-previous' ) );

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
				echo get_previous_post_link(
					'%link',
					HTML\beans_output( 'beans_previous_text[_post_navigation_item]', __( 'Previous Page', 'beans' ) )
				);

			HTML\beans_close_markup_e( 'beans_post_navigation_item[_previous]', 'li' );
		}

		if ( $next ) {
			HTML\beans_open_markup_e( 'beans_post_navigation_item[_next]', 'li', array( 'class' => 'uk-pagination-next' ) );

				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML output.
				echo get_next_post_link(
					'%link',
					HTML\beans_output( 'beans_next_text[_post_navigation_item]', __( 'Next Page', 'beans' ) )
				);

			HTML\beans_close_markup_e( 'beans_post_navigation_item[_next]', 'li' );
		}

		HTML\beans_close_markup_e( 'beans_post_navigation', 'ul' );

	HTML\beans_close_markup_e( 'beans_post_navigation_nav_container', 'nav' );
}

Actions\beans_add_smart_action( 'beans_after_posts_loop', __NAMESPACE__ . '\beans_posts_pagination' );
/**
 * Echo posts pagination.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_posts_pagination() {

	/**
	 * Filter whether {@see beans_posts_pagination()} should be short-circuit or not.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $pre True to short-circuit, False to let the function run.
	 */
	if ( apply_filters( 'beans_pre_post_pagination', is_singular() ) ) {
		return;
	}

	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$current = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$count   = (int) $wp_query->max_num_pages;

	HTML\beans_open_markup_e(
		'beans_posts_pagination_nav_container',
		'nav',
		array(
			'role'       => 'navigation',
			'aria-label' => __( 'Posts Pagination Navigation', 'beans' ), // Attributes are automatically escaped.
		)
	);

		HTML\beans_open_markup_e(
			'beans_posts_pagination',
			'ul',
			array(
				'class' => 'uk-pagination uk-grid-margin',
			)
		);

		// Previous.
		if ( get_previous_posts_link() ) {
			HTML\beans_open_markup_e( 'beans_posts_pagination_item[_previous]', 'li' );

				HTML\beans_open_markup_e(
					'beans_previous_link[_posts_pagination]',
					'a',
					array(
						'href' => previous_posts( false ), // Attributes are automatically escaped.
					),
					$current
				);

					HTML\beans_open_markup_e(
						'beans_previous_icon[_posts_pagination]',
						'span',
						array(
							'class'       => 'uk-icon-angle-double-left uk-margin-small-right',
							'aria-hidden' => 'true',
						)
					);

					HTML\beans_close_markup_e( 'beans_previous_icon[_posts_pagination]', 'span' );

					HTML\beans_output_e( 'beans_previous_text[_posts_pagination]', esc_html__( 'Previous Page', 'beans' ) );

				HTML\beans_close_markup_e( 'beans_previous_link[_posts_pagination]', 'a' );

			HTML\beans_close_markup_e( 'beans_posts_pagination_item[_previous]', 'li' );
		}

		// Links.
		foreach ( range( 1, (int) $wp_query->max_num_pages ) as $link ) {

			// Skip if next is set.
			if ( isset( $next ) && $link !== $next ) {
				continue;
			}

			$next = $link + 1;

			$is_separator = array(
				1 !== $link, // Not first.
				! (1 === $current && 3 === $link), // Force first 3 items.
				$count > 3, // More.
				$count !== $link, // Not last.
				( $current - 1 ) !== $link, // Not previous.
				$current !== $link, // Not current.
				( $current + 1 ) !== $link, // Not next.
			);

			// Separator.
			if ( ! in_array( false, $is_separator, true ) ) {
				HTML\beans_open_markup_e( 'beans_posts_pagination_item[_separator]', 'li' );

					HTML\beans_output_e( 'beans_posts_pagination_item_separator_text', '...' );

				HTML\beans_close_markup_e( 'beans_posts_pagination_item[_separator]', 'li' );

				// Jump.
				if ( $link < $current ) {
					$next = $current - 1;
				} elseif ( $link > $current ) {
					$next = $count;
				}

				continue;
			}

			// Integer.
			if ( $link === $current ) {
				HTML\beans_open_markup_e( 'beans_posts_pagination_item[_active]', 'li', array( 'class' => 'uk-active' ) );

					HTML\beans_open_markup_e( 'beans_posts_pagination_item[_active]_wrap', 'span' );

						HTML\beans_output_e( 'beans_posts_pagination_item[_active]_text', $link );

					HTML\beans_close_markup_e( 'beans_posts_pagination_item[_active]_wrap', 'span' );

				HTML\beans_close_markup_e( 'beans_posts_pagination_item[_active]', 'li' );
			} else {
				HTML\beans_open_markup_e( 'beans_posts_pagination_item', 'li' );

					HTML\beans_open_markup_e(
						'beans_posts_pagination_item_link',
						'a',
						array(
							'href' => get_pagenum_link( $link ), // Attributes are automatically escaped.
						),
						$link
					);

						HTML\beans_output_e( 'beans_posts_pagination_item_link_text', $link );

					HTML\beans_close_markup_e( 'beans_posts_pagination_item_link', 'a' );

				HTML\beans_close_markup_e( 'beans_posts_pagination_item', 'li' );
			}
		}

		// Next.
		if ( get_next_posts_link() ) {
			HTML\beans_open_markup_e( 'beans_posts_pagination_item[_next]', 'li' );

				HTML\beans_open_markup_e(
					'beans_next_link[_posts_pagination]',
					'a',
					array(
						'href' => next_posts( $count, false ), // Attributes are automatically escaped.
					),
					$current
				);

					HTML\beans_output_e( 'beans_next_text[_posts_pagination]', esc_html__( 'Next Page', 'beans' ) );

					HTML\beans_open_markup_e(
						'beans_next_icon[_posts_pagination]',
						'span',
						array(
							'class'       => 'uk-icon-angle-double-right uk-margin-small-left',
							'aria-hidden' => 'true',
						)
					);

					HTML\beans_close_markup_e( 'beans_next_icon[_posts_pagination]', 'span' );

				HTML\beans_close_markup_e( 'beans_next_link[_posts_pagination]', 'a' );

			HTML\beans_close_markup_e( 'beans_posts_pagination_item[_next]', 'li' );
		}

		HTML\beans_close_markup_e( 'beans_posts_pagination', 'ul' );

	HTML\beans_close_markup_e( 'beans_posts_pagination_nav_container', 'nav' );
}

Actions\beans_add_smart_action( 'beans_no_post', __NAMESPACE__ . '\beans_no_post' );
/**
 * Echo no post content.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_no_post() {
	HTML\beans_open_markup_e( 'beans_post', 'article', array( 'class' => 'tm-no-article uk-article' . ( current_theme_supports( 'beans-default-styling' ) ? ' uk-panel-box' : null ) ) );

		HTML\beans_open_markup_e( 'beans_post_header', 'header' );

			HTML\beans_open_markup_e( 'beans_post_title', 'h1', array( 'class' => 'uk-article-title' ) );

				HTML\beans_output_e( 'beans_no_post_article_title_text', esc_html__( 'Whoops, no result found!', 'beans' ) );

			HTML\beans_close_markup_e( 'beans_post_title', 'h1' );

		HTML\beans_close_markup_e( 'beans_post_header', 'header' );

		HTML\beans_open_markup_e( 'beans_post_body', 'div' );

			HTML\beans_open_markup_e( 'beans_post_content', 'div', array( 'class' => 'tm-article-content' ) );

				HTML\beans_open_markup_e( 'beans_no_post_article_content', 'p', array( 'class' => 'uk-alert uk-alert-warning' ) );

					HTML\beans_output_e( 'beans_no_post_article_content_text', esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'beans' ) );

				HTML\beans_close_markup_e( 'beans_no_post_article_content', 'p' );

					HTML\beans_output_e( 'beans_no_post_search_form', get_search_form( false ) );

			HTML\beans_close_markup_e( 'beans_post_content', 'div' );

		HTML\beans_close_markup_e( 'beans_post_body', 'div' );

	HTML\beans_close_markup_e( 'beans_post', 'article' );
}

// Filter.
Actions\beans_add_smart_action( 'the_password_form', __NAMESPACE__ . '\beans_post_password_form' );
/**
 * Modify password protected form.
 *
 * @since 1.0.0
 *
 * @return string The form.
 */
function beans_post_password_form() {
	global $post;

	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID ); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand -- Used as fallback in a label when no post ID exists.

	// Notice.
	$output = HTML\beans_open_markup( 'beans_password_form_notice', 'p', array( 'class' => 'uk-alert uk-alert-warning' ) );

		$output .= HTML\beans_output( 'beans_password_form_notice_text', esc_html__( 'This post is protected. To view it, enter the password below!', 'beans' ) );

	$output .= HTML\beans_close_markup( 'beans_password_form_notice', 'p' );

	// Form.
	$output .= HTML\beans_open_markup(
		'beans_password_form',
		'form',
		array(
			'class'  => 'uk-form uk-margin-bottom',
			'method' => 'post',
			'action' => site_url( 'wp-login.php?action=postpass', 'login_post' ), // Attributes are automatically escaped.
		)
	);

		$output .= HTML\beans_selfclose_markup(
			'beans_password_form_input',
			'input',
			array(
				'class'       => 'uk-margin-small-top uk-margin-small-right',
				'type'        => 'password',
				'placeholder' => apply_filters( 'beans_password_form_input_placeholder', __( 'Password', 'beans' ) ), // Attributes are automatically escaped.
				'name'        => 'post_password',
			)
		);

		$output .= HTML\beans_selfclose_markup(
			'beans_password_form_submit',
			'input',
			array(
				'class' => 'uk-button uk-margin-small-top',
				'type'  => 'submit',
				'name'  => 'submit',
				'value' => apply_filters( 'beans_password_form_submit_text', __( 'Submit', 'beans' ) ), // Attributes are automatically escaped.
			)
		);

	$output .= HTML\beans_close_markup( 'beans_password_form', 'form' );

	return $output;
}

// Filter.
Actions\beans_add_smart_action( 'post_gallery', __NAMESPACE__ . '\beans_post_gallery', 10, 3 );
/**
 * Modify WP {@link https://codex.wordpress.org/Function_Reference/gallery_shortcode Gallery Shortcode} output.
 *
 * This implements the functionality of the Gallery Shortcode for displaying WordPress images in a post.
 *
 * @since 1.3.0
 *
 * @param string $output   The gallery output. Default empty.
 * @param array  $attr     Attributes of the {@link https://codex.wordpress.org/Function_Reference/gallery_shortcode gallery_shortcode()}.
 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
 *
 * @return string HTML content to display gallery.
 */
function beans_post_gallery( $output, $attr, $instance ) {
	$post     = get_post();
	$html5    = current_theme_supports( 'html5', 'gallery' );
	$defaults = array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'itemtag'    => $html5 ? 'figure' : 'dl',
		'icontag'    => $html5 ? 'div' : 'dt',
		'captiontag' => $html5 ? 'figcaption' : 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => '',
	);
	$atts     = shortcode_atts( $defaults, $attr, 'gallery' );
	$id       = (int) $atts['id'];

	// Set attachments.
	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts(
			array(
				'include'        => $atts['include'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);

		$attachments = array();

		foreach ( $_attachments as $key => $val ) {
			$attachments[ $val->ID ] = $_attachments[ $key ];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	} else {
		$attachments = get_children(
			array(
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby'],
			)
		);
	}

	// Stop here if no attachment.
	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";

		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}

		return $output;
	}

	// Valid tags.
	$valid_tags = wp_kses_allowed_html( 'post' );
	$validate   = array(
		'itemtag',
		'captiontag',
		'icontag',
	);

	// Validate tags.
	foreach ( $validate as $tag ) {
		if ( ! isset( $valid_tags[ $atts[ $tag ] ] ) ) {
			$atts[ $tag ] = $defaults[ $tag ];
		}
	}

	// Set variables used in the output.
	$columns    = (int) $atts['columns'];
	$size_class = sanitize_html_class( $atts['size'] );

	// WP adds the opening div in the gallery_style filter (weird), so we follow it as we don't want to break people's site.
	$gallery_div = HTML\beans_open_markup(
		"beans_post_gallery[_{$id}]",
		'div',
		array(
			'class'               => "uk-grid uk-grid-width-small-1-{$columns} gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}", // Attributes are automatically escaped.
			'data-uk-grid-margin' => false,
		),
		$id,
		$columns
	);

	/**
	 * Apply WP core filter. Filter the default gallery shortcode CSS styles.
	 *
	 * Documented in WordPress.
	 *
	 * @ignore
	 */
	$output = apply_filters( 'gallery_style', $gallery_div ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Used in function scope.

		$i = 0; foreach ( $attachments as $attachment_id => $attachment ) {

			$attr        = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "gallery-{$instance}-{$id}" ) : '';
			$image_meta  = wp_get_attachment_metadata( $attachment_id );
			$orientation = '';

		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}

		// Set the image output.
		if ( 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $attachment_id, $atts['size'], false, $attr );
		} else {
			$image_output = wp_get_attachment_link( $attachment_id, $atts['size'], ( 'file' !== $atts['link'] ), false, false, $attr );
		}

			$output .= HTML\beans_open_markup( "beans_post_gallery_item[_{$attachment_id}]", $atts['itemtag'], array( 'class' => 'gallery-item' ) );

				$output .= HTML\beans_open_markup( "beans_post_gallery_icon[_{$attachment_id}]", $atts['icontag'], array( 'class' => "gallery-icon {$orientation}" ) ); // Attributes are automatically escaped.

					$output .= HTML\beans_output( "beans_post_gallery_icon[_{$attachment_id}]", $image_output, $attachment_id, $atts );

				$output .= HTML\beans_close_markup( "beans_post_gallery_icon[_{$attachment_id}]", $atts['icontag'] );

		if ( $atts['captiontag'] && trim( $attachment->post_excerpt ) ) {
			$output .= HTML\beans_open_markup( "beans_post_gallery_caption[_{$attachment_id}]", $atts['captiontag'], array( 'class' => 'wp-caption-text gallery-caption' ) );

				$output .= HTML\beans_output( "beans_post_gallery_caption_text[_{$attachment_id}]", wptexturize( $attachment->post_excerpt ) );

			$output .= HTML\beans_close_markup( "beans_post_gallery_caption[_{$attachment_id}]", $atts['captiontag'] );
		}

			$output .= HTML\beans_close_markup( "beans_post_gallery_item[_{$attachment_id}]", $atts['itemtag'] );
		}

		$output .= HTML\beans_close_markup( "beans_post_gallery[_{$id}]", 'div' );

		return $output;
}
