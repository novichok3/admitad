<?php
	
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
// Creating the widget 
class wpb_widget extends WP_Widget {
 
	function __construct() {
		parent::__construct(
	 		'wpb_widget', 
	 		'Список городов', 
			array( 'description' => 'Виджет для вывода списка городов', ) 
		);
	}
 
	// Creating widget front-end
 
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
 
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
 
		// This is where you run the code and display the output
		$this->showCityList();
		echo $args['after_widget'];
	}

	public function showCityList() {
	    $args = array('post_type' => 'city');
	    $query = new WP_Query($args);
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post(); ?>
                <ul class="widget_city_list">
                    <?php
                        $args2 = array('post_type' => 'real_estate', 'meta_key' => 'real_estate_city_id', 'meta_value' => get_the_ID(), );
                        $query2 = new WP_Query($args2);
                    ?>
                    <li><a href="<?php the_permalink() ?>" title="the_title();"><?php the_title(); echo " (".$query2->found_posts.")"; ?></li>
                </ul>
                <?php
            }
        } else {
            echo "Нет городов в базе";
        }
    }
         
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php 
	}
     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class wpb_widget ends here
