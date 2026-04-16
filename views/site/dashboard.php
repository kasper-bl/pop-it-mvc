<?php if ($isAdmin): ?>
    <h1>Панель администратора</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    <p>Вы имеете полный доступ к управлению диссертациями и публикациями.</p>
    
    <div style="margin-top: 30px;">
        <a href="<?= app()->route->getUrl('/dissertations') ?>" class="btn">Управление диссертациями</a>
        <a href="<?= app()->route->getUrl('/publications') ?>" class="btn">Управление публикациями</a>
    </div>
<?php else: ?>
    <h1>Панель сотрудника научного отдела</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($user->name) ?>!</p>
    <p>Учёт аспирантов, диссертаций и публикаций.</p>
    
    <div style="margin-top: 30px;">
        <a href="<?= app()->route->getUrl('/dissertations') ?>" class="btn">Диссертации</a>
        <a href="<?= app()->route->getUrl('/publications') ?>" class="btn">Публикации</a>
        <a href="<?= app()->route->getUrl('/reports') ?>" class="btn">Отчёты</a>
        <a href="<?= app()->route->getUrl('/search') ?>" class="btn">Поиск аспирантов</a>
    </div>
<?php endif; ?>