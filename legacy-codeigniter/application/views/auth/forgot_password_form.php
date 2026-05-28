<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<?php $this->load->view('header.php'); ?>
<div id="content">
	<div id="top" class="clearfix">
		<div class="span-16"><h1>Forgot Password</h1></div>
		
	</div>
	<div class="box">
		<?php echo form_open($this->uri->uri_string()); ?>

		<table  class="user_table">
			<tr>
				<th><?php echo form_label($login_label, $login['id']); ?></th>
				<td><?php echo form_input($login); ?></td>
				<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
			</tr>
			<tr><td>&nbsp;</td><td ><?php echo form_submit('reset', 'Get a new password'); ?></td><td>&nbsp;</td></tr>
		</table>
	

		<?php echo form_close(); ?>
	</div>
		
</div>		
<?php $this->load->view('footer.php'); ?>