<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if(isset($heading)):?><?=$heading?><?php else: echo 'Admin Home'; endif;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Admin Panel</a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i><?=$this->tank_auth->get_username()?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              
              <li><a href="<?=site_url('/ucp/logout')?>">Sign Out</a></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="/">Website</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
            <?php $uri = $this->uri->uri_string();?>
              <li class="nav-header">Category management</li>
              <li <?php if(preg_match('@/node/[^/]+/city@', $uri)):?>class="active" <?php endif; ?>><a href="<?=site_url('/admin/node/nodelist/city')?>">Cities</a></li>
              <li <?php if(preg_match('@/node/[^/]+/category@', $uri)):?>class="active" <?php endif; ?>><a href="<?=site_url('/admin/node/nodelist/category')?>">Business categories</a></li>
              
              <li class="nav-header">General</li>
              <li <?php if(preg_match('@admin/report@', $uri)):?>class="active" <?php endif; ?>><a href="<?=site_url('/admin/report')?>">Report</a></li>
              <li <?php if(preg_match('@admin/user@', $uri)):?>class="active" <?php endif; ?>><a href="<?=site_url('/admin/user')?>">User</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
		<div class="span9">
			
			<?php if($error=$this->session->flashdata('error')):?>
			<div  class="alert alert-block alert-error">
				<a class="close" data-dismiss="alert" href="#">x</a>
				<h4 class="alert-heading">Error Message</h4>
				<?=$error?>
			</div>
			<?php elseif($success=$this->session->flashdata('success')):?>
			<div  class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#">x</a>
				<?=$success?>
			</div>
			<?php endif;?>