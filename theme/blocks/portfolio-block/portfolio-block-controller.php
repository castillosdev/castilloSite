<?php
/**
 * Utilize the block_data_controller() function to add additional data to your ACF block.
 * Process the data and pass it to block_data_controller().
 * Ensure the data passed is how you want it to be used in the block.
 * The new data will be added to $context['fields']['model'] in the block.
 * It can be seen by adding {{ dump(fields.model) }} to the block template.
 * Example usage:
 * $data = [
 *     'foo' => 'bar',
 *    'bar' => 'foo'
 * ];
 * block_data_controller( $data );
 */