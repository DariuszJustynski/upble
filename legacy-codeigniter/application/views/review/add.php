<?php $this->load->view('header.php'); ?>
<div id="content">
		<div id="top" class="clearfix" >
			<?php if(isset($biz)):?>
			<div class="span-12">
			   <a href="<?=site_url('/biz/'.$biz->id)?>"><h1><?=$biz->name?></h1></a>
			   
			</div>
			
			
			<?php else:?>
			<div class="span-5">
			   <h1>Add A Review</h1>
			</div>
			<?php endif;?>
		</div>
		<div class="box last_box">
			<?php echo form_open($this->uri->uri_string()); ?>
			<table cellpadding="0" cellspacing="0" class="user_table biz_table">
				<input type="hidden" name="id" value="<?=$review['id']?>"/>
				<input type="hidden" name="bizid" value="<?=$review['bizid']?>"/>
				<?php $error=form_error("bizid");?>
				<?php if(!empty($error)):?>
				<tr>
					<td>&nbsp;</td>
					<td><span style="color: red;"><?php echo form_error("bizid"); ?></span></td>
				</tr>
				<?php endif;?>
				<tr>
					<th style="vertical-align:middle;">Rating:</th>
					<td>
						<div style="width:200px;">
							
							<div class="rating-block">
								<input type="hidden" name="rating" id="rating" value="<?=$review['rating']?>" />
								
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
						<textarea cols="50" rows="15" name="content"><?=$review['content']?></textarea>
						<span style="color: red;"><?php echo form_error("content"); ?></span>
					</td>
				</tr>
				<tr>
					<th>&nbsp;</th>
					<td><?php echo form_submit('submit', 'Submit','class="submit"'); ?>
						
					</td>
				</tr>
				
				
			</table>
			<?php echo form_close(); ?>
		</div>
</div>
<script lang="javascript">
$(function(){
	
	<?php if($review['rating']>0):?>
	var rating=parseInt('<?=$review['rating']?>');
	if(rating>0)
	{
		$('#rating').val(rating);
		$('.star-'+rating).addClass('active-star');
		$(".rating-hint").html($(".star-"+rating).attr("title"));
	}
	<?php endif;?>

});
</script>
<?php $this->load->view('footer.php'); ?>		