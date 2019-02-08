<nav class="nav">
    <ul class="nav__list container">
        <?php
        foreach ($categories as $item): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?=$item; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
