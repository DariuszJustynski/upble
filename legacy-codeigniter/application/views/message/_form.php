<?php echo form_open('/pm/compose'); ?>
	<table class="user_table new_pm">
		<tr>
			<th>Reciever:</th>
			<td><input type="text" name="reciever" value="<?=$message['reciever']?>"/><br/><span style="color: red;"><?php echo form_error("reciever"); ?></span></td>
		</tr>
		<tr>
			<th>Title:</th>
			<td><input type="text" name="title" size="40" value="<?=htmlentities($message['title'],ENT_QUOTES,'UTF-8')?>"/><br/><span style="color: red;"><?php echo form_error("title"); ?></span></td>
		</tr>
		<tr>
			<th style="vertical-align:top;">Content:</th>
			<td><textarea rows="6" cols="50" name="content"><?=$message['content']?></textarea><br/><span style="color: red;"><?php echo form_error("content"); ?></span></td>
		</tr>
		<tr>
		<th>&nbsp;</th>
		<td><?php echo form_submit('submit', 'Send','class="submit"'); ?>
			
		</td>
	</tr>
	</table>
<?php echo form_close(); ?>