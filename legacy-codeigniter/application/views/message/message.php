<?php $this->load->view('header.php'); ?>
<div id="content">
	<div id="messagebox" class="box last_box">
		
		<div class="submenu">
			<div class="add" ><a href="<?=site_url("/pm/compose")?>">Write A message</a></div>
			<ul  class="subnav clearfix">
				<li><a href="<?=site_url('/pm/inbox')?>" <?php if($type=="inbox"):?>class="active"<?php endif;?>>Inbox</a></li>
				<li><a href="<?=site_url('/pm/sent')?>" <?php if($type=="sent"):?>class="active"<?php endif;?>>Sent</a></li>
				
				
			</ul>
			
		</div>
		<div id="pmbox">
			<?php $this->load->view($partial);?>
		</div>
	</div>
	
</div>
<?php $this->load->view('footer.php'); ?>