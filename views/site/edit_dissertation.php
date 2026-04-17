<div class="form-container">
    <h1 class="form-title">Редактирование диссертации</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Аспирант:</label>
            <input type="text" value="<?= htmlspecialchars($dissertation->postgraduate->surname . ' ' . $dissertation->postgraduate->name) ?>" class="form-input" readonly disabled style="background: #f0f0f0;">
        </div>
        
        <div class="form-group">
            <label class="form-label">Тема диссертации:</label>
            <input type="text" name="topic" value="<?= htmlspecialchars($dissertation->topic) ?>" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Дата утверждения:</label>
            <input type="date" name="approval_date" value="<?= htmlspecialchars($dissertation->approval_date ?? '') ?>" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Статус:</label>
            <select name="status_id" class="form-select" required>
                <option value="">Выберите статус</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status->status_id ?>" <?= $status->status_id == $dissertation->status_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($status->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Специальность ВАК:</label>
            <input type="text" name="vak_specialty" value="<?= htmlspecialchars($dissertation->vak_specialty ?? '') ?>" class="form-input">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Сохранить изменения</button>
            <a href="<?= app()->route->getUrl('/dissertations') ?>" class="btn-back">Назад к списку</a>
        </div>
    </form>
</div>