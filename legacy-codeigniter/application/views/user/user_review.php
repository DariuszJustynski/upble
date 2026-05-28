<?php $this->load->view('header.php'); ?>
<div id="content">
	
	<div class="box last_box" style="padding-bottom:0px;">
		<div class="submenu">
			<div class="subsub" ><h1><?=ucfirst($user->username)?>'s Profile</h1></div>
			<ul  class="subnav clearfix">
				<li><a href="<?=site_url('member/'.$user->username.'/network')?>" >Network</a></li>
				<li><a href="<?=site_url('member/'.$user->username.'/reviews')?>" class="active">Reviews</a></li>
			</ul>
			
		</div>
	</div>
	<div class="wrap  clearfix">
		<div id="main" class="span-16">
			<?php if(!empty($reviews)):?>
			<ul class="review_list clearfix">
				<?php foreach($reviews as $review):?>
				<?php if($biz = getBizById($review->bizid)):?>
				<li>
					
					<div id="review_<?=$review->id?>" class="review user_review_list">
						
						
						<div class= "span-7" style="color:#999999;">
							
							<h2><a href="<?=site_url('biz/'.$biz->id)?>"><?=ucfirst($biz->name)?></a></h2>
							
							<p style="margin-top:5px;"> Categories:&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_1->slug)?>"><?=$biz->category_1->name?></a><?php if($biz->category_2):?>,&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_2->slug)?>"><?=$biz->category_2->name?></a><?php endif;?></p>
							
						</div>
						<div class="span-8 last" style="float:right;text-align:right;color:#999999">
							<p><?=$biz->addrs1?></p>
							<p><?php if($biz->district):?><?=$biz->district->name?><?php endif?>, <?=$biz->city->name?></p>
							
						</div>
						<br class="clear"/>
						<div class="span-3 rating-wrap rating-<?=$review->rating?>">
							
						</div>
						
						<div class="span-2 review_time"><span class="time" ><?=date('m/d/Y',$review->created_at)?></span></div>
						<?php if($review->updated_at):?>
						<div class="span-4 review_time"><span class="time" style="font-style:italic;">Updated at&nbsp;<?=date('m/d/Y',$review->updated_at)?></span></div>
						<?php endif;?>
						
						<?php if($photo_count = get_user_photo_count($review->uid,$review->bizid)):?>
						<div class="span-1 review_time camera"><a href="<?=site_url('photo/'.$review->bizid.'/'.get_user_first_photo($review->uid,$review->bizid).'/from/'.$review->username)?>"><?=$photo_count?></a></div>
						<?php endif;?>
						
						<?php if($this->tank_auth->is_logged_in() && ($this->tank_auth->get_user_id()==$review->uid)):?>
						<div class="span-1 review_time"><a href=<?=site_url('review/edit/'.$review->bizid.'/'.$review->id)?>>Edit</a></div>
						<?php endif;?>
						<div class="clear">
						  <?=nl2br(htmlspecialchars($review->content))?>
						</div>
						<div style="margin-top:10px;"><span class="thank" ><?=$this->config->item('thank_author_text')?></span>&nbsp;&nbsp;&nbsp;<span><a href="javascript:void(0)" class="send_flower"  id="send_flower_<?=$review->uid?>_<?=$review->bizid?>_<?=$review->id?>_review"><img src="<?=site_url('/images/flower.gif')?>"></a>&nbsp;<span class="blue_note" id="flower_holder_<?=$review->id?>"><?=$review->flower?></span></div>
						
						
					</div>
				</li>
				<?php endif;?>
				<?php endforeach;?>
								
									
			</ul>
			<?=$pagination_links?>
			<?php else:?>
				<?=ucfirst($user->username)?>&nbsp;hasn't written any review yet
			<?php endif;?>
			
		</div>
		<div id="side" class="span-8 last">
			<div id="bio_box" class="box">
				<?php $this->load->view('user/bio_container',$bioData)?>
			</div>
		
		</div>
		
	</div>
		
</div>
<?php $this->load->view('footer.php'); ?>