<?php $this->load->view('admin/header.php'); 
?>
<div><a href="<?=site_url('/admin/node/add/'.$table) ?>" class="btn">Add A <?=$alias[$table] ?></a></div>

<?=form_open(site_url('/admin/node/order/'.$table));?>
<table class="table">
	<thead>
		<tr>
			<th class="left" width="20%">order</th>
			<th>Name</th>
			<th>Operation</th>
			
		</tr>
	</thead>
	<tbody>
	    
		<?php foreach($topNodes as $tn):?>
		<tr>
			<td ><input type="text" name="order[<?=$tn->id?>]" value="<?=$tn->order?>" style="width:30px;"></td>
			<td ><strong><?=$tn->name?></strong></td>
			<td>
				<a href="<?=site_url('admin/node/del/'.$table.'/'.$tn->id)?>" onclick="return confirm('Do you really want to delete <?=$tn->name?>');" >Delete</a>
				&nbsp;&nbsp;&nbsp;
				<a href="<?=site_url('admin/node/edit/'.$table.'/'.$tn->id)?>">Edit</a>
			</td>
			
		</tr>
			<?php if(isset($childNodes[$tn->id])):?>
			<?php foreach ($childNodes[$tn->id] as $cn):?>
		<tr>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="order[<?=$cn->id?>]" value="<?=$cn->order?>" style="width:30px;"></td>
			<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$cn->name?></td>
			<td>
				<a href="<?=site_url('admin/node/del/'.$table.'/'.$cn->id)?>" onclick="return confirm('Do you really want to delete <?=$cn->name?>');" >Delete</a>
				&nbsp;&nbsp;&nbsp;
				<a href="<?=site_url('admin/node/edit/'.$table.'/'.$cn->id)?>">Edit</a>
			</td>
			
		</tr>
			<?php endforeach;?>
			<?php endif;?>
		<?php endforeach;?>
		
	</tbody>
</table>

<!-- table action -->
<div class="action">
	
	<div class="button">
		<input type="submit" class="btn" name="submit" value="submit" />
	</div>
</div>
<!-- end table action -->
<?=form_close();?>

<?php $this->load->view('admin/footer.php'); ?>	