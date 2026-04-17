<div class="form-container">
    <h1 class="form-title">Добавление диссертации</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Аспирант:</label>
            <select name="postgraduate_id" class="form-select" required>
                <option value="">Выберите аспиранта</option>
                <?php foreach ($postgraduates as $postgraduate): ?>
                    <option value="<?= $postgraduate->postgraduate_id ?>" <?= isset($_GET['postgraduate_id']) && $_GET['postgraduate_id'] == $postgraduate->postgraduate_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($postgraduate->surname . ' ' . $postgraduate->name . ' ' . $postgraduate->patronymic) ?>
                        (рук. <?= htmlspecialchars($postgraduate->supervisor->surname ?? '—') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Тема диссертации:</label>
            <input type="text" name="topic" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Дата утверждения:</label>
            <input type="date" name="approval_date" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Статус:</label>
            <select name="status_id" class="form-select" required>
                <option value="">Выберите статус</option>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= $status->status_id ?>">
                        <?= htmlspecialchars($status->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Специальность ВАК:</label>
            <input type="text" name="vak_specialty" class="form-input">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Добавить диссертацию</button>
            <a href="<?= app()->route->getUrl('/dissertations') ?>" class="btn-back">Назад к списку</a>
        </div>
    </form>
</div>