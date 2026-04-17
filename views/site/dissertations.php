<h1>Диссертации</h1>

<?php if (!empty($dissertations) && count($dissertations) > 0): ?>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>ID</th>
            <th>Аспирант</th>
            <th>Тема диссертации</th>
            <th>Дата утверждения</th>
            <th>Статус</th>
            <th>Специальность ВАК</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($dissertations as $dissertation): ?>
            <?php 
            $canEdit = ($isAdmin || $dissertation->postgraduate->supervisor_id == $user->supervisor_id);
            ?>
        <tr>
            <td><?= htmlspecialchars($dissertation->dissertation_id) ?></td>
            <td><?= htmlspecialchars($dissertation->postgraduate->surname . ' ' . $dissertation->postgraduate->name ?? '—') ?></td>
            <td><?= htmlspecialchars($dissertation->topic) ?></td>
            <td><?= htmlspecialchars($dissertation->approval_date ?? '—') ?></td>
            <td><?= htmlspecialchars($dissertation->status->name ?? '—') ?></td>
            <td><?= htmlspecialchars($dissertation->vak_specialty ?? '—') ?></td>
            <td>
                <?php if ($canEdit): ?>
                    <a href="<?= app()->route->getUrl('/edit-dissertation/' . $dissertation->dissertation_id) ?>">✏️ Редактировать</a>
                    <a href="<?= app()->route->getUrl('/delete-dissertation/' . $dissertation->dissertation_id) ?>" 
                       onclick="return confirm('Вы уверены, что хотите удалить диссертацию?')">🗑️ Удалить</a>
                <?php else: ?>
                    <span style="color: gray;">—</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Диссертаций пока нет.</p>
<?php endif; ?>

<p>
    <a href="<?= app()->route->getUrl('/add-dissertation') ?>">+ Добавить диссертацию</a>
</p>