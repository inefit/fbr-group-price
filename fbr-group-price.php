<?php
/*
Plugin Name: FBR Group Price
Plugin URI: http://fabrictheme.com
Description: Wordpress Plugin for grouping price (Support Woocommerce)
Version: 0.2.0
Author: Labscode
Author URI: http://fabrictheme.com

/*
* Creating a function to create our CPT
*/

function fbr_gpr_init() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => __( 'Groups', 'fbr-group' ),
		'singular_name'       => __( 'Group', 'fbr-group' ),
		'menu_name'           => __( 'Groups Prices', 'fbr-group' ),
		'all_items'           => __( 'All Groups', 'fbr-group' ),
		'view_item'           => __( 'View Group', 'fbr-group' ),
		'add_new_item'        => __( 'Add New Group', 'fbr-group' ),
		'add_new'             => __( 'Add New', 'fbr-group' ),
		'edit_item'           => __( 'Edit Group', 'fbr-group' ),
		'update_item'         => __( 'Update Group', 'fbr-group' ),
		'search_items'        => __( 'Search Group', 'fbr-group' ),
		'not_found'           => __( 'Not Found', 'fbr-group' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'fbr-group' ),
	);
	
// Set other options for Custom Post Type {P}
	
	$args = array(
		'label'               => __( 'Groups', 'fbr-group' ),
		'description'         => __( 'Groups Prices', 'fbr-group' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor' ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'fbr-group' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	
	// Registering your Custom Post Type
	register_post_type( 'gmember_price', $args );

}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/

add_action( 'init', 'fbr_gpr_init', 0 );


function fbr_add_metaboxes(){
	$lists = array( 'gmember_price' );
	foreach($lists as $list){
		add_meta_box(
			'fbr_meta_group',
			__( 'Members', 'fbr-group' ),
			'fbr_meta_group',
			$list
		);
	}
}

add_action( 'add_meta_boxes' , 'fbr_add_metaboxes');


function fbr_meta_group($post){
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'fbr_save_meta_box_data', 'fbr_meta_box_nonce' );

	$value = get_post_meta( $post->ID, 'fbr_group_member', true );

	$users = get_users();
	echo '<select id="fbr_member_field" name="fbr_member_field[]" multiple="multiple">';
	foreach($users as $u){
		echo '<option value="'.$u->ID.'">'.esc_html( $u->display_name ).'</option>';
	}
	echo '</select>';

	?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				<?php if(empty($value)) { ?>
					$('#fbr_member_field').select2({ placeholder: 'Select Member', width: '100%' });
				<?php } else { ?>
					var selectedValues = new Array();
					<?php
					$val = explode(',',$value);
					$i = 0;
					foreach($val as $v){
						echo 'selectedValues["'.$i.'"] = "'.$v.'";';
						$i++;
					}
					?>
					$('#fbr_member_field').val(selectedValues);
					$('#fbr_member_field').select2({ placeholder: 'Select Member', width: '100%' , val : selectedValues }); 
				<?php } ?>
			});
		</script>
	<?php
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function fbr_save_meta_box_data( $post_id ) {

	if ( isset( $_POST['post_type'] ) && 'gmember_price' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	}
	else
		return;

	// Make sure that it is set.
	if ( ! isset( $_POST['fbr_member_field'] ) ) {
		return;
	}

	$data = implode(",",$_POST['fbr_member_field']);
	// Sanitize user input.
	$my_data = sanitize_text_field( $data );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'fbr_group_member', $my_data );
}
add_action( 'save_post', 'fbr_save_meta_box_data' );

function fbr_admin_head() {

	// Bring the post type global into scope
	global $post_type;

	// If the current post type doesn't match, return, ie. end execution here
	if( 'gmember_price' == $post_type  || 'product' == $post_type){
		wp_enqueue_style('fbr-select2-style', plugin_dir_url( __FILE__ ).'assets/css/select2.css');
    	wp_enqueue_script( 'fbr-select2', plugin_dir_url( __FILE__ ).'assets/js/select2.js');

    	wp_enqueue_style('fbr-style', plugin_dir_url( __FILE__ ).'assets/css/style.css');
	}
	else{
		return;
	}

	
}

add_action( 'admin_head', 'fbr_admin_head' );

include "product.php";