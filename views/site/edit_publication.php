<h1>Редактирование публикации</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Название публикации:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($publication->title) ?>" required>
    </p>
    
    <p>
        <label>Дата публикации:</label><br>
        <input type="date" name="publication_date" value="<?= htmlspecialchars($publication->publication_date) ?>" required>
    </p>
    
    <p>
        <label>Научный руководитель (сотрудник):</label><br>
        <select name="staff_id" required>
            <option value="">Выберите сотрудника</option>
            <?php foreach ($staff as $person): ?>
                <option value="<?= $person->supervisor_id ?>" <?= $person->supervisor_id == $publication->staff_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($person->surname . ' ' . $person->name . ' ' . $person->patronymic) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <label>Издание:</label><br>
        <select name="edition_id" required>
            <option value="">Выберите тип издания</option>
            <?php foreach ($editions as $edition): ?>
                <option value="<?= $edition->edition_id ?>" <?= $edition->edition_id == $publication->edition_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($edition->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <label>Индексация:</label><br>
        <select name="index_type_id" required>
            <option value="">Выберите индекс</option>
            <?php foreach ($indexTypes as $index): ?>
                <option value="<?= $index->index_type_id ?>" <?= $index->index_type_id == $publication->index_type_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($index->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <button type="submit">Сохранить изменения</button>
        <a href="<?= app()->route->getUrl('/publications') ?>">Назад к списку</a>
    </p>
</form>