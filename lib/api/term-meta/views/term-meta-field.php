<?php

namespace Beans\Framework\API\Term_Meta;

use Beans\Framework\API\Fields;

/**
 * View file for rendering a term meta field.
 *
 * @package Beans\Framework\API\Term_Meta
 *
 * @since   1.0.0
 * @since   1.5.0 Moved to view file.
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect, Generic.WhiteSpace.ScopeIndent.IncorrectExact -- View file is indented for HTML structure.
?>
<tr class="form-field">
	<th scope="row">
		<?php beans_field_label( $field ); ?>
	</th>
	<td>
		<?php Fields\beans_field( $field ); ?>
	</td>
</tr>
