<div class="form-container">
    <h1 class="form-title">Добавление сотрудника</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?php
            $decoded = json_decode($message, true);
            if (is_array($decoded)) {
                echo '<ul class="error-list">';
                foreach ($decoded as $field => $errors) {
                    foreach ($errors as $error) {
                        echo '<li>' . htmlspecialchars($error) . '</li>';
                    }
                }
                echo '</ul>';
            } else {
                echo '<p>' . htmlspecialchars($message) . '</p>';
            }
            ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Логин:</label>
            <input type="text" name="login" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Пароль:</label>
            <input type="password" name="password" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Имя:</label>
            <input type="text" name="name" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Фамилия:</label>
            <input type="text" name="surname" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Отчество:</label>
            <input type="text" name="patronymic" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Кафедра/отдел:</label>
            <input type="text" name="department" class="form-input">
        </div>
        
        <div class="form-group">
            <label class="form-label">Роль:</label>
            <select name="id_role" class="form-select">
                <option value="2">Сотрудник научного отдела</option>
                <option value="1">Администратор</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Добавить сотрудника</button>
            <a href="<?= app()->route->getUrl('/dashboard') ?>" class="btn-back">Назад</a>
        </div>
    </form>
</div>