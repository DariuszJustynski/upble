<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title><?php if(isset($heading)):?><?=$heading?><?php endif;?></title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />

  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
  <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" />
  <![endif]-->
  <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />
   <link rel="stylesheet" href="/css/colorbox.css" type="text/css" />
  <script src="/js/jquery.js"></script>
  <script src="/js/jquery.colorbox-min.js"></script>
  <script src="/js/common.js"></script>
  
  
</head>

<body>
	<div id="loading" style="display:none;"><img width='32' height='32' src="/images/loading.gif"></div>
	<div id="prfs" >
		<div id="user_nav" class="container" >
			<div class="span-3" style="color:#9999CC;">
				<strong><?php echo $this->tank_auth->get_user_city() ? ucfirst($this->tank_auth->get_user_city()->name) : ''?></strong>
			</div>
			<ul id="citybar" >
				<li><span style="color:#999999">Other cities: </span></li>
				<?php 
					 $cities = $this->tank_auth->get_cities();
					 if($cities):
					 foreach($cities as $c):?>
				<li><a href="<?=site_url($c->slug)?>"><?=$c->name?></a></li>
				<?php endforeach;
					 endif;
				?>
				
			</ul>
			<div id="user">
				<?php $this->load->view('userpanel');?>
			</div>
			<div id="login_form" style="display:none;">
			<?=form_open('','id="loginForm"') ?>
				<input type="hidden" name="inajax" value="1"/>
				<a class="close" href="javascript:void(0)" onclick="Login._close();">X</a>
				<p><label for="login">username:&nbsp;&nbsp;</label><br/><input type="text" id="login" name="login" /></p>
				<p><label for="password">password:&nbsp;&nbsp;</label><br/><input type="password" id="password" name="password"/></p>
				<p><input type="checkbox" name="remember" id="remember" value="1"><label for="remember">Remember me</label></p>
				<a class="button" href="javascript:void(0)" onclick="Login._submit()">Login</a>
			<?=form_close()?>
		</div>
		</div>
		
	</div>
	
	<div id="header" class="container">
		
		<div class="span-5" id="logo"><img src="/images/logo.png"/></div>
		
		<div class="span-12 search_box" >
			<form id="searchbox" action="/biz/search" method="post">
				<input type="text"  id='q' name='q' value="<?php if(isset($terms)):?><?=$terms?><?php elseif($this->tank_auth->get_user_city()):?>Search Business In <?=$this->tank_auth->get_user_city()->name?><?php endif;?>"><button id="s_submit">GO</button>
			</form>
			
		</div>
		<div  class="add_btm"><a <?php if(!$this->tank_auth->is_logged_in()):?>class="required_login"<?php endif;?> href="<?=site_url('biz/add')?>">Add A Business</a></div>
		<div class="clear"></div>
		<div id="nav" class="container">
			<a href="/">Home</a>
			<?php 
				$cates = $this->tank_auth->get_categories();
				if($this->tank_auth->get_user_city() && $cates):
			?>
			
			<?php foreach($cates as $cat):?>
			<a href="<?=site_url('biz/c/'.$this->tank_auth->get_user_city()->slug.'/'.$cat->slug)?>" ><?=ucfirst($cat->name)?></a>
			<?php endforeach;?>
			<?php endif;?>
			
			
		</div>
		<?php if($error=$this->session->flashdata('error')):?>
		<div id="flashmsg" class="alert alert-error">
			<a class="close" data-dismiss="alert" href="#">x</a>
			<?=$error?>
		</div>
		<?php elseif($success=$this->session->flashdata('success')):?>
		<div id="flashmsg" class="alert alert-success">
			<a class="close"  href="javascript:;">x</a>
			<?=$success?>
		</div>
		<?php endif;?>
	</div>

	<div style="display:none">
		<div id="report_box" style="width:180px;height:150px;">
		<h5>The reason to flag</h5>
		<?php echo form_open(site_url("report/add"),'id="report_form"');?>
		<p style="margin-top:10px;">
			<textarea name="comment" id="comment" cols="20" rows="3"></textarea>
			<input type="hidden" name="url" value=""/>
		</p>
		<div style="color:red;" id="msg"></div>
		<p style="margin-top:10px;"><button id="report_bt">Submit</button></p>
		<?php echo form_close();?>
	</div>
	</div>