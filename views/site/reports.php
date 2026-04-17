<div class="form-container">
    <h1 class="form-title">Отчёт по количеству защит за период</h1>

    <?php if (!empty($message)): ?>
        <div class="message-error">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= app()->auth::generateCSRF() ?>">
        
        <div class="form-group">
            <label class="form-label">Дата с:</label>
            <input type="date" name="date_from" value="<?= htmlspecialchars($dateFrom) ?>" class="form-input" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Дата по:</label>
            <input type="date" name="date_to" value="<?= htmlspecialchars($dateTo) ?>" class="form-input" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Сформировать отчёт</button>
        </div>
    </form>

    <?php if (!empty($reportData) && $reportData['total_defenses'] > 0): ?>
        <div class="report-results">
            <h2 class="report-title">Результаты отчёта</h2>
            
            <div class="report-info">
                <p><strong>Период:</strong> <?= htmlspecialchars($reportData['date_from']) ?> — <?= htmlspecialchars($reportData['date_to']) ?></p>
                <p><strong>Всего защит:</strong> <?= $reportData['total_defenses'] ?></p>
            </div>
            
            <h3 class="report-subtitle">Список защищённых диссертаций</h3>
            
            <div class="table-responsive">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Аспирант</th>
                            <th>Тема диссертации</th>
                            <th>Дата утверждения</th>
                            <th>Научный руководитель</th>
                            <th>Специальность ВАК</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportData['dissertations'] as $diss): ?>
                            <tr>
                                <td><?= htmlspecialchars($diss->postgraduate->surname . ' ' . $diss->postgraduate->name) ?></td>
                                <td><?= htmlspecialchars($diss->topic) ?></td>
                                <td><?= htmlspecialchars($diss->approval_date ?? '—') ?></td>
                                <td><?= htmlspecialchars($diss->postgraduate->supervisor->surname . ' ' . $diss->postgraduate->supervisor->name ?? '—') ?></td>
                                <td><?= htmlspecialchars($diss->vak_specialty ?? '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>