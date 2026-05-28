<?php $this->load->view('header.php'); ?>
<div id="content">
		<div id="top" class="clearfix">
			<div class="span-5"><h1><?php echo isset($biz['id'])&&$biz['id'] ? 'Edit' : 'Add'; ?> A Business</h1></div>
			
		</div>
		<div class="box last_box clearfix">
			<div class="span-13">
			<?php echo form_open($this->uri->uri_string());?>
			<table cellpadding="0" cellspacing="0" class="user_table biz_table">
				<tr>
					<th>Name:</th>
					<td>
						<input type="text" name="name" value="<?=$biz['name']?>"/><br/>
						<span style="color: red;"><?php echo form_error('name'); ?></span>
					</td>
				</tr>
				
				<tr>
					<th>City:</th>
					<td>
						<select id="city_id" name="city_id">
							<?php foreach($cities as $c): ?>
							<option value="<?=$c->id?>" <?php if($biz['city_id']==$c->id):?>selected="selected"<?php endif;?>><?=$c->name?></option>
							<?php endforeach;?>
						</select>
						<span style="color: red;"><?php echo form_error('city_id'); ?></span>
					</td>
				</tr>
				<tr>
					<th>Address:</th>
					<td>
						<input type="text" name="addrs1" id ="address1" value="<?=$biz['addrs1']?>"/><br/>
						<span style="color: red;"><?php echo form_error('addrs1'); ?></span>
						<br/>
						<input type="text" name="addrs2" id="address2" value="<?=$biz['addrs2']?>"/><br/>
						<span style="color: red;"><?php echo form_error('addrs2'); ?></span>
					</td>
				</tr>
				<tr>	
					<th>Neighborhood:</th>
					<td>
						<select id="district_id" name="district_id">
							<option value=""></option>
							<?php foreach($districts as $d):?>
							<option value="<?=$d->id?>" <?php if($biz['district_id']==$d->id):?>selected<?php endif;?>><?=$d->name?></option>
							<?php endforeach;?>
						</select>
						<span style="color: red;"><?php echo form_error('district_id'); ?></span>
					</td>
				</tr>
				<tr>
					<th>Telephone:</th>
					<td>
						<input type="text" name="tel" value="<?=$biz['tel']?>"/><br/>
						<span style="color: red;"><?php echo form_error('tel'); ?></span>
					</td>
				</tr>
				<tr>
					<th>Website:</th>
					<td>
						<input type="text" name="website" value="<?=$biz['website']?>"/><br/>
						<span style="color: red;"><?php echo form_error('website'); ?></span>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:middle;">Category:</th>
					<td>
						<div class="span-3">
						    <select id="catid_1" name="catid_1">
								<option value=""></option>
								<?php foreach($categories as $c):?>
								<option value="<?=$c->id?>" <?php if($biz['catid_1']==$c->id):?>selected<?php endif;?>><?=$c->name?></option>
								<?php endforeach;?>
						    </select>
							<p><span style="color: red;"><?php echo form_error('catid_1'); ?></span></p>
						</div>
						<div class="span-3" id="subcat">
							<?php if(isset($subcats)&&!empty($subcats)):?>
								<select id="catid_2" name="catid_2">
									<option value=""></option>
									<?php foreach($subcats as $s):?>
									<option value="<?=$s->id?>" <?php if($biz['catid_2']==$s->id):?>selected<?php endif;?>><?=$s->name?></option>
									<?php endforeach;?>
								</select>
							<?php endif;?>
						</div>
					</td>
				</tr>
				<tr>
					<th style="vertical-align:top;">About:</th>
					<td>
						<textarea cols="50" rows="8" name="about"><?=$biz['about']?></textarea>
						<span style="color: red;"><?php echo form_error("about"); ?></span>
					</td>
				</tr>
				<?php if(!isset($biz['id'])):?>
				<tr>
					<td colspan="2"><input type="checkbox" name='with_review' value="1" <?php if($with_review==1):?>checked="checked"<?php endif;?>>&nbsp;&nbsp;<span style="font-weight:bold;">Write A Review for this business</span></td>
					
				</tr>
				<tr>
					<th style="vertical-align:middle;">Rating:</th>
					<td>
						<div style="width:200px;">
							
							<div class="rating-block">
								<input type="hidden" name="rating" id="rating" value="<?=$biz['rating']?>" />
								
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
						<textarea cols="50" rows="8" name="review"><?=$biz['review']?></textarea>
						<span style="color: red;"><?php echo form_error("review"); ?></span>
					</td>
				</tr>
				<?php else:?>
				<tr>
					<th>published:</th>
					<td>
						<select  name="published">
							<option value='0' <?php if(!$biz['published']):?>selected<?php endif;?>>0</option>
							<option value='1' <?php if($biz['published']):?>selected<?php endif;?>>1</option>
						</select>
					</td>
				</tr>
				<?php endif;?>
				<tr>
					<th>&nbsp;</th>
					<td><?php echo form_submit('submit', 'Submit','class="submit"'); ?>
						<input type="hidden" name="location_x" id="lat" value="<?=$biz['location_x']?>"/>
						<input type="hidden" name="location_y" id="lng" value="<?=$biz['location_y']?>"/>
					</td>
				</tr>
				
				
			</table>
			<?php echo form_close(); ?>
			</div>
			<div class="span-9 last" >
			<div id="map" class="box last_box" style="height:400px;" >
			</div>
			<p style="text-align:center;margin-top:5px;color:#66c">Drag and drop the marker to correct the location</p>
		</div>
		</div>
</div>
<script type="text/javascript">
var cities = [];
<?php foreach($cities as $city):?>
	cities[<?=$city->id ?>] = '<?=$city->name?>';
<?php endforeach;?>
$(function(){
	$('#city_id').change(function(){
		city_id=$('#city_id').val();
		Utils.loadAction('#district_id','/biz/get_children/'+city_id+'/city');
	
	});
	$('#catid_1').change(function(){
		catid_1=parseInt($('#catid_1').val());
		Utils.loadAction('#subcat','/biz/get_children/'+catid_1+'/category');
	
	});
	<?php if(isset($biz['rating']) && $biz['rating']>0):?>
	var biz_rating=parseInt('<?=$biz['rating']?>');
	if(biz_rating>0)
	{
		$('#rating').val(biz_rating);
		$('.star-'+biz_rating).addClass('active-star');
		$(".rating-hint").html($(".star-"+biz_rating).attr("title"));
	}
	<?php endif;?>

});


function display_map() 
{
	var lat = $.trim($('#lat').val());  
	var lng = $.trim($('#lng').val()); 
	
	mapOptions = {           
			zoom: 15,   
			mapTypeId: google.maps.MapTypeId.ROADMAP, 
			scaleControl: true,      
			mapTypeControl: true,         
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}     
	};
	var map = new google.maps.Map(document.getElementById("map"),mapOptions);  
	if(lat != '' && lng != '')
	{
		var point = new google.maps.LatLng(lat, lng);
		map.setCenter(point);
		var marker = new google.maps.Marker({
			 position: point,
			 map: map,
			 draggable : true
		});
		google.maps.event.addListener(marker, "dragend", function(event)
		{
			lat = event.latLng.lat();
			lng = event.latLng.lng();
			$("#lat").val(lat);
			$("#lng").val(lng);
			
			marker.setPosition(event.latLng);
		});
		
	}      

}

function getLatLng()
{
	var geocoder = new google.maps.Geocoder();
	var city_id = $("#city_id").val();
	var city_name = cities[parseInt(city_id)];
	var address = $.trim($("#address1").val() + ' ' + $("#address2").val() + ' ' + city_name);
	geocoder.geocode( { 'address': address}, function(results, status) 
	{
	      if (status == google.maps.GeocoderStatus.OK) {
			  var point = results[0].geometry.location;
		      $("#lat").val(point.lat());
		      $("#lng").val(point.lng());
		      display_map();
	       
	      } 
	});
}
function map_initialize()
{
	var lat = $.trim($('#lat').val());  
	var lng = $.trim($('#lng').val()); 
	if(lat == '' || lng == '')
	{
		getLatLng();
	}
	else 
		display_map();	
}

function loadScript() 
{
	  var script = document.createElement("script");
	  script.type = "text/javascript";
	  script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=map_initialize";
	  document.body.appendChild(script);
}


$(function(){
	$("#city_id").change(getLatLng);
	$("#address1,#address2").blur(getLatLng);
	loadScript();
	
});

</script>     
<?php $this->load->view('footer.php'); ?>		