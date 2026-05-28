<div id="report_box" style="width:180px;height:150px;">
<h5>The reason to flag</h5>
<?php echo form_open(site_url("report/add"),'id="report_form"');?>
<p style="margin-top:10px;">
	<textarea name="comment" id="comment" cols="20" rows="3"></textarea>
	<input type="hidden" name="url" value=""/>
</p>
<p style="color:red;" id="msg"></p>
<p style="margin-top:10px;"><button id="report_bt">Submit</button></p>
<?php echo form_close();?>
</div>
<script type="text/javascript">
$(function(){
	$("#report_bt").click(function(event){
		event.preventDefault();
		var comment = $.trim($('#comment').val());
		if(comment.length==0)
		{
			$("#msg").html('The reason can\'t be blank');
			return;
		}
		
		Utils.postAction($("#report_form").attr('action'),$("#report_form").serialize(),function(data)
		{
			if(data ==1)
			{
				$("#report_box").html('Thank You! Your report has been sent successfully');
			}
			else
			{
				$("#msg").html(data);
			}
		});

	});
});

</script>

