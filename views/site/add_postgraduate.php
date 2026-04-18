<div class="form-container">
    <h1 class="form-title">Добавление аспиранта</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Фамилия:</label>
            <input type="text" name="surname" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Имя:</label>
            <input type="text" name="name" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Отчество:</label>
            <input type="text" name="patronymic" class="form-input">
        </div>
        
        <?php if ($user->role_id == 1): ?>
            <div class="form-group">
                <label class="form-label">Научный руководитель:</label>
                <select name="supervisor_id" class="form-select">
                    <option value="">Выберите руководителя</option>
                    <?php foreach ($supervisors as $supervisor): ?>
                        <option value="<?= $supervisor->supervisor_id ?>">
                            <?= htmlspecialchars($supervisor->surname . ' ' . $supervisor->name . ' ' . $supervisor->patronymic) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Добавить аспиранта</button>
            <a href="<?= app()->route->getUrl('/postgraduates') ?>" class="btn-back">Назад к списку</a>
        </div>
    </form>
</div>