<?php
/**
 * Homepage "Blog" Component.
 *
 * Display your recent blog posts.
 *
 * @since 1.0.0
 * @uses $woo_options array.
 */

global $woo_options;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$query_args = array(
					'paged' => $paged, 
					'posts_per_page' => 2
					);

$title = apply_filters( 'woo_home_blog_title', __( 'Hits From The Blog', 'woothemes' ) );

query_posts( $query_args );
?>
<div id="blog" class="widget widget_woo_blog">
<?php if ( $title != '' ) { ?><h3 class="widget-title"><?php echo $title; ?></h3><?php } ?>
<?php if ( have_posts() ) { $count = 0; ?>
<?php while ( have_posts() ) { the_post(); $count++; ?>
                                                            
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
?>  
</div><!--/#blog-->