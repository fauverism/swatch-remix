<?php
// Do not delete these lines

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ( 'Please do not load this page directly. Thanks!' );

if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'woothemes' ); ?></p>

<?php return; } ?>

<?php $comments_by_type = &separate_comments( $comments ); ?>    

<!-- You can start editing here. -->
<!-- Begin Comment Tabs -->
<div id="comment-form-tabs">
<ul class="tabs">
	<li class="tab-discuss"><a href="#comments"><span><?php _e( 'View Comments', 'woothemes' ); ?> (<?php comments_number( '0', '1', '%' ); ?>)</span></a></li>
	<li class="tab-respond"><a href="#respond-container"><span><?php _e( 'Submit A Comment', 'woothemes' ); ?></span></a></li>
</ul>
<?php if ( have_comments() ) { ?>

<div id="comments">

	<?php if ( ! empty( $comments_by_type['comment'] ) ) { ?>

		<h3><?php comments_number(__( 'No Responses', 'woothemes' ), __( 'One Response', 'woothemes' ), __( '% Responses', 'woothemes' ) );?> <?php _e( 'to', 'woothemes' ); ?> &#8220;<?php the_title(); ?>&#8221;</h3>

		<ol class="commentlist">
	
			<?php wp_list_comments( 'avatar_size=40&callback=custom_comment&type=comment' ); ?>
		
		</ol>    

		<div class="navigation">
			<div class="fl"><?php previous_comments_link(); ?></div>
			<div class="fr"><?php next_comments_link(); ?></div>
			<div class="fix"></div>
		</div><!-- /.navigation -->
	<?php } ?>
		    
	<?php if ( ! empty($comments_by_type['pings']) ) { ?>
    		
        <h3 id="pings"><?php _e( 'Trackbacks/Pingbacks', 'woothemes' ); ?></h3>
    
        <ol class="pinglist">
            <?php wp_list_comments( 'type=pings&callback=list_pings' ); ?>
        </ol>
    	
	<?php } ?>
    	
</div> <!-- /#comments_wrap -->

<?php } else { // this is displayed if there are no comments so far ?>

<div id="comments">

	<?php 
		// If there are no comments and comments are closed, let's leave a little note, shall we?
		if ( ! comments_open() && is_singular() ) { ?><h5 class="nocomments"><?php _e( 'Comments are closed.', 'woothemes' ); ?></h5><?php }
		else { ?><h5 class="nocomments"><?php _e( 'No comments yet.', 'woothemes' ); ?></h5><?php }
	?>

</div> <!-- /#comments_wrap -->

<?php } ?>
<div id="respond-container">
<?php
	// Comment reply form.
	comment_form();
?>
</div><!--/#respond-container-->
</div><!--/#comment-form-tabs-->