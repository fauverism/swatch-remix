<?php get_header(); ?>
       
    <div id="content" class="col-full">
		<div id="main" class="col-left">
            <div class="page drop-shadow lifted">

                <h1 class="title"><?php _e( 'Error 404 - Page not found!', 'woothemes' ); ?></h1>
                <div class="entry">
                	<p><?php _e( 'The page you trying to reach does not exist, or has been moved. Please use the menus or the search box to find what you are looking for.', 'woothemes' ); ?></p>
                </div>

            </div><!-- /.page -->                                            
        </div><!-- /#main -->
        
        <?php get_sidebar(); ?>
        
    </div><!-- /#content -->		
<?php get_footer(); ?>