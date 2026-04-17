<?php if ($isAdmin): ?>
    <h1>Панель администратора</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    
    <h3>Управление сотрудниками</h3>
    <p>
        <a href="<?= app()->route->getUrl('/admin/users/add') ?>">➕ Добавить сотрудника</a>
    </p>
    
    <h3>Список сотрудников</h3>
    <?php
    $users = \Model\Staff::all();
    ?>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($users as $userItem): ?>
        <tr>
            <td><?= $userItem->supervisor_id ?></td>
            <td><?= htmlspecialchars($userItem->login) ?></td>
            <td><?= htmlspecialchars($userItem->name) ?></td>
            <td><?= htmlspecialchars($userItem->surname) ?></td>
            <td>
                <?php if ($userItem->role_id == 1): ?>
                    👑 Администратор
                <?php else: ?>
                    👤 Сотрудник
                <?php endif; ?>
            </td>
            <td>
                <?php if ($userItem->supervisor_id != $user->supervisor_id): ?>
                    <a href="<?= app()->route->getUrl('/admin/users/delete/' . $userItem->supervisor_id) ?>" 
                       onclick="return confirm('Вы уверены, что хотите удалить сотрудника <?= htmlspecialchars($userItem->name) ?> <?= htmlspecialchars($userItem->surname) ?>?')"
                       style="color: red;">🗑️ Удалить</a>
                <?php else: ?>
                    <span style="color: gray;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
<?php else: ?>
    <!-- контент для сотрудника -->
    <h1>Панель сотрудника</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    
    <ul>
        <li><a href="<?= app()->route->getUrl('/postgraduates') ?>">Аспиранты</a></li>
        <li><a href="<?= app()->route->getUrl('/dissertations') ?>">Диссертации</a></li>
        <li><a href="<?= app()->route->getUrl('/publications') ?>">Публикации</a></li>
        <li><a href="<?= app()->route->getUrl('/add-publication') ?>">+ Добавить публикацию</a></li>
        <li><a href="<?= app()->route->getUrl('/reports') ?>">Отчёты</a></li>
        <li><a href="<?= app()->route->getUrl('/search') ?>">Поиск аспирантов</a></li>
    </ul>
<?php endif; ?>