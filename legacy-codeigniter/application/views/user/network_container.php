<?php
		$s_class=array(
				'following'=>'',
				'followers'=>'',
		);
		$s_class[$type]=' selected';

?>
<div class=" heading clearfix">
	<div class="span-8"><h4><?=ucfirst($type)?>&nbsp;(&nbsp;<?=$count?>&nbsp;)</h4></div>
	<div class="span-7 last">
		<ul class="sort">
			<li class="last" id="li2_network_box"><?=anchor('/mcp/network/'.$user->username.'/followers/','Followers',array('class'=>'pg'.$s_class['followers']))?></li>
			<li  id="li1_network_box" ><?=anchor('/mcp/network/'.$user->username.'/following/','Following',array('class'=>'pg'.$s_class['following']))?></li>
			<li><span>Sort By:</span></li>
		</ul>
	</div>
</div>
<?php if($list):?>
<ul id="friends" class="clearfix">		 
	<?php foreach($list as $f):
	if($type == 'following')
	{
		$f->uid = $f->fid;
		$f->username = $f->fusername;		
	}

	?>
	<li >  
		<div class="img_mid">
			<a href="<?=site_url('member/'.$f->username)?>"><img  alt="<?=$f->username?>" src="<?=site_url(avatar($f->uid))?>" ></a>
		</div>
		<h5><a href="<?=site_url('member/'.$f->username)?>"><?=$f->username?></a></h5>
	</li>
	
	<?php endforeach; ?>
	
</ul>
<?php endif;?>	
<?=$pagination_links?>
								