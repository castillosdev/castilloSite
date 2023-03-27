<?php

/**
 * origins functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package origins
 */



$parentdir = realpath( dirname( __FILE__ ) . '/..' );
$composer_autoload = $parentdir . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'layouts', 'components' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles_and_scripts' ) );
		add_editor_style( 'style-editor.css' );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'acf/init', [ $this, 'register_options_page' ] );
		add_action( 'after_setup_theme', [ $this, 'register_custom_routes' ] );
		add_action( 'init', [ $this, 'add_filters' ] );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {
	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {
	}

	public function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'Site Settings',
					'menu_title' => 'Site Settings',
					'menu_slug' => 'site-settings',
					'capability' => 'edit_posts',
					'redirect' => false
				)
			);
		}
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['mainMenu'] = new Timber\Menu( 'Primary Navigation' );
		$context['footerMenu'] = new Timber\Menu( 'Footer Navigation' );
		$context['site'] = $this;
		$context['options'] = get_fields( 'option' );
		$context['logo'] = new Timber\Image( get_field( 'business_logo', 'option' ) );

		return $context;
	}

	public function theme_supports() {

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
				'style'
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

	/**
	 * Add the block editor class to TinyMCE.
	 *
	 * This allows TinyMCE to use Tailwind Typography styles.
	 *
	 * @param array $settings TinyMCE settings.
	 * @return array
	 */
	function origins_tinymce_add_class( $settings ) {
		$settings['body_class'] = 'block-editor-block-list__layout';
		return $settings;
	}

	public function add_filters() {
		global $wp_version;
		add_filter( 'block_categories' . ( version_compare( $wp_version, '5.8', '>=' ) ? '_all' : '' ), array( $this, 'origins_block_categories' ), 99 );
	}

	private function origins_block_categories( $existingCategories ) {
		$customCategories = [ 
			[ 
				'slug' => 'origins-blocks',
				'title' => __( 'Origins Blocks', 'domain' ),
			]
		];
		return array_merge( $customCategories, $existingCategories );
	}

	public function styles_and_scripts() {
		// Enqueue editor styles.
		wp_enqueue_style( 'origins-style', get_stylesheet_uri(), array(), 1.0 );
		wp_enqueue_script( 'origins-script', get_template_directory_uri() . '/js/script.min.js', array(), 1.0, true );
	}
}

new StarterSite();


require_once( 'php-inc/timber-acf-wp-blocks.php' );

function block_data_controller( $data ) {
	$caller = debug_backtrace()[0]['file'];
	$slug = str_replace( '-controller.php', '', basename( $caller ) );
	add_filter( 'timber/acf-gutenberg-blocks-data/' . $slug, function ($context) use ($data) {
		$context['fields']['model'] = $data;
		return $context;
	} );
}

// Get all the files in the blocks subdirectories that have -controller.php in the filename.
$controllers = glob( get_template_directory() . '/blocks/**/*-controller.php' );

// Loop through the files and require them.

for ( $i = 0; $i < count( $controllers ); $i++ ) {
	include_once( $controllers[ $i ] );
}