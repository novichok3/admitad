<?php 
// add city taxonomy to real_estate custom post
function real_estate_city() {
    register_taxonomy(
        'real_estate_city',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'real_estate',        //post type name
        array(
            'hierarchical' => false,
            'show_ui'      => false,
            'show_admin_column' => true,
            'meta_box_city'       => 'real_estate_city_meta_box',
            'label' => 'Город',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'themes', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before
            )
        )
    );
}
add_action( 'init', 'real_estate_city');


/// single city only meta box
add_action( 'after_setup_theme', 'real_estate_city_setup' );
function real_estate_city_setup(){
    add_action( 'add_meta_boxes', 'real_estate_city_meta_box' );
    add_action( 'save_post', 'save_real_estate_city_meta_box' );
}

function real_estate_city_meta_box(){
    //on which post types should the box appear?
    $post_types = array('real_estate');
    foreach($post_types as $pt){
        add_meta_box('real_estate_city_meta_box',__( 'City'),'real_estate_city_meta_box_func',$pt,'normal','high');
    }
}

function real_estate_city_meta_box_func( $post ) {
    $terms = get_terms( 'real_estate_city', array( 'hide_empty' => false ) );
    $post  = get_post();
    $city = wp_get_object_terms( $post->ID, 'real_estate_city', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
    $name  = '';
    if ( ! is_wp_error( $city ) ) {
        if ( isset( $city[0] ) && isset( $city[0]->name ) ) {
            $name = $city[0]->name;
        }
    }
    foreach ( $terms as $term ) {
        ?>
        <label title='<?php esc_attr_e( $term->name ); ?>'>
            <input type="radio" name="real_estate_city" value="<?php esc_attr_e( $term->name ); ?>" <?php checked( $term->name, $name ); ?>>
            <span><?php esc_html_e( $term->name ); ?></span>
        </label><br>
        <?php
    }
}

function save_real_estate_city_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! isset( $_POST['real_estate_city'] ) ) {
        return;
    }
    $city = sanitize_text_field( $_POST['real_estate_city'] );

    // A valid rating is required, so don't let this get published without one
    if ( empty( $city ) ) {
        // unhook this function so it doesn't loop infinitely
        remove_action( 'save_post_real_estate', 'save_real_estate_city_meta_box' );
        $postdata = array(
            'ID'          => $post_id,
            'post_status' => 'draft',
        );
        wp_update_post( $postdata );
    } else {
        $term = get_term_by( 'name', $city, 'real_estate_city' );
        if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
            wp_set_object_terms( $post_id, $term->term_id, 'real_estate_city', false );
        }
    }
}


?>