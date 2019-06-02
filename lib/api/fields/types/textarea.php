<?php

namespace Beans\Framework\API\Fields\Types;

use Beans\Framework\API\Actions;
use Beans\Framework\API\Utilities;

/**
 * Hanlder for rendering the textarea field.
 *
 * @package Beans\Framework\API\Fields\Type
 */

Actions\beans_add_smart_action( 'beans_field_textarea', __NAMESPACE__ . '\beans_field_textarea' );
/**
 * Render the textarea field.
 *
 * @since 1.0.0
 *
 * @param array $field      {
 *      For best practices, pass the array of data obtained using {@see beans_get_fields()}.
 *
 * @type mixed  $value      The field's current value.
 * @type string $name       The field's "name" value.
 * @type array  $attributes An array of attributes to add to the field. The array's key defines the attribute name
 *                          and the array's value defines the attribute value. Default is an empty array.
 * @type mixed  $default    The default value. Default false.
 * }
 */
function beans_field_textarea( array $field ) {
	printf(
		'<textarea id="%s" name="%s" %s>%s</textarea>',
		esc_attr( $field['id'] ),
		esc_attr( $field['name'] ),
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaping is handled in the function.
		Utilities\beans_esc_attributes( $field['attributes'] ),
		esc_textarea( $field['value'] )
	);
}
