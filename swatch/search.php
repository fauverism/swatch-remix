<?php get_header(); ?>

	<div id="header-page">
		<div class="col-full"></div>
	</div>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>  
			
			<?php if ( have_posts() ) { $count = 0; ?>
            
            <span class="archive_header"><?php _e( 'Search results:', 'woothemes' ); ?> <?php the_search_query();?></span>
                
            <?php while ( have_posts() ) { the_post(); $count++; ?>
                                                                        
            <!-- Post Starts -->
            <div <?php post_class(); ?>>
            
                <h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                
                <?php woo_post_meta(); ?>
                
                <div class="entry">
                    <?php the_excerpt(); ?>
                </div><!-- /.entry -->
                        
            </div><!-- /.post -->
                                                    
            <?php
            		} // End WHILE Loop
            	} else {
            ?>
            
            <div <?php post_class(); ?>>
                <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            </div><!-- /.post -->
            
            <?php } ?>
			<?php woo_pagenav(); ?>
                
        </div><!-- /#main -->

        <?php get_sidebar(); ?>

    </div><!-- /#content -->
		
<?php get_footer(); ?>