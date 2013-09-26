<!-- <form class="form-horizontal" role="form">
	<?php
	foreach (the_form_fields() as $field) {
		the_form_field($field);
	}
	?>
</form>
 -->
<form class="form-horizontal" role="form">
	<div class="row">
		<div class="main col-sm-6">
			<?=the_form_field('date')?>
			<?=the_form_field('time')?>
			<?=the_form_field('persons')?>
		</div>
		<div class="main col-sm-6">
			<?=the_form_field('name')?>
			<?=the_form_field('phone')?>
			<?=the_form_field('email')?>
		</div>
	</div>
</form>