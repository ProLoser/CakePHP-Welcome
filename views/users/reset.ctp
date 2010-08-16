<div class="users reset">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php echo __('Forgot Your Password?', true); ?></legend>
	<?php
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>