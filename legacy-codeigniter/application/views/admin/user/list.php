<?php $this->load->view('admin/header.php'); ?>
<div id="content">
	<div class="page-header"><h3>User Management</h3></div>
	<div class="box">
		<form action="<?=site_url('/admin/report/del')?>" method="post">
		<table class="table">
			<thead>
				<tr>
					<td class="selected last"><input type="checkbox" name='id[]' value='<?=$r->id?>'/></td>
					<th>Username</th>
					<th>Role</th>
					<th>Email</th>
					<th>Activated</th>
					<th>Banned</th>
					<th>Register Time</th>
					<th>Operation</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $r):?>
				<tr>
					<td><input type="checkbox" name='id[]' value='<?=$r->id?>'/></td>
					<td><?=$r->username?></td>
					<td><?=$r->role?></td>
					<td><?=$r->email?></td>
					<td><?=$r->activated == 0 ? 'N':'Y'?></td>
					<td><?=$r->banned == 0 ? 'N':'Y'?></td>
					<td><?=$r->created?></td>
					<td><a href="<?=site_url('admin/user/edit/'.$r->id) ?>">Edit</a></td>
					
				</tr>
				<?php endforeach;?>
				
			</tbody>
		</table>
		
		<div class="results">
			<?=$pagination_links?>
		</div>
			
		<div class="form-actions">
			<input type="submit" class="btn" name="submit" value="Delete Selected Items" />
		</div>
	
		</form>
		
	</div>
</div>
<?php $this->load->view('admin/footer.php'); ?>	