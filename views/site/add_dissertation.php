<h1>Добавление диссертации</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Аспирант:</label><br>
        <select name="postgraduate_id" required>
            <option value="">Выберите аспиранта</option>
            <?php foreach ($postgraduates as $postgraduate): ?>
                <option value="<?= $postgraduate->postgraduate_id ?>" <?= isset($_GET['postgraduate_id']) && $_GET['postgraduate_id'] == $postgraduate->postgraduate_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($postgraduate->surname . ' ' . $postgraduate->name . ' ' . $postgraduate->patronymic) ?>
                    (рук. <?= htmlspecialchars($postgraduate->supervisor->surname ?? '—') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <label>Тема диссертации:</label><br>
        <input type="text" name="topic" required>
    </p>
    
    <p>
        <label>Дата утверждения:</label><br>
        <input type="date" name="approval_date">
    </p>
    
    <p>
        <label>Статус:</label><br>
        <select name="status_id" required>
            <option value="">Выберите статус</option>
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status->status_id ?>">
                    <?= htmlspecialchars($status->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <label>Специальность ВАК:</label><br>
        <input type="text" name="vak_specialty">
    </p>
    
    <p>
        <button type="submit">Добавить диссертацию</button>
        <a href="<?= app()->route->getUrl('/dissertations') ?>">Назад к списку</a>
    </p>
</form>