 /*
* custom post type start
*/
  
function pdfUploader() {
	$labels = array(
		'name'                => _x( 'Resources', 'Resources'),
		'singular_name'       => _x( 'Resource', 'Resources'),
		'menu_name'           => __( 'Resources'),
		'parent_item_colon'   => __( 'Parent Resource'),
		'all_items'           => __( 'All Resources'),
		'view_item'           => __( 'View Resource'),
		'add_new_item'        => __( 'Add New Resource'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Resource'),
		'update_item'         => __( 'Update Resource'),
		'search_items'        => __( 'Search Resource'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash'),
	);

	$args = array(
		'label'               => __( 'Resources'),
		'description'         => __( 'Upload Pdf in Resources page'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail',),
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
		'capability_type'     => 'post',
		'show_in_rest' => true,
  
	);
	register_post_type( 'Resources', $args );
  
}
add_action( 'init', 'pdfUploader', 0 );

 // metabox for uploading pdf file    

 function ad_custom_metaboxes() 
 {
	 add_meta_box('ad_pdf_uploader_metabox','Upload Pdf File', 'ad_callback_func', 'Resources', 'normal', 'high');
 }
 add_action('add_meta_boxes', 'ad_custom_metaboxes');
 function ad_callback_func($post) {
	 wp_nonce_field(basename(__FILE__), 'resource_pdf_meta_box_nonce');
	 $pdf_id = get_post_meta($post->ID, 'resource_pdf_id', true);
	 $pdf_url = wp_get_attachment_url($pdf_id);
	 echo '<input type="button" class="button button-secondary" value="Select PDF" id="select_pdf">';
	 echo '<input type="hidden" name="custom_pdf_id" id="custom_pdf_id" value="' . esc_attr($pdf_id) . '">';
	 if ($pdf_url) {
		 echo '<div id="selected_pdf_display"><p><strong>Selected PDF:</strong> <a href="' . esc_attr($pdf_url) . '" target="_blank">' . esc_html(basename($pdf_url)) . '</a></p></div>';
	 } else {
		 echo '<div id="selected_pdf_display"></div>';
	 }
 }
 
// Enqueue scripts
function enqueue_pdf_upload_script() {
	wp_enqueue_media();
	wp_enqueue_script('pdf-upload-script', get_template_directory_uri() . '/js/pdf-upload-script.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_pdf_upload_script');

//save pdf-metabox
function save_resource_pdf($post_id) {
	if (!isset($_POST['resource_pdf_meta_box_nonce']) || !wp_verify_nonce($_POST['resource_pdf_meta_box_nonce'], basename(__FILE__))) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if ('resources' != $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
		return;
	}
	if (isset($_POST['custom_pdf_id'])) {
		$pdf_id = sanitize_text_field($_POST['custom_pdf_id']);
		update_post_meta($post_id, 'resource_pdf_id', $pdf_id);
	}
}
add_action('save_post', 'save_resource_pdf');
