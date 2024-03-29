<?php

namespace  Beans\Framework\API\Compiler;

/**
 * View file for the cache flush button.
 *
 * @package Beans\Framework\API\Compiler
 *
 * @since   1.0.0
 * @since   1.5.0 Moved to view file.
 */

?>
<input type="submit" name="beans_flush_compiler_cache" value="<?php esc_html_e( 'Flush assets cache', 'beans' ); ?>" class="button-secondary" />
