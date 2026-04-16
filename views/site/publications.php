<h1>Публикации</h1>

<a href="<?= app()->route->getUrl('/add-publication') ?>">Добавить публикацию</a>

<?php if (!empty($publications) && count($publications) > 0): ?>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Название</th>
            <th>Издание</th>
            <th>Дата публикации</th>
            <th>Руководитель</th>
            <th>Тип</th>
            <th>Индексация</th>
        </tr>
        <?php foreach ($publications as $pub): ?>
        <tr>
            <td><?= htmlspecialchars($pub->title) ?></td>
            <td><?= htmlspecialchars($pub->edition) ?></td>
            <td><?= htmlspecialchars($pub->publication_date) ?></td>
            <td><?= htmlspecialchars($pub->supervisor?->getFullName() ?? '—') ?></td>
            <td><?= htmlspecialchars($pub->publicationType?->name ?? '—') ?></td>
            <td><?= htmlspecialchars($pub->indexType?->name ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Публикаций пока нет.</p>
<?php endif; ?>