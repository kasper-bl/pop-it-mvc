<h2>Вход в систему</h2>
<?php if (!app()->auth::check()): ?>
    <form method="post">
        <input type="text" name="login" placeholder="Логин">
        <input type="password" name="password" placeholder="Пароль">
        <button>Войти</button>
    </form>
<?php else: ?>
    <h3>Вы уже вошли</h3>
<?php endif; ?>