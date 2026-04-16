<?php if ($isAdmin): ?>
    <h1>Панель администратора</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    
    <h3>Управление сотрудниками</h3>
    <ul>
        <li><a href="<?= app()->route->getUrl('/admin/users/add') ?>">Добавить сотрудника</a></li>
    </ul>
    
    <h3>Список сотрудников</h3>
    <?php
    $users = \Model\Staff::all();
    ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Роль</th>
        </tr>
        <?php foreach ($users as $userItem): ?>
        <tr>
            <td><?= $userItem->id_staff ?></td>
            <td><?= htmlspecialchars($userItem->login) ?></td>
            <td><?= htmlspecialchars($userItem->name) ?></td>
            <td><?= htmlspecialchars($userItem->surname) ?></td>
            <td>
                <?php if ($userItem->id_role == 1): ?>
                    Администратор
                <?php else: ?>
                    Сотрудник
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
<?php else: ?>
    <h1>Панель сотрудника</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    
    <ul>
        <li><a href="<?= app()->route->getUrl('/dissertations') ?>">Диссертации</a></li>
        <li><a href="<?= app()->route->getUrl('/publications') ?>">Публикации</a></li>
        <li><a href="<?= app()->route->getUrl('/reports') ?>">Отчёты</a></li>
        <li><a href="<?= app()->route->getUrl('/search') ?>">Поиск аспирантов</a></li>
    </ul>
<?php endif; ?>