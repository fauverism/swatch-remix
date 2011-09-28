<?php
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 */
 global $woo_options;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
<?php woo_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="wrapper">

	<?php if ( function_exists( 'has_nav_menu') && has_nav_menu( 'top-menu' ) ) { ?>

	<div id="top">
		<div class="col-full">
			<?php wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'top-nav', 'menu_class' => 'nav fl', 'theme_location' => 'top-menu' ) ); ?>
		</div>
	</div><!-- /#top -->

    <?php } ?>       
	<div id="header">
		<div class="col-full">
			<div id="logo">
		    <?php
		    	$logo = get_template_directory_uri() . '/images/logo.png';
		    	if ( isset( $woo_options['woo_logo'] ) && $woo_options['woo_logo'] != '' ) { $logo = $woo_options['woo_logo']; }
		    ?>
			<?php if ( ! isset( $woo_options['woo_texttitle'] ) || $woo_options['woo_texttitle'] != 'true' ) { ?>
				<a href="<?php bloginfo( 'url' ); ?>" title="<?php bloginfo( 'description' ); ?>">
					<img src="<?php echo $logo; ?>" alt="<?php bloginfo( 'name' ); ?>" />
				</a>
	        <?php } ?> 
	        
	        <?php if( is_singular() && ! is_front_page() ) { ?>
				<span class="site-title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></span>
	        <?php } else { ?>
				<h1 class="site-title"><a href="<?php bloginfo( 'url' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
	        <?php }; ?>
				<span class="site-description"><?php bloginfo( 'description' ); ?></span>
		      	
			</div><!-- /#logo -->

			<div id="navigation" class="fr"> 
			
				<?php
				if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
					wp_nav_menu( array( 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav fl', 'theme_location' => 'primary-menu' ) );
				} else {
				?>
				<ul id="main-nav" class="nav fl">
					<?php 
					if ( get_option('woo_custom_nav_menu') == 'true' ) {
						if ( function_exists('woo_custom_navigation_output') )
							woo_custom_navigation_output("name=Woo Menu 1");
			
					} else { ?>
						
						<?php if ( is_page() ) { $highlight = "page_item"; } else { $highlight = "page_item current_page_item"; } ?>
						<li class="<?php echo $highlight; ?>"><a href="<?php bloginfo('url'); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
						<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
			
					<?php } ?>
				</ul><!-- /#nav -->
				<?php } ?>
				<?php if ( isset( $woo_options['woo_feed_url'] ) && $woo_options['woo_feed_url'] != '' ) { ?>
				<ul class="rss fr">
					<li class="sub-rss"><a href="<?php if ( $woo_options['woo_feed_url'] ) { echo $woo_options['woo_feed_url']; } else { echo get_bloginfo_rss('rss2_url'); } ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-rss.png" alt="<?php bloginfo('name'); ?>" /></a></li>
				</ul>
				<?php } ?>
				
			</div><!-- /#navigation -->
	    	           
		</div><!--/.col-full-->
	</div><!--/#header-->