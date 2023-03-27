# Origins - The LPD Boilerplate Theme

TODO
- [ ] Add Tests
- [ ] Build the origins class to handle the theme functionality. Call it OG.
- [ ] Revisit Block Functionality To Determine What Can Be Done To Make It More Flexible. Potentially load in the styles and scripts for the block from the block folder itself.



Blocks: 

Example functionality of blocks and how they work in the theme.

blocks/test-block
- test-block.twig
  - This is the presentation layer that is used to render the block on the front end.
- test-block-example.twig
  - This is a representational preview of the block that is seen when hovering over the block from the selection panel.
  - It can take data, but for simplicity use the following:
  ```twig
  <img src="{{ theme.path }}/images/examples/large-header.png"
	alt=""
	class="w-[500px]" />
    ```
- test-block-preview.twig
  - This is the preview of the block that is seen within the editor after placing it in the editor.
- test-block-controller.php
  - This is the controller for the block that is used to add additional data to the block. 
```php
  <?php
/* 
This is an example of a controller that adds a list of categories to the block 

The block_data_controller function must be used and the file name must be the same as the block name. 
*/
$project_categories = get_terms( 'category' );

$project_categories = array_map(
	function ($category) {
		return array(
			'name' => $category->name,
			'link' => get_term_link( $category ),
		);
	},
	$project_categories
);

block_data_controller( $project_categories );
```


NPM Run Commands:

- npm run prod
  - compiles and minifies the assets
- npm run dev
  - compiles the assets
- npm run watch
  - uses browsersync to watch for changes in the theme and automatically reload the browser, and compile the assets
- npm run bundle
  - runs a production build of the theme and zips the contents
- npm run create-block
  - creates a new block in the blocks directory with the name provided
- npm run lint
  - runs eslint and prettier on the theme for linting and formatting
- npm run lint-fix
    - fixes any linting/formatting issues that can be fixed automatically




