<h1>Отчёт по количеству защит за период</h1>

<?php if (!empty($message)): ?>
    <div style="padding: 10px; margin: 10px 0; background: #f0f0f0; border: 1px solid #ccc;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
    
    <p>
        <label>Дата с:</label><br>
        <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>" required>
    </p>
    
    <p>
        <label>Дата по:</label><br>
        <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>" required>
    </p>
    
    <p>
        <button type="submit">Сформировать отчёт</button>
    </p>
</form>

<?php if (!empty($reportData) && $reportData['total_defenses'] > 0): ?>
    <hr>
    
    <h2>Результаты отчёта</h2>
    <p><strong>Период:</strong> <?= htmlspecialchars($reportData['date_from']) ?> — <?= htmlspecialchars($reportData['date_to']) ?></p>
    <p><strong>Всего защит:</strong> <?= $reportData['total_defenses'] ?></p>
    
    <h3>Список защищённых диссертаций</h3>
    <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
        <tr>
            <th>Аспирант</th>
            <th>Тема диссертации</th>
            <th>Дата утверждения</th>
            <th>Научный руководитель</th>
            <th>Специальность ВАК</th>
        </tr>
        <?php foreach ($reportData['dissertations'] as $diss): ?>
        <tr>
            <td><?= htmlspecialchars($diss->postgraduate->surname . ' ' . $diss->postgraduate->name) ?></td>
            <td><?= htmlspecialchars($diss->topic) ?></td>
            <td><?= htmlspecialchars($diss->approval_date ?? '—') ?></td>
            <td><?= htmlspecialchars($diss->postgraduate->supervisor->surname . ' ' . $diss->postgraduate->supervisor->name ?? '—') ?></td>
            <td><?= htmlspecialchars($diss->vak_specialty ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>