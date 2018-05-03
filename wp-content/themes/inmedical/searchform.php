<form method="get" action="<?php echo esc_url( home_url( '/' ) )?>">
    <div class="search-box">
        <input type="search" title="<?php echo esc_attr_x( 'Search for:', 'label','inmedical' ) ?>" value="<?php echo get_search_query() ?>" name="s" placeholder="<?php echo esc_attr_x( 'Enter your keywords', 'placeholder','inmedical' );?>" class="top-search">
        <img alt="" src="<?php echo esc_url(get_template_directory_uri())?>/assets/images/search.png" class="sub-search">
    </div>
</form>