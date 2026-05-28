<?php /** @var \App\Core\View $view  @var array $offers  @var string $search_query */ ?>

<div class="clearfix">
  <div class="span-16" id="top" style="margin-bottom:10px;">
    <h1 style="display:inline;">
      <?php if ($search_query !== ''): ?>
        Results for &ldquo;<?= $view->e($search_query) ?>&rdquo;
      <?php else: ?>
        All Offers
      <?php endif; ?>
    </h1>
    <span style="color:#999; font-size:14px; margin-left:10px;">
      <?= count($offers) ?> <?= count($offers) === 1 ? 'offer' : 'offers' ?>
    </span>
  </div>
</div>

<!-- Search refinement bar -->
<form action="/offers" method="get" style="margin-bottom:20px; background:#f5f5f5;
      padding:10px; border-radius:4px;">
  <input type="text" name="q" value="<?= $view->e($search_query) ?>"
         placeholder="Refine search…" style="width:280px; padding:6px;" />
  <button type="submit" style="padding:6px 14px;">Search</button>
  <?php if ($search_query !== ''): ?>
    <a href="/offers" style="margin-left:10px; color:#999;">Clear</a>
  <?php endif; ?>
</form>

<?php if (empty($offers)): ?>
  <div class="box">
    <p>No offers found<?= $search_query !== '' ? ' matching your search' : '' ?>.</p>
    <a href="/offers">&larr; Show all offers</a>
  </div>
<?php else: ?>
  <?php foreach ($offers as $offer): ?>
    <?= $view->partial('components/offer-card', ['offer' => $offer]) ?>
  <?php endforeach; ?>
<?php endif; ?>
