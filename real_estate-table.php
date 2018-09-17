<div class="table-responsive">
    <table class="table real_estate_front_data">
    <tbody>
    <tr>
        <th scope="row" class="first_col">Название объекта:</th>
        <td><?php echo $post->post_title ?></td>
    </tr>
    <tr>
        <th scope="row">Цена: </th>
        <td><?php echo get_post_meta($post->ID, 'real_estate_price', true )?></td>
    </tr>
    <tr>
        <th scope="row">Площадь: </th>
        <td><?php echo get_post_meta($post->ID, 'real_estate_area', true )?></td>
    </tr>
    <tr>
        <th scope="row">Жилая площадь: </th>
        <td><?php echo get_post_meta($post->ID, 'real_estate_living_area', true )?></td>
    </tr>
    <tr>
        <th scope="row">Город: </th>
        <td><?php

            $city_id = get_post_meta($post->ID, 'real_estate_city_id', true );

            if (is_numeric($city_id)) {
                $city_post_obj = get_post($city_id);
                if (is_a($city_post_obj, 'WP_Post')) { ?>
                    <a href="<?php echo $city_post_obj->guid ?>" title="<?php echo $city_post_obj->post_title ?>"><?php echo $city_post_obj->post_title ?></a>
                <?php }
            }
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row">Адрес: </th>
        <td><?php echo get_post_meta($post->ID, 'real_estate_address', true )?></td>
    </tr>
    <tr>
        <th scope="row">Этаж: </th>
        <td><?php echo get_post_meta($post->ID, 'real_estate_floor', true )?></td>
    </tr>
    </tbody>
</table>