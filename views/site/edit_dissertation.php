<h1>Редактирование диссертации</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Аспирант:</label><br>
        <input type="text" value="<?= htmlspecialchars($dissertation->postgraduate->surname . ' ' . $dissertation->postgraduate->name) ?>" readonly disabled style="background: #f0f0f0;">
    </p>
    
    <p>
        <label>Тема диссертации:</label><br>
        <input type="text" name="topic" value="<?= htmlspecialchars($dissertation->topic) ?>" required>
    </p>
    
    <p>
        <label>Дата утверждения:</label><br>
        <input type="date" name="approval_date" value="<?= htmlspecialchars($dissertation->approval_date ?? '') ?>">
    </p>
    
    <p>
        <label>Статус:</label><br>
        <select name="status_id" required>
            <option value="">Выберите статус</option>
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status->status_id ?>" <?= $status->status_id == $dissertation->status_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($status->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <label>Специальность ВАК:</label><br>
        <input type="text" name="vak_specialty" value="<?= htmlspecialchars($dissertation->vak_specialty ?? '') ?>">
    </p>
    
    <p>
        <button type="submit">Сохранить изменения</button>
        <a href="<?= app()->route->getUrl('/dissertations') ?>">Назад к списку</a>
    </p>
</form>