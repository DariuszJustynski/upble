<?php if(!empty($bizs)):?>

<ul class="review_list clearfix" style="margin-top:10px;">
	<?php foreach($bizs as $biz):?>
	
	<li class="biz_list clearfix">
		
		<div class="span-12">
			<div class="heading">
				<h2><a href="<?=site_url('biz/'.$biz->id)?>" ><?=ucfirst(htmlspecialchars($biz->name))?></a></h2>
			</div>
			<div class="span-3 star_wrap">
				<div class="active-star" style="width:<?=$biz->rating?>;height:16px;z-index:20;"></div>
			</div>
			<div class="span-2">
			   <a href="<?=site_url('biz/'.$biz->id)?>#commentlist" style="line-height:16px;"><?=$biz->review_num?>&nbsp;review<?php if($biz->review_num>1):?>s<?php endif;?></a> 
			</div>
			<div class="bizinfo clear">
				<p> Categories:&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_1->slug)?>"><?=$biz->category_1->name?></a><?php if($biz->category_2):?>,&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_2->slug)?>"><?=$biz->category_2->name?></a><?php endif;?></p>
				<p><?=htmlspecialchars($biz->addrs1)?></p>
				<p><?php if($biz->district):?><?=$biz->district->name?><?php endif?>, <?=$biz->city->name?></p>
				<p><?=$biz->tel?></p>
				
				
			</div>
		
		</div>
		<div class="span-3 last" style="float:right;text-align:center;line-height:20px;">
		<?php if($biz->photo_count > 0): ?>
			<a href="<?= site_url('photo/'.$biz->id.'/'.$biz->first_photo->id)?>">
				<img src='<?=site_url('upload/biz_photos/'.$biz->first_photo->folder.'/'.$biz->first_photo->id.'.thumb.jpg')?>' class='thumbimg' />
			</a>
			<p><a href="<?= site_url('photo/'.$biz->id.'/'.$biz->first_photo->id)?>"><?= $biz->photo_count?> photos</a></p>
		<?php endif;?>	
			
		</div>
	</li>
	
	<?php endforeach;?>
					
						
</ul>
<?=$pagination_links?>
<?php else:?>
	No record
<?php endif;?>