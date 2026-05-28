<ul id="bar">
<?php if($this->tank_auth->is_logged_in()||$this->tank_auth->is_logged_in(false)):?>
	<?php if($this->tank_auth->is_admin()):?>
	<li><a href="<?=site_url('/admin/node/nodelist/city')?>">admin</a></li>
	<?php endif;?>
	<li><a href="<?=site_url('/ucp/logout')?>" onclick="Login._logout(event);return false;" rel="nofollow">logout</a></li>
	
	<li><?php if($this->tank_auth->is_logged_in()):?><a href="<?=site_url('/pm/inbox')?>">message(<?=$this->tank_auth->get_newpm_count()?>)</a><?php endif;?></li>
	<li class="thumb"><a href="<?=site_url('member/'.$this->tank_auth->get_username())?>"><strong><?=$this->tank_auth->get_username()?></strong></a></li>
<?php else:?>
    <li><a href="<?=site_url('/ucp/forgot_password')?>"  rel="nofollow">forgot password</a></li>
	<li><a href="<?=site_url('/ucp/register')?>" rel="nofollow">register</a></li>
	<li><a href="<?=site_url('/ucp/login')?>" onclick="Login._show(event);return false;" rel="nofollow">login</a></li>
	
<?php endif;?>
</ul>

<!--
<img title="<?=site_url('member/'.$this->tank_auth->get_username())?>" alt="<?=site_url('member/'.$this->tank_auth->get_username())?>" src='<?=base_url().avatar($this->tank_auth->get_user_id(),'small')?>'/>
-->