<main>
    <?=include_template('navigation.php', ['categories' => $categories]); ?>
    <form class="form form--add-lot container <?=isset($errors) ? "form--invalid": ""; ?>" action="add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php
            $classname = isset($errors['title']) ? "form__item--invalid" : "";
            $value = isset($add_lot['title']) ? $add_lot['title'] : "";
            $error = isset($errors['title']) ? $dict['title']: "";
            ?>
            <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="title" value="<?=esc($value);?>" placeholder="Введите наименование лота">
                <span class="form__error"><?=$error; ?></span>
            </div>
            <?php
            $classname = isset($errors['category']) ? "form__item--invalid" : "";
            $value = isset($add_lot['category']) ? $add_lot['category'] : "";
            $error = isset($errors['category']) ? $dict['category']: "";
            ?>
            <div class="form__item <?=$classname;?>">
                <label for="category">Категория</label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?=$cat['id'];?>"<?php if($cat['id'] == $value): echo ' selected'; endif;?>><?=$cat['name']?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=$error; ?></span>
            </div>
        </div>
        <?php
        $classname = isset($errors['desc']) ? "form__item--invalid" : "";
        $value = isset($add_lot['desc']) ? $add_lot['desc'] : "";
        $error = isset($errors['desc']) ? $dict['desc']: "";
        ?>
        <div class="form__item form__item--wide <?=$classname; ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="desc" placeholder="Напишите описание лота"><?=esc($value);?></textarea>
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php
        $classname = isset($errors['lot_img']) ? "form__item--invalid" : "";
        ?>
        <div class="form__item form__item form__item--file <?=$classname; ?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="" name="lot_img">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <?php
            $classname = isset($errors['price']) ? "form__item--invalid" : "";
            $value = isset($add_lot['price']) ? $add_lot['price'] : "";
            $error = isset($errors['price']) ? $dict['price']: "";
            ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="text" name="price" value="<?=esc($value);?>" placeholder="0">
                <span class="form__error"><?=$error; ?></span>
            </div>
            <?php
            $classname = isset($errors['step']) ? "form__item--invalid" : "";
            $value = isset($add_lot['step']) ? $add_lot['step'] : "";
            $error = isset($errors['step']) ? $dict['step']: "";
            ?>
            <div class="form__item form__item--small <?=$classname;?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="text" name="step" value="<?=esc($value);?>" placeholder="0">
                <span class="form__error"><?=$error; ?></span>
            </div>
            <?php
            $classname = isset($errors['date']) ? "form__item--invalid" : "";
            $value = isset($add_lot['date']) ? $add_lot['date'] : "";
            $error = isset($errors['date']) ? $dict['date']: "";
            ?>
            <div class="form__item <?=$classname;?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="date" value="<?=esc($value);?>">
                <span class="form__error"><?=$error; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
