<div class="search_main">
    <form method="get" class="searchform" action="<?php echo home_url( '/' ); ?>" >
        <input type="text" class="field s" name="s" value="<?php esc_attr_e( 'Search...', 'woothemes' ); ?>" onfocus="if (this.value == '<?php esc_attr_e( 'Search...', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_attr_e( 'Search...', 'woothemes' ); ?>';}" />
        <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/ico-search.png" class="search-submit" name="submit" alt="" />
    </form>    
    <div class="fix"></div>
</div>