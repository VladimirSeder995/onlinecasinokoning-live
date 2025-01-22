<?php
/**
 * Zakra functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zakra
 */

include "CasinoApp/vendor/autoload.php";

if ( ! function_exists( 'zakra_setup' ) ) :
	// Sets up theme defaults and registers support for various WordPress features.
	function zakra_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'zakra', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Register menu.
		register_nav_menus(
			array(
				'menu-primary' => esc_html__( 'Primary', 'zakra' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'width'       => 170,
				'height'      => 60,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Custom background support.
		add_theme_support( 'custom-background' );

		// Gutenberg Wide/fullwidth support.
		add_theme_support( 'align-wide' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// AMP support.
		if ( defined( 'AMP__VERSION' ) && ( ! version_compare( AMP__VERSION, '1.0.0', '<' ) ) ) {
			add_theme_support(
				'amp',
				apply_filters(
					'zakra_amp_support_filter',
					array(
						'paired' => true,
					)
				)
			);
		}
	}
endif;
add_action( 'after_setup_theme', 'zakra_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function zakra_widgets_init() {
	$sidebars = apply_filters(
		'zakra_sidebars_args',
		array(
			'sidebar-right'            => esc_html__( 'Sidebar Right', 'zakra' ),
			'sidebar-left'             => esc_html__( 'Sidebar Left', 'zakra' ),
			'header-top-left-sidebar'  => esc_html__( 'Header Top Bar Left Sidebar', 'zakra' ),
			'header-top-right-sidebar' => esc_html__( 'Header Top Bar Right Sidebar', 'zakra' ),
			'footer-sidebar-1'         => esc_html__( 'Footer One', 'zakra' ),
			'footer-sidebar-2'         => esc_html__( 'Footer Two', 'zakra' ),
			'footer-sidebar-3'         => esc_html__( 'Footer Three', 'zakra' ),
			'footer-sidebar-4'         => esc_html__( 'Footer Four', 'zakra' ),
			'footer-bar-left-sidebar'  => esc_html__( 'Footer Bottom Bar Left Sidebar', 'zakra' ),
			'footer-bar-right-sidebar' => esc_html__( 'Footer Bottom Bar Right Sidebar', 'zakra' ),
		)
	);

	if ( zakra_is_woocommerce_active() ) {
		$sidebars['wc-left-sidebar']  = esc_html__( 'WooCommerce Left Sidebar', 'zakra' );
		$sidebars['wc-right-sidebar'] = esc_html__( 'WooCommerce Right Sidebar', 'zakra' );
	}

	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			apply_filters(
				'zakra_sidebars_widget_args',
				array(
					'id'            => $id,
					'name'          => $name,
					'description'   => esc_html__( 'Add widgets here.', 'zakra' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widget-title">',
					'after_title'   => '</h2>',
				)
			)
		);
	}
}

add_action( 'widgets_init', 'zakra_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function zakra_scripts() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/**
	 * Styles.
	 */
	// Font Awesome 4.
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/assets/lib/font-awesome/css/font-awesome' . $suffix . '.css', false, '4.7.0' );
	wp_enqueue_style( 'font-awesome' );

	// Theme style.
	wp_register_style( 'zakra-style', get_stylesheet_uri(), [], time() );
	wp_enqueue_style( 'zakra-style' );

	// Theme style.
	wp_register_style( 'zakra-style-backend', get_stylesheet_directory_uri() . '/style-backend.css', [], time() );
	wp_enqueue_style( 'zakra-style-backend' );

	// Support RTL.
	wp_style_add_data( 'zakra-style', 'rtl', 'replace' );

	/**
	 * Inline CSS for this theme.
	 */
	add_filter( 'zakra_dynamic_theme_css', array( 'Zakra_Dynamic_CSS', 'render_output' ) );

	// Enqueue required Google font for the theme.
	Zakra_Generate_Fonts::render_fonts();

	// Generate dynamic CSS to add inline styles for the theme.
	$theme_dynamic_css = apply_filters( 'zakra_dynamic_theme_css', '' );

	if ( zakra_is_zakra_pro_active() ) {
		wp_add_inline_style( 'zakra-pro', $theme_dynamic_css );
	} else {
		wp_add_inline_style( 'zakra-style', $theme_dynamic_css );
	}

	// Do not load scripts if AMP.
	if ( zakra_is_amp() ) {
		return;
	}

	/**
	 * Scripts.
	 */
	wp_enqueue_script( 'zakra-navigation', get_template_directory_uri() . '/assets/js/navigation' . $suffix . '.js', array(), '20151215', true );
	wp_enqueue_script( 'zakra-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . $suffix . '.js', array(), '20151215', true );

	// Theme JavaScript.
	wp_enqueue_script( 'zakra-custom', get_template_directory_uri() . '/assets/js/zakra-custom' . $suffix . '.js', array(), false, true );
	wp_enqueue_script( 'zakra-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'zakra_scripts' );

function zakra_custom_scripts_and_styles() {

	$theme_options = array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	);
	
	wp_localize_script( 'zakra-scripts', 'theme', $theme_options );
	
}
add_action('wp_enqueue_scripts', 'zakra_custom_scripts_and_styles');

/**
 * Enqueue block editor styles.
 *
 * @since Zakra 1.4.3
 */
function zakra_block_editor_styles() {
	wp_enqueue_style( 'zakra-block-editor-styles', get_template_directory_uri() . '/style-editor-block.css' );
}
add_action( 'enqueue_block_editor_assets', 'zakra_block_editor_styles', 1, 1 );

/**
 * Define constants.
 */
define( 'ZAKRA_PARENT_DIR', get_template_directory() );
define( 'ZAKRA_PARENT_URI', get_template_directory_uri() );
define( 'ZAKRA_PARENT_INC_DIR', ZAKRA_PARENT_DIR . '/inc' );
define( 'ZAKRA_PARENT_INC_URI', ZAKRA_PARENT_URI . '/inc' );
define( 'ZAKRA_PARENT_INC_ICON_URI', ZAKRA_PARENT_URI . '/assets/img/icons' );
define( 'ZAKRA_PARENT_CUSTOMIZER_DIR', ZAKRA_PARENT_INC_DIR . '/customizer' );

// Theme version.
$zakra_theme = wp_get_theme( 'zakra' );
define( 'ZAKRA_THEME_VERSION', $zakra_theme->get( 'Version' ) );

// AMP support files.
if ( defined( 'AMP__VERSION' ) && ( ! version_compare( AMP__VERSION, '1.0.0', '<' ) ) ) {
	require_once ZAKRA_PARENT_INC_DIR . '/compatibility/amp/class-zakra-amp.php';
}

/**
 * Include files.
 */
require ZAKRA_PARENT_INC_DIR . '/helpers.php';
require ZAKRA_PARENT_INC_DIR . '/custom-header.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-dynamic-filter.php';
require ZAKRA_PARENT_INC_DIR . '/template-tags.php';
require ZAKRA_PARENT_INC_DIR . '/template-functions.php';
require ZAKRA_PARENT_INC_DIR . '/customizer/class-zakra-customizer.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-css-classes.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-dynamic-css.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-migration.php';

// Load Jetpack compatibility file.
if ( defined( 'JETPACK__VERSION' ) ) {
	require ZAKRA_PARENT_INC_DIR . '/class-zakra-jetpack.php';
}

// WooCommerce hooks and functions.
if ( class_exists( 'WooCommerce' ) ) {
	require ZAKRA_PARENT_INC_DIR . '/compatibility/woocommerce/class-zakra-woocommerce.php';
}

// Load hooks.
require ZAKRA_PARENT_INC_DIR . '/hooks/hooks.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/header.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/footer.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/content.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/customize.php';

// Breadcrumbs class.
require_once ZAKRA_PARENT_INC_DIR . '/class-breadcrumb-trail.php';

// Elementor Pro compatibility.
require_once ZAKRA_PARENT_INC_DIR . '/compatibility/elementor/class-zakra-elementor-pro.php';

// Admin screen.
if ( is_admin() ) {
	// Meta boxes.
	require ZAKRA_PARENT_INC_DIR . '/meta-boxes/class-zakra-meta-box-page-settings.php';
	require ZAKRA_PARENT_INC_DIR . '/meta-boxes/class-zakra-meta-box.php';

	// Theme options page.
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-admin.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-notice.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-welcome-notice.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-upgrade-notice.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-dashboard.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-theme-review-notice.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-demo-import-migration-notice.php';
	require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-pro-minimum-version-notice.php';
}

// Set default content width.
if ( ! isset( $content_width ) ) {
	$content_width = 812;
}

// Calculate $content_width value according to layout options from Customizer and meta boxes.
function zakra_content_width_rdr() {
	global $content_width;

	// Get layout type.
	$layout_type     = zakra_get_layout_type();
	$layouts_sidebar = array( 'tg-site-layout--left', 'tg-site-layout--right' );

	/**
	 * Calculate content width.
	 */
	// Get required values from Customizer.
	$container_width_arr = get_theme_mod( 'zakra_general_container_width', 1160 );

	$content_width_arr = get_theme_mod( 'zakra_general_content_width', 70 );

	// Calculate Padding to reduce.
	$container_style = get_theme_mod( 'zakra_general_container_style', 'tg-container--wide' );

	$content_padding = ( 'tg-container--separate' === $container_style ) ? 120 : 60;

	if ( in_array( $layout_type, $layouts_sidebar, true ) ) {
		$content_width = ( ( (int) $container_width_arr * (int) $content_width_arr ) / 100 ) - $content_padding;
	} else {
		$content_width = (int) $container_width_arr - $content_padding;
	}

}
add_action( 'template_redirect', 'zakra_content_width_rdr' );

add_filter( 'use_block_editor_for_post', '__return_false', 10);
add_filter( 'use_block_editor_for_post_type', '__return_false', 10);
add_filter( 'use_widgets_block_editor', '__return_false' );

add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

add_filter('acf/settings/save_json', function() {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
    $paths = array(get_template_directory() . '/acf-json');

    if(is_child_theme()) {
        $paths[] = get_stylesheet_directory() . '/acf-json';
    }

    return $paths;
});

// add_filter('acf/fields/post_object/query/name=page_relation', 'my_acf_fields_post_object_query', 10, 3);
// function my_acf_fields_post_object_query( $args, $field, $post_id ) {

//     $args['post_parent'] = 0;

//     return $args;
// }

if( function_exists('acf_field') ) {

}
add_action('acf/include_field_types', function() {

    class AcfFieldUniqueId extends acf_field {

        /*
        *  __construct
        *
        *  This function will setup the field type data
        *
        *  @type	function
        *  @date	5/03/2014
        *  @since	5.0.0
        *
        *  @param	n/a
        *  @return	n/a
        */

        function __construct() {

            /*
            *  name (string) Single word, no spaces. Underscores allowed
            */

            $this->name = 'unique_id';


            /*
            *  label (string) Multiple words, can include spaces, visible when selecting a field type
            */

            $this->label = __('Unique ID', 'acf-unique_id');


            /*
            *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
            */

            $this->category = 'layout';


            /*
            *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
            *  var message = acf._e('unique_id', 'error');
            */

            $this->l10n = array(
            );


            // do not delete!
            parent::__construct();
            
        
        }


        /*
        *  render_field()
        *
        *  Create the HTML interface for your field
        *
        *  @param	$field (array) the $field being rendered
        *
        *  @type	action
        *  @since	3.6
        *  @date	23/01/13
        *
        *  @param	$field (array) the $field being edited
        *  @return	n/a
        */
        function render_field( $field ) {
            ?>
            <input type="text" readonly="readonly" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" />
            <?php
        }


        /*
        *  update_value()
        *
        *  This filter is applied to the $value before it is saved in the db
        *
        *  @type	filter
        *  @since	3.6
        *  @date	23/01/13
        *
        *  @param	$value (mixed) the value found in the database
        *  @param	$post_id (mixed) the $post_id from which the value was loaded
        *  @param	$field (array) the field array holding all the field options
        *  @return	$value
        */
        function update_value( $value, $post_id, $field ) {
            if (!$value) {
                $value = "[gamecards key=". uniqid() ."]";
            }
            return $value;
        }


        /*
        *  validate_value()
        *
        *  This filter is used to perform validation on the value prior to saving.
        *  All values are validated regardless of the field's required setting. This allows you to validate and return
        *  messages to the user if the value is not correct
        *
        *  @type	filter
        *  @date	11/02/2014
        *  @since	5.0.0
        *
        *  @param	$valid (boolean) validation status based on the value and the field's required setting
        *  @param	$value (mixed) the $_POST value
        *  @param	$field (array) the field array holding all the field options
        *  @param	$input (string) the corresponding input name for $_POST value
        *  @return	$valid
        */
        function validate_value( $valid, $value, $field, $input ){
            return true;
        }
    }

    new AcfFieldUniqueId();
});
// create field


add_filter('acf/load_field/name=cards', 'my_acf_fields_relationship_query', 10, 1);
function my_acf_fields_relationship_query( $field ) {
    $games = get_field( 'games', 'option' );
	if(!empty($games)){
		$field['choices'] = array();

    	foreach( $games as $game ) {
            $title = trim($game['title_override']) != '' ? trim($game['title_override']) : $game['page_relation']->post_title;
    		$field['choices'][ $game['unique_id'] ] = $title;
    	}
	}

    return $field;
}

function zakra_excerpt_length($length)
{
    return 35;
}
add_filter('excerpt_length', 'zakra_excerpt_length');

//yoast seo year
// define the custom replacement callback
setlocale(LC_TIME, 'nl_NL');
function year()
{
    $a = strftime("%Y", date('U'));
    $b = ucfirst($a);
    return $b;
}
// define the action for register yoast_variable replacments
function register_custom_yoast_variables_yearen()
{
    wpseo_register_var_replacement('%%year%%', 'year', 'advanced', 'some help text');
}
// Add action
add_action('wpseo_register_extra_replacements', 'register_custom_yoast_variables_yearen');


// latest_slots_widget
class latest_slots_widget extends WP_Widget {
  
	function __construct() {
	parent::__construct(
	  
		// Base ID of your widget
		'latest_slots_widget', 
		
		// Widget name will appear in UI
		__('Nieuwste gokkasten', 'ock'), 
		
		// Widget description
		array( 'description' => __( '', 'ock' ), ) 
		);
	}
	  
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$posts_num = ( $instance['posts_num'] )? $instance['posts_num']:5;

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		// This is where you run the code and display the output
		$gokkasten_parent = get_field('gokkasten_parent','option');

		$query_args = array(
			'post_type' 		=> 'page',
			'posts_per_page'	=> $posts_num,
			'post_parent'       => $gokkasten_parent,
		);

		$slots = get_posts($query_args);

		if( $slots ): ?>

			<ul>

				<?php foreach ($slots as $key => $slot): ?>

					<li>
						<a href="<?php echo get_the_permalink($slot->ID) ?>"><?php echo get_the_title($slot->ID); ?></a>
					</li>
					
				<?php endforeach; ?>

			</ul>

		<?php endif;

		echo $args['after_widget'];
	}
			  
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		if ( isset( $instance[ 'posts_num' ] ) ) {
			$posts_num = $instance[ 'posts_num' ];
		}else{
			$posts_num = 5;
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'posts_num' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label> 
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'posts_num' ); ?>" name="<?php echo $this->get_field_name( 'posts_num' ); ?>" type="number" value="<?php echo esc_attr( $posts_num ); ?>" />
		</p>
		<?php 
	}
		  
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts_num'] = ( ! empty( $new_instance['posts_num'] ) ) ? strip_tags( $new_instance['posts_num'] ) : '';
		return $instance;
	}
	 
// Class latest_slots_widget ends here
} 
	
// Register and load the widget
function wpb_load_widget() {
	register_widget( 'latest_slots_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// year shortcode
function year_sc(){
    $year = strftime("%Y", date('U'));

    return $year;
}
// register shortcode
add_shortcode('year', 'year_sc');

// gokkasten_relationship

// gokkasten_relationship_query
function gokkasten_relationship_query( $args, $field, $post_id ) {
	$args['post_parent'] = get_field('gokkasten_parent','option');
    $args['post_type'] = 'page';

    return $args;
}
add_filter('acf/fields/relationship/query/name=gokkasten_page_populair', 'gokkasten_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/name=gokkasten_page_video_slots', 'gokkasten_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/name=gokkasten_page_klassiekers', 'gokkasten_relationship_query', 10, 3);
add_filter('acf/fields/relationship/query/name=gokkasten_page_jackpots', 'gokkasten_relationship_query', 10, 3);

function gokkasten_relationship_shortcode( $atts ){
	ob_start();

	$type = ( isset($atts['type']) )? $atts['type']:false;
	$id = isset( $atts['id'] ) ? $atts['id'] : false;
	$cat = isset( $atts['cat'] ) ? $atts['cat'] : false;

	if( (!$type&&!$id&&!$cat) || $type == 1 ):
		
		$posts = get_field('gokkasten_page_populair','option');
		if( $posts ): ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row valign-wrapper">
								<div class="col s12 l6 gokkasten-relationship-section-title">
									<h2>Populair</h2>
								</div>
							</div>
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif;

	endif;

	if( (!$type&&!$id&&!$cat) || $type == 2 ):

		$args = array(
			'post_parent' => get_field('gokkasten_parent','option'),
			'post_type' => 'page',
			'posts_per_page'	=> 5,
		);

		$posts = get_posts( $args );
		?>

		<?php if ( $posts ) : ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row valign-wrapper">
								<div class="col s12 l6 gokkasten-relationship-section-title">
									<h2>Nieuw</h2>
								</div>
							</div>
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif; 

	endif;

	if( (!$type&&!$id&&!$cat) || $type == 3 ):
	
		$posts = get_field('gokkasten_page_video_slots','option');
		if( $posts ): ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row valign-wrapper">
								<div class="col s12 l6 gokkasten-relationship-section-title">
									<h2>Video slots</h2>
								</div>
							</div>
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif;

	endif;

	if( (!$type&&!$id&&!$cat) || $type == 4 ):
	
		$posts = get_field('gokkasten_page_klassiekers','option');
		if( $posts ): ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row valign-wrapper">
								<div class="col s12 l6 gokkasten-relationship-section-title">
									<h2>Klassiekers</h2>
								</div>
							</div>
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif;

	endif;
		
	if( (!$type&&!$id&&!$cat) || $type == 5 ):
		
		$posts = get_field('gokkasten_page_jackpots','option');
		if( $posts ): ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row valign-wrapper">
								<div class="col s12 l6 gokkasten-relationship-section-title">
									<h2>Jackpots</h2>
								</div>
							</div>
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif;

	endif;

	if( !$type && $id ):

		$id = explode(',',$id);

		$args = array(
			'post_parent' => get_field('gokkasten_parent','option'),
			'post_type' => 'page',
			'post__in' => $id
		);

		$posts = get_posts( $args );
		?>

		<?php if ( $posts ) : ?>

            <?php 
            
            $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
			$gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
			$lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
            
            ?>

			<div class="section lazy-load-section gokkasten-relationship-section">
				<div class="container">
					<div class="row">
						<div class="col s12">
							<div class="row">
								<div class="col s12 card-wrapper">
									<?php foreach( $posts as $post): ?>
										<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif; 

	endif;

	if( !$type && $cat ):

		$parent = get_page_by_title( $cat );

		if( $parent ):
			
			$args = array(
				'post_parent' => $parent->ID,
				'post_type' => 'page'
			);

			$posts = get_posts( $args );
			?>

			<?php if ( $posts ) : ?>

                <?php 
            
                $gokkasten_disable_lazyload = get_field('gokkasten_disable_lazyload','option');
                $gokkasten_disable_lazyload = ( $gokkasten_disable_lazyload )? $gokkasten_disable_lazyload:array();
                $lazyload_class = ( !in_array(get_the_ID(), $gokkasten_disable_lazyload) )? '': 'no-lazy';
                
                ?>

				<div class="section lazy-load-section gokkasten-relationship-section">
					<div class="container">
						<div class="row">
							<div class="col s12">
								<div class="row">
									<div class="col s12 card-wrapper">
										<?php foreach( $posts as $post): ?>
											<?php get_template_part('template-parts/gokkast-card-template', '', array('post_data' => $post, 'lazyload_class' => $lazyload_class)) ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php endif; 

		endif;

	endif;

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'gokkasten_relationship', 'gokkasten_relationship_shortcode' );

// set position_helper value
add_action("save_post", function ($post_ID, $post, $update) {

	$gokkasten_parent = get_field('gokkasten_parent','option');
	$live_casino_parent = get_field('live_casino_parent','option');
	$post_type_slug = '';

	if( $post->post_parent == $gokkasten_parent ):
		$post_type_slug = 'gokkasten';
	elseif( $post->post_parent == $live_casino_parent ):
		$post_type_slug = 'live_casino';
	endif;

	if( !$post_type_slug ):
		return;
	endif;

    if (wp_is_post_autosave($post_ID)):
        return;
    endif;

	$position_helper = get_post_meta($post_ID, $post_type_slug.'_position_helper',true);

	if( $position_helper ):
		return;
	endif;

    if( !empty($_POST) ):

		$args = array(
			'post_type' => 'page',
			'numberposts' => 1,
			'post_status' => array('publish', 'pending', 'draft'),
			'order' => 'DESC',
			'orderby' => 'meta_value_num',
			'meta_key' => $post_type_slug.'_position_helper'
		);
		$last_post = get_posts($args);

		if( isset($last_post[0]) ):

			$last_pos = get_post_meta( $last_post[0]->ID, $post_type_slug.'_position_helper', true );

			update_post_meta( $post_ID, $post_type_slug.'_position_helper', intval($last_pos)+1 );
		
		endif;

    endif;

}, 10, 3);

function gokkast_shortcode( $atts ){
	ob_start();

	$id = isset( $atts['id'] ) ? $atts['id'] : false;

	$postId = $id;

    $postMeta = get_post_meta($postId);
    $thumbUrl = get_the_post_thumbnail_url($postId, 'medium');

	$slot_data_game_provider_text = ( $postMeta['slot_data_game_provider_text'] )? $postMeta['slot_data_game_provider_text'][0]:'';
	$slot_data_reels_text = ( $postMeta['slot_data_reels_text'] )? $postMeta['slot_data_reels_text'][0]:'';
	$slot_data_winlijnen_text = ( $postMeta['slot_data_winlijnen_text'] )? $postMeta['slot_data_winlijnen_text'][0]:'';
	$slot_data_jackpot_text = ( $postMeta['slot_data_jackpot_text'] )? $postMeta['slot_data_jackpot_text'][0]:'';
	$slot_data_rtp_text = ( $postMeta['slot_data_rtp_text'] )? $postMeta['slot_data_rtp_text'][0]:'';
	$slot_data_minimum_inzet_text = ( $postMeta['slot_data_minimum_inzet_text'] )? $postMeta['slot_data_minimum_inzet_text'][0]:'';
	$slot_data_maximum_inzet_text = ( $postMeta['slot_data_maximum_inzet_text'] )? $postMeta['slot_data_maximum_inzet_text'][0]:'';
	$slot_data_echt_geld_relation = ( $postMeta['slot_data_echt_geld_relation'] )? $postMeta['slot_data_echt_geld_relation'][0]:'';

	$casino_affiliate_link = get_field( 'fcrp_affiliate_referral_url', $slot_data_echt_geld_relation );
	?>

	<div class="bonusmaand-wrapper">
		<div class="casinocard-hor-container casinocard-container z-depth-1">
			<div class="gok-hor-logo casinocard-logo center">
				<a href="<?php the_permalink($postId) ?>">
				<img class="" alt="<?php echo get_the_title($postId); ?>" loading="lazy" src="<?php echo $thumbUrl ?>" />
				</a>
			</div>
		<div class="bonusmaand-content">
			<div class="casinocard-title cas-hor-title">
			<div><?php echo get_the_title($postId); ?></div>
			</div>
			<div class="cas-hor-data-container">
			<div class="cas-hor-data-1 gok-hor-data-1">
			<div><strong>Gameprovider:</strong> <?php echo $slot_data_game_provider_text; ?></div>
			<div><strong>Reels:</strong> <?php echo $slot_data_reels_text; ?></div>
			<div><strong>Winlijnen:</strong> <?php echo $slot_data_winlijnen_text; ?></div>
			</div>
			<div class="cas-hor-data-2 gok-hor-data-2">
			<div><strong>Jackpot:</strong> <?php echo $slot_data_jackpot_text; ?></div>
			<div><strong>RTP:</strong> <?php echo $slot_data_rtp_text; ?></div>
			<div><strong>Min. inzet:</strong> <?php echo $slot_data_minimum_inzet_text; ?> / <strong>Max. inzet:</strong> <?php echo $slot_data_maximum_inzet_text; ?></div>
			</div>
			</div>
			<div class="bonusmaand-buttons">
			<div class="casinocard-btn">
				<a class="tg-read-more" href="<?php the_permalink($postId) ?>"><span>Bekijk review</span></a>
			</div>

            <?php if( false ): ?>
                <div class="casinocard-btn">
                    <a class="btn-cc waves-effect waves-light casino-list-btn" href="<?php echo $casino_affiliate_link ?>" target="_blank" rel="nofollow">Speel nu</a>
                </div>
            <?php endif; ?>

			</div>
		</div>
		</div>
	</div>

	<?php

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
add_shortcode( 'gokkast_shortcode', 'gokkast_shortcode' );

// search autosugest query
add_action('wp_ajax_GlobalSearchCustomEndpoint', 'GlobalSearchCustomEndpoint');
add_action('wp_ajax_nopriv_GlobalSearchCustomEndpoint', 'GlobalSearchCustomEndpoint');

if (!function_exists('GlobalSearchCustomEndpoint')) {
    function GlobalSearchCustomEndpoint($data)
    {
		$val = ( isset($_REQUEST['val']) ) ? $_REQUEST['val'] : '';

        global $wpdb;
        global $postsToExclude;
		$postsToExclude = array();
        global $postsToTop;
		$postsToTop = array();
        global $postsTemplateToExclude;
		$postsTemplateToExclude = array();

        $limit = 10;
        $offset = 0;
        if( isset($_GET['offset']) ):
            $offset = $_GET['offset'];
        endif;

        $replacedWhiteSpaces = str_replace('%20', ' ', $val);
        //$replacedWhiteSpaces = str_replace("'", "\'", $replacedWhiteSpaces);

        //$pattern = ["/%22/", "/;/", "/=/", '/\"/', "/'/", '/"/'];
        $pattern = ["/%22/", "/;/", "/=/", '/\"/', '/"/'];
        $replacedWhiteSpaces = preg_replace($pattern, '', $replacedWhiteSpaces);
        $replacedWhiteSpaces = trim($replacedWhiteSpaces);
        if (count($postsToExclude) > 0) {
            $stringWithExclPosts = implode(',', $postsToExclude);
        } else {
            $stringWithExclPosts = 0;
        }
        $templateQuery = '';
        if (count($postsTemplateToExclude) > 0) {
            foreach ($postsTemplateToExclude as $key => $value) {
                $templateQuery .= " AND wp_postmeta.meta_value != " . "'" . $value . "'";
            }
        }

        $results_count = 0;
        $first_pass_done = false;
        $prepared_sec_pass = false;
        $original_string = $original_string_reverse = $replacedWhiteSpaces;

        $original_string_lenght = strlen($original_string);
        $replacedAllWhiteSpaces = str_replace(' ', '', $replacedWhiteSpaces);
        $original_string_no_ws = $replacedAllWhiteSpaces;

        $replacedWhiteSpaces2 = $replacedWhiteSpaces;
        $replacedAllWhiteSpaces2 = $replacedAllWhiteSpaces;
        $has_exact_result = true;

        $first_letter = substr($original_string, 0, 1);
        $first_letters = substr($original_string, 0, 2);
        $words = explode(' ', $original_string);
        $words_count = count($words);
        $search_passes = 0;
        $is_similar_string = false;

        $replacedWhiteSpacesReverse = $replacedWhiteSpacesReverse2 = $replacedAllWhiteSpacesReverse = $replacedAllWhiteSpacesReverse2 = $replacedWhiteSpaces;
        if( $words_count > 1 ):
            $replacedWhiteSpacesReverse = $replacedWhiteSpacesReverse2 = $original_string_reverse = $words[1].' '.$words[0];
            $replacedAllWhiteSpacesReverse = $replacedAllWhiteSpacesReverse2 = str_replace(' ', '', $original_string_reverse);
        endif;

        while ($results_count < 4) {

            $helper_query = $helper_tax_query = "";

            if( !$has_exact_result ):
                $helper_query .= " OR wp_posts.post_title LIKE '%" . $replacedWhiteSpaces2 . "%' OR REPLACE(wp_posts.post_title,' ','') LIKE '%" . $replacedAllWhiteSpaces2 . "%' OR REPLACE(wp_posts.post_title,' ','') LIKE '%" . $replacedAllWhiteSpacesReverse2 . "%' ";
                $helper_query .= " OR wp_posts.post_title LIKE '%" . $replacedWhiteSpaces2 . "%' OR REPLACE(wp_posts.post_title,'\'','') LIKE '%" . $replacedAllWhiteSpaces2 . "%' OR REPLACE(wp_posts.post_title,'\'','') LIKE '%" . $replacedAllWhiteSpacesReverse2 . "%' ";
                $helper_tax_query .= " OR wp_terms.name LIKE '%" . $replacedWhiteSpaces2 . "%' OR REPLACE(wp_terms.name,' ','') LIKE '%" . $replacedAllWhiteSpaces2 . "%' OR REPLACE(wp_terms.name,' ','') LIKE '%" . $replacedAllWhiteSpacesReverse2 . "%' ";
                $helper_tax_query .= " OR wp_terms.name LIKE '%" . $replacedWhiteSpaces2 . "%' OR REPLACE(wp_terms.name,'\'','') LIKE '%" . $replacedAllWhiteSpaces2 . "%' OR REPLACE(wp_terms.name,'\'','') LIKE '%" . $replacedAllWhiteSpacesReverse2 . "%' ";

                if( $words_count > 1 ):

                    foreach ($words as $key => $word):

                        if( isset($words[$key+1]) ):
                            $next_word = ' '.$words[$key+1];
                            $next_word_reverse = $words[$key+1].' ';

                            $helper_query .= " OR wp_posts.post_title LIKE '%" . $word.$next_word. "%' OR wp_posts.post_title LIKE '%" . $next_word_reverse.$word. "%'";
                            $helper_tax_query  .= " OR wp_terms.name LIKE '%" . $word.$next_word . "%' OR wp_terms.name LIKE '%" . $next_word_reverse.$word. "%'";
                        endif;

                    endforeach;
                endif;

                if( $search_passes > 2 ):
                    
                    $vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
                    $noVowels = str_replace($vowels, "", $replacedWhiteSpaces);
                    
                    $helper_query .= " OR replace(replace(replace(replace(replace(wp_posts.post_title,'a',''),'e',''),'i','') ,'u','') ,'o','') LIKE '%" . $noVowels. "%' ";
                    $helper_tax_query  .= " OR replace(replace(replace(replace(replace(wp_terms.name,'a',''),'e',''),'i','') ,'u','') ,'o','') LIKE '%" . $noVowels . "%' ";
                    
                    if( $words_count > 1 ):
                        $noVowelsReverse = str_replace($vowels, "", $replacedWhiteSpacesReverse);

                        $helper_query .= " OR replace(replace(replace(replace(replace(wp_posts.post_title,'a',''),'e',''),'i','') ,'u','') ,'o','') LIKE '%" . $noVowelsReverse. "%' ";
                        $helper_tax_query  .= " OR replace(replace(replace(replace(replace(wp_terms.name,'a',''),'e',''),'i','') ,'u','') ,'o','') LIKE '%" . $noVowelsReverse . "%' ";    
                    endif;
            
                endif;

            endif;

            if( $is_similar_string ):

                $order_query = " ORDER BY CASE 
                    when post_title LIKE '" . $replacedWhiteSpaces . "%' then 1 
                    when REPLACE(post_title,'\'','') LIKE '" . $replacedAllWhiteSpaces . "%' then 2 
                    when REPLACE(post_title,'\'','') LIKE '%" . $replacedAllWhiteSpaces . "%' then 3 
                    when REPLACE(post_title,' ','') LIKE '" . $replacedAllWhiteSpaces . "%' then 4 
                    when REPLACE(post_title,' ','') LIKE '%" . $replacedAllWhiteSpaces . "%' then 5 
                    when post_title LIKE '%" . $replacedWhiteSpacesReverse . "%' then 6 
                    else 5 
                END ";

            else:

                $order_query = " ORDER BY CASE 
                    when post_title LIKE '" . $first_letter . "%' AND LENGTH(post_title) < " .strval($original_string_lenght*2). " then 1 
                    when post_title LIKE '" . $first_letters . "%' then 2 
                    when post_title LIKE '" . $replacedWhiteSpaces . "%' then 3 
                    when post_title LIKE '" . $replacedWhiteSpacesReverse . "%' then 4 
                    when REPLACE(post_title,'\'','') LIKE '" . $replacedAllWhiteSpaces . "%' then 5 
                    when REPLACE(post_title,'\'','') LIKE '%" . $replacedAllWhiteSpaces . "%' then 6 
                    when REPLACE(post_title,' ','') LIKE '" . $replacedAllWhiteSpaces . "%' then 7 
                    when REPLACE(post_title,' ','') LIKE '%" . $replacedAllWhiteSpaces . "%' then 8 
                    when post_title LIKE '%" . $replacedWhiteSpacesReverse . "%' then 9 
                    else 8 
                END ";
                
            endif;

            $query = "
            SELECT DISTINCT * 
            FROM (

                SELECT DISTINCT 
                    \"post\" as type,
                    wp_posts.ID as ID,
                    wp_postmeta.post_id as postmeta_post_id,

                    wp_posts.post_status as posts_post_status,
                    wp_postmeta.meta_key as postmeta_meta_key,
                    wp_postmeta.meta_value as postmeta_meta_value,
                    wp_posts.post_type as posts_post_type,
                    wp_posts.post_title as post_title,

                    Null as terms_term_id, 
                    Null as term_taxonomy_term_id,

                    Null as term_taxonomy_taxonomy 
                    
                FROM 
                    wp_posts 

                LEFT JOIN wp_postmeta on wp_posts.ID = wp_postmeta.post_id 

                WHERE 
                    wp_posts.post_status = 'publish' 
                    AND wp_postmeta.meta_key = '_wp_page_template' 
                    $templateQuery 
                    AND wp_posts.post_type != 'revision' 
                    AND wp_posts.post_type != 'attachment' 
                    AND wp_posts.post_type != 'acf-field' 
                    AND wp_posts.post_type != 'acf-field-group' 
                    AND (wp_posts.post_title LIKE '%" . $replacedWhiteSpaces . "%' OR REPLACE(wp_posts.post_title,' ','') LIKE '%" . $replacedAllWhiteSpaces . "%' OR REPLACE(wp_posts.post_title,'\'','') LIKE '%" . $replacedAllWhiteSpaces . "%' OR wp_posts.post_title LIKE '%" . $replacedWhiteSpacesReverse . "%' OR wp_posts.post_title LIKE '%" . $replacedWhiteSpacesReverse2 . "%'OR REPLACE(wp_posts.post_title,' ','') LIKE '%" . $replacedAllWhiteSpacesReverse . "%' OR REPLACE(wp_posts.post_title,'\'','') LIKE '%" . $replacedAllWhiteSpacesReverse . "%' ".$helper_query." ) 
                    AND wp_posts.ID NOT IN (" . $stringWithExclPosts . ") AND (LENGTH('" . $replacedWhiteSpaces . "') * 3) > " . $original_string_lenght . " 

                UNION
                SELECT DISTINCT 
                    \"term\" as type,
                    wp_terms.term_id as ID,
                    Null as postmeta_post_id,

                    Null as posts_post_status,
                    Null as postmeta_meta_key,
                    Null as postmeta_meta_value,
                    Null as posts_post_type,
                    wp_terms.name as post_title,

                    wp_terms.term_id as terms_term_id, 
                    wp_term_taxonomy.term_id as term_taxonomy_term_id,

                    wp_term_taxonomy.taxonomy as term_taxonomy_taxonomy 
                    
                FROM 
                    wp_terms 

                INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id 
                
                WHERE 
                    (REPLACE(wp_terms.name,'\'', '') LIKE '%" . $replacedWhiteSpaces . "%' OR REPLACE(REPLACE(wp_terms.name,' ',''),'\'', '') LIKE '%" . $replacedAllWhiteSpaces . "%' OR REPLACE(wp_terms.name,'\'', '') LIKE '%" . $replacedWhiteSpacesReverse . "%' OR REPLACE(wp_terms.name,'\'', '') LIKE '%" . $replacedWhiteSpacesReverse2 . "%' OR REPLACE(REPLACE(wp_terms.name,' ',''),'\'', '') LIKE '%" . $replacedAllWhiteSpacesReverse . "%' ".$helper_tax_query." ) 
                    AND wp_term_taxonomy.taxonomy = 'category' AND (LENGTH('" . $replacedWhiteSpaces . "') * 3) > " . $original_string_lenght . " 
                
            ) AS i 

            " . $order_query . "

            LIMIT ".$limit." OFFSET ".$offset;

            //Get Posts
            $posts = array();
            if (strlen($replacedWhiteSpaces) >= 2) {
                $posts = $wpdb->get_results($query);
            }

            $result = array();
            if ( count($posts) > 0 ) {
                $topPostsArray = [];

                if( !isset($relevantPostsArray) ):
                    $relevantPostsArray = [];
                endif;

                if( count($posts) > 0  ):

                    foreach ($posts as $key => $post) {

                        $percent_original = $percent_reverse = 0;
                        $similarity_original = similar_text( strtolower($post->post_title), strtolower($original_string), $percent_original );
                        $similarity_reverse = similar_text( strtolower($post->post_title), strtolower($original_string_reverse), $percent_reverse );

                        if ( $offset == 0 && ( $percent_original > 49 || $percent_reverse > 49 ) ){
                            $relevantPostsArray[] = $post;
                            unset($posts[$key]);
                            $is_similar_string = true;
                        }else if ( in_array(get_page_template_slug($post), $postsToTop) ) {
                            $topPostsArray[] = $post;
                            unset($posts[$key]);
                        }else if ( isset($post->term_taxonomy_taxonomy) && $post->term_taxonomy_taxonomy == 'category' ) {
                            $topPostsArray[] = $post;
                            unset($posts[$key]);
                        }
                    }

                endif;

                if( $offset > 0 ):
                    $relevantPostsArray = [];
                endif;

                array_filter($posts);
                $sortedArray = array_merge($relevantPostsArray, $topPostsArray);
                $sortedArray = array_merge($sortedArray, $posts);

                foreach ($sortedArray as $post) {

                    $postPermalink = false;
                    if( $post->type == 'post' ):
                        $postPermalink = get_permalink($post->ID);
                    elseif( $post->type == 'term' ):
                        $postPermalink = get_term_link(intval($post->ID));
                    endif;

                    $postThumbnail = get_the_post_thumbnail_url($post->ID, 'full');
                    $postTitle = str_replace('[year]', date("Y"), $post->post_title);
                    if (strpos($postPermalink, 'uncategorized')) {
                        $siteUrl = get_site_url();
                        $changedTitle = strtolower(str_replace(' ', '-', $postTitle));
                        $postPermalink = $siteUrl . '/' . $changedTitle;
                    }
                
                    $result[] = [
                        "id" => $post->ID,
                        "name" => $postTitle,
                        "thumbnail" => $postThumbnail,
                        "url" => $postPermalink
                    ];
                }
            }

            $results_count = count($result);
            if( $results_count > 3 ):
                break;
            else:
                $has_exact_result = false;
            endif;

            if( !$first_pass_done ):
                // removing letters from end
                if( strlen($replacedWhiteSpaces) > 3 && strlen($replacedAllWhiteSpaces) > 3 ):
                    $replacedWhiteSpaces = substr($replacedWhiteSpaces, 0, -1);
                    $replacedAllWhiteSpaces = substr($replacedAllWhiteSpaces, 0, -1);

                    $replacedWhiteSpaces2 = substr($replacedWhiteSpaces2, 1);
                    $replacedAllWhiteSpaces2 = substr($replacedAllWhiteSpaces2, 1);

                    if( $words_count > 1 ):
                        $replacedWhiteSpacesReverse = substr($replacedWhiteSpacesReverse, 0, -1);
                        $replacedAllWhiteSpacesReverse = substr($replacedAllWhiteSpacesReverse, 0, -1);

                        $replacedWhiteSpacesReverse2 = substr($replacedWhiteSpacesReverse2, 1);
                        $replacedAllWhiteSpacesReverse2 = substr($replacedAllWhiteSpacesReverse2, 1);
                    endif;

                    if( startsWith($replacedWhiteSpaces, "'") || endsWith($replacedWhiteSpaces, "'") ):
                        $replacedWhiteSpaces = str_replace("'",'',$replacedWhiteSpaces);
                    endif;
                    if( startsWith($replacedAllWhiteSpaces, "'") || endsWith($replacedAllWhiteSpaces, "'") ):
                        $replacedAllWhiteSpaces = str_replace("'",'',$replacedAllWhiteSpaces);
                    endif;
                    if( startsWith($replacedWhiteSpaces2, "'") || endsWith($replacedWhiteSpaces2, "'") ):
                        $replacedWhiteSpaces2 = str_replace("'",'',$replacedWhiteSpaces2);
                    endif;
                    if( startsWith($replacedAllWhiteSpaces2, "'") || endsWith($replacedAllWhiteSpaces2, "'") ):
                        $replacedAllWhiteSpaces2 = str_replace("'",'',$replacedAllWhiteSpaces2);
                    endif;
                    if( startsWith($replacedWhiteSpacesReverse, "'") || endsWith($replacedWhiteSpacesReverse, "'") ):
                        $replacedWhiteSpacesReverse = str_replace("'",'',$replacedWhiteSpacesReverse);
                    endif;
                    if( startsWith($replacedAllWhiteSpacesReverse, "'") || endsWith($replacedAllWhiteSpacesReverse, "'") ):
                        $replacedAllWhiteSpacesReverse = str_replace("'",'',$replacedAllWhiteSpacesReverse);
                    endif;
                    if( startsWith($replacedWhiteSpacesReverse2, "'") || endsWith($replacedWhiteSpacesReverse2, "'") ):
                        $replacedWhiteSpacesReverse2 = str_replace("'",'',$replacedWhiteSpacesReverse2);
                    endif;
                    if( startsWith($replacedAllWhiteSpacesReverse2, "'") || endsWith($replacedAllWhiteSpacesReverse2, "'") ):
                        $replacedAllWhiteSpacesReverse2 = str_replace("'",'',$replacedAllWhiteSpacesReverse2);
                    endif;

                else:
                    $first_pass_done = true;
                endif;

            else:
                // reseted string
                if( !$prepared_sec_pass ):
                    $prepared_sec_pass = true;
                    $replacedWhiteSpaces = $replacedWhiteSpaces2 = $original_string;
                    $replacedWhiteSpacesReverse = $replacedWhiteSpacesReverse2 = $original_string_reverse;
                endif;

                // removing letters from start
                if( strlen($replacedWhiteSpaces) > 3 && strlen($replacedAllWhiteSpaces) > 3 ):
                    $replacedWhiteSpaces = substr($replacedWhiteSpaces, 1);
                    $replacedAllWhiteSpaces = substr($replacedAllWhiteSpaces, 1);

                    $replacedWhiteSpaces2 = substr($replacedWhiteSpaces2, 0, -1);
                    $replacedAllWhiteSpaces2 = substr($replacedAllWhiteSpaces2, 0, -1);

                    if( $words_count > 1 ):
                        $replacedWhiteSpacesReverse = substr($replacedWhiteSpacesReverse, 1);
                        $replacedAllWhiteSpacesReverse = substr($replacedAllWhiteSpacesReverse, 1);

                        $replacedWhiteSpacesReverse2 = substr($replacedWhiteSpacesReverse2, 0, -1);
                        $replacedAllWhiteSpacesReverse2 = substr($replacedAllWhiteSpacesReverse2, 0, -1);
                    endif;

                    if( startsWith($replacedWhiteSpaces, "'") || endsWith($replacedWhiteSpaces, "'") ):
                        $replacedWhiteSpaces = str_replace("'",'',$replacedWhiteSpaces);
                    endif;
                    if( startsWith($replacedAllWhiteSpaces, "'") || endsWith($replacedAllWhiteSpaces, "'") ):
                        $replacedAllWhiteSpaces = str_replace("'",'',$replacedAllWhiteSpaces);
                    endif;
                    if( startsWith($replacedWhiteSpaces2, "'") || endsWith($replacedWhiteSpaces2, "'") ):
                        $replacedWhiteSpaces2 = str_replace("'",'',$replacedWhiteSpaces2);
                    endif;
                    if( startsWith($replacedAllWhiteSpaces2, "'") || endsWith($replacedAllWhiteSpaces2, "'") ):
                        $replacedAllWhiteSpaces2 = str_replace("'",'',$replacedAllWhiteSpaces2);
                    endif;
                    if( startsWith($replacedWhiteSpacesReverse, "'") || endsWith($replacedWhiteSpacesReverse, "'") ):
                        $replacedWhiteSpacesReverse = str_replace("'",'',$replacedWhiteSpacesReverse);
                    endif;
                    if( startsWith($replacedAllWhiteSpacesReverse, "'") || endsWith($replacedAllWhiteSpacesReverse, "'") ):
                        $replacedAllWhiteSpacesReverse = str_replace("'",'',$replacedAllWhiteSpacesReverse);
                    endif;
                    if( startsWith($replacedWhiteSpacesReverse2, "'") || endsWith($replacedWhiteSpacesReverse2, "'") ):
                        $replacedWhiteSpacesReverse2 = str_replace("'",'',$replacedWhiteSpacesReverse2);
                    endif;
                    if( startsWith($replacedAllWhiteSpacesReverse2, "'") || endsWith($replacedAllWhiteSpacesReverse2, "'") ):
                        $replacedAllWhiteSpacesReverse2 = str_replace("'",'',$replacedAllWhiteSpacesReverse2);
                    endif;

                else:
                    break; 
                endif;
                
            endif;
            $search_passes++;
        }

		$result_html = '';
		$old_url = '';
		$result_exist = array();

		if( $result ):
			foreach ($result as $key => $row):

				if( $row['url'] != $old_url && !in_array($row['url'], $result_exist) ):
					$result_html .= '<div class="mobile-search-result">';
					$result_html .= '<a href="'.$row['url'].'">';
					$result_html .= $row['name'];
					$result_html .= '</a>';
					$result_html .= '</div>';

					$result_exist[] = $row['url'];
				endif;

				$old_url = $row['url'];

			endforeach;
		endif;

		echo $result_html;

		die();
    }
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
   $length = strlen( $needle );
   if( !$length ) {
       return true;
   }
   return substr( $haystack, -$length ) === $needle;
}

//compare
function compare_shortcode( $atts ) {
    $id = ( isset($atts['id']) && $atts['id'] )? $atts['id']:false;
    ob_start();

    if( $id ):

        $ol_groups = get_field('compare_groups','option');

        if( $ol_groups ):

            foreach( $ol_groups as $key => $ol_group_item ):

                $group_id = ( isset($ol_group_item['id']) )? $ol_group_item['id']:false;

                if( $group_id == $id ): 
                
                    $title = ( isset($ol_group_item['title']) )? $ol_group_item['title']:false;
					$text = ( isset($ol_group_item['text']) )? $ol_group_item['text']:false;
					$title_left = ( isset($ol_group_item['title_left']) )? $ol_group_item['title_left']:false;
					$title_right = ( isset($ol_group_item['title_right']) )? $ol_group_item['title_right']:false;
                    $items = ( isset($ol_group_item['items']) )? $ol_group_item['items']:false;
					$text_bottom = ( isset($ol_group_item['text_bottom']) )? $ol_group_item['text_bottom']:false;

					?>
					
					<div class="compare-section">
						<div class="compare-section-inner">

							<?php if( $title || $text ): ?>

								<div class="compare-top">
									<div class="compare-top-inner">

										<?php if( $title ): ?>

											<div class="compare-top-title">
												<h2><?php echo $title; ?></h2>
											</div><!-- .compare-top-title -->
											
										<?php endif; ?>

										<?php if( $text ): ?>

											<div class="compare-top-text">
												<?php echo $text; ?>
											</div><!-- .compare-top-title -->

										<?php endif; ?>

									</div><!-- .compare-top-inner -->
								</div><!-- .compare-top -->
								
							<?php endif; ?>

							<?php if( $items ): ?>

								<div class="compare-table-body">

									<div class="compare-table-body-left">

										<?php if( $title_left ): ?>

											<div class="compare-table-head-item compare-table-head-left">
												<h3><?php echo $title_left; ?></h3>
											</div><!-- .compare-table-head-item -->

										<?php endif; ?>

										<ul class="compare-table-items">

											<?php foreach ($items as $key => $item): 
											
												$type_left = $item['type_left'];
												$text_left = $item['text_left'];
												
												?>

												<!-- <div class="compare-table-body-row"> -->

													<?php if( $text_left ): ?>

														<li class="compare-table-body-item compare-table-body-left-item">
															<div class="compare-table-body-icon compare-table-body-icon-<?php echo ( $type_left )? 'check':'close' ?>">
															</div><!-- .compare-table-body-icon -->
															<div class="compare-table-body-text">
																<span><?php echo $text_left; ?></span>
															</div><!-- .compare-table-body-text -->
														</li><!-- .compare-table-body-item -->

													<?php else: ?>

														<li class="compare-table-body-item compare-table-body-left-item compare-table-body-item-empty">
														</li><!-- .compare-table-body-item -->
														
													<?php endif; ?>

												<!-- </div> --><!-- .compare-table-body-row -->
													
											<?php endforeach; ?>

										</ul><!-- .compare-table-items -->

									</div><!-- .compare-table-body-left -->

									<div class="compare-table-body-right">

										<?php if( $title_right ): ?>

											<div class="compare-table-head-item compare-table-head-right">
												<h3><?php echo $title_right; ?></h3>
											</div><!-- .compare-table-head-item -->

										<?php endif; ?>

										<ul class="compare-table-items">

											<?php foreach ($items as $key => $item): 
											
												$type_right = $item['type_right'];
												$text_right = $item['text_right'];
												
												?>

												<!-- <div class="compare-table-body-row"> -->

													<?php if( $text_right ): ?>

														<li class="compare-table-body-item compare-table-body-right-item">
															<div class="compare-table-body-icon compare-table-body-icon-<?php echo ( $type_right )? 'check':'close' ?>">
															</div><!-- .compare-table-body-icon -->
															<div class="compare-table-body-text">
																<span><?php echo $text_right; ?></span>
															</div><!-- .compare-table-body-text -->
														</li><!-- .compare-table-body-item -->

													<?php else: ?>

														<li class="compare-table-body-item compare-table-body-right-item compare-table-body-item-empty">
														</li><!-- .compare-table-body-item -->

													<?php endif; ?>

												<!-- </div> --><!-- .compare-table-body-row -->
													

											<?php endforeach; ?>

										</ul><!-- .compare-table-items -->

									</div><!-- .compare-table-body-right -->

								</div><!-- .compare-table-body -->
								
							<?php endif; ?>

							<?php if( $text_bottom ): ?>

								<div class="compare-table-bottom">
									<?php echo $text_bottom; ?>
								</div><!-- .compare-table-bottom -->
								
							<?php endif; ?>

						</div><!-- .compare-section-inner -->
					</div><!-- .compare-section -->
					
					<?php
                
                endif;
                
            endforeach;
            
        endif;

    endif;

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'compare', 'compare_shortcode' );

// Author Relationship Pages
function author_relationship_pages() {
    
    global $post;
    $author_display_pages = false;

    if( $post->post_parent == 261 ):
        $arp_slug = 'single-gokkasten';
        $single_gokkasten_author = get_field('single_gokkasten_author','option');

        if( $single_gokkasten_author ):
            $author_display_pages = array(array(
                'page_slug' => 'single-gokkasten',
                'author_object' => $single_gokkasten_author
            ));
        endif;

    elseif( $post->post_parent == 12 ):
        $arp_slug = 'single-casino';
        $single_casino_author = get_field('single_casino_author','option');

        if( $single_casino_author ):
            $author_display_pages = array(array(
                'page_slug' => 'single-casino',
                'author_object' => $single_casino_author
            ));
        endif;

    // elseif( is_singular('post') ):
    //     $arp_slug = 'single-post';
    //     $single_news_author = get_field('single_news_author','option');

    //     if( $single_news_author ):
    //         $author_display_pages = array(array(
    //             'page_slug' => 'single-post',
    //             'author_object' => $single_news_author
    //         ));
    //     endif;

    else:
        $queried_object = get_queried_object();
        $arp_slug = ( isset($queried_object->rewrite['slug']) )? $queried_object->rewrite['slug']:'';
        $arp_slug = ( !$arp_slug && isset($queried_object->slug) )? $queried_object->slug:$arp_slug;
        $arp_slug = ( !$arp_slug && isset($queried_object->post_name) )? $queried_object->post_name:$arp_slug;

        $author_display_pages = get_field('author_display_pages','option');
        
    endif;

    if( is_front_page() ):
        $arp_slug = 'homepage';
    endif;

    if ( $author_display_pages ) :
        foreach ($author_display_pages as $key => $author_display_page):

            $arp_item_slug = ( isset($author_display_page['page_slug']) )? $author_display_page['page_slug']:'';
            if( $arp_item_slug && $arp_slug == $arp_item_slug ):
                $author_object = ( isset($author_display_page['author_object']) )? $author_display_page['author_object']:'';
                
                if ( $author_object ) : 
                    $author_id = $author_object->ID;
                ?>
                    
                    <aside>
                        <div class="section ock-author-archive-top archive author global-author">
                            <div class="container">
                                <div class="row">
                                    <div class="col s12">

                                        <h2>Auteur</h2>
                                        <div class="ock-author-archive-top-inner">

                                            <div class="ock-author-archive-side">

                                                <div class="author__name__date__wrap">
                                                    <?php 			
                                                    // Vars
                                                    $birthdate   = get_field('birthdate', 'user_'.$author_id);
                                                    $location    = get_field('location', 'user_'.$author_id);
                                                    $description = get_field('description_2', 'user_'.$author_id);
                                                    $facebook    = get_the_author_meta( 'facebook', $author_id );
                                                    $twitter     = get_the_author_meta( 'twitter', $author_id );
                                                    $linkedin    = get_the_author_meta( 'linkedin', $author_id );
                                                    

                                                    if( $birthdate || $location ): ?>
                                                    <?php endif; ?>
                                                </div>

												<?php if( $facebook || $twitter || $linkedin ): ?>

													<div class="author-archive-social">



													</div><!-- .author-archive-social -->

												<?php endif; ?>

                                            </div><!-- .ock-author-archive-side -->

                                            <div class="ock-author-archive-main">
                                                <div class="ock-author-archive-main-inner">
                                                    <?php if( $description ): ?>
                                                        <div class="author-archive-desc" style="margin-left: 0; border-radius: 0;">
                                                <div class="author-archive-profile-pic" style="float: right;">
                                                    <?php $image = get_field('profile_picture', 'user_'.$author_id);
                                                    if( !empty($image) ): ?>
                                                        <?php echo wp_get_attachment_image( $image, 'medium' ); ?>
                                                    <?php else: ?>
                                                        <img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/icon-author.svg" alt="icon-author" />
                                                    <?php endif; ?>
                                                </div><!-- .author-archive-profile-pic -->
														<p><a style="color: #fff; font-size: 18px; font-weight: 800;" href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_object->data->display_name; ?></a></p>
                                                            <?php echo $description; ?>
                                                        </div><!-- .author-archive-desc -->
                                                    <?php endif; ?>
                                                </div><!-- .ock-author-archive-main-inner -->
                                            </div><!-- .ock-author-archive-main -->

                                        </div><!-- .ock-author-archive-top-inner -->



                                    </div><!-- .col s12 -->
                                </div><!-- .row -->
                            </div>


                        </div><!-- .ock-author-archive-top -->
                    </aside>

                    <?php 
                    // Scheme Vars
                    $job_title                = get_field('job_title', 'user_'.$author_id);
                    $imageURL                 = wp_get_attachment_image_url($image);
                    $wpseo_author_metadesc    = get_the_author_meta( 'wpseo_metadesc', $author_id );
                    
                    if ( is_archive() && !is_category() ) {
                        $current_queried_post_type = get_post_type( get_queried_object_id() );
                        
                        $page_title = post_type_archive_title( '', false );
                        $page_url = get_post_type_archive_link($current_queried_post_type);
                        $image_url = "https://www.onlinecasinokoning.com/wp-content/themes/zakra/assets/img/images/default-image.png";
                    }
                    if ( is_category() ) {
                        $category = get_category( get_query_var( 'cat' ) );
                        $cat_id = $category->cat_ID;

                        $term_id = get_queried_object_id();
                        $term_id_prefixed = 'category_'. $term_id;
                        $image = get_field( 'image', $term_id_prefixed ); 

                        $page_title = single_cat_title("", false);
                        $page_url = get_category_link($cat_id);
                        $image_url = wp_get_attachment_image_url($image, 'full');

                        if( !$image_url ):
                            $image_url = "https://www.onlinecasinokoning.com/wp-content/themes/zakra/assets/img/images/default-image.png";
                        endif;
                    }
                    if ( is_page() || is_singular() ) {

                        if( is_front_page() ):
                            $page_title = get_post_meta($post->ID, '_yoast_wpseo_title', true);
                        else:
                            $page_title = get_the_title($post->ID);
                        endif;

                        $page_url = get_permalink($post->ID);
                        $image_url = get_the_post_thumbnail_url($post->ID);

                        if( !$image_url ):
                            $image_url = "https://www.onlinecasinokoning.com/wp-content/themes/zakra/assets/img/images/default-image.png";
                        endif;
                    }
                    ?>
                    <script type="application/ld+json">
                        {
                        "@context": "https://schema.org",
                        "@type": "Article",
                        "dateModified": "<?php echo get_the_modified_date('Y-m-d'); ?>",
                        "datePublished": "<?php echo get_the_date('Y-m-d'); ?>",
                        "image": "<?php echo $image_url; ?>",
                        "headline": "<?php echo str_replace('"','\"', $page_title); ?>",
                        "name": "<?php echo str_replace('"','\"', $page_title); ?>",
                        "url": "<?php echo $page_url; ?>",
                        "publisher": {
                            "@type": "Organization",
                            "url": "https://www.onlinecasinokoning.com/",
                            "name": "OnlineCasinoKoning.com",
                            "logo": "https://www.onlinecasinokoning.com/wp-content/themes/zakra/assets/img/images/default-image.png"
                        },
                        "author": {
                            "@type": "Person",
                            "worksFor": {
                            "@type": "Organization",
                            "url": "https://www.onlinecasinokoning.com/",
                            "name": "OnlineCasinoKoning.com"
                            },
                            "image": "<?php echo $imageURL ?>",
                            "knowsLanguage": "nl",
                            "knowsAbout": [
                            "https://nl.wikipedia.org/wiki/Online_casino"
                            ],
                            "jobTitle": "<?php echo str_replace('"','\"', $job_title); ?>",
                            "url": "<?php echo get_author_posts_url($author_id); ?>",
                            "sameAs": [
                                "<?php echo $facebook; ?>",
                                "<?php echo $linkedin; ?>"
                            ],
                            "name": "<?php echo str_replace('"','\"', $author_object->data->display_name); ?>",
                            "description": "<?php echo str_replace('"','\"', $wpseo_author_metadesc); ?>"
                        },
                        "mainEntityOfPage": "<?php echo $page_url; ?>"
                        }
                    </script>
                <?php
                endif;
            endif;

        endforeach;
     endif;
}
add_action('after_content', 'author_relationship_pages', 10);


//table
function table_shortcode( $atts ) {
    $id = ( isset($atts['id']) && $atts['id'] )? $atts['id']:false;

    ob_start();

    if( $id ):

        $table_groups = get_field('table_groups','option');

        if( $table_groups ):

            foreach( $table_groups as $key => $table_group_item ):

                $group_id = ( isset($table_group_item['id']) )? $table_group_item['id']:false;

                if( $group_id == $id ): 
                
                    $group_columns = ( isset($table_group_item['columns']) )? $table_group_item['columns']:0;
                    $group_rows = ( isset($table_group_item['table_rows']) )? $table_group_item['table_rows']:false;
                    $group_image_max_width = ( isset($table_group_item['image_max_width']) )? $table_group_item['image_max_width']:false;

                    if( $group_rows ): ?>
                        
                        <table class="ock-casinotable">
                        
                            <?php $row_index = 0;
                            
                            foreach ($group_rows as $key => $group_row):

                                if( $key == 0 ):
                                    ?><thead><?php
                                elseif( $key == 1 ):
                                    ?></thead><tbody><?php
                                endif;

                                $row_index++;

                                if( $group_row ): ?>
                                
                                    <tr>
                                    
                                        <?php $col_index = 0;
                                        
                                        foreach ($group_row as $key => $group_row_item):
                                            $col_index++;

                                            $has_img  = ( isset($group_row_item['col_type']) )? $group_row_item['col_type']:0;
                                            $col_img_url  = ($has_img)? $group_row_item['col'. $col_index . '_image']['sizes']['large']:null;
                                            $col_img_alt  = ($has_img)?$group_row_item['col'. $col_index . '_image']['alt']:null;
                                            $class = ($has_img)? 'class="has-img"':'';
                            
                                            $col_text = ($col_index > 1)? $group_row_item:$group_row_item['col' . $col_index];

                                            if( $col_index <= $group_columns ):

                                                echo ($row_index > 1)? "<td " . $class . ">" : "<th " . $class . ">";

                                                // echo $has_img;
                                                if ($has_img): ?>

                                                    <img loading="lazy" src="<?php echo $col_img_url; ?>" alt="<?php echo $col_img_alt; ?>" 
                                                        <?php echo ( $group_image_max_width )? 'style="max-width:'.$group_image_max_width.'px"':''; ?> /> 

                                                <?php else: 

                                                    echo $col_text;

                                                endif; ?>

                                                <?php
                                                echo ($row_index > 1)? "</td>" : "</th>";
                                            endif;

                                        endforeach; ?>
                                                                        
                                    </tr>

                                <?php endif;
                                    
                            endforeach; ?>

                            </tbody>
                        </table>
                        
                    <?php endif;
                
                endif;
                
            endforeach;
            
        endif;

    endif;

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'table', 'table_shortcode' );

//change author archive base
function change_author_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->author_base = 'auteur';
    $wp_rewrite->flush_rules();
}
add_action('init','change_author_permalinks');
  
function change_author_query_vars($vars) {
    $new_vars = array('auteur');
    $vars = $new_vars + $vars;
    return $vars;
}
add_filter('query_vars', 'change_author_query_vars');
  
function change_author_rewrite_rules( $wp_rewrite ) {
    $newrules = array();
    $new_rules['auteur/(\d*)$'] = 'index.php?author=$matches[1]';
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules','change_author_rewrite_rules');

// fix author archice schema
function author_change_canonical($url){
    if( is_author() ):
        return str_replace('author', 'auteur', $url);
    endif;
   
    return $url;
}
add_filter('wpseo_canonical' , 'author_change_canonical', 10,1);

function author_archive_change_url( $url ) {
    if( is_author() ):
        return str_replace('author', 'auteur', $url);
    endif;

    return $url;
}
add_filter( 'wpseo_opengraph_url', 'author_archive_change_url' );

function change_breadcrumbs_property_for_author( $data ) {
    if( is_author() ):
        if( isset($data['@id']) ):
            $data['@id'] = str_replace('author', 'auteur', $data['@id']);
        endif;

        if( isset($data['url']) ):
            $data['url'] = str_replace('author', 'auteur', $data['url']);
        endif;

        if( isset($data['breadcrumb']['@id']) ):
            $data['breadcrumb']['@id'] = str_replace('author', 'auteur', $data['breadcrumb']['@id']);
        endif;

        if( isset($data['potentialAction'][0]['target'][0]) ):
            $data['potentialAction'][0]['target'][0] = str_replace('author', 'auteur', $data['potentialAction'][0]['target'][0]);
        endif;

        if (array_key_exists('breadcrumb', $data)):
            unset($data['breadcrumb']);
        endif;
    endif;

    return $data;
}
add_filter( 'wpseo_schema_webpage', 'change_breadcrumbs_property_for_author', 11, 1 );

function yoast_seo_author_breadcrumb_append_link( $links ) {
    if( is_author() ):
        if( isset($links[1]['url']) ):
            $links[1]['url'] = str_replace('author', 'auteur', $links[1]['url']);
        endif;
    endif;

    return $links;
}
add_filter( 'wpseo_breadcrumb_links', 'yoast_seo_author_breadcrumb_append_link' );

function wpseo_schema_graph_pieces_author( $pieces, $context ) {
    if( is_author() ):
        return \array_filter( $pieces, function( $piece ) {
            return ! $piece instanceof \Yoast\WP\SEO\Generators\Schema\Breadcrumb;
        } );
    endif;
    return $pieces;
}
add_filter( 'wpseo_schema_graph_pieces', 'wpseo_schema_graph_pieces_author', 11, 2 );

// remove page/1/ from pagination
add_filter('paginate_links', function($link){
    if( is_paged() ){
        $link = str_replace('page/1/', '', $link);
    }
    return $link;
});

//ol
function ol_shortcode( $atts ) {
    $id = ( isset($atts['id']) && $atts['id'] )? $atts['id']:false;
    $bg= ( isset($atts['bg']) && $atts['bg'] == 'blue' )? $atts['bg']:'white';
    $template = ( isset($atts['template']) && $atts['template'] )? $atts['template']:'grid1';
    $view = ( ( isset($atts['view']) && $atts['template'] ) && ($template != 'grid1') )? true:false;
    ob_start();

    if( $id ):

        $ol_groups = get_field('ol_groups','option');

        if( $ol_groups ):

            foreach( $ol_groups as $key => $ol_group_item ):

                $group_id = ( isset($ol_group_item['id']) )? $ol_group_item['id']:false;

                if( $group_id == $id ): 
                
                    $group_how_to_title = ( isset($ol_group_item['how_to_title']) )? $ol_group_item['how_to_title']:false;
                    $group_has_images = ( isset($ol_group_item['has_images']) )? $ol_group_item['has_images']:false;
                    $group_image_size = ( isset($ol_group_item['image_size']) )? $ol_group_item['image_size']:false;
                    $group_image_max_width = ( isset($ol_group_item['image_max_width']) )? $ol_group_item['image_max_width']:false;
                    $group_show_json = ( isset($ol_group_item['show_json']) )? $ol_group_item['show_json']:false;
                    $has_images_class = ( $group_has_images )? 'has-image':'no-image';
                    $image_size_class = ( $group_image_size )? 'image-size-wide':'image-size-standard';
                    $items = ( isset($ol_group_item['ol_items']) )? $ol_group_item['ol_items']:false;

                    if( $items ): ?>
                
                    <ol class="ock-ol-el ock-ol-el-<?php echo $has_images_class; ?> ock-ol-el-<?php echo $image_size_class; ?> ock-ol-el-<?php echo $bg.' ock-ol-el-'.$template; ?>">
                        <?php foreach( $items as $key => $item ): 
                            $title = ( isset($item['title']) )? $item['title']:false;
                            $text = ( isset($item['text']) )? $item['text']:false;
                            $link = ( isset($item['link']) )? $item['link']:false;

                            $has_link = ( $link )? 'has-link':'no-link';

                            if( $title || $text ): ?>

                                <li class="ock-ol-el-item ock-ol-el-item-<?php echo $has_link; ?>" id="step<?php echo $key+1; ?>">
                                    <span class="ock-ol-el-index"><?php echo $key+1; ?></span>

                                    <?php if( $group_has_images ): ?>
                                        

                                            <?php $image = ( isset($item['image']) )? $item['image']:false; 
                                            // Check for alt view
                                            if($view) : ?>
                                                <div class="ock-ol-el-alt">
                                                    <?php if( !empty($image) ): ?>
                                                        <div class="image-max-width-wrap" <?php echo ( $group_image_max_width )? 'style="max-width:'.$group_image_max_width.'px"':''; ?>>
                                                            <img loading="lazy" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                        </div><!-- .image-max-width-wrap -->
                                                    <?php endif; ?>
                                                    <?php if( $title ): ?>
                                                        <h3 class="ock-ol-el-title-alt"><?php echo $title; ?></h3>
                                                    <?php endif; ?>
                                                </div>

                                            <?php else : ?>

                                                <?php if( !empty($image) ): ?>
                                                    <div class="ock-ol-el-image">
                                                        <div class="image-max-width-wrap" <?php echo ( $group_image_max_width )? 'style="max-width:'.$group_image_max_width.'px"':''; ?>>
                                                            <img loading="lazy" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                        </div><!-- .image-max-width-wrap -->
                                                    </div><!-- .ock-ol-el-image -->
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        
                                    <?php endif; ?>
    
                                    <div class="ock-ol-el-main">

                                        <?php if( $title && !$view ): ?>

                                            <h3 class="ock-ol-el-title"><?php echo $title; ?></h3>
                                            
                                        <?php endif; ?>

                                        <?php if( $text ): ?>

                                            <?php echo $text; ?>
                                            
                                        <?php endif; ?>

                                        <?php
                                        if( $link ): 
                                            $link_url = $link['url'];
                                            $link_title = $link['title'];
                                            $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        
                                            <div class="ock-ol-el-link">
                                                <a class="button casino-list-btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?><svg class="cta icon small"><use xlink:href="#right-chevron"></use></svg></a>
                                            </div><!-- .ock-ol-el-link -->
                                        
                                        <?php endif; ?>

                                    </div><!-- .ock-ol-el-main -->

                                </li><!-- .ock-ol-el-item -->

                            <?php endif; ?>
                            
                        <?php endforeach; ?>

                    </ol><!-- .ock-ol-el -->

                    <?php if( $group_has_images && $group_show_json ): ?>
                        
                        <script type="application/ld+json">
                            {
                            "@context": "https://schema.org",
                            "@type": "HowTo",
                            "image": {
                                "@type": "ImageObject",
                                "url": "<?php echo ( has_post_thumbnail() )? the_post_thumbnail_url():home_url('/').'wp-content/uploads/2019/12/ogimage-home.png'; ?>"
                            },
                            "name": "<?php echo $group_how_to_title; ?>",
                            "step":[
                                <?php foreach( $items as $key => $item ): 
                                    $title = ( isset($item['title']) )? wp_strip_all_tags($item['title']):false;
                                    $text = ( isset($item['text']) )? wp_strip_all_tags($item['text']):false;    
                                    $image = ( isset($item['image']) )? $item['image']:false;
                                    $step = $key+1;
                                ?>
                                    <?php echo ( $key )? ',':''; ?>{
                                    "@type": "HowToStep",
                                    "name": "<?php echo str_replace('"','\"', $title); ?>",
                                    "text": "<?php echo str_replace('"','\"', $text); ?>",
                                    <?php if( $group_has_images ): ?>
                                    "image": "<?php echo $image['url']; ?>",
                                    <?php endif; ?>
                                    "url": "<?php echo get_the_permalink().'#step'.$step; ?>"
                                    }
                                <?php endforeach; ?>
                            ]
                            }
                        </script>

                    <?php endif; ?>

                    <?php endif;
                
                endif;
                
            endforeach;
            
        endif;

    endif;

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'ol', 'ol_shortcode' );

//ul
function ul_shortcode( $atts ) {
    $id = ( isset($atts['id']) && $atts['id'] )? $atts['id']:false;
    $bg = ( isset($atts['bg']) && $atts['bg'] == 'blue' )? $atts['bg']:'white';
    $template = ( isset($atts['template']) && $atts['template'] )? $atts['template']:'grid1';
    $view = ( ( isset($atts['view']) && $atts['template'] ) && ($template != 'grid1') )? true:false;

    ob_start();

    if( $id ):

        $ul_groups = get_field('ul_groups','option');

        if( $ul_groups ):

            foreach( $ul_groups as $key => $ul_group_item ):

                $group_id = ( isset($ul_group_item['id']) )? $ul_group_item['id']:false;

                if( $group_id == $id ): 
                
                    $group_has_images = ( isset($ul_group_item['has_images']) )? $ul_group_item['has_images']:false;
                    $group_image_size = ( isset($ul_group_item['image_size']) )? $ul_group_item['image_size']:false;
                    $group_image_max_width = ( isset($ul_group_item['image_max_width']) )? $ul_group_item['image_max_width']:false;
                    $has_images_class = ( $group_has_images )? 'has-image':'no-image';
                    $image_size_class = ( $group_image_size )? 'image-size-wide':'image-size-standard';
                    $items = ( isset($ul_group_item['ul_items']) )? $ul_group_item['ul_items']:false;

                    if( $items ): ?>
                
                    <ul class="ock-ol-el ock-ol-el-<?php echo $has_images_class; ?> ock-ol-el-<?php echo $image_size_class; ?> ock-ol-el-<?php echo $bg.' ock-ol-el-'.$template; ?>">

                        <?php foreach( $items as $key => $item ): 
                            $title = ( isset($item['title']) )? $item['title']:false;
                            $text = ( isset($item['text']) )? $item['text']:false;
                            $link = ( isset($item['link']) )? $item['link']:false;

                            $has_link = ( $link )? 'has-link':'no-link';

                            if( $title || $text ): ?>

                                <li class="ock-ol-el-item ock-ol-el-item-<?php echo $has_link; ?>">
                                    
                                    <?php if( $group_has_images ): ?>
                                            

                                            <?php $image = ( isset($item['image']) )? $item['image']:false; 
                                            // Check for alt view
                                            if($view) : ?>
                                                <div class="ock-ol-el-alt ock-ul-el-alt">
                                                    <?php if( !empty($image) ): ?>
                                                        <div class="image-max-width-wrap" <?php echo ( $group_image_max_width )? 'style="max-width:'.$group_image_max_width.'px"':''; ?>>
                                                            <img loading="lazy" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                        </div><!-- .image-max-width-wrap -->
                                                    <?php endif; ?>
                                                    <?php if( $title ): ?>
                                                        <h3 class="ock-ol-el-title-alt"><?php echo $title; ?></h3>
                                                    <?php endif; ?>
                                                </div>

                                            <?php else : ?>

                                                <?php if( !empty($image) ): ?>
                                                    <div class="ock-ol-el-image">
                                                        <div class="image-max-width-wrap" <?php echo ( $group_image_max_width )? 'style="max-width:'.$group_image_max_width.'px"':''; ?>>
                                                            <img loading="lazy" src="<?php echo $image['sizes']['medium']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                        </div><!-- .image-max-width-wrap -->
                                                    </div><!-- .ock-ol-el-image -->
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        
                                    <?php endif; ?>
    
                                    <div class="ock-ol-el-main">

                                        <?php if( $title && !$view ): ?>

                                            <h3 class="ock-ol-el-title"><?php echo $title; ?></h3>
                                            
                                        <?php endif; ?>

                                        <?php if( $text ): ?>

                                            <?php echo $text; ?>
                                            
                                        <?php endif; ?>

                                        <?php
                                        if( $link ): 
                                            $link_url = $link['url'];
                                            $link_title = $link['title'];
                                            $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        
                                            <div class="ock-ol-el-link">
                                                <a class="button casino-list-btn" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>"><?php echo esc_html($link_title); ?><svg class="cta icon small"><use xlink:href="#right-chevron"></use></svg></a>
                                            </div><!-- .ock-ol-el-link -->
                                        
                                        <?php endif; ?>

                                    </div><!-- .ock-ol-el-main -->

                                </li><!-- .ock-ol-el-item -->

                            <?php endif; ?>
                            
                        <?php endforeach; ?>

                    </ul><!-- .ock-ol-el -->

                    <?php endif;
                
                endif;
                
            endforeach;
            
        endif;

    endif;

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
add_shortcode( 'ul', 'ul_shortcode' );