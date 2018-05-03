<div class="page-nav">
    <?php echo paginate_links(array(
									'type'=>'plain',
									'prev_text'          => wp_kses(__('<i class="fa fa-angle-left"></i>', 'inmedical'), inwave_allow_tags('i')),
									'next_text'          => wp_kses(__('<i class="fa fa-angle-right"></i>', 'inmedical'), inwave_allow_tags('i'))
								)
							) ?>
	<div class="clearfix"></div>
</div>