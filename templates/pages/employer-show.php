<?php
/**
 * Employer profile page.
 * @var \App\Core\View $view
 * @var array          $employer
 * @var array          $offers
 * @var array          $reviews
 */
?>

<!-- Breadcrumb -->
<p style="color:#999; font-size:13px; margin-bottom:10px;">
  <a href="/">Home</a> &rsaquo;
  <?= $view->e($employer['name']) ?>
</p>

<div class="clearfix">

  <!-- Open offers -->
  <div class="span-16" id="main">

    <h1 style="margin-bottom:4px;"><?= $view->e($employer['name']) ?></h1>
    <p style="color:#999; font-size:13px; margin-top:0;">
      <?= $view->e($employer['city']) ?>, <?= $view->e($employer['country']) ?>
      &nbsp;&middot;&nbsp; <?= $view->e($employer['category']) ?>
    </p>

    <!-- Open offers -->
    <h2 style="margin-top:20px; margin-bottom:5px;">Open Offers</h2>
    <?php if (empty($offers)): ?>
      <div class="box"><p style="color:#999;">No open offers at this time.</p></div>
    <?php else: ?>
      <?php foreach ($offers as $offer): ?>
        <?= $view->partial('components/offer-card', ['offer' => $offer]) ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Reviews -->
    <?php if (!empty($reviews)): ?>
      <h2 style="margin-top:30px; margin-bottom:5px;">
        Reviews
        <span style="font-size:14px; color:#999; font-weight:normal;">
          (<?= count($reviews) ?>)
        </span>
      </h2>
      <?php foreach ($reviews as $review): ?>
        <div class="review-item">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <strong><?= $view->e($review['author']) ?></strong>
            <span class="stars" style="color:#e67e22;">
              <?= $view->stars((int) $review['rating']) ?>
            </span>
          </div>
          <p style="margin:6px 0 2px; line-height:1.6;"><?= $view->e($review['content']) ?></p>
          <span class="meta"><?= $view->date($review['created_at']) ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div>

  <!-- Sidebar: employer bio -->
  <div class="span-8 last" id="side">
    <div class="box" id="bio_box">

      <div id="profile_img" style="text-align:center; margin-bottom:15px;">
        <img src="<?= $view->e($employer['avatar']) ?>"
             alt="<?= $view->e($employer['name']) ?>"
             style="width:90px; border-radius:50%;" />
      </div>

      <ul style="list-style:none; padding:0; margin:0; font-size:13px; line-height:1.8;">
        <li><span style="color:#999;">Name: </span>
            <strong><?= $view->e($employer['name']) ?></strong></li>
        <li><span style="color:#999;">City: </span><?= $view->e($employer['city']) ?></li>
        <li><span style="color:#999;">Country: </span><?= $view->e($employer['country']) ?></li>
        <li><span style="color:#999;">Category: </span><?= $view->e($employer['category']) ?></li>
        <li>
          <span class="employer-rating">
            <?= $view->stars((float) $employer['rating']) ?>
          </span>
          <span style="color:#666; font-size:12px;">
            &nbsp;<?= $view->e((string) $employer['rating']) ?>/5
            &nbsp;(<?= $view->e((string) $employer['review_count']) ?> reviews)
          </span>
        </li>
        <?php if (!empty($employer['website'])): ?>
          <li style="margin-top:6px;">
            <a href="<?= $view->e($employer['website']) ?>"
               rel="nofollow noreferrer" target="_blank">
              <?= $view->e($employer['website']) ?>
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <div style="margin-top:15px; padding-top:12px; border-top:1px solid #eee;
                  font-size:13px; line-height:1.7; color:#444;">
        <?= nl2br($view->e($employer['description'])) ?>
      </div>

    </div>
  </div>

</div>
