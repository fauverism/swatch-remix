<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Add custom styling to HEAD
- Add custom typograhpy to HEAD
- Add layout to body_class output
- woo_feedburner_link

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Theme Setup */
/*-----------------------------------------------------------------------------------*/
/**
 * Theme Setup
 *
 * This is the general theme setup, where we add_theme_support(), create global variables
 * and setup default generic filters and actions to be used across our theme.
 *
 * @package WooFramework
 * @subpackage Logic
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */

if ( ! isset( $content_width ) ) $content_width = 515; // The width of the ".entry" DIV.

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support for post thumbnails.
 *
 * To override woothemes_setup() in a child theme, add your own woothemes_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for automatic feed links.
 * @uses add_editor_style() To style the visual editor.
 */

add_action( 'after_setup_theme', 'woothemes_setup' );

if ( ! function_exists( 'woothemes_setup' ) ) {
	function woothemes_setup () {

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		if ( is_child_theme() ) {
			$theme_data = get_theme_data( get_bloginfo( 'stylesheet_url' ) );

			define( 'CHILD_THEME_URL', $theme_data['URI'] );
			define( 'CHILD_THEME_NAME', $theme_data['Name'] );
		}

	}
}

/*-----------------------------------------------------------------------------------*/
/* Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head','woo_custom_styling' );			// Add custom styling to HEAD

if ( ! function_exists( 'woo_custom_styling' ) ) {
function woo_custom_styling() {
	
		global $woo_options;
		
		$output = '';
		// Get options
		$options = array( 'body_color', 'body_img', 'body_repeat', 'body_pos', 'link_color', 'link_hover_color', 'button_color' );
		$shortname = 'woo_';
		
		foreach ( $options as $o ) {
			${$o} = '';
			if ( isset( $woo_options[$shortname . $o] ) && ( $woo_options[$shortname . $o] != '' ) ) {
				${$o} = $woo_options[$shortname . $o];
			}
		}
			
		// Add CSS to output
		if ( $body_color ) {
			$output .= 'body { background-color: ' . $body_color . ' !important; }' . "\n";
			if ( ! $body_img ) { $output .= 'body { background-image: none; }' . "\n"; } // Disable the default background image if a background colour is set.
		}
		
		if ( $body_img )
			$output .= 'body { background-image: url( ' . $body_img . ' ) !important; }' . "\n";

		if ( $body_img && $body_repeat && $body_pos )
			$output .= 'body { background-repeat: ' . $body_repeat . ' !important; }' . "\n";

		if ( $body_img && $body_pos )
			$output .= 'body { background-position: ' . $body_pos . ' !important; }' . "\n";

		if ( $link_color ) {
			$output .= '#wrapper a, .post-more a:link, .post-meta a:link, .post p.tags a:link, #wrapper #comments .reply a { color: ' . $link_color . ' !important; }' . "\n";
			$output .= '#wrapper a, .post-more a:visited, .post-meta a:visited, .post p.tags a:visited, #wrapper #comments .reply a:visited { color: ' . $link_color . ' !important; }' . "\n";
		}

		if ( $link_hover_color )
			$output .= '#wrapper a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover, #wrapper #comments .reply a:hover { color: ' . $link_hover_color . ' !important; }' . "\n";

		if ( $button_color ) {
			$output .= 'a.button, #commentform #submit, #contact-page .submit { background: ' . $button_color . '; border-color: ' . $button_color . ' !important; }' . "\n";
			$output .= 'a.button:hover, a.button.hover, a.button.active, #commentform #submit:hover, #contact-page .submit:hover { background: ' . $button_color . ' !important; opacity: 0.9; }' . "\n";
		}
		
		// Output styles
		if ( isset( $output ) && $output != '' ) {
			$output = strip_tags( $output );
			$output = "<!-- Woo Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
			
	}
}

/*-----------------------------------------------------------------------------------*/
/* Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/

add_action( 'woo_head', 'woo_custom_typography' );			// Add custom typography to HEAD

if ( ! function_exists( 'woo_custom_typography' ) ) {
	function woo_custom_typography() {
	
		// Get options
		global $woo_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( isset( $woo_options['woo_texttitle'] ) && $woo_options['woo_texttitle'] == 'true' ) {		
			
			if ( $woo_options['woo_font_site_title'] )
				$output .= 'body #wrapper #logo .site-title a { '.woo_generate_font_css($woo_options['woo_font_site_title']).' }' . "\n";	
			if ( $woo_options['woo_tagline'] == "true" AND $woo_options['woo_font_tagline'] ) 
				$output .= '#logo .site-description { '.woo_generate_font_css($woo_options[ 'woo_font_tagline']).'; }' . "\n";	
		}

		if ( isset( $woo_options['woo_typography'] ) && $woo_options['woo_typography'] == 'true' ) {
			
			if ( isset( $woo_options['woo_font_body'] ) && $woo_options['woo_font_body'] )
				$output .= 'body { '.woo_generate_font_css($woo_options['woo_font_body'], '1.5').' }' . "\n";	

			if ( isset( $woo_options['woo_font_nav'] ) && $woo_options['woo_font_nav'] )
				$output .= '#navigation, #navigation .nav a { '.woo_generate_font_css($woo_options['woo_font_nav'], '1.4').' !important; }' . "\n";	

			if ( isset( $woo_options['woo_font_post_title'] ) && $woo_options['woo_font_post_title'] )
				$output .= '.post .title { '.woo_generate_font_css($woo_options[ 'woo_font_post_title' ]).' }' . "\n";	
		
			if ( isset( $woo_options['woo_font_post_meta'] ) && $woo_options['woo_font_post_meta'] )
				$output .= '.post-meta { '.woo_generate_font_css($woo_options[ 'woo_font_post_meta' ]).' }' . "\n";	

			if ( isset( $woo_options['woo_font_post_entry'] ) && $woo_options['woo_font_post_entry'] )
				$output .= '.entry, .entry p { '.woo_generate_font_css($woo_options[ 'woo_font_post_entry' ], '1.5').' } h1, h2, h3, h4, h5, h6 { font-family: '.stripslashes($woo_options[ 'woo_font_post_entry' ]['face']).', arial, sans-serif; }'  . "\n";	

			if ( isset( $woo_options['woo_font_widget_titles'] ) && $woo_options['woo_font_widget_titles'] )
				$output .= '#sidebar .widget h3 { '.woo_generate_font_css($woo_options[ 'woo_font_widget_titles' ]).' }'  . "\n";
				
			if ( isset( $woo_options['woo_font_footer_widget_titles'] ) && $woo_options['woo_font_footer_widget_titles'] )
				$output .= '#footer-widgets .widget h3 { '.woo_generate_font_css($woo_options[ 'woo_font_footer_widget_titles' ]).' }'  . "\n";

		// Add default typography Google Font
		} else {
		
			// Set default font face
			// $woo_options['woo_default_face'] = array('face' => 'Arial, Helvetica, sans-serif');
			
			// Output default font face
			if ( isset( $woo_options['woo_default_face'] ) && !empty( $woo_options['woo_default_face'] ) )
				$output .= 'h1, h2, h3, h4, h5, h6, .post .title, .archive_header { '.woo_generate_font_css($woo_options['woo_default_face']).' }' . "\n";			
		
		} 
		
		// Output styles
		if (isset($output) && $output != '') {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists( 'woo_google_webfonts')) woo_google_webfonts();
			
			$output = "<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n\n";
			echo $output;
			
		}
			
	}
}

// Returns proper font css output
if (!function_exists( 'woo_generate_font_css')) {
	function woo_generate_font_css($option, $em = '1') {

		// Test if font-face is a Google font
		global $google_fonts;
		foreach ( $google_fonts as $google_font ) {

			// Add single quotation marks to font name and default arial sans-serif ending
			if ( $option[ 'face' ] == $google_font[ 'name' ] )
				$option[ 'face' ] = "'" . $option[ 'face' ] . "', arial, sans-serif";

		} // END foreach

		if ( !@$option["style"] && !@$option["size"] && !@$option["unit"] && !@$option["color"] )
			return 'font-family: '.stripslashes($option["face"]).';';
		else
			return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].' !important;';
	}
}

// Output stylesheet and custom.css after custom styling
remove_action( 'wp_head', 'woothemes_wp_head' );
add_action( 'woo_head', 'woothemes_wp_head' );


/*-----------------------------------------------------------------------------------*/
/* Add layout to body_class output */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class','woo_layout_body_class' );		// Add layout to body_class output

if (!function_exists( 'woo_layout_body_class')) {
	function woo_layout_body_class($classes) {
		
		global $woo_options;
		
		$layouts = array( 'layout-left-content' => 'two-col-left', 'layout-right-content' => 'two-col-right' );
		
		$layout = '';
		// Set main layout
		if ( is_singular() ) {
			global $post;
			$layout = get_post_meta($post->ID, '_layout', true);
			if ( $layout != '' ) {
				global $woo_options;
				$woo_options['woo_layout'] = $layout;
			}
		}
		if ( $layout == '' ) {
			$layout = get_option( 'woo_layout' );
		}
		
		// Cater for custom portfolio gallery layout option.
		if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) ) {
			$portfolio_gallery_layout = get_option( 'woo_portfolio_layout' );
			
			if ( $portfolio_gallery_layout != '' ) { $layout = $portfolio_gallery_layout; }
		}
		
		if ( $layout == '' || $layout == 'layout-default' || $layout == 'false' ) { 
			$layout = $layouts[$woo_options['woo_site_layout']];
		}

		if ( is_tax( 'portfolio-gallery' ) || is_post_type_archive( 'portfolio' ) || is_page_template( 'template-portfolio.php' ) || ( is_singular() && get_post_type() == 'portfolio' ) ) {
			$classes[] = 'portfolio-component';
		}
		
		// Add layout to $woo_options array for use in theme
		$woo_options['woo_layout'] = $layout;
		
		// Add classes to body_class() output 
		$classes[] = $layout;
		return $classes;						
					
	}
}


/*-----------------------------------------------------------------------------------*/
/* woo_feedburner_link() */
/*-----------------------------------------------------------------------------------*/
/**
 * woo_feedburner_link()
 *
 * Replace the default RSS feed link with the Feedburner URL, if one
 * has been provided by the user.
 *
 * @package WooFramework
 * @subpackage Filters
 */

add_filter( 'feed_link', 'woo_feedburner_link', 10 );

function woo_feedburner_link ( $output, $feed = null ) {

	global $woo_options;

	$default = get_default_feed();

	if ( ! $feed ) $feed = $default;

	if ( $woo_options[ 'woo_feed_url' ] && ( $feed == $default ) && ( ! stristr( $output, 'comments' ) ) ) $output = esc_url( $woo_options[ 'woo_feed_url' ] );

	return $output;

} // End woo_feedburner_link()

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>