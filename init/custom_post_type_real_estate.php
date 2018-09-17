<?php 

function create_post_type_realestate() {
    register_post_type( 'real_estate',
        array(
            'labels' => array(
            'name' => 'Недвижимость',
            'singular_name' => 'Недвижимость',
            ),
        'public' => true,
        'menu_position' => 5,
        'rewrite' => array('slug' => 'real_estate'),
        'has_archive' => true,
        'supports' => array( 'title', 'thumbnail' )
        )
    );
  flush_rewrite_rules();
}
add_action( 'init', 'create_post_type_realestate' );

function real_estate_type() {
    register_taxonomy(
        'real_estate_types',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'real_estate',        //post type name
        array(
            'hierarchical' => true,
            'label' => 'Тип недвижимости',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'real_estate_type', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before
            )
        )
    );
}
add_action( 'init', 'real_estate_type');

/// add some functionality to the real estate grid in admin
add_filter( 'manage_real_estate_posts_columns', 'real_estate_filter_posts_columns' );
function real_estate_filter_posts_columns( $columns ) {
    $columns['image'] = 'IMG';
    $columns['price'] = 'Цена';
    $columns['area'] = 'Площадь';
//    $columns['real_estate_city'] = 'Город';
    return $columns;
}

add_filter( 'manage_edit-real_estate_sortable_columns', 'real_estate_sortable_columns');
function real_estate_sortable_columns( $columns ) {
    $columns['price'] = 'price';
    $columns['area'] = 'area';
    return $columns;
}

add_action( 'pre_get_posts', 'real_estate_posts_orderby' );
function real_estate_posts_orderby( $query ) {
    if( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( 'price' === $query->get( 'orderby') ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'price' );
        $query->set( 'meta_type', 'numeric' );
    }

    if ( 'area' === $query->get( 'orderby') ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', 'area' );
        $query->set( 'meta_type', 'numeric' );
    }
}

add_action( 'manage_real_estate_posts_custom_column', 'real_estate_column', 10, 2);
function real_estate_column( $column, $post_id ) {
    // Image column

    switch($column) {
        case 'image':
            echo get_the_post_thumbnail( $post_id, array(80, 80) );
            break;
        case 'price':
            if ( !$price = get_post_meta( $post_id, 'real_estate_price', true ) ) {
                echo 'n/a';
            } else {
                echo number_format( $price, 0, '.', ',' ) . ' руб.';
            }
            break;
        case 'area':
            echo get_post_meta( $post_id, 'real_estate_area', true ) . ' кв.м.';
            break;
/*        case 'real_estate_city':
            $real_estate_city = wp_get_post_terms( $post_id, 'real_estate_city' );
            //var_dump($real_estate_city);
            echo $real_estate_city[0]->name;
            break; */
    }
}



//init the extra image meta box in real estate
add_action( 'after_setup_theme', 'custom_postimage_setup' );
function custom_postimage_setup(){
    add_action( 'add_meta_boxes', 'custom_postimage_meta_box' );
    add_action( 'save_post', 'custom_postimage_meta_box_save' );
}

function custom_postimage_meta_box(){
    //on which post types should the box appear?
    $post_types = array('real_estate');
    foreach($post_types as $pt){
        add_meta_box('custom_postimage_meta_box',__( 'More Featured Images'),'custom_postimage_meta_box_func',$pt,'side','low');
    }
}

function custom_postimage_meta_box_func($post){

    //an array with all the images (ba meta key). The same array has to be in custom_postimage_meta_box_save($post_id) as well.
    $meta_keys = array('second_featured_image','third_featured_image');

    foreach($meta_keys as $meta_key){
        $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
        ?>
        <div class="custom_postimage_wrapper" id="<?php echo $meta_key; ?>_wrapper" style="margin-bottom:20px;">
            <img src="<?php echo ($image_meta_val!=''?wp_get_attachment_image_src( $image_meta_val)[0]:''); ?>" style="width:100%;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" alt="">
            <a class="addimage button" onclick="custom_postimage_add_image('<?php echo $meta_key; ?>');"><?php _e('add image'); ?></a><br>
            <a class="removeimage" style="color:#a00;cursor:pointer;display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" onclick="custom_postimage_remove_image('<?php echo $meta_key; ?>');"><?php _e('remove image'); ?></a>
            <input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $image_meta_val; ?>" />
        </div>
    <?php } ?>
    <script>
        function custom_postimage_add_image(key){

            var $wrapper = jQuery('#'+key+'_wrapper');

            custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('select image'); ?>',
                button: {
                    text: '<?php _e('select image'); ?>'
                },
                multiple: false
            });
            custom_postimage_uploader.on('select', function() {

                var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
                var img_url = attachment['url'];
                var img_id = attachment['id'];
                $wrapper.find('input#'+key).val(img_id);
                $wrapper.find('img').attr('src',img_url);
                $wrapper.find('img').show();
                $wrapper.find('a.removeimage').show();
            });
            custom_postimage_uploader.on('open', function(){
                var selection = custom_postimage_uploader.state().get('selection');
                var selected = $wrapper.find('input#'+key).val();
                if(selected){
                    selection.add(wp.media.attachment(selected));
                }
            });
            custom_postimage_uploader.open();
            return false;
        }

        function custom_postimage_remove_image(key){
            var $wrapper = jQuery('#'+key+'_wrapper');
            $wrapper.find('input#'+key).val('');
            $wrapper.find('img').hide();
            $wrapper.find('a.removeimage').hide();
            return false;
        }
    </script>
    <?php
    wp_nonce_field( 'custom_postimage_meta_box', 'custom_postimage_meta_box_nonce' );
}

function custom_postimage_meta_box_save($post_id){

    if ( ! current_user_can( 'edit_posts', $post_id ) ){ return 'not permitted'; }

    if (isset( $_POST['custom_postimage_meta_box_nonce'] ) && wp_verify_nonce($_POST['custom_postimage_meta_box_nonce'],'custom_postimage_meta_box' )){

        //same array as in custom_postimage_meta_box_func($post)
        $meta_keys = array('second_featured_image','third_featured_image');
        foreach($meta_keys as $meta_key){
            if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
            }else{
                update_post_meta( $post_id, $meta_key, '');
            }
        }
    }
}

/// end of real estate extra images metabox

?>