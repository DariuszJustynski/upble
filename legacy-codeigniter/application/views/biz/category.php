<?php $this->load->view('header.php'); ?>
<div id="content">
	
	<div class="wrap  clearfix">
		
		<div class="navigate">
			
			<div class="cat_nav">
					<a href="<?=site_url('/'.$city->slug)?>"><?=$city->name?></a>&nbsp;&gt;&gt;&nbsp;<a href="<?=site_url('biz/c/'.$city->slug.'/'.$top_cat->slug)?>"><?=$top_cat->name?></a><?php if(isset($sub_cat)):?>&nbsp;&gt;&gt;&nbsp;<a href="<?=site_url('biz/c/'.$city->slug.'/'.$sub_cat->slug)?>"><?=$sub_cat->name?></a><?php endif;?><?php if(isset($district)):?>&nbsp;&gt;&gt;&nbsp;<?=$district->name?><?php endif;?>
			</div>
			<?php if(isset($districts)):?>
			<div class="cat_list clearfix">
				 <div class="span-2" style="width:80px;" ><span >Neighborhood</span>&nbsp;</div>
				 <div class="span-16 last" >
					<ul>
						<?php foreach($districts as $distr):?>
						<li><a href="<?=site_url('biz/c/'.$distr->slug.'/'.$cat->slug)?>" <?php if($distr->id == $local->id):?>class="selected"<?php endif;?>><?=$distr->name?></a></li>
						<?php endforeach;?>
					</ul>
				 </div>
			</div>
			<?php endif;?>
			<?php if(isset($sub_cats)):?>
			<div class="cat_list clearfix">
				 <div class="span-2" style="width:60px;" ><span >Categories</span>&nbsp;</div>
				 <div class="span-16 last">
					<ul>
						<?php foreach($sub_cats as $sub_c):?>
						<li><a href="<?=site_url('biz/c/'.$local->slug.'/'.$sub_c->slug)?>" <?php if($sub_c->id == $cat->id):?>class="selected"<?php endif;?>><?=$sub_c->name?></a></li>
						<?php endforeach;?>
					</ul>
				 </div>
			</div>
			<?php endif;?>
		</div>	
		<div id="main" class="span-16">
		   <?php $this->load->view('biz/_list');?>
		</div>
		<div id="side" class="span-8 last">
		
		</div>
		
	</div>
		
</div>
<?php $this->load->view('footer.php'); ?>