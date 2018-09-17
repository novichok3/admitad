<div class="alert alert-success" role="alert" id="success_msg_box">
    <div id="success_msg_text">Error</div>
    <button id="close_primary_box_button" type="button" class="btn btn-success float-right">Закрыть</button>
</div>

<div class="alert alert-danger" role="alert" id="error_msg_box" >
    <div id="error_msg_text">Error</div>
    <button id="close_error_box_button" type="button" class="btn btn-danger float-right">Закрыть</button>
 </div>

<form id="add_real_estate_form" enctype="multipart/form-data" onsubmit="return false;">
    <h2 class="form_title">Добавить объект</h2>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="real_estate_name">Название</label>
            <input type="text" class="form-control" id="real_estate_name" name="real_estate_name" placeholder="Например: квартира с балконом 3 спальни">
        </div>
        <div class="form-group col-md-6">
            <label for="real_estate_price">Цена (в рублях)</label>
            <input class="form-control" id="real_estate_price" name="real_estate_price" type="number" placeholder="10,000,000" min="0" data-bind="value:replyNumber">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="real_estate_area">Общая площать (кв.м)</label>
            <input class="form-control" id="real_estate_area" name="real_estate_area" type="number" placeholder="Например: 150">
        </div>
        <div class="form-group col-md-6">
            <label for="real_estate_linivng_area">Жилая площать (кв.м)</label>
            <input class="form-control" id="real_estate_linivng_area" name="real_estate_living_area" type="number" placeholder="Например: 110">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="real_estate_address">Адрес</label>
            <input class="form-control" id="real_estate_address" name="real_estate_address" type="text" placeholder="Тверская, д.7, кв.666">
        </div>

        <div class="form-group col-md-6">
            <label for="real_estate_floor">Этаж</label>
            <input class="form-control" id="real_estate_floor" name="real_estate_floor" type="number" placeholder="Например: 13">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="add_real_estate_type">Выберите тип недвижимости</label>
            <select class="form-control" id="add_real_estate_type" name="real_estate_type_slug">
                <?php

                $real_estate_types = get_terms(array('taxonomy' => 'real_estate_types', 'hide_empty' => false,));
                if (!is_a($real_estate_types, 'WP_Error')) {
                    foreach ($real_estate_types as $real_estate_type) {
                        if ($real_estate_type->name === 'Uncategorized') continue;
                        echo "<option value='$real_estate_type->slug'>$real_estate_type->name</option>";
                    }
                } else {
                    echo "<option value='error'>Список типов пуст</option>";
                }

                ?>

            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="add_real_estate_city">Выберите город:</label>
            <select class="form-control" id="add_real_estate_city" name="real_estate_city_id">

                <?php
                    $cities = get_posts(array('post_type' => 'city', 'post_status' => 'publish', 'posts_per_page' => 100, 'order' => 'ASC'));
                    if ($cities) { //
                        foreach ($cities as $city) {
                            echo "<option value='$city->ID'>$city->post_title</option>";
                        }
                    } else {
                        echo "<option value='error'>Список городов пуст</option>";
                    }
                ?>

            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="real_estate_img">Загрузите фото:</label>
        <input id="real_estate_img" name="real_estate_img" multiple="" type="file" class="btn btn-secondary"/>
        <button type="submit" title="submit" class="btn btn-primary float-right" id="add_object">Добавить объект</button>
    </div>
</form>

<div id = "ajax_result"></div>

