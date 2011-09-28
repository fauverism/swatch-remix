<?php
/**
 * Portfolio Gallery Taxonomy Template
 *
 * The portfolio page template displays your portfolio items with
 * a switcher to quickly filter between the various portfolio galleries. 
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $wp_query; 
 get_header();
 
 $taxonomy_obj = $wp_query->get_queried_object();
 $taxonomy_nice_name = $taxonomy_obj->name;
 $term_id = $taxonomy_obj->term_id;
 $term_taxonomy_id = $taxonomy_obj->term_taxonomy_id;
 $taxonomy_short_name = $taxonomy_obj->taxonomy;
 $taxonomy_top_level_items = get_taxonomies(array('name' => $taxonomy_short_name), 'objects');
 $taxonomy_top_level_item = $taxonomy_top_level_items[$taxonomy_short_name]->label;
?>
	 <div id="content" class="page col-full">
		<div id="main" class="col-left">
                                                                            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>
		<span class="archive_header">
    		<span class="fl cat"><?php _e( 'Archive', 'woothemes' ); ?> | <?php echo $taxonomy_nice_name; ?></span> 
    		<span class="fr catrss"><?php echo '<a href="' . get_term_feed_link( $term_id, 'portfolio-gallery' ) . '">' . __( 'RSS feed for this section', 'woothemes' ) . '</a>'; ?></span>
    	</span>  
		<?php
			get_template_part( 'loop', 'portfolio' );
		?>
                                                            
		</div><!-- /#main -->
		
        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>