<h1>Публикации</h1>

<?php if ($isAdmin): ?>
    <p style="color: green;">✅ Режим администратора: полный доступ</p>
<?php else: ?>
    <p style="color: blue;">🔵 Режим сотрудника: просмотр и добавление</p>
<?php endif; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Название</th>
        <th>Издание</th>
        <th>Дата публикации</th>
        <th>Индекс</th>
        <th>Автор (руководитель)</th>
        <th>Действия</th>
    </tr>
    <tr>
        <td colspan="6">Список публикаций появится после добавления функционала</td>
    </tr>
</table>

<a href="#" class="btn">+ Добавить публикацию</a>

<?php if ($isAdmin): ?>
    <a href="#" class="btn btn-danger">🗑️ Режим массового удаления (только админ)</a>
<?php endif; ?>