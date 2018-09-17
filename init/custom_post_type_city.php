<?php 

function create_post_type_city() {
    register_post_type( 'city',
        array(
            'labels' => array(
            'name' => 'Город',
            'singular_name' => 'Город',
            ),
        'public' => true,
        'menu_position' => 6,
        'rewrite' => array('slug' => 'city'),
        'has_archive' => true,
        'supports' => array( 'title', 'thumbnail', 'editor' )
        )
    );
  flush_rewrite_rules();
}
add_action( 'init', 'create_post_type_city' );


/// single city only meta box
add_action( 'after_setup_theme', 'real_estate_city_setup' );
function real_estate_city_setup(){
    add_action( 'add_meta_boxes', 'real_estate_city_meta_box' );
    add_action( 'save_post', 'save_real_estate_city_meta_box' );
}

function real_estate_city_meta_box(){
    //on which post types should the box appear?
    $post_type = 'real_estate';
    add_meta_box('real_estate_city_meta_box', 'Город' ,'real_estate_city_meta_box_func', $post_type,'normal','high');
}

function real_estate_city_meta_box_func( $post) {

    $cities = get_posts(array('post_type' => 'city', 'post_status' => 'publish', 'posts_per_page' => 100, 'order' => 'ASC'));

    $current_city = get_post_meta($post->ID, 'real_estate_city_id', true);

    if ($cities) { //
        foreach ($cities as $city) { ?>
            <label title='<?php echo $city->post_title ?>'>
                <input type="radio" name="real_estate_city_id" value="<?php echo $city->ID ?>" <?php checked( $city->ID, $current_city ); ?>>
                <span><?php echo  $city->post_title; ?></span>
            </label><br>
<?php   }
    } else {
        echo "Список городов пуст";
    }
}

function save_real_estate_city_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! isset( $_POST['real_estate_city_id'] ) ) {
        return;
    }
    $city_id = sanitize_text_field( $_POST['real_estate_city_id'] );

    // A valid rating is required, so don't let this get published without one
    if ( empty( $city_id ) ) {
        // unhook this function so it doesn't loop infinitely
        remove_action( 'save_post_real_estate', 'save_real_estate_city_meta_box' );
        $postdata = array(
            'ID'          => $post_id,
            'post_status' => 'draft',
        );
        wp_update_post( $postdata );
    } else {
        update_post_meta( $post_id, 'real_estate_city_id', $city_id );
    }
}
