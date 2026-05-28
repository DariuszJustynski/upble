<?php $this->load->helper('boostrap');
	  $roles = array('user'=>'User','editor'=>'Editor','admin'=>'Admin');
	  $activatedStatus = array('0'=>'N','1'=>'Y');
	  $BannedStatus =array('0'=>'N','1'=>'Y');
?>
<?php $this->load->view('admin/header.php'); ?>
<div id="content">
	<!-- forms -->
	<div class="page-header"><h3><?=ucfirst($this->uri->segment(3))?> User</h3></div>
	<div class="box">
		
        <?=form_open(site_url($this->uri->uri_string()),'class="form-horizontal"');?>
        <fieldset>
          <?=textFieldRow('Username', 'username',$user,array('readonly'=>'readonly')) ?>
          <?=selectFieldRow('Role', 'role',$roles,$user) ?>
          <?=selectFieldRow('Activated', 'activated',$activatedStatus,$user) ?>
          <?=selectFieldRow('Banned', 'banned',$BannedStatus,$user) ?>
          <?=textAreaFieldRow('Banned Reason', 'ban_reason',$user,array('cols'=>'40','rows'=>'6')) ?>
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
        
          </div>
        </fieldset>
      <?=form_close();?>
	</div>
	<!-- end forms -->
</div>
<?php $this->load->view('admin/footer.php'); ?>		