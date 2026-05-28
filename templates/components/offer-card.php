<?php
/**
 * Reusable offer card.
 * @var array          $offer   Required. Keys: id, title, location, category,
 *                              salary, accommodation, meals_included, created_at
 * @var \App\Core\View $view
 */
?>
<div class="offer-card clearfix">
  <div class="span-14">
    <h2>
      <a href="/offers/<?= $view->e($offer['id']) ?>">
        <?= $view->e($offer['title']) ?>
      </a>
    </h2>
    <p style="color:#999; margin:3px 0;">
      <?= $view->e($offer['location']) ?>
      &nbsp;&middot;&nbsp;
      <a href="/offers?category=<?= $view->e(urlencode($offer['category'])) ?>">
        <?= $view->e($offer['category']) ?>
      </a>
      <?php if (!empty($offer['accommodation'])): ?>
        &nbsp;&middot;&nbsp;<span class="badge">&#127968; Accommodation</span>
      <?php endif; ?>
      <?php if (!empty($offer['meals_included'])): ?>
        &nbsp;&middot;&nbsp;<span class="badge">&#127869; Meals</span>
      <?php endif; ?>
    </p>
  </div>
  <div class="span-10 last" style="text-align:right; color:#999;">
    <p style="font-weight:bold; color:#333; margin:0;">
      <?= $view->e($offer['salary']) ?>
    </p>
    <p style="font-size:12px; margin:4px 0 0;">
      <?= $view->date($offer['created_at']) ?>
    </p>
  </div>
</div>
