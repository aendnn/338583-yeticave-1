<main>
   <?=include_template('navigation.php', ['categories' => $categories]); ?>
    <section class="lot-item container">
        <?php foreach ($lot as $item): ?>
        <h2><?=esc($item['title']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?=$item['pic']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?=esc($item['cat_name']); ?></span></p>
                <p class="lot-item__description"><?=esc($item['desc']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        <?=esc(time_counter("now", "tomorrow midnight")); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <?php if (empty($bids)): ?>
                            <span class="lot-item__cost">
                                <?=esc($lot[0]['primary_price']); ?></span>
                            <?php else: ?>
                            <span class="lot-item__cost">
                                <?=esc($bids[0]['max_bid'] + $lot[0]['primary_price']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?=esc($item['step_bid']); ?></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user']) && strtotime($item['date_end']) > strtotime("now") && $_SESSION['user']['id'] !== $lot[0]['user_id'] && !$bid_done): ?>
                    <form class="lot-item__form" action="lot.php?id=<?=$id;?>" method="post">
                        <?php
                        $classname = isset($errors['cost']) ? "form__item--invalid" : "";
                        $value = isset($bid['cost']) ? $bid['cost'] : "";
                        $error = isset($errors['cost']) ? $errors['cost']: "";
                        ?>
                        <p class="lot-item__form-item form__item <?=$classname;?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="12 000" value="<?=esc($value);?>">
                            <span class="form__error"><?=$error; ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?=count($bids); ?></span>)</h3>
                    <?php foreach ($bids as $val): ?>
                    <table class="history__list">
                        <tr class="history__item">
                            <td class="history__name"><?=esc($val['user_name']); ?></td>
                            <td class="history__price"><?=esc($val['sum_bid']); ?> р</td>
                            <td class="history__time"><?=esc(bid_time($val['date_bid'])); ?></td>
                        </tr>
                    </table>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
</main>
