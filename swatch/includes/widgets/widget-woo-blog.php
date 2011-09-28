<?php
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: A custom WooThemes blog widget.
Date Created: 2011-09-01.
Last Modified: 2011-09-01.
Author: WooThemes.
Since: 1.1.0


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

class Woo_Widget_Blog extends WP_Widget {

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
	
	function Woo_Widget_Blog () {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_woo_blog';
		$this->woo_widget_description = __( 'This is a WooThemes standardized blog widget.', 'woothemes' );
		$this->woo_widget_idbase = 'woo_blog';
		$this->woo_widget_title = __('Woo - Blog', 'woothemes' );
		
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
		
		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('woo_home_blog_title', $instance['title'], $instance, $this->id_base );
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );
		
		$limit = $instance['limit'];
		
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
		global $woo_options;
		
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		$query_args = array(
							'paged' => $paged, 
							'posts_per_page' => $limit
							);
		
		query_posts( $query_args );
		
		if ( have_posts() ) { $count = 0;
			while ( have_posts() ) { the_post(); $count++; ?>
		                                                            
		    <div <?php post_class(); ?>>
		        
		        <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		                        
		        <div class="entry">
		            <?php the_excerpt(); ?>
		        </div>
		
		        <?php woo_post_meta(); ?>
		       
		    </div><!-- /.post -->                                     
		<?php
				} // End WHILE Loop
			} else {
		?>
		
		    <div <?php post_class(); ?>>
		        <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
		    </div><!-- /.post -->
		<?php
			}
			
			wp_reset_query();
	
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
		
		if ( ! is_numeric( $new_instance['limit'] ) || $new_instance['limit'] == '' ) { $new_instance['limit'] = 2; } // Set a default limit value.
		
		$instance['limit'] = intval( strip_tags( $new_instance['limit'] ) );
		
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
						'title' => __( 'Hits From The Blog', 'woothemes' ), 
						'limit' => 2
					);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Limit: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit (default: 2):', 'woothemes' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>
<?php
	} // End form()
	
} // End Class

/*----------------------------------------
  Register the widget on `widgets_init`.
  ----------------------------------------
  
  * Registers this widget.
----------------------------------------*/

add_action( 'widgets_init', create_function( '', 'return register_widget("Woo_Widget_Blog");' ), 1 ); 
?>