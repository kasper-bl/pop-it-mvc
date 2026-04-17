<h1>Редактирование аспиранта</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Фамилия:</label><br>
        <input type="text" name="surname" value="<?= htmlspecialchars($postgraduate->surname) ?>" required>
    </p>
    
    <p>
        <label>Имя:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($postgraduate->name) ?>" required>
    </p>
    
    <p>
        <label>Отчество:</label><br>
        <input type="text" name="patronymic" value="<?= htmlspecialchars($postgraduate->patronymic ?? '') ?>">
    </p>
    
    <?php if ($user->role_id == 1): ?>
        <p>
            <label>Научный руководитель:</label><br>
            <select name="supervisor_id" required>
                <option value="">Выберите руководителя</option>
                <?php foreach ($supervisors as $supervisor): ?>
                    <option value="<?= $supervisor->supervisor_id ?>" <?= $supervisor->supervisor_id == $postgraduate->supervisor_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($supervisor->surname . ' ' . $supervisor->name . ' ' . $supervisor->patronymic) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php endif; ?>
    
    <p>
        <button type="submit">Сохранить изменения</button>
        <a href="<?= app()->route->getUrl('/postgraduates') ?>">Назад к списку</a>
    </p>
</form>