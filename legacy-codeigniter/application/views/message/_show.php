<div id="pmbox">
	<div class="box  clearfix last_box" >
		<div class="heading">
		<h4><?=htmlspecialchars($pm->title)?></h4>
		<p>
		<?php if($type=="inbox"):?>
		From:&nbsp;<a href="<?=site_url('member/'.$pm->username)?>"><?=$pm->username?></a>
		<?php else:?>
		To:&nbsp;<a href="<?=site_url('member/'.$pm->tousername)?>"><?=$pm->tousername?></a>
		<?php endif;?>
		&nbsp;&nbsp;<span class="time"><?=date('Y-m-d H:i:s',$pm->created_at)?></span></p>
		</div>
		<div style="width:500px;"><?=nl2br(htmlspecialchars($pm->content))?></div>
		<?php if($type=="inbox"):?><a class="button" href="<?=site_url('/pm/reply/'.$pm->id)?>" style="margin-top:20px;">Reply</a><?php endif;?>
	</div>
	
</div>