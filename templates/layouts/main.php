<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $view->e($page_title ?? 'Upble') ?></title>

  <!-- Blueprint grid + app styles (proxied from frontend-template/assets/ in dev) -->
  <link rel="stylesheet" href="/assets/css/screen.css" />
  <link rel="stylesheet" href="/assets/css/style.css" />
  <!--[if lt IE 8]><link rel="stylesheet" href="/assets/css/ie.css" /><![endif]-->

  <style>
    /* ── Upble app-level overrides ───────────────────────────────────────── */
    body { background: #f9f9f7; color: #333; }

    #top_bar { background: #2c2c2c; padding: 5px 0; font-size: 12px; }
    #top_bar a { color: #aaa; }
    #top_bar a:hover { color: #fff; }

    #header { background: #c0392b; padding: 10px 0; }
    #header #logo { color: #fff; font-size: 26px; font-weight: bold;
                    text-decoration: none; letter-spacing: -1px; }
    #header form input[type=text] { padding: 6px 10px; width: 260px;
                                    border: none; border-radius: 3px 0 0 3px; }
    #header form button { padding: 6px 14px; background: #922b21; color: #fff;
                          border: none; border-radius: 0 3px 3px 0; cursor: pointer; }

    .offer-card { border-bottom: 1px solid #eee; padding: 14px 0; margin: 0; }
    .offer-card h2 { margin: 0 0 4px; font-size: 17px; }
    .offer-card h2 a { color: #c0392b; }
    .offer-card h2 a:hover { text-decoration: underline; }
    .offer-card .badge { display: inline-block; background: #eaf4ea; color: #2e7d32;
                         font-size: 11px; padding: 1px 6px; border-radius: 10px; }

    .employer-rating { color: #e67e22; font-size: 15px; }

    #hero { background: linear-gradient(135deg, #c0392b 0%, #922b21 100%);
            color: #fff; text-align: center; padding: 50px 20px; border-radius: 4px; }
    #hero h1 { color: #fff; font-size: 2.2em; margin-bottom: 10px; }
    #hero p { font-size: 1.15em; opacity: .9; margin-bottom: 20px; }
    #hero a.button { background: #fff; color: #c0392b; padding: 10px 24px;
                     border-radius: 4px; font-weight: bold; text-decoration: none; }
    #hero a.button:hover { background: #f5f5f5; }

    #footer { background: #2c2c2c; color: #888; padding: 20px 0; margin-top: 40px;
              font-size: 12px; }
    #footer a { color: #aaa; }

    .review-item { border-bottom: 1px solid #eee; padding: 12px 0; }
    .review-item .stars { color: #e67e22; }
    .review-item .meta { color: #999; font-size: 12px; }

    .container { max-width: 960px; margin: 0 auto; padding: 0 15px; }

    table.info-table th { text-align: left; padding: 6px 12px 6px 0;
                          color: #888; font-weight: normal; white-space: nowrap; }
    table.info-table td { padding: 6px 0; }
  </style>
</head>
<body>

<?php require TEMPLATES . '/components/header.php'; ?>

<div class="container" style="margin-top: 20px; margin-bottom: 40px;">
  <?= $content ?>
</div>

<?php require TEMPLATES . '/components/footer.php'; ?>

<script src="/assets/js/jquery.js"></script>
</body>
</html>
