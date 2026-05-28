<?php

$picture = array(
	'name'	=> 'picture',
	'id'	=> 'picture',
	'maxlength'	=> 255,
	'size'	=> 30,
);
$website = array(
	'name'	=> 'website',
	'id'	=> 'website',
	'value' => $website,
	'maxlength'	=> 255,
	'size'	=> 30,
);
$city = array(
	'name'	=> 'city',
	'id'	=> 'city',
	'value' => $city,
	'maxlength'	=> 255,
	'size'	=> 30,
);


$about_me = array(
	'name'	=> 'about_me',
	'id'	=> 'about_me',
	'value' => $about_me,
	'maxlength'	=> 700,
	'cols'	=> 50,
	'rows'	=> 5,
);



 ?>
 
<?php $this->load->view('header.php'); ?>
<div id="content">
	<div id="top" class="clearfix">
		<div class="span-16"><h1>Profile setting</h1></div>
		
	</div>
	<div class="box">
			<?php echo form_open_multipart($this->uri->uri_string()); ?>
		<table cellpadding="0" cellspacing="0" class="user_table">
			<tr>
				<th></th>
				<td><img class="thumbimg" src="<?=base_url()?><?=avatar($this->tank_auth->get_user_id(),'big')?>?<?=time() ?>"/></td>
			</tr>
			<tr>
				<th>avatar</th>
				<td><span class="note">Only jpg/png/gif format is allowed and max size should be under 1M.</span><br/>
				<?php echo form_upload($picture); ?>
				<span style="color: red;"><?php echo form_error($picture['name']); ?></span></td>
			</tr>
			<tr>
				<th><?php echo form_label('Website', $website['id']); ?></th>
				<td>
				<?php echo form_input($website); ?>
				<span style="color: red;"><?php echo form_error($website['name']); ?></span>
				</td>
			</tr>
			<tr>
				<th><?php echo form_label('City', $city['id']); ?></th>
				<td><span class="note">The city you are currently living in.</span><br/><?php echo form_input($city); ?>
				<span style="color: red;"><?php echo form_error($city['name']); ?></span></td>
			</tr>
			<tr>
				<th style="vertical-align:top;" ><?php echo form_label('About Me', $about_me['id']); ?></th>
				<td><?php echo form_textarea($about_me); ?>
				<span style="color: red;"><?php echo form_error($about_me['name']); ?></span></td>
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
<?php $this->load->view('footer.php'); ?>