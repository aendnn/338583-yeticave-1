<main>
    <?=include_template('navigation.php', ['categories' => $categories]); ?>
    <form class="form container <?=isset($errors) ? "form--invalid": ""; ?>" action="login.php" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <?php
        $classname = isset($errors['email']) ? "form__item--invalid" : "";
        $value = isset($login['email']) ? $login['email'] : "";
        $error = isset($errors['email']) ? $errors['email']: "";
        ?>
        <div class="form__item <?=$classname;?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="login[email]" value="<?=esc($value); ?>" placeholder="Введите e-mail">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php
        $classname = isset($errors['password']) ? "form__item--invalid" : "";
        $value = isset($login['password']) ? $login['password'] : "";
        $error = isset($errors['password']) ? $errors['password']: "";
        ?>
        <div class="form__item form__item--last <?=$classname;?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="login[password]" value="<?=esc($value); ?>" placeholder="Введите пароль">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
