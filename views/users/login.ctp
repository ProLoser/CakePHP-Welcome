<?php $this->set('actions', array(
	$this->Html->link(__('Need to Register?', true), array('action' => 'add')),
	$this->Html->link(__('Forgotten Password?', true), array('action' => 'reset')),
)); ?>
<div class="users login">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Login')?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Login', true));?>
</div>