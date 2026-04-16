<h1>Диссертации</h1>

<?php if ($isAdmin): ?>
    <p style="color: green;">✅ Режим администратора: полный доступ (добавление/редактирование/удаление)</p>
<?php else: ?>
    <p style="color: blue;">🔵 Режим сотрудника: просмотр и добавление</p>
<?php endif; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>Аспирант</th>
        <th>Тема диссертации</th>
        <th>Дата утверждения</th>
        <th>Статус</th>
        <th>Специальность ВАК</th>
        <th>Действия</th>
    </tr>
    <tr>
        <td colspan="6">Список диссертаций появится после добавления функционала</td>
    </tr>
</table>

<a href="#" class="btn">+ Добавить диссертацию</a>

<?php if ($isAdmin): ?>
    <a href="#" class="btn btn-danger">Режим массового удаления (только админ)</a>
<?php endif; ?>