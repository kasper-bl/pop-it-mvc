<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="public\assets\style\main.css">
        <link rel="stylesheet" href="public\assets\style\login.css">
        <link rel="stylesheet" href="public\assets\style\register.css">
        <title>Pop it MVC</title>
        
    </head>
    <body>
        <header class="wrapper">
            <nav>
                <a href="<?= app()->route->getUrl('/') ?>">Главная</a>

                <?php if (app()->auth::check()): ?>
                    <?php
                        $user = app()->auth::user();
                        $isAdmin = ($user->id_role == 1);
                    ?>

                    <?php if ($isAdmin): ?>
                        <!-- Меню администратора -->
                        <a href="<?= app()->route->getUrl('/dashboard') ?>">Панель администратора</a>
                        <a href="<?= app()->route->getUrl('/dissertations') ?>">Управление диссертациями</a>
                        <a href="<?= app()->route->getUrl('/publications') ?>">Управление публикациями</a>
                        <a href="<?= app()->route->getUrl('/admin/users/add') ?>">Добавить сотрудника</a>
                    <?php else: ?>
                        <!-- Меню сотрудника научного отдела -->
                        <a href="<?= app()->route->getUrl('/dashboard') ?>">Панель сотрудника</a>
                        <a href="<?= app()->route->getUrl('/dissertations') ?>">Диссертации</a>
                        <a href="<?= app()->route->getUrl('/publications') ?>">Публикации</a>
                        <a href="<?= app()->route->getUrl('/reports') ?>">Отчёты</a>
                        <a href="<?= app()->route->getUrl('/search') ?>">Поиск аспирантов</a>
                    <?php endif; ?>

                    <a href="<?= app()->route->getUrl('/logout') ?>">Выход (<?= $user->name ?>)</a>
                    <?php else: ?>
                        <a href="<?= app()->route->getUrl('/login') ?>">Вход</a>
                    <?php endif; ?>
            </nav>
        </header>
        <main class="wrapper">
            <?= $content ?? '' ?>
        </main>
    </body>
</html>

   