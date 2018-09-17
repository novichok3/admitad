<?php

add_action( 'wp_ajax_my_action', 'my_action_callback' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
function my_action_callback(){

    $meta_fields = array(
        'real_estate_price',
        'real_estate_area',
        'real_estate_living_area',
        'real_estate_address',
        'real_estate_floor',
        'real_estate_city_id'
    );

    $post_id = wp_insert_post(array (
        'post_type' => 'real_estate',
        'post_title' => $_POST['real_estate_name'],
        'post_status' => 'publish',
    ));

    foreach($meta_fields as $field) {
        $field_vlue = $_POST[$field];
        add_post_meta($post_id, $field, $field_vlue);
    }

    $__test_meta_update =  get_post_meta($post_id);

    $real_estate_type_slug = $_POST['real_estate_type_slug'];

    wp_set_object_terms( $post_id, $real_estate_type_slug, 'real_estate_types' );

    if( ! isset( $_FILES ) || empty( $_FILES ) || ! isset( $_FILES['file'] ) )
        die();

    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    $upload_overrides = array( 'test_form' => false );

    $file = $_FILES['file'];

    $uploadedfile = array(
        'name'     => $file['name'],
        'type'     => $file['type'],
        'tmp_name' => $file['tmp_name'],
        'error'    => $file['error'],
        'size'     => $file['size']
    );

    if ($movefile = wp_handle_upload( $uploadedfile, $upload_overrides ) ) {
        echo "file uploaded<br>";

        $upload_id = wp_insert_attachment( array(
            'guid'           => $movefile['file'],
            'post_mime_type' => $movefile['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', $file['name'] ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ), $movefile['file'], $post_id );

        wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $movefile['file'] ) );
        set_post_thumbnail( $post_id, $upload_id );
    }


    die();
}

