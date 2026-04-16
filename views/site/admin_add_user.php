<h1>Добавление сотрудника</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?php
        // Пробуем распарсить JSON с ошибками
        $decoded = json_decode($message, true);
        if (is_array($decoded)) {
            echo '<ul>';
            foreach ($decoded as $field => $errors) {
                foreach ($errors as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
            }
            echo '</ul>';
        } else {
            echo htmlspecialchars($message);
        }
        ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Логин:</label><br>
        <input type="text" name="login">
    </p>
    
    <p>
        <label>Пароль:</label><br>
        <input type="password" name="password">
    </p>
    
    <p>
        <label>Имя:</label><br>
        <input type="text" name="name">
    </p>
    
    <p>
        <label>Фамилия:</label><br>
        <input type="text" name="surname">
    </p>
    
    <p>
        <label>Отчество:</label><br>
        <input type="text" name="patronymic">
    </p>
    
    <p>
        <label>Кафедра/отдел:</label><br>
        <input type="text" name="department">
    </p>
    
    <p>
        <label>Роль:</label><br>
        <select name="id_role">
            <option value="2">Сотрудник научного отдела</option>
            <option value="1">Администратор</option>
        </select>
    </p>
    
    <p>
        <button type="submit">Добавить сотрудника</button>
        <a href="/dashboard">Назад</a>
    </p>
</form>