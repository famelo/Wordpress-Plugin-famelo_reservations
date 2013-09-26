<?php global $field; ?>
<div class="form-group field_type-<?php echo $field['type']; ?> field_key-<?php echo $field['key']; ?>">
	<label for="<?=$field['id']?>" class="col-lg-2 control-label"> <?=$field['label']?></label>
	<div class="col-lg-10">
		<?=do_action('acf/create_field', $field)?>
		<p class="help-block"><?=$field['instructions']?></p>
	</div>
</div>