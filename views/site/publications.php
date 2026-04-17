<h1>Публикации</h1>

<?php if (!empty($publications) && count($publications) > 0): ?>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Название</th>
            <th>Дата публикации</th>
            <th>Научный руководитель</th>
            <th>Издание</th>
            <th>Индексация</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($publications as $pub): ?>
            <?php 
            $canEdit = ($isAdmin || $pub->staff_id == $user->supervisor_id);
            ?>
        <tr>
            <td><?= htmlspecialchars($pub->title) ?></td>
            <td><?= htmlspecialchars($pub->publication_date) ?></td>
            <td><?= htmlspecialchars($pub->staff?->surname . ' ' . $pub->staff?->name ?? '—') ?></td>
            <td><?= htmlspecialchars($pub->edition?->name ?? '—') ?></td>
            <td><?= htmlspecialchars($pub->indexType?->name ?? '—') ?></td>
            <td>
                <?php if ($canEdit): ?>
                    <a href="<?= app()->route->getUrl('/edit-publication/' . $pub->publication_id) ?>">✏️ Редактировать</a>
                    <a href="<?= app()->route->getUrl('/delete-publication/' . $pub->publication_id) ?>" 
                       onclick="return confirm('Вы уверены, что хотите удалить эту публикацию?')">🗑️ Удалить</a>
                <?php else: ?>
                    <span style="color: gray;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Публикаций пока нет.</p>
<?php endif; ?>

<p>
    <a href="<?= app()->route->getUrl('/add-publication') ?>">+ Добавить публикацию</a>
</p>