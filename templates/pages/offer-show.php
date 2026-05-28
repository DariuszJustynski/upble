<?php
/**
 * Single offer detail page.
 * @var \App\Core\View $view
 * @var array          $offer
 * @var array|null     $employer
 */
?>

<!-- Breadcrumb -->
<p style="color:#999; font-size:13px; margin-bottom:10px;">
  <a href="/">Home</a> &rsaquo;
  <a href="/offers">Offers</a> &rsaquo;
  <?= $view->e($offer['title']) ?>
</p>

<div class="clearfix">

  <!-- Main content -->
  <div class="span-16" id="main">

    <h1 style="margin-bottom:4px;"><?= $view->e($offer['title']) ?></h1>
    <?php if ($employer): ?>
      <p style="color:#999; font-size:14px; margin-top:0;">
        Posted by
        <a href="/employers/<?= $view->e($employer['id']) ?>"><?= $view->e($employer['name']) ?></a>
        &nbsp;&middot;&nbsp; <?= $view->e($offer['location']) ?>
      </p>
    <?php endif; ?>

    <!-- Key details table -->
    <div class="box" style="margin-top:15px;">
      <table class="info-table" style="width:100%;">
        <tr>
          <th>Location</th>
          <td><?= $view->e($offer['location']) ?></td>
        </tr>
        <tr>
          <th>Category</th>
          <td>
            <a href="/offers?category=<?= $view->e(urlencode($offer['category'])) ?>">
              <?= $view->e($offer['category']) ?>
            </a>
          </td>
        </tr>
        <tr>
          <th>Salary</th>
          <td><strong><?= $view->e($offer['salary']) ?></strong></td>
        </tr>
        <tr>
          <th>Accommodation</th>
          <td>
            <?php if ($offer['accommodation']): ?>
              <span style="color:#2e7d32;">&#10003; Included</span>
            <?php else: ?>
              <span style="color:#999;">&#10007; Not included</span>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Meals</th>
          <td>
            <?php if ($offer['meals_included']): ?>
              <span style="color:#2e7d32;">&#10003; Included</span>
            <?php else: ?>
              <span style="color:#999;">&#10007; Not included</span>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Posted</th>
          <td><?= $view->date($offer['created_at']) ?></td>
        </tr>
      </table>
    </div>

    <!-- Description -->
    <div class="box" style="margin-top:10px;">
      <h3 style="margin-top:0;">About this offer</h3>
      <p style="line-height:1.7;"><?= nl2br($view->e($offer['description'])) ?></p>
    </div>

    <!-- Tags -->
    <?php if (!empty($offer['tags'])): ?>
      <div style="margin-top:10px;">
        <?php foreach ($offer['tags'] as $tag): ?>
          <a href="/offers?q=<?= $view->e(urlencode($tag)) ?>"
             style="display:inline-block; background:#eee; color:#555;
                    padding:2px 9px; border-radius:10px; font-size:12px;
                    margin:2px; text-decoration:none;">
            <?= $view->e($tag) ?>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>

  <!-- Sidebar: employer info -->
  <div class="span-8 last" id="side">
    <?php if ($employer): ?>
      <div class="box" id="employer_box">
        <img src="<?= $view->e($employer['avatar']) ?>"
             alt="<?= $view->e($employer['name']) ?>"
             style="max-width:80px; border-radius:4px; margin-bottom:10px;" />

        <ul style="list-style:none; padding:0; margin:0; font-size:13px;">
          <li style="margin-bottom:6px;">
            <strong><?= $view->e($employer['name']) ?></strong>
          </li>
          <li style="color:#999;">
            <?= $view->e($employer['city']) ?>, <?= $view->e($employer['country']) ?>
          </li>
          <li style="margin-top:6px;" class="employer-rating">
            <?= $view->stars((float) $employer['rating']) ?>
            <span style="color:#666; font-size:12px;">
              <?= $view->e((string) $employer['rating']) ?>/5
              (<?= $view->e((string) $employer['review_count']) ?> reviews)
            </span>
          </li>
          <?php if (!empty($employer['website'])): ?>
            <li style="margin-top:6px;">
              <a href="<?= $view->e($employer['website']) ?>"
                 rel="nofollow noreferrer" target="_blank">
                Visit website &rsaquo;
              </a>
            </li>
          <?php endif; ?>
          <li style="margin-top:10px;">
            <a href="/employers/<?= $view->e($employer['id']) ?>"
               class="button" style="font-size:12px;">
              View Employer Profile
            </a>
          </li>
        </ul>
      </div>
    <?php endif; ?>
  </div>

</div>
