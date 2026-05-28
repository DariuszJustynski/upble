<?php $this->load->helper('boostrap');
	  $nodes = array('0'=>'');
	   foreach($topNodes as $tn)
	   {
	   	 $nodes[$tn->id] = $tn->name;
	   }
?>
<?php $this->load->view('admin/header.php'); ?>
<div id="content">
	<!-- forms -->
	<div class="page-header"><h3><?=ucfirst($this->uri->segment(3))?> <?=$alias[$table]?></h3></div>
	<div class="box">
		<p style="color:#3A87AD">
	    <?php if($this->uri->segment(4) == 'city'):?>
	    If you are adding a city, please leave the Parent Node field blank. If you are adding a neighborhood, please select the city it belongs to from the Parent Node field
	    <?php elseif($this->uri->segment(4) == 'category'):?>
	    If you are adding a top category, leave the Parent Node field blank. If you are adding a sub category, please select its parent category from the Parent Node field.
	    <?php endif;?>
	    </p>
		<?php echo form_open(site_url($this->uri->uri_string()),'class="form-horizontal"');?>
        <fieldset>
          <?=textFieldRow('Name', 'name',$node) ?>
          <?=textFieldRow('Slug', 'slug',$node) ?>
          <?=textFieldRow('Order', 'order',$node) ?>
          <?=selectFieldRow('Parent Node', 'parent_id',$nodes,$node) ?>
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit</button>
        
          </div>
        </fieldset>
      <?php form_close();?>
	</div>
	<!-- end forms -->
</div>
<?php $this->load->view('admin/footer.php'); ?>		