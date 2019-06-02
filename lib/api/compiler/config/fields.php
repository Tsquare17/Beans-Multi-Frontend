<?php

namespace Beans\Framework\API\Compiler;

/**
 * Runtime fields configuration parameters.
 *
 * @package Beans\Framework\API\Compiler
 *
 * @since   1.5.0
 */

return array(
	'beans_compiler_items'            => array(
		'id'          => 'beans_compiler_items',
		'type'        => 'flush_cache',
		'description' => __( 'Clear CSS and Javascript cached files. New cached versions will be compiled on page load.', 'beans' ),
	),
	'beans_compile_all_styles'        => array(
		'id'             => 'beans_compile_all_styles',
		'label'          => __( 'Compile all WordPress styles', 'beans' ),
		'checkbox_label' => __( 'Select to compile styles.', 'beans' ),
		'type'           => 'checkbox',
		'default'        => false,
		'description'    => __( 'Compile and cache all the CSS files that have been enqueued to the WordPress head.', 'beans' ),
	),
	'beans_compile_all_scripts_group' => array(
		'id'          => 'beans_compile_all_scripts_group',
		'label'       => __( 'Compile all WordPress scripts', 'beans' ),
		'type'        => 'group',
		'fields'      => array(
			array(
				'id'      => 'beans_compile_all_scripts',
				'type'    => 'activation',
				'label'   => __( 'Select to compile scripts.', 'beans' ),
				'default' => false,
			),
			array(
				'id'      => 'beans_compile_all_scripts_mode',
				'type'    => 'select',
				'label'   => __( 'Choose the level of compilation.', 'beans' ),
				'default' => 'aggressive',
				'options' => array(
					'aggressive' => __( 'Aggressive', 'beans' ),
					'standard'   => __( 'Standard', 'beans' ),
				),
			),
		),
		'description' => __( 'Compile and cache all the JavaScript files that have been enqueued to the WordPress head. <br/> JavaScript is outputted in the footer if the level is set to <strong>Aggressive</strong> and might conflict with some third-party plugins which are not following WordPress standards.', 'beans' ),
	),
);
