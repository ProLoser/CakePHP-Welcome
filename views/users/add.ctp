<?php $this->set('actions', array(
	$this->Html->link(__('Forgotten Password?', true), array('action' => 'reset')),
)); ?>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php echo $this->title_for_layout = __('Register', true)?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('confirm_password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>