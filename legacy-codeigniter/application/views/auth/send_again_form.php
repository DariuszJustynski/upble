<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
?>
<?php $this->load->view('header.php'); ?>
<div id="content">
	
	<div class="box">
		<h3>Your account is still not actived. Get another activation code</h3>
		<?php echo form_open($this->uri->uri_string()); ?>
		<table class="user_table">
			<tr>
				<td><?php echo form_label('Email Address', $email['id']); ?></td>
				<td><?php echo form_input($email); ?></td>
				<td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
			</tr>
			<tr><td>&nbsp;</td><td ><?php echo form_submit('send', 'Send'); ?></td><td>&nbsp;</td></tr>
		</table>
		
		<?php echo form_close(); ?>
	</div>
		
</div>		
<?php $this->load->view('footer.php'); ?>