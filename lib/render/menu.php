<?php

namespace Beans\Framework\Render;

use Beans\Framework\API\Widgets;
use Beans\Framework\API\Actions;
use Beans\Framework\API\Utilities;
use Beans\Framework\API\HTML;

/**
 * Sets up the Beans menus.
 *
 * @package Beans\Framework\Render
 *
 * @since   1.0.0
 */

Actions\beans_add_smart_action( 'after_setup_theme', __NAMESPACE__ . '\beans_do_register_default_menu' );
/**
 * Register default menu.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_do_register_default_menu() {

	// Stop here if a menu already exists.
	if ( wp_get_nav_menus() ) {
		return;
	}

	$name = __( 'Navigation', 'beans' );

	// Set up a default menu if it doesn't exist.
	if ( ! wp_get_nav_menu_object( $name ) ) {
		wp_update_nav_menu_item(
			wp_create_nav_menu( $name ),
			0,
			array(
				'menu-item-title'   => __( 'Home', 'beans' ),
				'menu-item-classes' => 'home',
				'menu-item-url'     => home_url( '/' ),
				'menu-item-status'  => 'publish',
			)
		);
	}
}

Actions\beans_add_smart_action( 'after_setup_theme', __NAMESPACE__ . '\beans_do_register_nav_menus' );
/**
 * Register nav menus.
 *
 * @since 1.0.0
 *
 * @return void
 */
function beans_do_register_nav_menus() {
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'beans' ),
		)
	);
}

// Filter.
Actions\beans_add_smart_action( 'wp_nav_menu_args', __NAMESPACE__ . '\beans_modify_menu_args' );
/**
 * Modify wp_nav_menu arguments.
 *
 * This function converts the wp_nav_menu to UIkit format. It uses the Beans custom walker and also makes
 * use of the Beans HTML API.
 *
 * @since 1.0.0
 *
 * @param array $args The wp_nav_menu arguments.
 *
 * @return array The modified wp_nav_menu arguments.
 */
function beans_modify_menu_args( $args ) {
	// Get type.
	$type = Utilities\beans_get( 'beans_type', $args );

	// Check if the menu is in a widget area and set the type accordingly if it is defined.
	$widget_area_type = Widgets\beans_get_widget_area( 'beans_type' );

	if ( $widget_area_type ) {
		$type = 'stack' === $widget_area_type ? 'sidenav' : $widget_area_type;
	}

	// Stop if it isn't a Beans menu.
	if ( ! $type ) {
		return $args;
	}

	// Default item wrap attributes.
	$attr = array(
		'id'    => '%1$s',
		'class' => array( Utilities\beans_get( 'menu_class', $args ) ),
	);

	// Add UIkit navbar item wrap attributes.
	if ( 'navbar' === $type ) {
		$attr['class'][] = 'uk-navbar-nav';
	}

	// Add UIkit sidenav item wrap attributes.
	if ( 'sidenav' === $type ) {
		$attr['class'][]     = 'uk-nav uk-nav-parent-icon uk-nav-side';
		$attr['data-uk-nav'] = '{multiple:true}';
	}

	// Add UIkit offcanvas item wrap attributes.
	if ( 'offcanvas' === $type ) {
		$attr['class'][]     = 'uk-nav uk-nav-parent-icon uk-nav-offcanvas';
		$attr['data-uk-nav'] = '{multiple:true}';
	}

	// Implode to avoid empty spaces.
	$attr['class'] = implode( ' ', array_filter( $attr['class'] ) );

	// Set to null if empty to avoid outputing an empty HTML class attribute.
	if ( ! $attr['class'] ) {
		$attr['class'] = null;
	}

	$location = Utilities\beans_get( 'theme_location', $args );

	$location_subfilter = $location ? "[_{$location}]" : null;

	// Force Beans menu arguments.
	$force = array(
		'beans_type' => $type,
		'items_wrap' => HTML\beans_open_markup( "beans_menu[_{$type}]{$location_subfilter}", 'ul', $attr, $args ) .
			'%3$s' . HTML\beans_close_markup( "beans_menu[_{$type}]{$location_subfilter}", 'ul', $args ),
	);

	// Allow walker overwrite.
	if ( ! Utilities\beans_get( 'walker', $args ) ) {
		$args['walker'] = new _Beans_Walker_Nav_Menu();
	}

	// Adapt level to walker depth.
	$level = Utilities\beans_get( 'beans_start_level', $args );

	$force['beans_start_level'] = $level ? $level - 1 : 0;

	return array_merge( $args, $force );
}
