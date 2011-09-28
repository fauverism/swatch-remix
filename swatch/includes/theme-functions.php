<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Exclude categories from displaying on the "Blog" page template.
- Exclude categories from displaying on the homepage.
- Register WP Menus
- Page navigation
- Post Meta
- Subscribe & Connect
- Comment Form Fields
- Comment Form Settings
- Custom Post Type - Mini Features
- Custom Post Type - Slides
- woo_slides_add_sortable_columns() - Make "menu_order" column in admin sortable.
- woo_slides_add_custom_column_headings() - Add custom column headings for "slide" post type admin.
- woo_slides_add_custom_column_data() - Add custom column data for "slide" post type admin.
- Custom Post Type - Portfolio
- woo_portfolio_change_default_admin_sorting() - Set the default admin sorting field to "menu_order" and sort order to "asc".
- woo_portfolio_add_sortable_columns() - Make "menu_order" column in admin sortable.
- woo_portfolio_add_custom_column_headings() - Add custom column headings for "portfolio" post type admin.
- woo_portfolio_add_custom_column_data() - Add custom column data for "portfolio" post type admin.
- Get Post image attachments
- Woo Portfolio Meta
- Woo Portfolio Navigation
- Woo Portfolio Item Extras (Testimonial and Link)
- Woo Portfolio Item Settings
- Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs
- Woo Portfolio, change the "post more" content for portfolio items.
- Woo Portfolio, get image dimensions based on layout and website width settings.
- Custom Post Type - Feedback (Feedback Component)
- Woo Feedback, woo_get_feedback_entries()
- Woo Feedback, woo_display_feedback_entries()
- Add custom CSS class to the <body> tag if the lightbox option is enabled.
- Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled.

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'woo_blog_template_query_args', 'woo_exclude_categories_blogtemplate' );

function woo_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_blog' );

	// Homepage logic.
	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

} // End woo_exclude_categories_blogtemplate()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the homepage.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the homepage.
add_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );

function woo_exclude_categories_homepage ( $query ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $query; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_home' );

	// Homepage logic.
	if ( is_home() && ( count( $excluded_cats ) > 0 ) ) {
		$query->set( 'category__not_in', $excluded_cats );
	}

	$query->parse_query();

	return $query;

} // End woo_exclude_categories_homepage()

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'woothemes' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'woothemes' ) ) );
}


/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <div class="nav-entries">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( '<span class="meta-nav">&larr;</span> Older posts', 'woothemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'woothemes' ) . '</span>' ); ?>
                <div class="fix"></div>
            </div>
		<?php
			}
		} else {
			woo_pagination();

		} // End IF Statement

	} // End woo_pagenav()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_tabs_popular')) {
	function woo_tabs_popular( $posts = 5, $size = 45 ) {
		global $post;
		$popular = get_posts( 'caller_get_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li>
		<?php if ($size <> 0) woo_image( 'height='.$size.'&width='.$size.'&class=thumbnail&single=true' ); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<div class="fix"></div>
	</li>
	<?php endforeach;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_post_meta' ) ) {
	function woo_post_meta() {
?>
<p class="post-meta">
    <span class="post-author"><span class="small"><?php _e( 'by', 'woothemes' ) ?></span> <?php the_author_posts_link(); ?></span>
    <span class="post-date"><span class="small"><?php _e( 'on', 'woothemes' ) ?></span> <?php the_time( get_option( 'date_format' ) ); ?></span>
    <span class="post-category"><span class="small"><?php _e( 'in', 'woothemes' ) ?></span> <?php the_category( ', ' ); ?></span>
    <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>
</p>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_subscribe_connect' ) ) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<div id="connect">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-rss.png" title="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-twitter.png" title="<?php _e('Follow us on Twitter', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-facebook.png" title="<?php _e('Connect on Facebook', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_youtube'] ); ?>" class="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-youtube.png" title="<?php _e('Watch on YouTube', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-flickr.png" title="<?php _e('See photos on Flickr', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_linkedin'] ); ?>" class="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-linkedin.png" title="<?php _e('Connect on LinkedIn', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_delicious'] ); ?>" class="delicious"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-delicious.png" title="<?php _e('Discover on Delicious', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_googleplus'] ); ?>" class="googleplus"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-googleplus.png" title="<?php _e('View Google+ profile', 'woothemes'); ?>" alt=""/></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</div>
	<?php endif; ?>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Fields */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_default_fields', 'woo_comment_form_fields' );

	if ( ! function_exists( 'woo_comment_form_fields' ) ) {
		function woo_comment_form_fields ( $fields ) {

			$commenter = wp_get_current_commenter();

			$required_text = ' <span class="required">(' . __( 'Required', 'woothemes' ) . ')</span>';

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$fields =  array(
				'author' => '<p class="comment-form-author">' .
							'<input id="author" class="txt" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="author">' . __( 'Name' ) . ( $req ? $required_text : '' ) . '</label> ' .
							'</p>',
				'email'  => '<p class="comment-form-email">' .
				            '<input id="email" class="txt" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				            '<label for="email">' . __( 'Email' ) . ( $req ? $required_text : '' ) . '</label> ' .
				            '</p>',
				'url'    => '<p class="comment-form-url">' .
				            '<input id="url" class="txt" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
				            '<label for="url">' . __( 'Website' ) . '</label>' .
				            '</p>',
			);

			return $fields;

		} // End woo_comment_form_fields()
	}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Settings */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_defaults', 'woo_comment_form_settings' );

	if ( ! function_exists( 'woo_comment_form_settings' ) ) {
		function woo_comment_form_settings ( $settings ) {

			$settings['comment_notes_before'] = '';
			$settings['comment_notes_after'] = '';
			$settings['label_submit'] = __( 'Submit Comment', 'woothemes' );
			$settings['cancel_reply_link'] = __( 'Click here to cancel reply.', 'woothemes' );

			return $settings;

		} // End woo_comment_form_settings()
	}

/*-----------------------------------------------------------------------------------*/
/* Misc back compat */
/*-----------------------------------------------------------------------------------*/

// array_fill_keys doesn't exist in PHP < 5.2
// Can remove this after PHP <  5.2 support is dropped
if ( !function_exists( 'array_fill_keys' ) ) {
	function array_fill_keys( $keys, $value ) {
		return array_combine( $keys, array_fill( 0, count( $keys ), $value ) );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Mini Features */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'woo_add_infoboxes', 10 );
if ( ! function_exists( 'woo_add_infoboxes' ) ) {
	function woo_add_infoboxes() 
	{
	  $labels = array(
	    'name' => _x( 'Mini-Features', 'post type general name', 'woothemes' ),
	    'singular_name' => _x( 'Mini-Feature', 'post type singular name', 'woothemes' ),
	    'add_new' => _x( 'Add New', 'infobox', 'woothemes' ),
	    'add_new_item' => __( 'Add New Mini-Feature', 'woothemes' ),
	    'edit_item' => __( 'Edit Mini-Feature', 'woothemes' ),
	    'new_item' => __( 'New Mini-Feature', 'woothemes' ),
	    'view_item' => __( 'View Mini-Feature', 'woothemes' ),
	    'search_items' => __( 'Search Mini-Features', 'woothemes' ),
	    'not_found' =>  __( 'No Mini-Features found', 'woothemes' ),
	    'not_found_in_trash' => __( 'No Mini-Features found in Trash', 'woothemes' ), 
	    'parent_item_colon' => ''
	  );
	  
	  $infobox_rewrite = get_option( 'woo_infobox_rewrite' );
	  if( empty( $infobox_rewrite ) ) { $infobox_rewrite = 'infobox'; }
	  
	  $args = array(
	    'labels' => $labels,
	    'public' => true,
	    'publicly_queryable' => true,
	    'show_ui' => true, 
	    'query_var' => true,
	    'rewrite' => array( 'slug'=> $infobox_rewrite ),
	    'capability_type' => 'post',
	    'hierarchical' => false,
	    'menu_icon' => get_template_directory_uri() . '/includes/images/box.png',
	    'menu_position' => null,
	    'supports' => array( 'title', 'editor', /*'author', 'thumbnail', 'excerpt', 'comments'*/ )
	  ); 
	  register_post_type( 'infobox', $args );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Slides */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'woo_add_slides', 10 );
if ( ! function_exists ( 'woo_add_slides' ) ) {
	function woo_add_slides() 
	{
	  $labels = array(
	    'name' => _x( 'Slides', 'post type general name', 'woothemes', 'woothemes' ),
	    'singular_name' => _x( 'Slide', 'post type singular name', 'woothemes' ),
	    'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
	    'add_new_item' => __( 'Add New Slide', 'woothemes' ),
	    'edit_item' => __( 'Edit Slide', 'woothemes' ),
	    'new_item' => __( 'New Slide', 'woothemes' ),
	    'view_item' => __( 'View Slide', 'woothemes' ),
	    'search_items' => __( 'Search Slides', 'woothemes' ),
	    'not_found' =>  __( 'No slides found', 'woothemes' ),
	    'not_found_in_trash' => __( 'No slides found in Trash', 'woothemes' ), 
	    'parent_item_colon' => ''
	  );
	  $args = array(
	    'labels' => $labels,
	    'public' => false,
	    'publicly_queryable' => false,
	    'show_ui' => true, 
	    'query_var' => true,
	    'rewrite' => true,
	    'capability_type' => 'post',
	    'hierarchical' => false,
	    'menu_icon' => get_template_directory_uri() . '/includes/images/slides.png',
	    'menu_position' => null,
	    'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes'/*, 'author', 'thumbnail', 'excerpt', 'comments'*/ )
	  ); 
	  register_post_type( 'slide', $args );
	}
}

/*-----------------------------------------------------------------------------------*/
/* woo_slides_add_sortable_columns() */
/*-----------------------------------------------------------------------------------*/

	if ( is_admin() ) { add_filter( 'manage_edit-slide_sortable_columns', 'woo_slides_add_sortable_columns', 10, 1 ); }
	
	if ( ! function_exists( 'woo_slides_add_sortable_columns' ) ) {
		function woo_slides_add_sortable_columns ( $columns ) {
			$columns['menu_order'] = 'menu_order';
			return $columns;
		} // End woo_slides_add_sortable_columns()
	}

/*-----------------------------------------------------------------------------------*/
/* woo_slides_add_custom_column_headings() */
/*-----------------------------------------------------------------------------------*/
	
	if ( is_admin() ) { add_filter( 'manage_edit-slide_columns', 'woo_slides_add_custom_column_headings', 10, 1 ); }
	
	if ( ! function_exists( 'woo_slides_add_custom_column_headings' ) ) {
		function woo_slides_add_custom_column_headings ( $defaults ) {
			
			$new_columns['cb'] = '<input type="checkbox" />';
			// $new_columns['id'] = __( 'ID' );
			$new_columns['title'] = _x( 'Slide Title', 'column name' );
			$new_columns['menu_order'] = __( 'Order', 'woothemes' );
			// $new_columns['slide-categories'] = __( 'Slide Categories', 'woothemes' );
			$new_columns['author'] = __( 'Added By', 'woothemes' );
	 		$new_columns['date'] = _x('Added On', 'column name');
	 
			return $new_columns;
			
		} // End woo_slides_add_custom_column_headings()
	}
	
/*-----------------------------------------------------------------------------------*/
/* woo_slides_add_custom_column_data() */
/*-----------------------------------------------------------------------------------*/
	
	if ( is_admin() ) { add_action( 'manage_posts_custom_column', 'woo_slides_add_custom_column_data', 10, 2 ); }
	
	if ( ! function_exists( 'woo_slides_add_custom_column_data' ) ) {
		function woo_slides_add_custom_column_data ( $column_name, $id ) {
		
			global $wpdb, $post;
			
			$custom_values = get_post_custom( $id );
			
			switch ($column_name) {
			
				case 'id':
				
					echo $id;
				
				break;
				
				case 'menu_order':
				
					echo $post->menu_order;
				
				break;
				
				case 'slide-categories':
				
					$terms = get_the_term_list( $post->ID, 'slide-category', '', ', ', '' );
					
					if ( $terms ) { echo $terms; } else { echo __( 'No Slide Categories', 'woothemes' ); }
				
				break;
				
				default:
				break;
			
			} // End SWITCH Statement
			
		} // End woo_slides_add_custom_column_data()
	}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Portfolio Item (Portfolio Component) */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_portfolio' ) ) {
	function woo_add_portfolio() {
	
		global $woo_options;
	
		// "Portfolio Item" Custom Post Type
		$labels = array(
			'name' => _x( 'Portfolio', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Portfolio Item', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
			'add_new_item' => __( 'Add New Portfolio Item', 'woothemes' ),
			'edit_item' => __( 'Edit Portfolio Item', 'woothemes' ),
			'new_item' => __( 'New Portfolio Item', 'woothemes' ),
			'view_item' => __( 'View Portfolio Item', 'woothemes' ),
			'search_items' => __( 'Search Portfolio Items', 'woothemes' ),
			'not_found' =>  __( 'No portfolio items found', 'woothemes' ),
			'not_found_in_trash' => __( 'No portfolio items found in Trash', 'woothemes' ), 
			'parent_item_colon' => ''
		);
		
		$portfolioitems_rewrite = get_option( 'woo_portfolioitems_rewrite' );
 		if( empty( $portfolioitems_rewrite ) ) { $portfolioitems_rewrite = 'portfolio-items'; }
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => $portfolioitems_rewrite ),
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/portfolio.png',
			'menu_position' => null, 
			'has_archive' => true, 
			'taxonomies' => array( 'portfolio-gallery' ), 
			'supports' => array( 'title','editor','thumbnail', 'page-attributes' )
		);
		
		if ( isset( $woo_options['woo_portfolio_excludesearch'] ) && ( $woo_options['woo_portfolio_excludesearch'] == 'true' ) ) {
			$args['exclude_from_search'] = true;
		}
		
		register_post_type( 'portfolio', $args );
		
		// "Portfolio Galleries" Custom Taxonomy
		$labels = array(
			'name' => _x( 'Portfolio Galleries', 'taxonomy general name' ),
			'singular_name' => _x( 'Portfolio Gallery', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Portfolio Galleries', 'woothemes' ),
			'all_items' => __( 'All Portfolio Galleries', 'woothemes' ),
			'parent_item' => __( 'Parent Portfolio Gallery', 'woothemes' ),
			'parent_item_colon' => __( 'Parent Portfolio Gallery:', 'woothemes' ),
			'edit_item' => __( 'Edit Portfolio Gallery', 'woothemes' ), 
			'update_item' => __( 'Update Portfolio Gallery', 'woothemes' ),
			'add_new_item' => __( 'Add New Portfolio Gallery', 'woothemes' ),
			'new_item_name' => __( 'New Portfolio Gallery Name', 'woothemes' ),
			'menu_name' => __( 'Portfolio Galleries', 'woothemes' )
		); 	
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'portfolio-gallery' )
		);
		
		register_taxonomy( 'portfolio-gallery', array( 'portfolio' ), $args );
	}
	
	add_action( 'init', 'woo_add_portfolio' );
}

/*-----------------------------------------------------------------------------------*/
/* woo_portfolio_change_default_admin_sorting() */
/*-----------------------------------------------------------------------------------*/

	if ( is_admin() ) { add_filter( 'pre_get_posts', 'woo_portfolio_change_default_admin_sorting', 10, 1 ); }
	
	if ( ! function_exists( 'woo_portfolio_change_default_admin_sorting' ) ) {
		function woo_portfolio_change_default_admin_sorting ( $query ) {
			global $pagenow, $post_type;
			
			if ( ( $pagenow == 'edit.php' ) && ( $post_type == 'portfolio' ) && ! isset( $_GET['orderby'] ) && ! isset( $_GET['order'] ) ) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'asc' );
				$query->parse_query();
			}
			
			return $query;
		} // End woo_portfolio_change_default_admin_sorting()
	}

/*-----------------------------------------------------------------------------------*/
/* woo_portfolio_add_sortable_columns() */
/*-----------------------------------------------------------------------------------*/

	if ( is_admin() ) { add_filter( 'manage_edit-portfolio_sortable_columns', 'woo_portfolio_add_sortable_columns', 10, 1 ); }
	
	if ( ! function_exists( 'woo_portfolio_add_sortable_columns' ) ) {
		function woo_portfolio_add_sortable_columns ( $columns ) {
			$columns['portfolio_menu_order'] = 'menu_order';
			return $columns;
		} // End woo_portfolio_add_sortable_columns()
	}

/*-----------------------------------------------------------------------------------*/
/* woo_portfolio_add_custom_column_headings() */
/*-----------------------------------------------------------------------------------*/
	
	if ( is_admin() ) { add_filter( 'manage_edit-portfolio_columns', 'woo_portfolio_add_custom_column_headings', 10, 1 ); }
	
	if ( ! function_exists( 'woo_portfolio_add_custom_column_headings' ) ) {
		function woo_portfolio_add_custom_column_headings ( $defaults ) {
			
			$new_columns['cb'] = '<input type="checkbox" />';
			// $new_columns['id'] = __( 'ID' );
			$new_columns['title'] = _x( 'Title', 'column name' );
			$new_columns['portfolio_menu_order'] = __( 'Order', 'woothemes' );
			$new_columns['portfolio-galleries'] = __( 'Portfolio Galleries', 'woothemes' );
			$new_columns['author'] = __( 'Added By', 'woothemes' );
	 		$new_columns['date'] = _x( 'Added On', 'column name' );
	 
			return $new_columns;
			
		} // End woo_portfolio_add_custom_column_headings()
	}
	
/*-----------------------------------------------------------------------------------*/
/* woo_portfolio_add_custom_column_data() */
/*-----------------------------------------------------------------------------------*/
	
	if ( is_admin() ) { add_action( 'manage_posts_custom_column', 'woo_portfolio_add_custom_column_data', 10, 2 ); }
	
	if ( ! function_exists( 'woo_portfolio_add_custom_column_data' ) ) {
		function woo_portfolio_add_custom_column_data ( $column_name, $id ) {
		
			global $wpdb, $post;
			
			$custom_values = get_post_custom( $id );
			
			switch ( $column_name ) {
			
				case 'id':
				
					echo $id;
				
				break;
				
				case 'portfolio_menu_order':
				
					echo $post->menu_order;
				
				break;
				
				case 'portfolio-galleries':
				
					$terms = get_the_term_list( $post->ID, 'portfolio-gallery', '', ', ', '' );
					
					if ( $terms ) { echo $terms; } else { echo __( 'No Portfolio Galleries Assigned', 'woothemes' ); }
				
				break;
				
				default:
				break;
			
			} // End SWITCH Statement
			
		} // End woo_portfolio_add_custom_column_data()
	}

/*-----------------------------------------------------------------------------------*/
/* Get Post image attachments */
/*-----------------------------------------------------------------------------------*/
/* 
Description:

This function will get all the attached post images that have been uploaded via the 
WP post image upload and return them in an array. 

*/
function woo_get_post_images($offset = 1) {
	
	// Arguments
	$repeat = 100; 				// Number of maximum attachments to get 
	$photo_size = 'large';		// The WP "size" to use for the large image

	global $post;

	$output = array();

	$id = get_the_id();
	$attachments = get_children( array(
	'post_parent' => $id,
	'numberposts' => $repeat,
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order' => 'ASC', 
	'orderby' => 'menu_order date' )
	);
	if ( !empty($attachments) ) :
		$output = array();
		$count = 0;
		foreach ( $attachments as $att_id => $attachment ) {
			$count++;  
			if ($count <= $offset) continue;
			$url = wp_get_attachment_image_src($att_id, $photo_size, true);	
				$output[] = array( 'url' => $url[0], 'caption' => $attachment->post_excerpt, 'id' => $att_id );
		}  
	endif; 
	return $output;
} // End woo_get_post_images()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Meta */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_portfolio_meta' ) ) {
	function woo_portfolio_meta() {
?>
<p class="post-meta">
    <span class="post-author"><span class="small"><?php _e( 'by', 'woothemes' ) ?></span> <?php the_author_posts_link(); ?></span>
    <span class="post-date"><span class="small"><?php _e( 'on', 'woothemes' ) ?></span> <?php the_time( get_option( 'date_format' ) ); ?></span>
    <span class="post-category"><span class="small"><?php _e( 'in', 'woothemes' ) ?></span> <?php the_terms( $post->ID, 'portfolio-gallery', '', ', ', '' ); ?></span>
    <?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>
</p>
<?php
	} // End woo_portfolio_meta()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Navigation */
/*-----------------------------------------------------------------------------------*/

	if ( ! function_exists( 'woo_portfolio_navigation' ) ) {
		function woo_portfolio_navigation ( $galleries ) {
			
			// Sanity check.
			if ( ! is_array( $galleries ) || ( count( $galleries ) <= 0 ) ) { return; }
			
			global $woo_options;
			
			$settings = array(
							'id' => 'port-tags', 
							'label' => __( 'Select a category:', 'woothemes' ), 
							'display_all' => true
							 );
							 
			$settings = apply_filters( 'woo_portfolio_navigation_args', $settings );
			
			// Prepare the anchor tags of the various gallery items.
			$gallery_anchors = '';
			foreach ( $galleries as $g ) { $gallery_anchors .= '<a href="#' . $g->slug . '" rel="' . $g->slug . '" class="navigation-slug-' . $g->slug . ' navigation-id-' . $g->term_id . '">' . $g->name . '</a>' . "\n"; }
			
			$html = '<div id="' . $settings['id'] . '" class="port-tags">' . "\n";
				$html .= '<div class="fl">' . "\n";
					$html .= '<span class="port-cat">' . "\n";
					
					// Display label, if one is set.
					if ( $settings['label'] != '' ) { $html .= $settings['label'] . ' '; }
					
					// Display "All", if set to "true".
					if ( $settings['display_all'] == 'all' ) { $html .= '<a href="#" rel="all" class="current">' . __( 'All','woothemes' ) . '</a> '; }
					
					// Add the gallery anchors in.
					$html .= $gallery_anchors;
					
					$html .= '</span>' . "\n";
				$html .= '</div><!--/.fl-->' . "\n";
				$html .= '<div class="fix"></div>' . "\n";
			$html .= '</div><!--/#' . $settings['id'] . ' .port-tags-->' . "\n";
			
			
			$html = apply_filters( 'woo_portfolio_navigation', $html );
			
			echo $html;
		
		} // End woo_portfolio_navigation()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Item Extras (Testimonial and Link) */
/*-----------------------------------------------------------------------------------*/

	if ( ! function_exists( 'woo_portfolio_item_extras' ) ) {
		function woo_portfolio_item_extras ( $data ) {
		
			$settings = array(
								'id' => 'extras', 
								'display_button' => true
							 );
			
			// Allow child themes/plugins to filter these settings.				 
			$settings = apply_filters( 'woo_portfolio_item_extras_settings', $settings, $data );
			
			$html = '';
			
			$html .= '<div id="' . $settings['id'] . '">' . "\n";
			
			if ( $data['display_url'] != '' ) { $html .= '<a class="button" href="' . $data['display_url'] . '">' . __( 'Visit Website', 'woothemes' ) . '</a>' . "\n"; }
			
			if ( $data['testimonial'] != '' ) { $html .= '<blockquote>' . $data['testimonial'] . '</blockquote>' . "\n"; } // End IF Statement
			
			if ( $data['testimonial_author'] != '' ) {
				$html .= '<cite>&ndash; ' . $data['testimonial_author'] . "\n";
					if ( $data['display_url'] != '' ) { $html .= ' - <a href="' . $data['display_url'] . '">' . $data['display_url'] . '</a>' . "\n"; }
				$html .= '</cite>' . "\n";
			} // End IF Statement
			
   			$html .= '</div><!--/#extras-->' . "\n";
   			
   			// Allow child themes/plugins to filter this HTML.
   			$html = apply_filters( 'woo_portfolio_item_extras_html', $html, $data );
   			
   			echo $html;
		
		} // End woo_portfolio_item_extras()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio Item Settings */
/* @uses woo_portfolio_image_dimensions() */
/*-----------------------------------------------------------------------------------*/
 
 	if ( ! function_exists( 'woo_portfolio_item_settings' ) ) {
		function woo_portfolio_item_settings ( $id ) {
			
			global $woo_options;
			
			// Sanity check.
			if ( ! is_numeric( $id ) ) { return; }
			
			$website_layout = 'two-col-left';
			$website_width = '960px';
			
			if ( isset( $woo_options['woo_layout'] ) ) { $website_layout = $woo_options['woo_layout']; }
			if ( isset( $woo_options['woo_layout_width'] ) ) { $website_width = $woo_options['woo_layout_width']; }
			
			$dimensions = woo_portfolio_image_dimensions( $website_layout, $website_width );
			
			$width = $dimensions['width'];
			$height = $dimensions['height'];
			
			$enable_gallery = false;
			if ( isset( $woo_options['woo_portfolio_gallery'] ) ) { $enable_gallery = $woo_options['woo_portfolio_gallery']; }
			
			$settings = array(
								'large' => '', 
								'caption' => '', 
								'rel' => '', 
								'gallery' => array(), 
								'css_classes' => 'group post portfolio-img', 
								'embed' => '', 
								'enable_gallery' => $enable_gallery, 
								'testimonial' => '', 
								'testimonial_author' => '', 
								'display_url' => '', 
								'width' => $width, 
								'height' => $height
							 );
			
			$meta = get_post_custom( $id );
			
			// Check if there is a gallery in post.
			// woo_get_post_images is offset by 1 by default. Setting to offset by 0 to show all images.
        	
        	$large =  $meta['portfolio-image'][0];
        	$caption = '';
        	
        	if ( $settings['enable_gallery'] == 'true' ) { 
        	
	        	$gallery = woo_get_post_images( '0' );
	        	if ( $gallery ) {
	        		// Get first uploaded image in gallery
	        		$large = $gallery[0]['url'];
	        		$caption = $gallery[0]['caption'];
	            } 
            
            } // End IF Statement
            
            // If we only have one image, disable the gallery functionality.
            if ( is_array( $gallery ) && ( count( $gallery ) <= 1 ) ) {
           		$settings['enable_gallery'] = 'false';
            }
            
            // Check for a post thumbnail, if support for it is enabled.
            if ( ( $woo_options['woo_post_image_support'] == 'true' ) && current_theme_supports( 'post-thumbnails' ) ) {
            	$image_id = get_post_thumbnail_id( $id );
            	if ( intval( $image_id ) > 0 ) {
            		$large_data = wp_get_attachment_image_src( $image_id, 'large' );
            		if ( is_array( $large_data ) ) {
            			$large = $large_data[0];
            		}
            	}
            }
            
            // See if lightbox-url custom field has a value
            if ( isset( $meta['lightbox-url'] ) && ( $meta['lightbox-url'][0] != '' ) ) {
            	$large = $meta['lightbox-url'][0];
            }
            
            // Set rel on anchor to show lightbox
      		$rel = 'rel="lightbox['. $id .']"';
			
			// Create CSS classes string.
			$css = '';
			$galleries = array();
			$terms = get_the_terms( $id, 'portfolio-gallery' );
			if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) { foreach ( $terms as $t ) { $galleries[] = $t->slug; } }				
			$css = join( ' ', $galleries );
			
			// If on the single item screen, check for a video.
			if ( is_singular() ) { $settings['embed'] = woo_embed( 'width=540' ); }
			
			// Add testimonial information.
			if ( isset( $meta['testimonial'] ) && ( $meta['testimonial'][0] != '' ) ) {
				$settings['testimonial'] = $meta['testimonial'][0];
			}
			
			if ( isset( $meta['testimonial_author'] ) && ( $meta['testimonial_author'][0] != '' ) ) {
				$settings['testimonial_author'] = $meta['testimonial_author'][0];
			}
			
			// Look for a custom display URL of the portfolio item (used if it's a website, for example)
			if ( isset( $meta['url'] ) && ( $meta['url'][0] != '' ) ) {
				$settings['display_url'] = $meta['url'][0];
			}
			
			// Assign the values we have to our array.
			$settings['large'] = $large;
			$settings['caption'] = $caption;
			$settings['rel'] = $rel;
			$settings['gallery'] = $gallery;
			$settings['css_classes'] .= ' ' . $css;
			
			// Disable "enable_gallery" option is gallery is empty.
			if ( ! is_array( $settings['gallery'] ) || ( $settings['gallery'] == '' ) || ( count( $settings['gallery'] ) <= 0 ) ) {
				$settings['enable_gallery'] = 'false';
			}
			
			// Allow child themes/plugins to filter these settings.
			$settings = apply_filters( 'woo_portfolio_item_settings', $settings, $id );
			
			return $settings;
		
		} // End woo_portfolio_item_settings()
	}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, show portfolio galleries in portfolio item breadcrumbs */
/* Modify woo_breadcrumbs() Arguments Specific to this Theme */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_breadcrumbs_args', 'woo_portfolio_filter_breadcrumbs_args', 10 );

if ( ! function_exists( 'woo_portfolio_filter_breadcrumbs_args' ) ) {
	function woo_portfolio_filter_breadcrumbs_args( $args ) {
	
		$args['singular_portfolio_taxonomy'] = 'portfolio-gallery';
	
		return $args;
	
	} // End woo_portfolio_filter_breadcrumbs_args()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, change the "post more" content for portfolio items. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_post_more', 'woo_portfolio_post_more', 20 );

function woo_portfolio_post_more ( $content ) {
	
	global $post;
	
	$new_content = $content;
	
	if ( get_post_type() != 'portfolio' ) { return $new_content; } // Skip the functionality if it's not a portfolio item.
	
	$taxonomy = 'portfolio-gallery';
	
	$terms = get_the_terms( $post->ID, $taxonomy );
	$term_links = array();
	$term_text = '';
	
	if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) {
		foreach ( $terms as $t ) {
			$term_links[] = '<a href="' . get_term_link( $t->slug, $taxonomy ) . '">' . $t->name . '</a>';
		}
		
		$term_text = join( ', ', $term_links );
	}
	
	if ( $term_text != '' ) { $new_content = __( 'Posted In ', 'woothemes' ) . $term_text; }
	
	return $new_content;
	
} // End woo_portfolio_post_more()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, change the "post meta" content for portfolio items. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_filter_post_meta', 'woo_portfolio_post_meta', 20 );

function woo_portfolio_post_meta ( $content ) {
	
	global $post;
	
	if ( get_post_type() != 'portfolio' ) { return $content; } // Skip the functionality if it's not a portfolio item.
	
	$taxonomy = 'portfolio-gallery';
	
	$terms = get_the_terms( $post->ID, $taxonomy );
	$term_links = array();
	$term_text = '';
	
	if ( is_array( $terms ) && ( count( $terms ) > 0 ) ) {
		foreach ( $terms as $t ) {
			$term_links[] = '<a href="' . get_term_link( $t->slug, $taxonomy ) . '">' . $t->name . '</a>';
		}
		
		$term_text = join( ', ', $term_links );
	}
	
	$post_info = '<span class="small">' . __( 'By', 'woothemes' ) . '</span> [post_author_posts_link] <span class="small">' . __( 'on', 'woothemes' ) . '</span> [post_date]';
	
	if ( $term_text != '' ) { $post_info .= ' <span class="small">' . __( 'in', 'woothemes' ) . '</span> ' . $term_text; }

	$content = sprintf( '<div class="post-meta">%s</div>' . "\n", apply_filters( 'woo_filter_portfolio_post_meta', $post_info ) );

	return $content;

} // End woo_portfolio_post_meta()

/*-----------------------------------------------------------------------------------*/
/* Woo Portfolio, get image dimensions based on layout and website width settings. */
/*-----------------------------------------------------------------------------------*/

function woo_portfolio_image_dimensions ( $layout = 'one-col', $width = '960' ) {
	
	$dimensions = array( 'width' => 575, 'height' => 0, 'thumb_width' => 150, 'thumb_height' => 150 );
	
	// Allow child themes/plugins to filter these dimensions.
	$dimensinos = apply_filters( 'woo_portfolio_gallery_dimensions', $dimensions );

	return $dimensions;

} // End woo_post_gallery_dimensions()

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Feedback (Feedback Component) */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_add_feedback' ) ) {
	function woo_add_feedback() {
		global $woo_options;
		
		if ( ( isset( $woo_options['woo_feedback_disable'] ) && $woo_options['woo_feedback_disable'] == 'true' ) ) { return; }
		
		$labels = array(
			'name' => _x( 'Feedback', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Feedback Item', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'slide', 'woothemes' ),
			'add_new_item' => __( 'Add New Feedback Item', 'woothemes' ),
			'edit_item' => __( 'Edit Feedback Item', 'woothemes' ),
			'new_item' => __( 'New Feedback Item', 'woothemes' ),
			'view_item' => __( 'View Feedback Item', 'woothemes' ),
			'search_items' => __( 'Search Feedback Items', 'woothemes' ),
			'not_found' =>  __( 'No Feedback Items found', 'woothemes' ),
			'not_found_in_trash' => __( 'No Feedback Items found in Trash', 'woothemes' ), 
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'exclude_from_search' => true, 
			'_builtin' => false,
			'show_ui' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_icon' => get_template_directory_uri() .'/includes/images/feedback.png',
			'menu_position' => null,
			'supports' => array( 'title', 'editor'/*, 'author', 'thumbnail', 'excerpt', 'comments'*/ ),
		);
		
		register_post_type( 'feedback', $args );

	} // End woo_add_feedback()
}

add_action( 'init', 'woo_add_feedback', 10 );

/*-----------------------------------------------------------------------------------*/
/* Woo Feedback, woo_get_feedback_entries() */
/*
/* Get feedback entries.
/*
/* @param array/string $args
/* @since 4.5.0
/* @return array/boolean
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_get_feedback_entries' ) ) {
	function woo_get_feedback_entries ( $args = '' ) {
		$defaults = array(
			'limit' => 5, 
			'orderby' => 'post_date', 
			'order' => 'DESC', 
			'id' => 0
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// Allow child themes/plugins to filter here.
		$args = apply_filters( 'woo_get_feedback_args', $args );
		
		// The Query Arguments.
		$query_args = array();
		$query_args['post_type'] = 'feedback';
		$query_args['numberposts'] = $args['limit'];
		$query_args['orderby'] = $args['orderby'];
		$query_args['order'] = $args['order'];
		
		if ( is_numeric( $args['id'] ) && ( intval( $args['id'] ) > 0 ) ) {
			$query_args['p'] = intval( $args['id'] );
		}
		
		// Whitelist checks.
		if ( ! in_array( $query_args['orderby'], array( 'none', 'ID', 'author', 'title', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num' ) ) ) {
			$query_args['orderby'] = 'date';
		}
		
		if ( ! in_array( $query_args['order'], array( 'ASC', 'DESC' ) ) ) {
			$query_args['order'] = 'DESC';
		}
		
		if ( ! in_array( $query_args['post_type'], get_post_types() ) ) {
			$query_args['post_type'] = 'feedback';
		}
		
		// The Query.
		$query = get_posts( $query_args );
		
		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {} else {
			$query = false;
		}
		
		return $query;
		
	} // End woo_get_feedback_entries()
}

/*-----------------------------------------------------------------------------------*/
/* Woo Feedback, woo_display_feedback_entries() */
/*
/* Display posts of the "feedback" post type.
/*
/* @param array/string $args
/* @since 4.5.0
/* @return string $html (if "echo" not set to true)
/* @uses woo_get_feedback_entries()
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_display_feedback_entries' ) ) {
	function woo_display_feedback_entries ( $args = '' ) {
		$defaults = array(
			'limit' => 5, 
			'orderby' => 'rand', 
			'order' => 'DESC', 
			'id' => 0, 
			'display_author' => true, 
			'display_url' => true, 
			'effect' => 'fade', // Options: 'fade', 'none'
			'pagination' => false, 
			'echo' => true
		);
		
		$args = wp_parse_args( $args, $defaults );
		
		// Allow child themes/plugins to filter here.
		$args = apply_filters( 'woo_display_feedback_args', $args );
		
		$html = '';
		
		woo_do_atomic( 'woo_feedback_before', $args );
		
		// The Query.
		$query = woo_get_feedback_entries( $args );
		
		// The Display.
		if ( ! is_wp_error( $query ) && is_array( $query ) && count( $query ) > 0 ) {
			
			if ( $args['effect'] != 'none' ) {
				$effect = ' ' . $args['effect'];
			}
			
			$html .= '<div class="feedback' . $effect . '">' . "\n";
			$html .= '<div class="feedback-list">' . "\n";
		
			foreach ( $query as $post ) {
				setup_postdata( $post );
				
				$author = '';
				$author_text = '';
				
				// If we need to display either the author, URL or both, get the data.
				if ( $args['display_author'] == true || $args['display_url'] == true ) {
					$meta = get_post_custom( $post->ID );
					
					if ( isset( $meta['feedback_author'] ) && ( $meta['feedback_author'][0] != '' ) && $args['display_author'] == true ) {
						$author .= '<cite class="feedback-author">' . $meta['feedback_author'][0] . '</cite><!--/.feedback-author-->' . "\n";
					}
					
					if ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) && $args['display_url'] == true ) {
						$author .= '<a href="' . $meta['feedback_url'][0] . '" title="' . esc_attr( $author_text ) . '" class="feedback-url">' . $meta['feedback_url'][0] . '</a>';
					}
				}
				
				$html .= '<div id="quote-' . $post->ID . '" class="quote">' . "\n";
					$html .= '<blockquote class="feedback-text">' . get_the_content() . '</blockquote>' . "\n";
					$html .= $author;
				$html .= '</div>' . "\n";
			}
			
			$html .= '</div><!--/.feedback-list-->' . "\n";
			
			if ( $args['pagination'] == true && count( $query ) > 1 && $args['effect'] != 'none' ) {
			
				$html .= '<div class="pagination">' . "\n";
				$html .= '<a href="#" class="btn-prev">' . apply_filters( 'woo_feedback_prev_btn', '&larr; ' . __( 'Previous', 'woothemes' ) ) . '</a>' . "\n";
		        $html .= '<a href="#" class="btn-next">' . apply_filters( 'woo_feedback_next_btn', __( 'Next', 'woothemes' ) . ' &rarr;' ) . '</a>' . "\n";
		        $html .= '</div><!--/.pagination-->' . "\n";
			
			}
			
			$html .= '</div><!--/.feedback-->' . "\n";
		}
		
		// Allow child themes/plugins to filter here.
		$html = apply_filters( 'woo_feedback_html', $html, $query );
		
		if ( $args['echo'] != true ) { return $html; }
		
		// Should only run is "echo" is set to true.
		echo $html;
		
		woo_do_atomic( 'woo_feedback_after', $args ); // Only if "echo" is set to true.
		
		wp_reset_query();
		
	} // End woo_display_feedback_entries()
}

/*-----------------------------------------------------------------------------------*/
/* Add custom CSS class to the <body> tag if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'woo_add_lightbox_body_class', 10 );

function woo_add_lightbox_body_class ( $classes ) {
	global $woo_options;
	
	if ( isset( $woo_options['woo_enable_lightbox'] ) && $woo_options['woo_enable_lightbox'] == 'true' ) {
		$classes[] = 'has-lightbox';
	}
	
	return $classes;
} // End woo_add_lightbox_body_class()

/*-----------------------------------------------------------------------------------*/
/* Load PrettyPhoto JavaScript and CSS if the lightbox option is enabled. */
/*-----------------------------------------------------------------------------------*/

add_action( 'woothemes_add_javascript', 'woo_load_prettyphoto', 10 );
add_action( 'woothemes_add_css', 'woo_load_prettyphoto', 10 );

function woo_load_prettyphoto () {
	global $woo_options;
	
	if ( ! isset( $woo_options['woo_enable_lightbox'] ) || $woo_options['woo_enable_lightbox'] == 'false' ) { return; }
	
	$filter = current_filter();
	
	switch ( $filter ) {
		case 'woothemes_add_javascript':
			wp_enqueue_script( 'prettyPhoto' );
		break;
		
		case 'woothemes_add_css':
			wp_enqueue_style( 'prettyPhoto' );
		break;
	}
} // End woo_load_prettyphoto()

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>