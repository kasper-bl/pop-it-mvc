<?php if ($isAdmin): ?>
    <section class="dashboard">
        <div class="dashboard__title">
            <h1>Панель администратора</h1>
            <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
        </div>
        <div class="dashboard__staff">
            <div class="dashboard__staff-title">
                <h3>Управление сотрудниками</h3>
                <p>
                    <a class="btn-add" href="<?= app()->route->getUrl('/admin/users/add') ?>">Добавить сотрудника</a>
                </p>
            </div>
            <div class="dashboard__staff-content">
                <h3>Список сотрудников</h3>
                <?php
                    $users = \Model\Staff::all();
                ?>
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Логин</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Роль</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $userItem): ?>
                        <tr>
                            <td><?= $userItem->supervisor_id ?></td>
                            <td><?= htmlspecialchars($userItem->login) ?></td>
                            <td><?= htmlspecialchars($userItem->name) ?></td>
                            <td><?= htmlspecialchars($userItem->surname) ?></td>
                            <td>
                                <?php if ($userItem->role_id == 1): ?>
                                    Администратор
                                <?php else: ?>
                                    Сотрудник
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($userItem->supervisor_id != $user->supervisor_id): ?>
                                    <a href="<?= app()->route->getUrl('/admin/users/delete/' . $userItem->supervisor_id) ?>" 
                                    class="btn-delete-user"
                                    onclick="return confirm('Вы уверены, что хотите удалить сотрудника <?= htmlspecialchars($userItem->name) ?> <?= htmlspecialchars($userItem->surname) ?>?')">
                                    Удалить
                                    </a>
                                <?php else: ?>
                                    <span class="disabled-action">Действия вам не разрешены</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    
<?php else: ?>
    <section class="dashboard">
        <h1>Панель сотрудника</h1>
        <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    </section>
<?php endif; ?>