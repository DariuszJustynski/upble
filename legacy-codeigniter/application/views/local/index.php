<?php $this->load->view('header.php'); ?>
<div id="content">
	<div class="wrap  clearfix">
		<div id="main" class="span-16">
			
			<div class="box last_box">
				<?php if(isset($reviews) && $reviews):?>
				<div class="heading">
					<h2>Newest Review In <?php echo htmlspecialchars($city->name)?></h2>
					
				</div>
				<div class="info">
					<ul id="newreview">
						<?php foreach($reviews as $review):
								$biz = getBizById($review->bizid);
							
						?>
						<li class="clearfix">
						    
							<div class="span-1"><a href="<?=site_url('member/'.$review->username)?>"><img class="thumbimg thumb40" src="<?=site_url(avatar($review->uid))?>"/></a></div>
							<div class="span-13">
								<div class="title-name"><a  href="<?=site_url('member/'.$review->username)?>"><?php echo ucfirst($review->username)?></a> reviewed <a href="<?=site_url('biz/'.$biz->id)?>#review_<?php echo $review->id ?>"><?=$biz->name?></a></div>
								<div class="span-3 rating-wrap rating-<?php echo $review->rating ?>"></div>
								<p class="clear"><?=word_limiter($review->content,40)?></p>
							</div>
							
							
						</li>
						<?php endforeach;?>
					</ul>
					
				</div>
				<?php endif;?>
			</div>
			
		</div>
		<div id="side" class="span-8 last">
			<div class="box last_box"></div>
		
		</div>
		
	</div>
	
</div>
<?php $this->load->view('footer.php'); ?>