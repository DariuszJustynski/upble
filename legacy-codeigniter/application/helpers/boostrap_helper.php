<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function textFieldRow($label,$name,$model,$html_options = array())
{
	$error = form_error($name);
	$value = $model[$name];
	$group_class = $error=='' ? 'control-group' : 'control-group error';
	
	$options = '';
	if(!empty($html_options))
	{
		foreach($html_options as $k => $v)
		{
			$options .=' '.$k.' = "'.$v.'"';
		}
	}
	
	return
	'<div class="'.$group_class.'">
		<label class="control-label" for="'.$name.'">'.$label.'</label>
		<div class="controls">
			<input '.$options.' type="text" class="input-xlarge" id="'.$name.'" name="'.$name.'" value="'.$value.'"/>
			<span class="help-inline">'.$error.'</span>
		</div>
	</div>';
}

function textAreaFieldRow($label,$name,$model,$html_options = array())
{
	$error = form_error($name);
	$value = $model[$name];
	$group_class = $error=='' ? 'control-group' : 'control-group error';

	$options = '';
	if(!empty($html_options))
	{
		foreach($html_options as $k => $v)
		{
			$options .=' '.$k.' = "'.$v.'"';
		}
	}

	return
	'<div class="'.$group_class.'">
		<label class="control-label" for="'.$name.'">'.$label.'</label>
		<div class="controls">
			<textarea '.$options.' class="input-xlarge" id="'.$name.'" name="'.$name.'" >'.$value.'</textarea>
			<span class="help-inline">'.$error.'</span>
		</div>
	</div>';
}

function selectFieldRow($label,$name,$list=array(),$model)
{
	$error = form_error($name);
	$value = $model[$name];
	$group_class = $error=='' ? 'control-group' : 'control-group error';
	$options ='';
	foreach($list as $k => $v)
	{
		$selected = '';
		
		if($k == $value)
			$selected = 'selected';
		$options .= '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
	}
	return
	'<div class="'.$group_class.'">
		<label class="control-label" for="'.$name.'">'.$label.'</label>
		<div class="controls">
			<select id="'.$name.'"  name="'.$name.'">
				'.$options.'
				<span class="help-inline">'.$error.'</span>
			</select>
		</div>
	</div>';
}

function textAreaFiedRow()
{
	return '<div class="control-group">
            <label class="control-label" for="textarea">Textarea</label>
            <div class="controls">
              <textarea class="input-xlarge" id="textarea" rows="3"></textarea>
            </div>
          </div>';
}