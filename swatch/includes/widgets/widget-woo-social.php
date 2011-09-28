<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes social links widget.
Date Created: 2011-08-29.
Last Modified: 2011-08-29.
Author: WooThemes.
Since: 1.0.0


TABLE OF CONTENTS

- var $woo_widget_cssclass
- var $woo_widget_description
- var $woo_widget_idbase
- var $woo_widget_title

- function (constructor)
- function widget ()
- function update ()
- function form ()

- Register the widget on `widgets_init`.

-----------------------------------------------------------------------------------*/

class Woo_Widget_Social extends WP_Widget {

	/*----------------------------------------
	  Variable Declarations.
	  ----------------------------------------
	  
	  * Variables to setup the widget.
	----------------------------------------*/

	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_title;

	/*----------------------------------------
	  Constructor.
	  ----------------------------------------
	  
	  * The constructor. Sets up the widget.
	----------------------------------------*/
	
	function Woo_Widget_Social () {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_woo_social';
		$this->woo_widget_description = __( 'This is a WooThemes standardized social links widget.', 'woothemes' );
		$this->woo_widget_idbase = 'woo_social';
		$this->woo_widget_title = __('Woo - Social', 'woothemes' );
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->woo_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->woo_widget_idbase, $this->woo_widget_title, $widget_ops, $control_ops );
		
	} // End Constructor

	/*----------------------------------------
	  widget()
	  ----------------------------------------
	  
	  * Displays the widget on the frontend.
	----------------------------------------*/

	function widget( $args, $instance ) {  
		global $woo_options;
		
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );
			
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title != '' ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_top' );
		
		// Load widget content here.
?>
		<div id="social">
		<?php if ( isset( $woo_options['woo_connect_facebook'] ) && ( $woo_options['woo_connect_facebook'] != '' ) ) { ?>
			<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook" title="<?php _e( 'Connect on Facebook', 'woothemes' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/social-fb.png" alt="<?php _e( 'Connect on Facebook', 'woothemes' ); ?>" /></a>
		<?php } ?>
		<?php if ( isset( $woo_options['woo_connect_flickr'] ) && ( $woo_options['woo_connect_flickr'] != '' ) ) { ?>
			<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr" title="<?php _e( 'See photos on Flickr', 'woothemes' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/social-fl.png" alt="<?php _e( 'See photos on Flickr', 'woothemes' ); ?>" /></a>
		<?php } ?>
		<?php if ( isset( $woo_options['woo_connect_twitter'] ) && ( $woo_options['woo_connect_twitter'] != '' ) ) { ?>
			<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter" title="<?php _e( 'Follow us on Twitter', 'woothemes' ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/social-tw.png" alt="<?php _e( 'Follow us on Twitter', 'woothemes' ); ?>" /></a>
		<?php } ?>
			<a href="<?php if ( isset( $woo_options['woo_feed_url'] ) && ( $woo_options['woo_feed_url'] != '' ) ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/social-rs.png" alt="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>" /></a>
		</div><!--/#social-->
<?php	
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;

	} // End widget()

	/*----------------------------------------
	  update()
	  ----------------------------------------
	
	* Function to update the settings from
	* the form() function.
	
	* Params:
	* - Array $new_instance
	* - Array $old_instance
	----------------------------------------*/
	
	function update ( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
		
	} // End update()

   /*----------------------------------------
	 form()
	 ----------------------------------------
	  
	  * The form on the widget control in the
	  * widget administration area.
	  
	  * Make use of the get_field_id() and 
	  * get_field_name() function when creating
	  * your form elements. This handles the confusing stuff.
	  
	  * Params:
	  * - Array $instance
	----------------------------------------*/

   function form( $instance ) {       
   
		/* Set up some default widget settings. */
		$defaults = array(
						'title' => __( 'Follow this Blog', 'woothemes' )
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<p><small><?php printf( __( 'The Facebook, Twitter and Flickr URLs can be entered in under the "Subscribe & Connect" section of the %sTheme Options%s screen.' ), '<a href="' . admin_url( 'admin.php?page=woothemes' ) . '">', '</a>' ); ?></small></p>
<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Social");' ), 1 ); 
?>