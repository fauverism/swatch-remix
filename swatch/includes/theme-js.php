<?php
if ( ! is_admin() ) {
	add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
	add_action( 'wp_print_styles', 'woothemes_add_css' );
}

if ( ! function_exists( 'woothemes_add_javascript' ) ) {
	function woothemes_add_javascript() {
		global $woo_options;
		
		wp_register_script( 'slides', get_template_directory_uri() . '/includes/js/slides.min.jquery.js', array( 'jquery' ) );
		wp_register_script( 'prettyPhoto', get_template_directory_uri() . '/includes/js/jquery.prettyPhoto.js', array( 'jquery' ) );
		wp_register_script( 'jCarousel', get_template_directory_uri() . '/includes/js/jquery.jcarousel.min.js', array( 'jquery' ) );
		wp_register_script( 'portfolio', get_template_directory_uri() . '/includes/js/portfolio.js', array( 'jquery', 'prettyPhoto', 'jCarousel', 'slides' ) );
		wp_register_script( 'woo-feedback', get_template_directory_uri() . '/includes/js/feedback.js', array( 'jquery', 'slides' ) );
		
		// Load the prettyPhoto JavaScript and CSS for use on the portfolio page template.
		
		if ( is_page_template( 'template-portfolio.php' ) || is_front_page() || ( is_singular() && get_post_type() == 'portfolio' ) || is_tax( 'portfolio-gallery' ) ) {			
			wp_enqueue_script( 'portfolio' );
		}
		
		// Conditionally load the Feedback JavaScript, where needed.
		$load_feedback_js = false;
		
		if ( is_page_template( 'template-feedback.php' ) ) {
			$load_feedback_js = true;
		}
			 
		// Allow child themes/plugins to load the Feedback JavaScript when they need it.
		$load_feedback_js = apply_filters( 'woo_load_feedback_js', $load_feedback_js );

		if ( $load_feedback_js ) { wp_enqueue_script( 'woo-feedback' ); }
		
		do_action( 'woothemes_add_javascript' );
		
		wp_enqueue_script( 'general', get_template_directory_uri().'/includes/js/general.js', array( 'jquery' ) );
		
		// Load the JavaScript for the slides and testimonals on the homepage.
		
		if ( is_home() ) {
			wp_enqueue_script( 'slides' );
			
			// Load the custom slider settings.
			
			$autoStart = false;
			$autoSpeed = 6;
			$slideSpeed = 0.5;
			$effect = 'slide';
			$nextprev = 'true';
			$pagination = 'true';
			$hoverpause = 'true';
			$autoheight = 'false';
			
			// Get our values from the database and, if they're there, override the above defaults.
			$fields = array(
							'autoStart' => 'auto', 
							'autoSpeed' => 'interval', 
							'slideSpeed' => 'speed', 
							'effect' => 'effect', 
							'nextprev' => 'nextprev', 
							'pagination' => 'pagination', 
							'hoverpause' => 'hover', 
							'autoHeight' => 'autoheight'
							);
			
			foreach ( $fields as $k => $v ) {
				if ( is_array( $woo_options ) && isset( $woo_options['woo_slider_' . $v] ) && $woo_options['woo_slider_' . $v] != '' ) {
					${$k} = $woo_options['woo_slider_' . $v];
				}
			}
			
			// Set auto speed to 0 if we want to disable automatic sliding.
			if ( $autoStart == 'false' ) {
				$autoSpeed = 0;
			}
			
			$data = array(
						'speed' => $slideSpeed, 
						'auto' => $autoSpeed, 
						'effect' => $effect, 
						'nextprev' => $nextprev, 
						'pagination' => $pagination, 
						'hoverpause' => $hoverpause, 
						'autoheight' => $autoHeight
						);
						
			wp_localize_script( 'general', 'woo_slider_settings', $data );
		}
		
	} // End woothemes_add_javascript()
}

if ( ! function_exists( 'woothemes_add_css' ) ) {
	function woothemes_add_css () {
		
		wp_register_style( 'prettyPhoto', get_template_directory_uri().'/includes/css/prettyPhoto.css' );
	
		if ( is_page_template('template-portfolio.php') || is_front_page() || ( is_singular() && get_post_type() == 'portfolio' ) || is_tax( 'portfolio-gallery' ) ) {
			wp_enqueue_style( 'prettyPhoto' );
		}
	
		do_action( 'woothemes_add_css' );
	
	} // End woothemes_add_css()
}
?>