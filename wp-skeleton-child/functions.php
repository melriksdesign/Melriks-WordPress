<?php
/* Load Child Theme Style with enqueue --------------------------------------------------------------------
http://wordpress.stackexchange.com/questions/116463/how-to-override-enqueued-styles-using-a-child-theme
*/

function cherrypick_child_enqueue_css()
{
    wp_register_style( 'theme-stylesheet', get_stylesheet_uri() );
    wp_enqueue_style( 'theme-stylesheet' );
}
add_action( 'wp_enqueue_scripts', 'cherrypick_child_enqueue_css', 99 ); // using priority 99 we make sure the child theme style.css will be loaded after all other css


// Removes misc stuff from wp_head in header.php ---------------------------------------------------
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'add_viewport_meta_tag' );
function add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
	//echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
}

/* Remove comments from pages ----------------------------------------------------------------------------------- 
http://wordpress.org/support/topic/turn-off-comments-on-pages-by-default */

add_filter( 'comments_template', 'remove_comments_template_on_pages', 11 );

function remove_comments_template_on_pages( $file ) {
if ( is_page() )
$file = STYLESHEETPATH . '/no-comments-please.php';
return $file;
}

/**
 * Get stylesheet directory for images -----------------------------------------------------------------------
 *
 * @author WPSnacks.com
 * @link http://www.wpsnacks.com
 */
function child_shortcode() {
return get_bloginfo('stylesheet_directory');
}
add_shortcode('path', 'child_shortcode');


/* Add slug to body class list 
http://stv.whtly.com/2011/02/19/wordpress-append-page-slug-to-body-class/ 
*/

function add_body_class( $classes )
{
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_body_class' );


/* Allow php in Widgets ------------------------------------------------ 
http://redstarwebdevelopment.com/2013/04/09/quick-tip-allow-php-in-wordpress-widgets/ */

add_filter('widget_text', 'php_text', 99);

function php_text($text) {
 if (strpos($text, '<' . '?') !== false) {
 ob_start();
 eval('?' . '>' . $text);
 $text = ob_get_contents();
 ob_end_clean();
 }
 return $text;
}

/*Allow Shortcodes in Widgets ----------------------------------
http://wordpress.org/support/topic/shortcode-in-text-widget-1

*/

//add_filter('widget_text', 'do_shortcode');



/* Remove stock jquery
http://codex.wordpress.org/Function_Reference/wp_deregister_script */

//wp_deregister_script( 'jquery' );


/* Add custom logo to the WordPress log-in page --------------------------------------------------
*/

function custom_login_logo() {
  echo '<style type="text/css">
    html { background: #ffffff none repeat scroll 0 0 !important; }
	body.login { margin-top:20px; background-color:#ffffff; }
	body.login #login { width:390px; padding-top:0; }
    body.login h1 a{
      background:url('.get_bloginfo('stylesheet_directory').'/images/logo.png) no-repeat top center ;
      height:104px;
	  width:355px;
    }
	
    body.login #nav { color:#EFEBEA; }
	body.login #nav a:first-of-type { display:none; }
		body.login-action-lostpassword #nav a + a  { display:none; }
		body.login.login-action-lostpassword #nav a:first-of-type { display:inline; }
		body.login #nav a:link { color:#011A58; }
		body.login #nav a:visited { color:#011A58; }
		body.login #nav a:hover { color:#1E8CBE; }
		body.login #nav a:active { color:#1E8CBE; }
    body.login #backtoblog { font-size:10px; margin-bottom:15px;  }
	#footer .wrap { border-top: 1px solid #777777; margin: 0 auto; overflow: hidden; padding: 10px 15px; width: 960px;}
	#footer .copyright { color: #777777; font-size: 10pt; height: 20px; padding-top: 5px; text-align:center; }
  </style>';
}
add_action('login_head', 'custom_login_logo');

// WordPress Log-in Page Change logo link location

function my_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );


/* Add footer to log-in page 
http://forum.ait-pro.com/forums/topic/customize-your-wordpress-login-page-customize-wp-login-php/
*/

// function to add a custom footer message and a custom link with a background color to make it pop
function aitpro_login_footer() {
echo '<div id="footer" class="row">';
echo '<div class="sixteen columns">';
echo '<div class="copyright">Copyright &copy;'.date('Y').' '.bloginfo('name').'</div>';
echo '</div></div>';
}
add_action( 'login_footer', 'aitpro_login_footer' );



/** Add a custom drop down to TinyMCE 
http://code.tutsplus.com/tutorials/adding-custom-styles-in-wordpress-tinymce-editor--wp-24980  **/

/*
Plugin Name: Custom Styles
Plugin URI: http://www.speckygeek.com
Description: Add custom styles in your posts and pages content using TinyMCE WYSIWYG editor. The plugin adds a Styles dropdown menu in the visual post editor.
Based on TinyMCE Kit plug-in for WordPress
http://plugins.svn.wordpress.org/tinymce-advanced/branches/tinymce-kit/tinymce-kit.php
*/
/**
 * Apply styles to the visual editor
 */ 
add_filter('mce_css', 'tuts_mcekit_editor_style');
function tuts_mcekit_editor_style($url) {
 
    if ( !empty($url) )
        $url .= ',';
 
    // Retrieves the plugin directory URL
    // Change the path here if using different directories
    $url .= trailingslashit( get_stylesheet_directory_uri() ) . '/company-editor-style.css';
 
    return $url;
}
 
/**
 * Add "Styles" drop-down
 */
add_filter( 'mce_buttons_2', 'tuts_mce_editor_buttons' );
 
function tuts_mce_editor_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
 
/**
 * Add styles/classes to the "Styles" drop-down
 http://code.tutsplus.com/tutorials/adding-custom-styles-in-wordpress-tinymce-editor--wp-24980
 http://wordpress.stackexchange.com/questions/128931/tinymce-adding-css-to-format-dropdown (stacked drop down)
 */
 
add_filter( 'tiny_mce_before_init', 'tuts_mce_before_init' );
 
function tuts_mce_before_init( $settings ) {
 
    $style_formats = array(
        array(
            'title' => 'Divs',
                'items' => array(
                array(
                    'title' => 'one columns',
                    'selector' => 'div',
					'classes' => 'one columns'
                ),
                array(
                    'title' => 'two columns',
                    'selector' => 'div',
					'classes' => 'two columns'
                ),
				array(
                    'title' => 'three columns',
                    'selector' => 'div',
					'classes' => 'three columns'
                ),
				array(
                    'title' => 'four columns',
                    'selector' => 'div',
					'classes' => 'four columns'
                ),
				array(
                    'title' => 'fivecolumns',
                    'selector' => 'div',
					'classes' => 'five columns'
                ),
				array(
                    'title' => 'six columns',
                    'selector' => 'div',
					'classes' => 'six columns'
                ),
				array(
                    'title' => 'seven columns',
                    'selector' => 'div',
					'classes' => 'seven columns'
                ),
				array(
                    'title' => 'eight columns',
                    'selector' => 'div',
					'classes' => 'eight columns'
                ),
				array(
                    'title' => 'none columns',
                    'selector' => 'div',
					'classes' => 'none columns'
                ),
				array(
                    'title' => 'one columns',
                    'selector' => 'div',
					'classes' => 'one columns'
                ),
				array(
                    'title' => 'ten columns',
                    'selector' => 'div',
					'classes' => 'ten columns'
                ),
				array(
                    'title' => 'eleven columns',
                    'selector' => 'div',
					'classes' => 'eleven columns'
                ),
				array(
                    'title' => 'twelve columns',
                    'selector' => 'div',
					'classes' => 'twelve columns'
                ),
				array(
                    'title' => 'thirteen columns',
                    'selector' => 'div',
					'classes' => 'thirteen columns'
                ),
				array(
                    'title' => 'fourteen columns',
                    'selector' => 'div',
					'classes' => 'fourteen columns'
                ),
				array(
                    'title' => 'fifteen columns',
                    'selector' => 'div',
					'classes' => 'fifteen columns'
                ),
				array(
                    'title' => 'sixteen columns',
                    'selector' => 'div',
					'classes' => 'sixteen columns'
                ),
				array(
                    'title' => 'one-third column',
                    'selector' => 'div',
					'classes' => 'one-third column'
                ),
				array(
                    'title' => 'two-thirds column',
                    'selector' => 'div',
					'classes' => 'two-thirds column'
                )
            )
        ),
       array(
            'title' => 'p',
                'items' => array(
                array(
                    'title' => 'one columns',
                    'selector' => 'p',
					'classes' => 'one columns'
                ),
                array(
                    'title' => 'two columns',
                    'selector' => 'p',
					'classes' => 'two columns'
                ),
				array(
                    'title' => 'three columns',
                    'selector' => 'p',
					'classes' => 'three columns'
                ),
				array(
                    'title' => 'four columns',
                    'selector' => 'p',
					'classes' => 'four columns'
                ),
				array(
                    'title' => 'fivecolumns',
                    'selector' => 'p',
					'classes' => 'five columns'
                ),
				array(
                    'title' => 'six columns',
                    'selector' => 'p',
					'classes' => 'six columns'
                ),
				array(
                    'title' => 'seven columns',
                    'selector' => 'p',
					'classes' => 'seven columns'
                ),
				array(
                    'title' => 'eight columns',
                    'selector' => 'p',
					'classes' => 'eight columns'
                ),
				array(
                    'title' => 'none columns',
                    'selector' => 'p',
					'classes' => 'none columns'
                ),
				array(
                    'title' => 'one columns',
                    'selector' => 'p',
					'classes' => 'one columns'
                ),
				array(
                    'title' => 'ten columns',
                    'selector' => 'p',
					'classes' => 'ten columns'
                ),
				array(
                    'title' => 'eleven columns',
                    'selector' => 'p',
					'classes' => 'eleven columns'
                ),
				array(
                    'title' => 'twelve columns',
                    'selector' => 'p',
					'classes' => 'twelve columns'
                ),
				array(
                    'title' => 'thirteen columns',
                    'selector' => 'p',
					'classes' => 'thirteen columns'
                ),
				array(
                    'title' => 'fourteen columns',
                    'selector' => 'p',
					'classes' => 'fourteen columns'
                ),
				array(
                    'title' => 'fifteen columns',
                    'selector' => 'p',
					'classes' => 'fifteen columns'
                ),
				array(
                    'title' => 'sixteen columns',
                    'selector' => 'p',
					'classes' => 'sixteen columns'
                ),
				array(
                    'title' => 'one-third column',
                    'selector' => 'p',
					'classes' => 'one-third column'
                ),
				array(
                    'title' => 'two-thirds column',
                    'selector' => 'p',
					'classes' => 'two-thirds column'
                )
            )
        )
    );
 
    $settings['style_formats'] = json_encode( $style_formats );
 
    return $settings;
 
}

/**
 * Show the Visual Editor Kitchen Sink Row by Default
 */

function unhide_kitchensink( $args ) {
$args['wordpress_adv_hidden'] = false;
return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );



//Giving Editors Access to Gravity Forms
function add_grav_forms(){
	$role = get_role('editor');
	$role->add_cap('gform_full_access');
	$role->add_cap('edit_theme_options');
}
add_action('admin_init','add_grav_forms');


/** Hiding Menus from Editors
http://wordpress.org/support/topic/how-do-i-add-css-to-admin-backend-to-displaynone-specific-menu-items
http://wordpress.org/support/topic/solution-to-how-to-allow-editors-to-edit-menus-only

**/

function custom_colors() {
	global $user_level;
	if ($user_level != '10' ) {
	   echo '<style type="text/css">
li#menu-appearance.wp-has-submenu li a[href="themes.php"] { display:none; } 
li#menu-appearance.wp-has-submenu li a[href="widgets.php"] {  }
li#menu-appearance.wp-has-submenu li a[href="customize.php"] { display:none; }
li#menu-appearance.wp-has-submenu li a[href="themes.php?page=custom-background"] { display:none; }
li#menu-appearance.wp-has-submenu li a[href="themes.php?page=custom-header"] { display:none; } 
</style>';
   }
}

add_action('admin_head', 'custom_colors');



/* Hide Admin bar from subscribers
http://digwp.com/2011/04/admin-bar-tricks/#disable-for-non-admins

*/

// show admin bar only for admins
if (!current_user_can('manage_options')) {
	add_filter('show_admin_bar', '__return_false');
}
// show admin bar only for admins and editors
if (!current_user_can('edit_posts')) {
	add_filter('show_admin_bar', '__return_false');
}


// Adds class to nav to allow for hiding of draft pages -------------------------------------------------------
// Adds the class of draft to menu
// .draft { display:none; }
// http://wordpress.stackexchange.com/questions/67929/exclude-private-draft-pages-from-primary-nav-when-using-custom-menu

add_filter('nav_menu_css_class' , 'nav_menu_add_post_status_class' , 10 , 2);
function nav_menu_add_post_status_class($classes, $item){
	$post_status = get_post_status($item->object_id);
	$classes[] = $post_status;
	return $classes;
}

//Widget support for the footer - Override for the Parent Function
register_sidebar( array(
	'name' => 'Footer Sidebar',
	'id' => 'footer-sidebar',
	'description' => 'Widgets in this area will be shown in the footer.',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title' => '<h4>',
	'after_title' => '</h4>'
));

add_filter('cWCdeveloperClasses','widgetclasses');

function widgetclasses(){
         $clsses['classes']= array(
            array(
                'class'=>'one columns',
                'desc'=>'one columns (40px)'

            ),
            array(
                'class'=>'two columns',
                'desc'=>'two columns (100px)'
            ),
			array(
                'class'=>'three columns',
                'desc'=>'three columns (160px)'
            ),
			array(
                'class'=>'four columns',
                'desc'=>'four columns (220px)'
            ),
			array(
                'class'=>'five columns',
                'desc'=>'five columns (280px)'
            ),
			array(
                'class'=>'six columns',
                'desc'=>'six columns (340px)'
            ),
			array(
                'class'=>'seven columns',
                'desc'=>'seven columns (400px)'
            ),
			array(
                'class'=>'eight columns',
                'desc'=>'eight columns (460px)'
            ),
			array(
                'class'=>'nine columns',
                'desc'=>'nine columns (520px)'
            ),
			array(
                'class'=>'ten columns',
                'desc'=>'ten columns (580px)'
            ),
			array(
                'class'=>'eleven columns',
                'desc'=>'eleven columns (640px)'
            ),
			array(
                'class'=>'twelve columns',
                'desc'=>'twelve columns (700px)'
            ),
			array(
                'class'=>'two columns',
                'desc'=>'two columns (760px)'
            ),
			array(
                'class'=>'thirteen columns',
                'desc'=>'thirteen columns (820px)'
            ),
			array(
                'class'=>'fourteen columns',
                'desc'=>'fourteen columns (900px)'
            ),
			array(
                'class'=>'fifteen columns',
                'desc'=>'fifteen columns (960px)'
            ),
			array(
                'class'=>'sixteen columns',
                'desc'=>'sixteen columns (960px)'
            ),
				array(
                'class'=>'one-third column',
                'desc'=>'one-third column'
            ),
				array(
                'class'=>'two-thirds column',
                'desc'=>'two-thirds column'
            )
        );              

    return $clsses;         
}


/**
 * Add "first" and "last" CSS classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
 */
function widget_first_last_classes($params) {

	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	

	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

	if($my_widget_num[$this_id] == 1) { // If this is the first widget
		$class .= 'alpha ';
	} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
		$class .= 'omega ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;

}
add_filter('dynamic_sidebar_params','widget_first_last_classes');



/* Add Company Phone number field 
https://trepmal.com/2011/03/07/add-field-to-general-settings-page/
*/

$new_general_setting = new new_general_setting();

class new_general_setting {
    function new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    function register_fields() {
        register_setting( 'general', 'telephone', 'esc_attr' );
        add_settings_field('telephone', '<label for="telephone">'.__('Telephone?' , 'telephone' ).'</label>' , array(&$this, 'fields_html') , 'general' );
    }
    function fields_html() {
        $value = get_option( 'telephone', '' );
        echo '<input type="text" id="telephone" name="telephone" value="' . $value . '" />';
    }
}



/* Exclude pages from WordPress Search
http://www.johnparris.com/exclude-certain-pages-from-wordpress-search-results/
*/

function jp_search_filter( $query ) {
  if ( $query->is_search && $query->is_main_query() ) {
    $query->set( 'post__not_in', array( 22,23,24,25 ) );
  }
}
add_filter( 'pre_get_posts', 'jp_search_filter' );