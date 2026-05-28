<?php $this->load->view('header.php'); ?>
<div id="content">
		
		
	<div class="box clearfix" style="text-align:center">
		<div class="span-11">
			<a class="page_button <?php if($pre_photo) echo 'active_button'; else echo 'blur_button';?> span-2" <?php if($pre_photo):?>href="<?=site_url('photo/'.$pre_photo->bizid.'/'.$pre_photo->id)?><?php if($single_user_mode) echo '/from/'.$pre_photo->username?>"<?php endif;?>>Previous</a>
			<div class="span-4" style="line-height:30px;"> <strong><?=$photo->order?></strong>&nbsp;of &nbsp;<strong><?=$count?></strong></div>
			<a id="next_page" class="page_button <?php if($next_photo) echo 'active_button'; else echo 'blur_button';?> span-2" <?php if($next_photo):?>href="<?=site_url('photo/'.$next_photo->bizid.'/'.$next_photo->id)?><?php if($single_user_mode) echo '/from/'.$next_photo->username?>"<?php endif;?>>next</a>
		</div>
		<div class="span-12" style="text-align:right; line-height:20px;">
			
			<p><h1><a href="<?=site_url('/biz/'.$biz->id)?>"><?=$biz->name?> </a>Photos</h1></p>
			<p><a href="<?=site_url('/photo/upload/'.$biz->id)?>">Add your photos</a></p>
			
		</div>
	</div>
	<div class="box clearfix" >
	
		<div class="span-16" style="background-color:#F5F5F5;width:630px;padding:10px 0;text-align:center;">
			<img style="cursor:pointer;" id="current_img" src="<?=site_url(biz_photo($photo))?>"/>
		
		</div>
		<div class="span-7 last">
		
			<div class="box last_box ">
				<div class="span-4">
					<div class="img_mid">
						<a href="<?=site_url('member/'.$photo->username)?>"><img src="<?=site_url(avatar($photo->uid))?>" alt="<?=$photo->username?>"></a>
					</div>
					<div class="span-2 last" ><strong><a href="<?=site_url('member/'.$photo->username)?>"><?=$photo->username?></a></strong></div>
					<?php if($user_reviews = get_user_review_count($photo->uid)):?>
					<div class="span-2 last" style="margin-top:4px;">
						<div class="icon"><img title="<?=$user_reviews?> reviews" src="<?=site_url('images/reviews.gif')?>"/></div>
						<div style="width:45px;overflow:hidden;float:left;"><a title="<?=$user_reviews?> reviews" href="<?=site_url('member/'.$photo->username.'/reviews')?>"><?=$user_reviews?></a></div>
					</div>
					<?php endif;?>
					<?php $user_flowers =get_user_flowers($photo->uid);?>
					<div class="span-2 last <?php if($user_flowers):?>user_flower<?php endif;?>" id="user_flower_<?=$photo->uid?>" style="margin-top:4px;"><?php if($user_flowers):?><?=$user_flowers?><?php endif;?>
					</div>
				</div>
				
			    <div class="clear"></div>
				<?php if($photo->caption):?>
				<p style="margin: 10px 0;font-weight:bold;">"<?=$photo->caption?>"</p>
				<?php endif;?>
				<p class="thank"><?=$this->config->item('thank_author_text')?><a href="javascript:void(0)" class="send_flower" id="send_flower_<?=$photo->uid?>_<?=$photo->bizid?>_<?=$photo->id?>_photo"><img src="<?=site_url('/images/flower.gif')?>"></a>&nbsp;<span class="blue_note" id="flower_holder_<?=$photo->id?>"><?=$photo->flower?></span></p>
				<p class="time" style="margin: 10px 0;">Uploaded on <?=date('Y-m-d',$photo->created_at)?></p>
				<?php if($single_user_mode):?>
				<p  style="margin: 10px 0;"><a href="<?=site_url('photo/'.$biz->id.'/'.$first_photo->id)?>">View all photos for <span style="font-weight:bold"><?=$biz->name?></span></a></p>
				<?php endif;?>
				<p style="margin: 10px 0;"><a href="<?=site_url('report/add/photo/'.$photo->id.'/'.$biz->id)?>" class="flag" >Flag this photo</a></p>
				<?php if($this->tank_auth->is_admin()):?>
				<?=form_open('photo/del','id="del_photo_form"')?>
					<input type="hidden" name="bizid" value="<?=$biz->id?>" />
					<input type="hidden" name="id" value="<?=$photo->id?>" />
					<p style="margin: 10px 0;"><a href="javascript:;" class="admin_del_photo">Delete this photo</a></p>
				<?=form_close()?>
				<?php endif;?>
			</div>
			
		</div>
		
	</div>
	
	<div class="wrap  clearfix">
		
		<div id="main" class="span-16" style="minHeight:800px;">
			<div class="box last_box clearfix" >
			<?php foreach($photo_list as $p):?>
				<div class="span-3" style="margin-bottom:20px;">
				   <div class="photo_inner <?php if($p->id==$photo->id) echo 'cur_photo';?>">
						<a href="<?= site_url('photo/'.$p->bizid.'/'.$p->id)?><?php if($single_user_mode) echo '/from/'.$p->username?>">
							<img src='<?=site_url(biz_photo($p,'thumb'))?>' class='thumbimg' title="<?=$photo->caption?>"/>
						</a>
				   </div>
				   <div style="text-align:center;">
				    From&nbsp;&nbsp;<a href="<?=site_url('member/'.$p->username)?>"><?=$p->username?></a>
				   </div>
				</div>
			<?php endforeach;?>	
			
			
			</div>
			<?=$pagination_links?>
		</div>
		<div id="side" class="span-8 last">
		</div>
	</div>
	
</div>
<script type="text/javascript">  
$(function(){
		
		$('#current_img').click(function(){
			
			if( $('#next_page').attr('href'))
			{
				window.location.href= $('#next_page').attr('href');
			}
		
		});
		<?php if($this->tank_auth->is_admin()):?>
		$(".admin_del_photo").click(function(){
			if(confirm("Do you really want to delete this photo?"))
			Utils.postAction('<?=site_url("photo/del")?>',$("#del_photo_form").serialize(),function(data){
				if(data == 1)
				{
					var page_bts = $(".page_button");
					var href = '';
					for(var i = 0; i < page_bts.length; i++)
					{
						var bt = page_bts[i];
						if($(bt).attr('href'))
							href = $(bt).attr('href');
					}

					if(href == '')
					{
						href = '<?=site_url("biz/".$biz->id)?>';
					}
					window.location = href;
				}
				});
			});
		<?php endif;?>
	
	});	
	
</script>	
<?php $this->load->view('footer.php'); ?>		