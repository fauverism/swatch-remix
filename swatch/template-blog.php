<?php
/*
Template Name: Blog
*/

get_header();
global $woo_options;
?>

    <!-- #content Starts -->
	<?php woo_content_before(); ?>
    <div id="content" class="col-full">
    
        <!-- #main Starts -->
        <div id="main" class="col-left">      
                    
		<?php if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>  

        <?php
        	if ( get_query_var( 'paged') ) { $paged = get_query_var( 'paged' ); } elseif ( get_query_var( 'page') ) { $paged = get_query_var( 'page' ); } else { $paged = 1; }
        	
        	$query_args = array(
        						'post_type' => 'post', 
        						'paged' => $paged
        					);
        	
        	$query_args = apply_filters( 'woo_blog_template_query_args', $query_args ); // Do not remove. Used to exclude categories from displaying here.
        	
        	query_posts( $query_args );
        	
        	if ( have_posts() ) {
        		$count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>                                                            
            <!-- Post Starts -->
            <div <?php post_class('drop-shadow lifted'); ?>>
               
                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                
                <?php woo_post_meta(); ?>

                <div class="entry">

<?php if ( $woo_options['woo_post_content'] != 'content' ) { woo_image( 'width=' . $woo_options['woo_thumb_w'] . '&height='.$woo_options['woo_thumb_h'] . '&class=thumbnail ' . $woo_options['woo_thumb_align'] ); } ?> 

					<?php global $more; $more = 0; ?>	                                        
                    <?php if ( $woo_options['woo_post_content'] == 'content' ) { the_content(__( 'Read More...', 'woothemes' ) ); } else { the_excerpt(); } ?>
                </div>
    			<div class="fix"></div>
    			
                <div class="post-more">      
					<span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'woothemes' ), __( '1 Comment', 'woothemes' ), __( '% Comments', 'woothemes' ) ); ?></span>
                	<?php if ( $woo_options['woo_post_content'] == 'excerpt' ) { ?>
					<span class="post-more-sep">&bull;</span>
                    <span class="read-more"><a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'woothemes' ); ?>"><?php _e( 'Continue Reading &rarr;', 'woothemes' ); ?></a></span>
                    <?php } ?>
                </div>   
    
            </div><!-- /.post -->
                                                
        <?php
        		} // End WHILE Loop
        	
        	} else {
        ?>
            <div <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </div><!-- /.post -->
        <?php } // End IF Statement ?>  
    
            <?php woo_pagenav(); ?>
			<?php wp_reset_query(); ?>                

        </div><!-- /#main -->
            
		<?php get_sidebar(); ?>

    </div><!-- /#content -->    
		
<?php get_footer(); ?>