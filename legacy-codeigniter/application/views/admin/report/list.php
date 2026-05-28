<?php $this->load->view('admin/header.php'); ?>
<div id="content">
	<div class="page-header"><h3>Content Flaged By Users</h3></div>
	<div class="box">
		<?=form_open(site_url('admin/report/del'))?>
		<table class="table">
			<thead>
				<tr>
					<td class="selected last"><!--<input type="checkbox" name='id[]' value='<?=$r->id?>'/>  --></td>
					<th class="left">Reporter</th>
					<th>Link</th>
					<th>Reason</th>
					<th>Report Time</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $r):?>
				<tr>
					<td class="selected last"><input type="checkbox" name='id[]' value='<?=$r->id?>'/></td>
					<td><a href="<?=site_url('/mcp/'.$r->username.'/profile')?>" target="_blank"><?= $r->username?></a></td>
					<td><a href="<?=htmlspecialchars($r->url) ?>" target="_blank"><?=htmlspecialchars($r->url)?></a></td>
					<td><?=htmlspecialchars($r->comment)?></td>
					<td><?=date('Y-m-d',$r->created_at)?></td>
					
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
	
		<?=form_close();?>
		
	</div>
</div>
<?php $this->load->view('admin/footer.php'); ?>	