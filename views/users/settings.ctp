<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('My Account Settings'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link(__('Update Password', true), array('action' => 'password'));?></li>
		<li><?php echo $this->Html->link(__('Close Account', true), array('action' => 'delete'), array('class' => 'delete'));?></li>
	</ul>
</div>