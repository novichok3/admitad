<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <!-- .entry-header -->
    <div class="real_estate">
        <div class="row">
            <div class="col-sm-3">
                <?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?>
            </div>
            <div class="entry-content col-sm-9">
                <header class="entry-header">

                    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
                        '</a></h2>' ); ?>

                </header>

                <?php //the_excerpt(); ?>

            <strong>Цена:</strong> <?php echo number_format ( (int)get_post_meta($post->ID, 'real_estate_price', true ) , 0 ,  "." , "," ) ?> руб.&nbsp;&nbsp;&nbsp;
            Площадь: <?php echo get_post_meta($post->ID, 'real_estate_area', true )?> кв.м.&nbsp;&nbsp;
            Жилая площадь:
            <?php echo get_post_meta($post->ID, 'real_estate_living_area', true )?> кв.м.<br>
            Город:
                <?php

                $city_id = get_post_meta($post->ID, 'real_estate_city_id', true );

                if (is_numeric($city_id)) {
                    $city_post_obj = get_post($city_id);
                    if (is_a($city_post_obj, 'WP_Post')) { ?>
                        <a href="<?php echo $city_post_obj->guid ?>" title="<?php echo $city_post_obj->post_title ?>" target="_blank"><?php echo $city_post_obj->post_title ?></a>
                   <?php }
                }
                ?>&nbsp;&nbsp;
            Адрес: <?php echo get_post_meta($post->ID, 'real_estate_address', true )?><br>
            Этаж: <?php echo get_post_meta($post->ID, 'real_estate_floor', true )?><br>
            <a href="<?php the_permalink() ?>" title="<?php echo $post->post_title ?>" class="real_eastate_more_link">Посмотреть объект</a>
                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
                    'after'  => '</div>',
                ) );
                ?>

            </div><!-- .entry-content -->

            <footer class="entry-footer">

                <?php // understrap_entry_footer(); ?>

            </footer><!-- .entry-footer -->
        </div>

    </div>

</article><!-- #post-## -->