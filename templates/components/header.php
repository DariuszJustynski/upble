<?php
/** @var \App\Core\View $view */
/** @var string $search_query (optional) */
?>
<!-- Top city bar -->
<div id="top_bar">
  <div class="container clearfix">
    <div class="span-14">
      <span>
        <a href="/offers?location=Bangkok">Bangkok</a> &middot;
        <a href="/offers?location=ChiangMai">Chiang Mai</a> &middot;
        <a href="/offers?location=KohTao">Koh Tao</a> &middot;
        <a href="/offers?location=Bali">Bali</a> &middot;
        <a href="/offers?location=Pai">Pai</a>
      </span>
    </div>
    <div class="span-10 last" style="text-align:right;">
      <!-- Login/register links — authentication not yet implemented -->
      <a href="#">Sign In</a> &nbsp;&middot;&nbsp; <a href="#">Join Free</a>
    </div>
  </div>
</div>

<!-- Main header -->
<div id="header">
  <div class="container clearfix">
    <div class="span-4">
      <a href="/" id="logo">Upble</a>
    </div>
    <div class="span-14">
      <form action="/offers" method="get" style="margin-top:4px;">
        <input type="text" name="q"
               value="<?= $view->e($search_query ?? '') ?>"
               placeholder="Search offers, locations, categories&hellip;" />
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="span-6 last" style="text-align:right;padding-top:8px;">
      <a href="/offers" style="color:#fff;font-weight:bold;">Browse All Offers</a>
    </div>
  </div>
</div>
