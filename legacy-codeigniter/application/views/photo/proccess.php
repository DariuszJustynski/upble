<img src="<?=site_url($thumb)?>"/>
<?php if(!$this->tank_auth->is_admin()): ?>
<p style="margin: 10px 0;"><a href="<?=site_url('photo/del/'.$biz->id.'/'.$id) ?>" class="del_img"  >Delete</a></p>
<?php endif;?>
<p>
caption <span class="time">(Optional)</span>: <br/>
<textarea name="caption[<?=$id?>]" cols="22" rows="3"></textarea>
<p>