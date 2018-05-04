<?php
$layout = Inwave_Helper::getPanelSetting('layout');
$mainColor = Inwave_Helper::getPanelSetting('mainColor');
$bgColor = Inwave_Helper::getPanelSetting('bgColor');
?>
<div class="panel-tools">
	<div class="panel-content">
		<div class="row-setting layout-setting">
			<h3 class="title"><?php esc_html_e('LAYOUT', 'inmedical');?></h3>
			<div class="setting">
				<button value="wide" class="button-command button-command-layout wide <?php if($layout=='wide') echo 'active' ?>"><?php esc_html_e('WIDE', 'inmedical');?></button>
				<button value="boxed" class="button-command button-command-layout boxed <?php if($layout=='boxed') echo 'active' ?>"><?php esc_html_e('BOXED', 'inmedical');?></button>
			</div>
		</div>
		<div class="row-setting color-setting sample-setting">
			<h3 class="title"><?php esc_html_e('SAMPLE COLOR', 'inmedical');?></h3>
			<div class="setting">
				<?php
					$color_arr = array('#ed9914', '#49a32b', '#ec3642', '#00c8d6', '#db084d', '#efc10a');
					foreach ($color_arr as $color){
						?>
						<button <?php if($mainColor==$color) echo 'class="active"' ?> value="<?php echo esc_attr($color); ?>" style="background-color: <?php echo esc_attr($color); ?>"></button>
						<?php
					}
				?>
			</div>
			<div class="description">
				<?php esc_html_e('Please read our documentation file to know how to change colors as you want', 'inmedical');?>
			</div>
		</div>
		<div class="overlay-setting <?php if($layout=='wide') echo 'disabled' ?>">
		<div class="row-setting color-setting background-setting">
			<h3 class="title"><?php esc_html_e('BACKGROUND COLOR', 'inmedical');?></h3>
			<div class="setting">
				<?php
				$background_color_arr = array('#87a8a5', '#38424a', '#e3e6e8', '#242d39', '#000000', '#222222');
				foreach ($background_color_arr as $color){
					?>
					<button <?php if($bgColor==$color) echo 'class="active"' ?> value="<?php echo esc_attr($color); ?>" style="background-color: <?php echo esc_attr($color); ?>"></button>
					<?php
				}
				?>
			</div>
		</div>
		<div class="row-setting color-setting background-setting">
			<h3 class="title"><?php esc_html_e('BACKGROUND TEXTURE', 'inmedical');?></h3>
			<div class="setting">
				<div class="setting">
					<?php
					$background_image_arr = array('texture-1.png', 'texture-2.png', 'texture-3.png', 'texture-4.png', 'texture-5.png', 'texture-6.png');
					foreach ($background_image_arr as $image){
						$image_url = get_template_directory_uri().'/assets/images/texture/'.$image;
						?>
						<button <?php if($bgColor== esc_url($image_url)) echo 'class="active"' ?> value="<?php echo esc_url($image_url); ?>" style="background-image: url(<?php echo esc_url($image_url); ?>)"></button>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		</div>
        <?php if(class_exists('RTLTester')): ?>
			<div class="row-setting rtl-setting">
				<div class="setting">
                    <a href="?d=ltr"><span data-value="ltr" class="button-command <?php if(!is_rtl()) echo 'active'; ?>"><?php esc_html_e('LEFT TO RIGHT', 'inmedical');?></span></a>
					<a href="?d=rtl"><span data-value="rtl" class="button-command <?php if(is_rtl()) echo 'active'; ?>"><?php esc_html_e('RIGHT TO LEFT', 'inmedical');?></span></a>

				</div>
			</div>
        <?php endif;?>
		<div class="reset-button">
			<button>Reset</button>
		</div>
	</div>
	<button class="panel-button"><i class="theme-color fa fa-cog"></i></button>
</div>