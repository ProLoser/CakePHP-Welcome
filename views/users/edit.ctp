<?php $this->set('actions', array(
	$this->Html->link(__('Update Password', true), array('action' => 'password')),
	$this->Html->link(__('Close Account', true), array('action' => 'delete'), array('class' => 'delete')),
	$this->Html->link(__('Logout', true), array('action' => 'logout')),
)); ?>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), __('User', true)); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>