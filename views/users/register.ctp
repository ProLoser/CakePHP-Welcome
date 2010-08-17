<div class="users register">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Register')?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		echo $this->Form->input('password', array('type' => 'password'));
		echo $this->Form->input('confirm_password', array('type' => 'password'));
		if (isset($captcha)) {
			echo $this->Form->input('captcha');	
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>