<div class="form-container">
    <h1 class="form-title">Редактирование публикации</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Название публикации:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($publication->title) ?>" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Дата публикации:</label>
            <input type="date" name="publication_date" value="<?= htmlspecialchars($publication->publication_date) ?>" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Научный руководитель (сотрудник):</label>
            <select name="staff_id" class="form-select" required>
                <option value="">Выберите сотрудника</option>
                <?php foreach ($staff as $person): ?>
                    <option value="<?= $person->supervisor_id ?>" <?= $person->supervisor_id == $publication->staff_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($person->surname . ' ' . $person->name . ' ' . $person->patronymic) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Издание:</label>
            <select name="edition_id" class="form-select" required>
                <option value="">Выберите тип издания</option>
                <?php foreach ($editions as $edition): ?>
                    <option value="<?= $edition->edition_id ?>" <?= $edition->edition_id == $publication->edition_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($edition->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Индексация:</label>
            <select name="index_type_id" class="form-select" required>
                <option value="">Выберите индекс</option>
                <?php foreach ($indexTypes as $index): ?>
                    <option value="<?= $index->index_type_id ?>" <?= $index->index_type_id == $publication->index_type_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($index->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Изображение (обложка):</label>
            <input type="file" name="image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
            <small class="form-hint">Оставьте пустым, чтобы сохранить текущее изображение</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="<?= app()->route->getUrl('/publications') ?>" class="btn-back">Назад к списку</a>
        </div>
    </form>
</div>