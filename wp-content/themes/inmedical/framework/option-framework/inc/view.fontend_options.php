<div class="wrap" id="of_container">

	<div id="of-popup-save" class="of-save-popup">
		<div class="of-save-save"><?php esc_html_e('Options Updated', 'inmedical'); ?></div>
	</div>
	
	<div id="of-popup-reset" class="of-save-popup">
		<div class="of-save-reset"><?php esc_html_e('Options Reset', 'inmedical'); ?></div>
	</div>
	
	<div id="of-popup-fail" class="of-save-popup">
		<div class="of-save-fail"><?php esc_html_e('Error!', 'inmedical'); ?></div>
	</div>
	
	<span class="hide" id="hooks"><?php echo json_encode(inwave_of_get_header_classes_array()); ?></span>
	<input type="hidden" id="reset" value="<?php if(isset($_REQUEST['reset'])) echo esc_attr($_REQUEST['reset']); ?>" />
	<input type="hidden" id="security" name="security" value="<?php echo wp_create_nonce('of_ajax_nonce'); ?>" />

	<form id="of_form" method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" enctype="multipart/form-data" >
	
		<div id="header">
		
			<div class="logo">
				<h2><?php echo INWAVE_OF_THEMENAME; ?></h2>
				<span><?php echo ('v'. INWAVE_OF_THEMEVERSION); ?></span>
			</div>
		
			<div id="js-warning">Warning- This options panel will not work properly without javascript!</div>

		<div id="info_bar">

						
			<img src="<?php echo esc_url(INWAVE_OF_DIR); ?>assets/images/loading.gif" class="hide ajax-loading-img ajax-loading-img-bottom" alt="<?php echo esc_html__('Working...', 'inmedical'); ?>" />

			<button id="of_save" type="button" class="button-primary">
				<?php esc_html_e('Save Change','inmedical');?>
			</button>
			
		</div><!--.info_bar--> 	
    	</div>
		<div class="clear"></div>
		<div id="main">
			<div id="of-nav">
				<ul>
				  <?php echo wp_kses_post( $inwave_options_machine->Menu );
                  ?>
				</ul>
			</div>

			<div id="content">
                <?php
                echo (string)$inwave_options_machine->Inputs;
                ?>
                <?php if(isset($_REQUEST['imported'])){
                    ?>
                    <h2 id="import-notice"><?php echo esc_html__('Imported successfully!', 'inmedical');?></h2>
                    <?php
                }?>
		  	</div>
		  	
			<div class="clear"></div>
		</div>
	</form>
	
	<div class="clear"></div>

</div><!--wrap-->
