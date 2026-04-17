<section class="publications">
    <div class="publications__title">
        <h1>Публикации</h1>
    </div>

    <div class="publications__list">
        <div class="publications__list-title">
            <h3>Список публикаций</h3>
            <p>
                <a href="<?= app()->route->getUrl('/add-publication') ?>" class="btn-add">Добавить публикацию</a>
            </p>
        </div>
        <div class="publications__list-content">
            <?php if (!empty($publications) && count($publications) > 0): ?>
                <div class="publications-grid">
                    <?php foreach ($publications as $pub): ?>
                        <?php 
                        $canEdit = ($isAdmin || $pub->staff_id == $user->supervisor_id);
                        ?>
                        <div class="publication-card">
                            <?php if ($pub->image_path): ?>
                                <div class="publication-card__image">
                                    <img src="<?= app()->route->getUrl($pub->image_path) ?>" alt="Обложка">
                                </div>
                            <?php else: ?>
                                <div class="publication-card__no-image">
                                    <span> Нет изображения (а где оно Олег)</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="publication-card__content">
                                <h3 class="publication-card__title"><?= htmlspecialchars($pub->title) ?></h3>
                                
                                <p class="publication-card__date">
                                    <?= date('d.m.Y', strtotime($pub->publication_date)) ?>
                                </p>
                                
                                <p class="publication-card__supervisor">
                                    <?= htmlspecialchars($pub->staff?->surname . ' ' . $pub->staff?->name ?? '—') ?>
                                </p>
                                
                                <p class="publication-card__edition">
                                    <?= htmlspecialchars($pub->edition?->name ?? '—') ?>
                                </p>
                                
                                <p class="publication-card__index">
                                    <?= htmlspecialchars($pub->indexType?->name ?? '—') ?>
                                </p>
                            </div>
                            
                            <?php if ($canEdit): ?>
                                <div class="publication-card__actions">
                                    <a href="<?= app()->route->getUrl('/edit-publication/' . $pub->publication_id) ?>" class="btn-edit">Редактировать</a>
                                    <a href="<?= app()->route->getUrl('/delete-publication/' . $pub->publication_id) ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Вы уверены, что хотите удалить эту публикацию?')">Удалить</a>
                                </div>
                            <?php else: ?>
                                <div class="publication-card__view-only">
                                    <span>Только для просмотра</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-message">Публикаций пока нет.</p>
            <?php endif; ?>
        </div>
    </div>
</section>