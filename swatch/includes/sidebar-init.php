<?php

// Register widgetized areas

if ( ! function_exists( 'the_widgets_init' ) ) {
	function the_widgets_init() {
	    if ( ! function_exists( 'register_sidebar' ) )
	        return;
	
		/* Primary Sidebar */
	    register_sidebar(array( 'name' => __( 'Primary', 'woothemes' ),'id' => 'primary','description' => __( 'Normal full width Sidebar', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	    
	    /* Homepage Sidebars */
	    register_sidebar(array( 'name' => __( 'Homepage - Left', 'woothemes' ),'id' => 'homepage-left','description' => __( 'The left column on the homepage', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => __( 'Homepage - Middle', 'woothemes' ),'id' => 'homepage-middle','description' => __( 'The middle column on the homepage', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => __( 'Homepage - Right', 'woothemes' ),'id' => 'homepage-right','description' => __( 'The right column on the homepage', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3 class="widget-title">','after_title' => '</h3>'));
	    
	    /* Footer Sidebars */
	    register_sidebar(array( 'name' => __( 'Footer 1', 'woothemes' ),'id' => 'footer-1', 'description' => "Widetized footer", 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => __( 'Footer 2', 'woothemes' ),'id' => 'footer-2', 'description' => "Widetized footer", 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => __( 'Footer 3', 'woothemes' ),'id' => 'footer-3', 'description' => "Widetized footer", 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	    register_sidebar(array( 'name' => __( 'Footer 4', 'woothemes' ),'id' => 'footer-4', 'description' => "Widetized footer", 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	}
}

add_action( 'init', 'the_widgets_init' );    
?>