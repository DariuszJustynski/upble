<?php $this->load->view('header.php'); ?>
<div id="content">
	<div class="wrap  clearfix">
		<div class="span-16" id="main">
			<div class="box  last_box clearfix">
				<div class="span-12">
					<div class="heading">
						<h2><?=ucfirst(htmlspecialchars($biz->name))?></h2>
					</div>
					<div class="span-3 star_wrap">
						<div class="active-star" style="width:<?=$biz->rating?>;height:16px;z-index:20;"></div>
					</div>
					<div class="span-2">
					   <a href="#commentlist" style="line-height:16px;"><?=$biz->review_num?>&nbsp;review<?php if($biz->review_num>1):?>s<?php endif;?></a> 
					</div>
					<div class="bizinfo clear">
						<?php if($this->tank_auth->is_admin()):?>
						<?=form_open('biz/del','id="biz_form" onsubmit=\'return confirm("Are you sure that you want to delete this business?")\'')?>
						<input type="hidden" name="id" value="<?=$biz->id?>"/>
						<?=form_close()?>
						<?php endif;?>
						<p> Categories:&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_1->slug)?>"><?=$biz->category_1->name?></a><?php if($biz->category_2):?>,&nbsp;<a href="<?=site_url('biz/c/'.$biz->city->slug.'/'.$biz->category_2->slug)?>"><?=$biz->category_2->name?></a><?php endif;?></p>
						<p><?=htmlspecialchars($biz->addrs1)?></p>
						<?php if($biz->addrs2):?>
						<p><?=htmlspecialchars($biz->addrs2)?></p>
						<?php endif;?>
						<p><?php if($biz->district):?><?=$biz->district->name?>, <?php endif?><?=$biz->city->name?></p>
						<p><?=htmlspecialchars($biz->tel)?></p>
						<?php if($biz->website):?>
						<p><a href="<?=$biz->website?>"><?=htmlspecialchars($biz->website)?></a></p>
						<?php endif;?>
						<p><?php if(isset($first_review)&&$first_review):?>First review by <a href="<?= site_url('member/'.$first_review->username)?>"><?=$first_review->username?></a><?php endif;?></p>
						 <?php if(($this->tank_auth->is_logged_in() && $this->tank_auth->get_user_id() == $biz->uid) ||$this->tank_auth->is_admin()):?>
						 <p><a href="<?=site_url('/biz/edit/'.$biz->id)?>">Edit</a>
						 <?php endif; 
						 	if($this->tank_auth->is_admin()):?>
						 	, <a href="javascript:;" onclick="$('#biz_form').submit()">Delete</a></p>
						 <?php endif;?>
					</div>
					
				</div>
				<div class="span-3 last" style="float:right;text-align:center;line-height:20px;">
				<?php if($biz->photo_count > 0): ?>
					<a href="<?= site_url('photo/'.$biz->first_photo->bizid.'/'.$biz->first_photo->id)?>">
						<img src='<?=site_url(biz_photo($biz->first_photo,'thumb'))?>' class='thumbimg' title="<?=$biz->first_photo->caption?>"/>
					</a>
					<p><a href="<?= site_url('photo/'.$biz->first_photo->bizid.'/'.$biz->first_photo->id)?>"><?= $biz->photo_count?> photos</a></p>
				<?php endif;?>	
					<p><a class="<?php if(!$this->tank_auth->is_logged_in()):?>required_login<?php endif;?>" href="<?=site_url('/photo/upload/'.$biz->id)?>">add photos</a></p>
				</div>
				
			</div>
			<div class="box last_box clearfix"> 
				
				<div class="span-7"  style="line-height:1em">
					<div class="heading"><h6>Rating distribution</h6></div>
					<ul class="rating-dis">
						<li><span class="dis_pre">5 star</span><span class="dis_bg"><span class="dis_show" style="width:<?=$biz->rating_5?>"><?=$biz->rating_5?></span></span><span class="dis_num"><?=$biz->star_5?> </span></li>
						<li><span class="dis_pre">4 star</span><span class="dis_bg"><span class="dis_show" style="width:<?=$biz->rating_4?>"><?=$biz->rating_4?></span></span><span class="dis_num"><?=$biz->star_4?> </span></li>
						<li><span class="dis_pre">3 star</span><span class="dis_bg"><span class="dis_show" style="width:<?=$biz->rating_3?>"><?=$biz->rating_3?></span></span><span class="dis_num"><?=$biz->star_3?> </span></li>
						<li><span class="dis_pre">2 star</span><span class="dis_bg"><span class="dis_show" style="width:<?=$biz->rating_2?>"><?=$biz->rating_2?></span></span><span class="dis_num"><?=$biz->star_2?> </span></li>
						<li><span class="dis_pre">1 star</span><span class="dis_bg"><span class="dis_show" style="width:<?=$biz->rating_1?>"><?=$biz->rating_1?></span></span><span class="dis_num"><?=$biz->star_1?> </span></li>
					</ul>
					
				</div>
				<div class="span-8 last">
				
					
				</div>
				
			</div>
			<div class="box last_box" style="padding-bottom:0px;">
				<div class="submenu">
					<?php if($this->tank_auth->is_logged_in()):?>
					<div class="subsub" ><a href="<?=site_url('biz/'.$biz->id)?>" class="flag" >Flag this business</a></div>
					<?php endif;?>
					<?php if(!isset($my_review)):?>
					<div class="add" ><a href="#review_form">Write A Review</a></div>
					<?php endif;?>
					<ul  class="subnav clearfix">
						<li><a href="#review_box" class="review_tab active">Reviews</a></li>
						<li><a href="#info_box" class="review_tab">About</a></li>
					</ul>
					
				</div>
			</div>
			
			<div class="box  last_box" id="review_box">
				<?php if($biz->review_num>0):?>
				<ul id="commentlist" class="review_list">
					<?php foreach($reviews as $review):?>
					<li class="clearfix" >
						<div class="user_info span-2 last">
							<div class="img_mid">
								<a href="<?=site_url('member/'.$review->username)?>"><img src="<?=site_url(avatar($review->uid,'mid'))?>" ></a>
							</div>
							<?php if($user_reviews = get_user_review_count($review->uid)):?>
							<div class="span-2 last" style="margin-top:4px;">
								<div class="icon"><img title="<?=$user_reviews?> reviews" src="<?=site_url('images/reviews.gif')?>"/></div>
								<div style="width:45px;overflow:hidden;float:left;"><a title="<?=$user_reviews?> reviews" href="<?=site_url('member/'.$review->username.'/reviews')?>"><?=$user_reviews?></a></div>
							</div>
							<?php endif;?>
							<?php $user_flowers =get_user_flowers($review->uid);?>
							<div class="span-2 last <?php if($user_flowers):?>user_flower<?php endif;?>" id="user_flower_<?=$review->uid?>" style="margin-top:4px;"><?php if($user_flowers):?><?=$user_flowers?><?php endif;?></div>
							
						</div>
						<div id="review_<?=$review->id?>" class="review">
							<div class="clearfix"  style="height:25px;overflow:hidden;">
								<div class="span-2" style="margin-top:4px;"><strong><a href="<?=site_url('member/'.$review->username)?>"><?=$review->username?></a></strong></div>
								<div class="span-2 review_time"><span class="time" ><?=date('m/d/Y',$review->created_at)?></span></div>
								<?php if($review->updated_at):?>
								<div class="span-4 review_time"><span class="time" style="font-style:italic;">Updated at&nbsp;<?=date('m/d/Y',$review->updated_at)?></span></div>
								<?php endif;?>
								
							</div>
							
							<div class="clearfix" style="height:25px;margin-bottom:8px;overflow:hidden;">
								<div class="span-3 rating-wrap rating-<?=$review->rating?>">
									
								</div>
								
								<?php if($photo_count = get_user_photo_count($review->uid,$review->bizid)):?>
								<div class="span-1 review_time camera"><a href="<?=site_url('photo/'.$biz->id.'/'.get_user_first_photo($review->uid,$review->bizid).'/from/'.$review->username)?>"><?=$photo_count?></a></div>
								<?php endif;?>
								<?php if($this->tank_auth->is_admin()):?>
								<div class="span-1 review_time">
									<?=form_open('review/del','onsubmit=\'return confirm("Are you sure that you want to delete this review?")\'')?>
										<input type="hidden" name="bizid" value="<?=$biz->id?>"/>
										<input type="hidden" name="id" value="<?=$review->id?>"/>
										<a href="javascript:;" onclick="$(this).parent().submit();">Delete</a>
									<?=form_close()?>
								
								</div>
								<?php endif;?>
								<?php if($this->tank_auth->is_logged_in() && ($this->tank_auth->get_user_id()==$review->uid)):?>
								<div class="span-1 review_time"><a href=<?=site_url('review/edit/'.$biz->id.'/'.$review->id)?>>Edit</a></div>
								<?php endif;?>
							</div>
							<div class="rev_wrap">
							  <?=nl2br(htmlspecialchars($review->content))?>
							</div>
							<div class="span-8" style="margin-top:10px;"><span class="thank" ><?=$this->config->item('thank_author_text')?></span>&nbsp;&nbsp;&nbsp;<span><a href="javascript:void(0)" class="send_flower"  id="send_flower_<?=$review->uid?>_<?=$review->bizid?>_<?=$review->id?>_review"><img src="<?=site_url('/images/flower.gif')?>"></a>&nbsp;<span class="blue_note" id="flower_holder_<?=$review->id?>"><?=$review->flower?></span></div>
							<div class="span-4" style="margin-top:10px;float:right;text-align:right;">
								<a href="<?=site_url('review/show/'.$review->id) ?>" class="flag" rel="nofollow">Flag this review</a>
							</div>
							<div class="clear">
							</div>
							
						</div>
					</li>
					<?php endforeach;?>
					
				</ul>
				<?=$pagination_links?>
				<?php else:?>
				No reviews yet, why not be the first to write one?
				<?php endif;?>
				
			</div>
			
			<div class="box last_box" id="info_box" style="display:none;">
				<?php if($biz->about):?>
					<?=nl2br($biz->about)?>
				<?php else:?>
					No infomation provided for this business yet
				<?php endif;?>
			</div>
			
			<?php if(!isset($my_review)):?>
			<div class="box last_box">	
				<div class="caption" style="padding-left:70px;" >
					<h6>Write A Review</h6>
					
				</div>
				
				
				<?=form_open('review/add','id="review_form"') ?>
				<table cellpadding="0" cellspacing="0" class="user_table biz_table">
						<input type="hidden" name='bizid' value="<?=$biz->id?>"/>
						<tr>
						<th style="vertical-align:middle;">Rating:</th>
						<td>
							<div style="width:200px;">
								
								<div class="rating-block">
									<input type="hidden" name="rating" id="rating" value="" />
									
									<ul>
										<li>
											<a class="star-1" rating-value="1" title="very poor" href="javascript:void(0);"></a>
										</li>
										<li>
											<a class="star-2"  rating-value="2" title="poor" href="javascript:void(0);"></a>
										</li>
										<li>
											<a class="star-3"    rating-value="3"  title="average" href="javascript:void(0);"></a>
										</li>
										<li>
											<a class="star-4"    rating-value="4"  title="good" href="javascript:void(0);"></a>
										</li>
										<li>
											<a class="star-5"    rating-value="5" title="excellent" href="javascript:void(0);"></a>
										</li>
									
									</ul>
									
								</div>
								<span class="rating-hint">click to rate</span>
							</div>
							
						</td>
					</tr>
					<tr>
						<th style="vertical-align:top;">Review:</th>
						<td>
							<textarea cols="50" rows="10" name="content"></textarea>
							<span style="color: red;"></span>
						</td>
					</tr>
					<tr>
						<th>&nbsp;</th>
						<td>
							<input type="button" value="Submit" class="submit" onclick="submit_review();">
						</td>
					</tr>
					
					
				</table>
				<?php echo form_close(); ?>
			</div>
			<?php endif;?>
			
		</div>
		<div class="span-8 last" id="side">
			<div id="map_canvas" class="box last_box" style="height:300px;" data-point='{"lat":"<?=$biz->location_x?>","lng":"<?=$biz->location_y?>"}'>
			</div>
			<?php if($this->tank_auth->is_logged_in() && ($this->tank_auth->get_user_id() == $biz->uid || $this->tank_auth->is_admin())):?>
			<p style="text-align:center;margin-top:5px;"><a href="#" class="fix_location">Fix incorrect map marker</a></p>
			<div style="display:none">
				<div id="map">
					<div id="map_edit" style="width:450px;height:450px">
		
					</div>
					<p style="margin:10px 0 5px 200px;"><a class="button" href="#" onclick="confirm_fix()">Save changes</a></p>
				</div>
			</div>
			<?php endif;?>
			
		</div>
	</div>
	
</div>


<script type="text/javascript">
	
	var map,map_edit,marker,editMarker,mapOptions;
    var lat = <?=json_encode($biz->location_x)?>;
	var lng = <?=json_encode($biz->location_y)?>;
   
	function map_initialize() {  
		mapOptions = {           
				zoom: 15,   
				center: new google.maps.LatLng(lat, lng),     
				mapTypeId: google.maps.MapTypeId.ROADMAP, 
				scaleControl: true,      
				mapTypeControl: true,         
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}     
		};      
		map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);  
		marker = new google.maps.Marker({
		 position: new google.maps.LatLng(lat, lng),
		 map: map
		 
		});
		
	
	}
	
	//map_initialize();	
	function fix_location_map()
	{
		map_edit = new google.maps.Map(document.getElementById("map_edit"),mapOptions);  
		editMarker = new google.maps.Marker({
		 position: new google.maps.LatLng(lat, lng),
		 map: map_edit,
		 draggable: true
		});
		map_edit.setCenter(editMarker.position);
		google.maps.event.addListener(editMarker, "dragend", function(event)
		{
		
			lat = event.latLng.lat();
			lng = event.latLng.lng();
			editMarker.setPosition(event.latLng);
		});
	}
	
	function confirm_fix()
	{
		$.ajaxSetup({async: false}); 
		Utils.postAction("<?=site_url('/biz/location')?>",{id:'<?=$biz->id?>',lat:lat,lng:lng});
		var point = new google.maps.LatLng(lat, lng);
		marker.setPosition(point);
		map.setCenter(point);
		$.colorbox.close();
		
		
	}
	function loadScript() 
	{
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=map_initialize";
		  document.body.appendChild(script);
	}

	function submit_review()
	{
		Utils.postAction($("#review_form").attr('action'),$("#review_form").serialize());
	}
	$(function(){
		$(".review_tab").click(function(event){
			event.preventDefault();
			var cur_active = $(".active").attr('href');
			var cur_hide = $(this).attr('href');
			$(".active").removeClass('active');
			$(this).addClass('active');
			$(cur_active).hide();
			$(cur_hide).show();
		});
		loadScript();
		
	});

</script>     
<?php $this->load->view('footer.php'); ?>		