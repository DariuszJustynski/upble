<div id="profile_img" class="clearfix">
	<?=form_open('','id="friendForm"')?>
		<input type="hidden" name="username" value="<?=$user->username?>"/>
	<?=form_close()?>		
	<img class="thumbimg" src="<?=base_url()?><?=avatar($user->id,'big')?>"/>
	<p>
	<?php if(!$is_self):?>
		<?php if(!$is_friend):?>
		<a  href="<?=site_url('mcp/follow/'.$user->username) ?>" class="button f_req">Follow this user</a>
		<?php else:?>
		<a  href="javascript:;" class="button following">Following</a>
		<a  href="<?=site_url('mcp/unfollow/'.$user->username) ?>" class="button unfollow" style="display:none;">Unfollow</a>
		<?php endif;?>
	<?php endif;?>
	</p>
</div>
<ul id="profile_info">

   <li><span>Name: </span><?=$user->username?></li>
   	<?php if($user_reviews = get_user_review_count($user->id)):?>
	<li class="clearfix">
		<div class="icon"><img title="<?=$user_reviews?> reviews" src="<?=site_url('images/reviews.gif')?>"/></div>
		<div style="width:45px;overflow:hidden;float:left;"><a title="<?=$user_reviews?> reviews" href="<?=site_url('member/'.$user->username.'/reviews')?>"><?=$user_reviews?></a></div>
	</li>
	<?php endif;?>
	<?php  if($user_flowers =get_user_flowers($user->id)):?>
	<li><div class="user_flower"><?=$user_flowers?></div></li>
	<?php endif;?>
	<?php if($profile->website){?>
	<li><span>website: </span><a href="<?=htmlspecialchars($profile->website)?>"><?=htmlspecialchars($profile->website)?></a></li>
	<?php }?>
	<?php if($profile->city){?>
	<li><span>City: </span><?=htmlspecialchars($profile->city)?></li>
	<?php }?>
	<?php if($profile->country){?>
	<li><span>Country: </span><?=htmlspecialchars($profile->country)?></li>
	<?php }?>
	<?php if($profile->about_me){?>
	<li><span>About Me</span><br/><?=htmlspecialchars($profile->about_me)?></li>
	<?php }?>
	<?php if($is_self):?>
	<li><a href="<?=site_url('mcp/setting')?>">Update Your Profile</a></li>
	<li><a href="<?=site_url('ucp/change_password')?>">Change Password</a></li>
	<?php endif;?>
	<?php if(!$is_self && $this->tank_auth->is_logged_in()):?>
	<li><a href="<?=site_url('pm/compose/'.$user->username)?>">Send A Message</a></li>
	<?php endif;?>
</ul>