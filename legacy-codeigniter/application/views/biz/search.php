<?php $this->load->view('header.php'); ?>
<div id="content">
	
	<div class="wrap  clearfix">
		
		<div id="top" style="margin-bottom:10px;">
			
			<h1><?=$heading?></h1>
		</div>	
		<div id="main" class="span-16">
		   <?php $this->load->view('biz/_list');?>
		</div>
		<div id="side" class="span-8 last">
		
		</div>
		
	</div>
		
</div>
<?php $this->load->view('footer.php'); ?>