<section class="postgraduates">
    <div class="postgraduates__title">
        <h1>Аспиранты</h1>
    </div>
    
    <div class="postgraduates__filter">
        <div class="postgraduates__filter-title">
            <h3>Фильтр по научному руководителю</h3>
        </div>
        <div class="postgraduates__filter-content">
            <form method="get" class="filter-form">
                <div class="filter-form__field">
                    <select name="supervisor_id" class="filter-select">
                        <option value="">Все руководители</option>
                        <?php foreach ($supervisors as $supervisor): ?>
                            <option value="<?= $supervisor->supervisor_id ?>" <?= ($searchSupervisorId == $supervisor->supervisor_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($supervisor->surname . ' ' . $supervisor->name . ' ' . $supervisor->patronymic) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-form__actions">
                    <button type="submit" class="btn-filter"> Найти</button>
                    <a href="<?= app()->route->getUrl('/postgraduates') ?>" class="btn-reset">Сбросить фильтр</a>
                </div>
            </form>
        </div>
    </div>

    <div class="postgraduates__list">
        <div class="postgraduates__list-title">
            <h3>Список аспирантов</h3>
            <p>
                <a href="<?= app()->route->getUrl('/add-postgraduate') ?>" class="btn-add">Добавить аспиранта</a>
            </p>
        </div>
        <div class="postgraduates__list-content">
            <?php if (!empty($postgraduates) && count($postgraduates) > 0): ?>
                <table class="postgraduates-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Фамилия</th>
                            <th>Имя</th>
                            <th>Отчество</th>
                            <th>Научный руководитель</th>
                            <th>Диссертация</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($postgraduates as $postgraduate): ?>
                            <?php 
                            $canEdit = ($isAdmin || $postgraduate->supervisor_id == $user->supervisor_id);
                            $hasDissertation = $postgraduate->dissertation ? true : false;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($postgraduate->postgraduate_id) ?></td>
                                <td><?= htmlspecialchars($postgraduate->surname) ?></td>
                                <td><?= htmlspecialchars($postgraduate->name) ?></td>
                                <td><?= htmlspecialchars($postgraduate->patronymic ?? '—') ?></td>
                                <td><?= htmlspecialchars($postgraduate->supervisor?->surname . ' ' . $postgraduate->supervisor?->name ?? '—') ?></td>
                                <td class="dissertation-cell">
                                    <?php if ($hasDissertation): ?>
                                        <a href="<?= app()->route->getUrl('/edit-dissertation/' . $postgraduate->dissertation->dissertation_id) ?>" class="btn-dissertation">
                                            Редактировать диссертацию
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= app()->route->getUrl('/add-dissertation?postgraduate_id=' . $postgraduate->postgraduate_id) ?>" class="btn-dissertation-add">
                                            Добавить диссертацию
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="actions-cell">
                                    <?php if ($canEdit): ?>
                                        <a href="<?= app()->route->getUrl('/edit-postgraduate/' . $postgraduate->postgraduate_id) ?>" class="btn-edit">Ред.</a>
                                        <a href="<?= app()->route->getUrl('/delete-postgraduate/' . $postgraduate->postgraduate_id) ?>" 
                                           class="btn-delete"
                                           onclick="return confirm('Вы уверены, что хотите удалить аспиранта <?= htmlspecialchars($postgraduate->name) ?> <?= htmlspecialchars($postgraduate->surname) ?>?')">
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
                <p class="empty-message">Аспирантов не найдено.</p>
            <?php endif; ?>
        </div>
    </div>
</section>