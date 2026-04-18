<div class="form-container">
    <h1 class="form-title">Добавление публикации</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Название публикации:</label>
            <input type="text" name="title" class="form-input" >
        </div>
        
        <div class="form-group">
            <label class="form-label">Дата публикации:</label>
            <input type="date" name="publication_date" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Научный руководитель (сотрудник):</label>
            <select name="staff_id" class="form-select">
                <option value="">Выберите сотрудника</option>
                <?php foreach ($staff as $person): ?>
                    <option value="<?= $person->supervisor_id ?>">
                        <?= htmlspecialchars($person->surname . ' ' . $person->name . ' ' . $person->patronymic) ?>
                        <?php if ($user->role_id != 1 && $person->supervisor_id == $user->supervisor_id): ?>
                            (это вы)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Издание:</label>
            <select name="edition_id" class="form-select">
                <option value="">Выберите тип издания</option>
                <?php foreach ($editions as $edition): ?>
                    <option value="<?= $edition->edition_id ?>">
                        <?= htmlspecialchars($edition->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Индексация:</label>
            <select name="index_type_id" class="form-select">
                <option value="">Выберите индекс</option>
                <?php foreach ($indexTypes as $index): ?>
                    <option value="<?= $index->index_type_id ?>">
                        <?= htmlspecialchars($index->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Изображение (обложка):</label>
            <input type="file" name="image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
            <small class="form-hint">Поддерживаются форматы: JPG, PNG, GIF, WEBP</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Добавить публикацию</button>
            <a href="<?= app()->route->getUrl('/publications') ?>" class="btn-back">Назад к списку</a>
        </div>
    </form>
</div>