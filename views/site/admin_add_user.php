<h1>Добавление сотрудника</h1>

<?php if (!empty($message)): ?>
    <p><strong><?= $message ?></strong></p>
<?php endif; ?>

<form method="post">
    <p>
        <label>Логин:</label><br>
        <input type="text" name="login" required>
    </p>
    
    <p>
        <label>Пароль:</label><br>
        <input type="password" name="password" required>
    </p>
    
    <p>
        <label>Имя:</label><br>
        <input type="text" name="name" required>
    </p>
    
    <p>
        <label>Фамилия:</label><br>
        <input type="text" name="surname" required>
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
            <?php foreach ($roles as $id => $role): ?>
                <option value="<?= $id ?>"><?= htmlspecialchars($role) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    
    <p>
        <button type="submit">Добавить сотрудника</button>
        <a href="/dashboard">Назад</a>
    </p>
</form>