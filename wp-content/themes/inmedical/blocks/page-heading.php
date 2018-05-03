<?php
$inwave_theme_option = Inwave_Helper::getConfig();
$post = get_post();
$show_page_heading = Inwave_Helper::getPostOption('show_pageheading', 'show_page_heading');
?>

<?php if (($show_page_heading && $show_page_heading != 'no') && !is_404()) { ?>
    <div class="page-heading <?php echo !is_page_template( 'page-templates/home-page-kid.php' ) ? 'default' : '' ?>">
        <div class="container">
			<div class="container-inner">
				<div class="container-inner-2">
					<div class="page-title">
						<div class="iw-heading-title">
							<h1>
								<?php
								$text['home']     = esc_html__('Home', 'inmedical'); // text for the 'Home' link
								$text['category'] = esc_html__('%s', 'inmedical'); // text for a category page
								$text['tax'] 	  = esc_html__('Archive for "%s"', 'inmedical'); // text for a taxonomy page
								$text['search']   = esc_html__('Search Results for "%s" Query', 'inmedical'); // text for a search results page
								$text['tag']      = esc_html__('Posts Tagged "%s"', 'inmedical'); // text for a tag page
								$text['author']   = esc_html__('Articles Posted by %s', 'inmedical'); // text for an author page
								$text['404']      = esc_html__('Oops! That page can&rsquo;t be found', 'inmedical'); // text for the 404 page

								$page_title = '';
								if(is_home()){
									$page_id = get_option('page_for_posts', true);
									if($page_id){
										$page_title .= get_the_title($page_id );
									}
									else{
										$page_title .= get_bloginfo('name');
									}
								}elseif ( is_category() ) {
									$page_title .= sprintf($text['category'], single_cat_title('', false));
								} elseif( is_tax() ){
									if(is_tax('cat')){
										$page_title .= sprintf($text['tax'], single_cat_title('', false));
									}
									else
									{
										$page_title .= sprintf(single_cat_title('', false));
									}
								}elseif ( is_search() ) {
									$page_title .= sprintf($text['search'], get_search_query());
								} elseif ( is_day() ) {
									$page_title .= sprintf($text['tax'], get_the_time('F jS, Y'));
								} elseif ( is_month() ) {
									$page_title .= sprintf($text['tax'], get_the_time('F, Y'));
								} elseif ( is_year() ) {
									$page_title .= sprintf($text['tax'], get_the_time('Y'));
								} elseif ( is_single()) {
									if(get_post_type() == 'post'){
										$page_for_posts = get_option( 'page_for_posts' );
										if($page_for_posts){
											$page_title .= get_the_title($page_for_posts);
										}else{
											$page_title .= esc_html__('Blog', 'inmedical');
										}
									}elseif(function_exists('is_product') && is_product()){
										$page_title .= get_the_title(wc_get_page_id( 'shop' ));
									}else{
										$page_title .= get_the_title();
									}
								} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && !is_author() ) {
									if(function_exists('is_shop') && is_shop()){
										$page_title .= get_the_title(wc_get_page_id( 'shop' ));
									}
									else{
										$post_type = get_post_type_object(get_post_type());
										$page_title .= $post_type->labels->all_items;
									}
								} elseif ( is_page()) {
									$page_title .= get_the_title();
								}elseif ( is_tag() ) {
									$page_title .= single_tag_title('', false);
								} elseif ( is_author() ) {
									global $author;
									$userdata = get_userdata($author);
									$page_title .= sprintf($text['author'], $userdata->display_name);
								} elseif ( is_404() ) {
									$page_title .= $text['404'];
								}

								echo esc_html($page_title);
								?>
							</h1>

						</div>
					</div>
					<?php
					$show_breadcrums = Inwave_Helper::getPostOption('breadcrumbs', 'breadcrumbs');
					if (!is_page_template( 'page-templates/home-page.php' ) && !is_page_template( 'page-templates/home-page-kid.php' ) && !is_page_template( 'page-templates/home-page-plastic-surgery.php' ) && $show_breadcrums && $show_breadcrums != 'no') {?>
					<div class="breadcrumbs-line"></div>
					<div class="breadcrumbs-top" ><?php get_template_part('blocks/breadcrumbs'); ?></div>
					<?php } ?>
				</div>
			</div>
		</div>
    </div>
<?php } ?>