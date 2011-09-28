<?php
/*
Template Name: Tags
*/
?>

<?php get_header(); ?>
       
    <div id="content" class="page col-full">
		<div id="main" class="fullwidth">
            
		<?php if ( $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>  
                                                                        
            <div <?php post_class('drop-shadow lifted'); ?>>

			    <h1 class="title"><?php the_title(); ?></h1>
                
	            <?php if ( have_posts() ) { the_post(); ?>
            	<div class="entry">
            		<?php the_content(); ?>
            	</div>	            	
	            <?php } ?>  
	            
                <div class="tag_cloud">
        			<?php wp_tag_cloud( 'number=0' ); ?>
    			</div>

            </div><!-- /.post -->
        
		</div><!-- /#main -->
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>