<section class="postgraduates">
    <div class="postgraduates__title">
        <h1>Диссертации</h1>
    </div>

    <div class="postgraduates__list">
        <div class="postgraduates__list-title">
            <h3>Список диссертаций</h3>
            <p>
                <a href="<?= app()->route->getUrl('/add-dissertation') ?>" class="btn-add">Добавить диссертацию</a>
            </p>
        </div>
        <div class="postgraduates__list-content">
            <?php if (!empty($dissertations) && count($dissertations) > 0): ?>
                <table class="postgraduates-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Аспирант</th>
                            <th>Тема диссертации</th>
                            <th>Дата утверждения</th>
                            <th>Статус</th>
                            <th>Специальность ВАК</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dissertations as $dissertation): ?>
                            <?php 
                            $canEdit = ($isAdmin || $dissertation->postgraduate->supervisor_id == $user->supervisor_id);
                            ?>
                            <tr>
                                <td class="dissertation-id"><?= htmlspecialchars($dissertation->dissertation_id) ?></td>
                                <td class="dissertation-postgraduate"><?= htmlspecialchars($dissertation->postgraduate->surname . ' ' . $dissertation->postgraduate->name ?? '—') ?></td>
                                <td class="dissertation-topic"><?= htmlspecialchars($dissertation->topic) ?></td>
                                <td class="dissertation-date"><?= htmlspecialchars($dissertation->approval_date ?? '—') ?></td>
                                <td class="dissertation-status"><?= htmlspecialchars($dissertation->status->name ?? '—') ?></td>
                                <td class="dissertation-vak"><?= htmlspecialchars($dissertation->vak_specialty ?? '—') ?></td>
                                <td class="actions-cell">
                                    <?php if ($canEdit): ?>
                                        <a href="<?= app()->route->getUrl('/edit-dissertation/' . $dissertation->dissertation_id) ?>" class="btn-edit">Ред.</a>
                                        <a href="<?= app()->route->getUrl('/delete-dissertation/' . $dissertation->dissertation_id) ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Вы уверены, что хотите удалить диссертацию?')">
                                           Удалить
                                        </a>
                                    <?php else: ?>
                                        <span class="disabled-action">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty-message">Диссертаций пока нет.</p>
            <?php endif; ?>
        </div>
    </div>
</section>