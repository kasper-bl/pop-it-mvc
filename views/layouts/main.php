<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="<?= app()->route->getUrl('/assets/style/main.css') ?>">
        <title>Научный отдел | Аспирантура</title>
    </head>
    <body>
        <header class="wrapper">
            <nav>
                <?php if (app()->auth::check()): ?>
                    <?php
                        $user = app()->auth::user();
                        $isAdmin = ($user->role_id == 1);
                    ?>
                    <?php if ($isAdmin): ?>
                        <a href="<?= app()->route->getUrl('/dashboard') ?>">Панель администратора</a>
                        <a href="<?= app()->route->getUrl('/postgraduates') ?>">Аспиранты</a>
                        <a href="<?= app()->route->getUrl('/dissertations') ?>">Диссертации</a>
                        <a href="<?= app()->route->getUrl('/publications') ?>">Публикации</a>
                        <a href="<?= app()->route->getUrl('/reports') ?>">Отчёты</a>
                    <?php else: ?>
                        <a href="<?= app()->route->getUrl('/dashboard') ?>">Панель сотрудника</a>
                        <a href="<?= app()->route->getUrl('/postgraduates') ?>">Аспиранты</a>
                        <a href="<?= app()->route->getUrl('/dissertations') ?>">Диссертации</a>
                        <a href="<?= app()->route->getUrl('/publications') ?>">Публикации</a>
                        <a href="<?= app()->route->getUrl('/reports') ?>">Отчёты</a>
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