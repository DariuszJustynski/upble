<?php $this->load->view('header.php'); ?>
<div id="content">
	
	<div class="box last_box" style="padding-bottom:0px;">
		<div class="submenu">
			<div class="subsub" ><h1><?=ucfirst($user->username)?>'s Profile</h1></div>
			<ul  class="subnav clearfix">
				<li><a href="<?=site_url('member/'.$user->username.'/network')?>" class="active">Network</a></li>
				<li><a href="<?=site_url('member/'.$user->username.'/reviews')?>" >Reviews</a></li>
			</ul>
			
		</div>
	</div>
	<div class="wrap  clearfix">
		<div id="main" class="span-16">
			
			
			<div id="feed_box" class="box">
				<?php $this->load->view('user/feed_container',$feedData)?>
			</div>
			<div id="network_box" class="box last_box">
				<?php $this->load->view('user/network_container',$friendData)?>
				
			</div>
		</div>
		<div id="side" class="span-8 last">
			<div id="bio_box" class="box">
				<?php $this->load->view('user/bio_container',$bioData)?>
			</div>
		
		</div>
		
	</div>
		
</div>
<?php $this->load->view('footer.php'); ?>