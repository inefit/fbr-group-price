<div id="fbr_price_tab" class="panel woocommerce_options_panel">
	<div class="options_group">
		<div class="fbr-p">
			<div class="fbr-width-30">
				<?php _e('Group' , 'fbr-group'); ?>
			</div>
			<div class="fbr-width-30">
				<?php _e('Price' , 'fbr-group'); ?>
			</div>
			<div class="fbr-width-30">
				<?php _e('Action' , 'fbr-group'); ?>
			</div>
		</div>
		<div class="fbr-p">
			<div class="fbr-width-30">
				<select name="slk_fbr_group_list" id="slk_fbr_group_list" style="width:90%">
					<option value="0"><?php _e('Select Group'); ?></option>
					<?php $args = array( 'post_type' => 'gmember_price'); ?>
					<?php $loop = new WP_Query( $args ); ?>
					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
						<option value="<?php the_ID() ?>"><?php the_title() ?></option>
					<?php endwhile;	?>
				</select>
			</div>
			<div class="fbr-width-30">
				<input type="text" name="txt_fbr_price" class="short wc_input_price" id="txt_fbr_price" placeholder="<?php _e('Input Price' ,'fbr-group') ?>" />				
			</div>
			<div class="fbr-width-30">
				<button type="button" id="btn_group_add" class="button"><?php _e('Add' , 'fbr-group'); ?></button>
			</div>
		</div>
		
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#slk_fbr_group_list').select2();

		$('#btn_group_add').click(function(){
			if( $('#slk_fbr_group_list').val() == '0' ){
				$('#slk_fbr_group_list').select2('open');
			}
			else if( $('#txt_fbr_price').val() == '' ){
				$('#txt_fbr_price').focus();
			}
		});
	})
</script>