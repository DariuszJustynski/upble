<?php /** @var \App\Core\View $view  @var array $latest_offers  @var array $top_employers */ ?>

<!-- Hero banner -->
<div id="hero">
  <h1>Find Work &amp; Adventure Abroad</h1>
  <p>Browse opportunities with accommodation &amp; meals included — no recruiter fees.</p>
  <a href="/offers" class="button">Browse All Offers &nbsp;&rarr;</a>
</div>

<div class="clearfix" style="margin-top:30px;">

  <!-- Latest offers list -->
  <div class="span-16" id="main">
    <h2 style="margin-bottom:5px;">Latest Offers</h2>
    <p style="color:#999;font-size:13px;margin-bottom:15px;">
      <?= count($latest_offers) ?> most recent positions
    </p>

    <?php if (empty($latest_offers)): ?>
      <div class="box"><p>No offers yet — check back soon.</p></div>
    <?php else: ?>
      <?php foreach ($latest_offers as $offer): ?>
        <?= $view->partial('components/offer-card', ['offer' => $offer]) ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <p style="margin-top:15px;">
      <a href="/offers">&rarr; See all offers</a>
    </p>
  </div>

  <!-- Sidebar -->
  <div class="span-8 last" id="side">

    <!-- Top employers -->
    <div class="box" style="margin-bottom:15px;">
      <h3 style="margin-top:0;">Top Employers</h3>
      <ul style="list-style:none;padding:0;margin:0;">
        <?php foreach ($top_employers as $emp): ?>
          <li style="padding:6px 0; border-bottom:1px solid #f0f0f0;">
            <a href="/employers/<?= $view->e($emp['id']) ?>">
              <?= $view->e($emp['name']) ?>
            </a>
            <span class="employer-rating" style="float:right;font-size:13px;">
              <?= $view->stars((float) $emp['rating']) ?>
            </span>
            <br />
            <small style="color:#999;"><?= $view->e($emp['city']) ?>, <?= $view->e($emp['country']) ?></small>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Category links -->
    <div class="box">
      <h3 style="margin-top:0;">Browse by Category</h3>
      <ul style="list-style:none;padding:0;margin:0;">
        <?php foreach (['Hospitality', 'Education', 'Wellness', 'Sports & Outdoors', 'Agriculture'] as $cat): ?>
          <li style="padding:4px 0;">
            <a href="/offers?category=<?= $view->e(urlencode($cat)) ?>">
              &rsaquo; <?= $view->e($cat) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>
</div>
