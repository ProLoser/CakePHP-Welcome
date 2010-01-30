<?php $this->set('actions', array(
	$this->Html->link(__('My Account', true), array('action' => 'index')),
	$this->Html->link(__('Logout', true), array('action' => 'logout')),
)); ?>
<div class="users form">
	<h2><?php __('Close Account'); ?></h2>
	<fieldset>
 		<legend><?php __('Are you sure you wish to close your account?'); ?></legend>
	<?php
		echo $this->Form->create('User');
		echo $this->Form->input('confirm', array('type' => 'hidden', 'value' => false));
		echo $this->Form->button('Yes');
		echo $this->Form->button('No', array('action' => 'index'));
		echo $this->Form->end();
	?>
	</fieldset>
</div>