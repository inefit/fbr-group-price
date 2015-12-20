<?php


function fbr_product_list_tabs(){
	?>
		<li class="fbr-group-options">
			<a href="#fbr_price_tab"><?php _e('Groups Prices' , 'fbr-group'); ?></a>
		</li>
	<?php

	add_action( 'woocommerce_product_write_panels', 'fbr_product_tab_content' );
}

add_action( 'woocommerce_product_write_panel_tabs' , 'fbr_product_list_tabs');


function fbr_product_tab_content(){
	include "views/product.php";
}