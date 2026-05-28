
<div class="info">
    <?php if($list) :?>
	<ul id="feeds">
		<?php foreach($list as $feed) :?>	
		<li>
			<div class="feed_content"><img width="16" height="16" src="<?=site_url('/'.avatar($feed->uid,'small'))?>" />&nbsp;<a href="/member/<?=$feed->username?>"><?=$feed->username?></a>&nbsp;<?=$feed->content?>&nbsp;&nbsp;<span class="time"><?=time_format($feed->created_at)?></span></div>
			<?php if(isset($feed->images) && $feed->images):?>
			<div class="feed_images"><?=$feed->images?></div>
			<?php endif;?>
		</li>
		<?php endforeach;?>
	</ul>
	<?=$pagination_links?>
	<?php else:?>
	<p>No Activity recently</p>
	<?php endif;?>
		
	
</div>