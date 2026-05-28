<?php $this->load->view('header.php'); ?>
<link href="/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<div id="content">
		<div id="top" class="clearfix">
			<div class="span-10"><h1>Upload Photos: <a href="/biz/<?=$biz->id?>"><?=$biz->name?></a></h1></div>
			
		</div>
		<div class="box last_box" id="uploader">
		    <p style="margin-bottom:15px;"><strong>1.</strong> Please click the button below to upload you photos (4 at max a time).<br/>Only JPG format is supported and should be under 1MB</p>
            <p><input id="file_upload" name="file_upload" type="file" /></p>

			
		</div>
		<?php echo form_open(site_url('/photo/edit/'.$biz->id)); ?>
		<div class="box last_box clearfix" id="img_holder">
			
			
		</div>
		<div class="box last_box" id="caption_submit" style="display:none">
			<input type="submit" value="Update caption(s)"/>
		</div>
		<?php echo form_close(); ?>
</div>
<div>
</div>
<script type="text/javascript" src="/uploadify/jquery.uploadify-3.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript">

  $(document).ready(function() {

	browser_cookie = $.cookie('<?=$this->session->sess_cookie_name?>');
	$('#file_upload').uploadify({
	  'uploader'  : '<?=site_url("/photo/uploadFile")?>',
	  'swf'  : '/uploadify/uploadify.swf',
	  'formData':{'bizid':<?=$biz->id?>,'browser_cookie':browser_cookie},
      'buttonText'  : 'Upload Photos',
	  'fileTypeExts'     : '*.jpg;',
	  'fileTypeDesc'    : 'JPG Files',
	  'cancelImg' : '/uploadify/cancel.png',

	  'folder'    : '/tmp',
	  'multi'     : true,
      'queueSizeLimit' : 4,
	  'auto'      : true,
	  'onQueueFull' : function(event,queueSizeLimit){
						alert('You can upload 4 photos one time at max');
						return false;
	  
					},
	  'onUploadSuccess': function(fileObj, data,response) {
						
						data=jQuery.parseJSON(data);
						
						if(typeof data == "object" && data.type==0)
							alert(data.msg);
						else
						{
							$('<div class="span-5 single_img">'+data.view+'</div>').appendTo($("#img_holder"));
							
						}
					},
		'onQueueComplete': function(event,data)
						  {
							if($(".single_img").length>0)
							{
								$("#caption_submit").show();
								$("#uploader").html('<p style="margin-bottom:15px;"><strong>2.</strong> Please fill the caption field to give us some hint about these photos you uploaded.</p>');
							
							}
							
							
						  
						  }

	});

  });
  $(function(){
		$(".del_img").live('click',function(e){
			
			var url=$(this).attr('href');
			var container = this;
			if(confirm("Do you really want to delete this photo?"))
			{
				Utils.getAction(url,{},function(d){
				
					if(d==1)
					{
						$(container).parents('.single_img').remove();
						
						if($(".single_img").length==0)
						{
							window.location=window.location;
						}
					}
				});
			}
			
			e.preventDefault();
  });
 });

</script>

<?php $this->load->view('footer.php'); ?>		