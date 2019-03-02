<main>
    <?=include_template('navigation.php', ['categories' => $categories]); ?>
    <form class="form container <?=isset($errors) ? "form--invalid": ""; ?>" action="sign-up.php" enctype="multipart/form-data" method="post"> <!-- form--invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <?php
        $classname = isset($errors['email']) ? "form__item--invalid" : "";
        $value = isset($registration['email']) ? $registration['email'] : "";
        $error = isset($errors['email']) ? $errors['email']: "";
        ?>
        <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="signup[email]" value="<?=esc($value);?>" placeholder="Введите e-mail">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php
        $classname = isset($errors['password']) ? "form__item--invalid" : "";
        $value = isset($registration['password']) ? $registration['password'] : "";
        $error = isset($errors['password']) ? $dict['password']: "";
        ?>
        <div class="form__item <?=$classname;?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="signup[password]" value="<?=esc($value); ?>" placeholder="Введите пароль">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php
        $classname = isset($errors['username']) ? "form__item--invalid" : "";
        $value = isset($registration['username']) ? $registration['username'] : "";
        $error = isset($errors['username']) ? $dict['username']: "";
        ?>
        <div class="form__item <?=$classname;?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="signup[username]" value="<?=esc($value); ?>" placeholder="Введите имя">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php
        $classname = isset($errors['contacts']) ? "form__item--invalid" : "";
        $value = isset($registration['contacts']) ? $registration['contacts'] : "";
        $error = isset($errors['contacts']) ? $dict['contacts']: "";
        ?>
        <div class="form__item <?=$classname;?>">
            <label for="message">Контактные данные*</label>
            <textarea id="message" name="signup[contacts]" placeholder="Напишите как с вами связаться"><?=esc($value); ?></textarea>
            <span class="form__error"><?=$error; ?></span>
        </div>
        <div class="form__item form__item--file form__item--last">
            <label>Аватар</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="avatar" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
</main>
