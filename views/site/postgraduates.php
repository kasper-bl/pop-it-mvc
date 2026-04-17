<h1>Аспиранты</h1>

<form method="get" style="margin-bottom: 20px; padding: 15px; background: #f5f5f5; border: 1px solid #ddd;">
    <p>
        <label>Фильтр по научному руководителю:</label><br>
        <select name="supervisor_id" style="width: 250px;">
            <option value="">Все руководители</option>
            <?php foreach ($supervisors as $supervisor): ?>
                <option value="<?= $supervisor->supervisor_id ?>" <?= ($searchSupervisorId == $supervisor->supervisor_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($supervisor->surname . ' ' . $supervisor->name . ' ' . $supervisor->patronymic) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <button type="submit">🔍 Найти</button>
        <a href="<?= app()->route->getUrl('/postgraduates') ?>">Сбросить фильтр</a>
    </p>
</form>

<?php if (!empty($postgraduates) && count($postgraduates) > 0): ?>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <table>
            <th>ID</th>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Научный руководитель</th>
            <th>Диссертация</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($postgraduates as $postgraduate): ?>
            <?php 
            $canEdit = ($isAdmin || $postgraduate->supervisor_id == $user->supervisor_id);
            $hasDissertation = $postgraduate->dissertation ? true : false;
            ?>
        <tr>
            <td><?= htmlspecialchars($postgraduate->postgraduate_id) ?></td>
            <td><?= htmlspecialchars($postgraduate->surname) ?></td>
            <td><?= htmlspecialchars($postgraduate->name) ?></td>
            <td><?= htmlspecialchars($postgraduate->patronymic ?? '—') ?></td>
            <td><?= htmlspecialchars($postgraduate->supervisor?->surname . ' ' . $postgraduate->supervisor?->name ?? '—') ?></td>
            <td>
                <?php if ($hasDissertation): ?>
                    <a href="<?= app()->route->getUrl('/edit-dissertation/' . $postgraduate->dissertation->dissertation_id) ?>">📖 Редактировать диссертацию</a>
                <?php else: ?>
                    <a href="<?= app()->route->getUrl('/add-dissertation?postgraduate_id=' . $postgraduate->postgraduate_id) ?>">➕ Добавить диссертацию</a>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($canEdit): ?>
                    <a href="<?= app()->route->getUrl('/edit-postgraduate/' . $postgraduate->postgraduate_id) ?>">✏️ Ред.</a>
                    <a href="<?= app()->route->getUrl('/delete-postgraduate/' . $postgraduate->postgraduate_id) ?>" 
                       onclick="return confirm('Вы уверены, что хотите удалить аспиранта?')">🗑️ Удалить</a>
                <?php else: ?>
                    <span style="color: gray;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Аспирантов не найдено.</p>
<?php endif; ?>

<p style="margin-top: 20px;">
    <a href="<?= app()->route->getUrl('/add-postgraduate') ?>">+ Добавить аспиранта</a>
</p>