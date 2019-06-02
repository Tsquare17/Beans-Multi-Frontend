<?php

namespace Beans\Framework\API;

use Beans\Framework\API\Options;
use Beans\Framework\API\Utilities;

/**
 * This class build the Beans admin page.
 *
 * @package Beans\Framework\API
 *
 * @since 1.0.0
 */

/**
 * Beans admin page.
 *
 * @since   1.0.0
 * @ignore
 * @access  private
 *
 * @package Beans\Framework\API
 */
final class _Beans_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 150 );
		add_action( 'admin_init', array( $this, 'register' ), 20 );
	}

	/**
	 * Add Beans' menu.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_theme_page( __( 'Settings', 'beans' ), __( 'Settings', 'beans' ), 'manage_options', 'beans_settings', array( $this, 'display_screen' ) );
	}

	/**
	 * Beans options page content.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_screen() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Beans Settings', 'beans' ); ?><span style="float: right; font-size: 12px; color: #555;"><?php esc_html_e( 'Version ', 'beans' ); ?><?php echo esc_attr( BEANS_VERSION ); ?></span></h1>
			<?php Options\beans_options( 'beans_settings' ); ?>
		</div>
		<?php
	}

	/**
	 * Register options.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		global $wp_meta_boxes;

		$fields = array(
			array(
				'id'             => 'beans_dev_mode',
				'label'          => __( 'Enable development mode', 'beans' ),
				'checkbox_label' => __( 'Select to activate development mode.', 'beans' ),
				'type'           => 'checkbox',
				'description'    => __( 'This option should be enabled while your website is in development.', 'beans' ),
			),
		);

		Options\beans_register_options(
			$fields,
			'beans_settings',
			'mode_options',
			array(
				'title'   => __( 'Mode options', 'beans' ),
				'context' => Utilities\beans_get( 'beans_settings', $wp_meta_boxes ) ? 'column' : 'normal', // Check for other beans boxes.
			)
		);
	}
}

new _Beans_Admin();
