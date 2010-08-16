<?php
/**
 * Membership Component
 *
 * @author Dean Sofer (proloser@hotmail.com)
 * @version 1.0
 * @package User Plugin
 **/
class MembershipComponent extends Object {
	
	var $name = 'Membership';
	
	var $components = array('Email');
	
	var $settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'password',
			'old_password' => 'old_password',
			'confirm_password' => 'confirm_password',
			'email' => 'email',
		),
		'app' => array(
			'from' => 'no-reply@example.com',
			'name' => 'Example.com',
		)
	);
	
	
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
		if (!empty($settings)) {
			foreach ($settings as $group => $setting) {
				foreach ($setting as $option => $value) {
					$this->settings[$group][$option] = $value;
				}
			}
		}
		if (!isset($settings['app']['from'])) {
			$settings['app']['from'] = 'no-reply@' . $_SERVER['HTTP_HOST'];
		}
		if (!isset($settings['app']['name'])) {
			$settings['app']['name'] = Inflector::humanize(APP_DIR);
		}
	}
	
	/**
	 * Blanks all form data when save fails
	 *
	 * @return void
	 * @author Dean Sofer
	 */
	function resetPasswords() {
		if (isset($this->controller->data[$this->controller->modelClass][$this->settings['fields']['old_password']])) {
			$this->controller->data[$this->controller->modelClass][$this->settings['fields']['old_password']] = '';
		}
		if (isset($this->controller->data[$this->controller->modelClass][$this->settings['fields']['password']])) {
			$this->controller->data[$this->controller->modelClass][$this->settings['fields']['password']] = '';
		}
		if (isset($this->controller->data[$this->controller->modelClass][$this->settings['fields']['confirm_password']])) {
			$this->controller->data[$this->controller->modelClass][$this->settings['fields']['confirm_password']] = '';
		}
	}
	
	// @TODO Send Email after registering
	function register() {
		$this->Email->to = $this->controller->data[$this->controller->modelClass][$this->settings['fields']['email']];
		$this->Email->from = $this->settings['app']['from'];
		$this->Email->subject = 'Thank you for registering at ' . $this->settings['app']['name'];
		$this->Email->sendAs = 'both';
		$this->Email->template = 'register';
		$this->Email->send();
	}
	
	function spamCheck() {
		if (!empty($_REQUEST['name']) || !empty($_REQUEST['email']) || !empty($_REQUEST['username'])) {
			return false;
		} else {
			return true;
		}
	}
	
	// @TODO Code email confirmation activation function
	function activate() {

	}
	
	// @TODO Code emailing of new password to the user
	function reset() {
		if ($user = $this->User->forgotRetrieval($this->data)) {
			$password = $this->Controller->User->generatePassword();
			$this->Controller->User->id = $this->Controller->data[$this->Controller->modelClass]['id'];
			$this->saveField($this->settings['fields']['password'], $this->Controller->Auth->password($password));
		
			// Send email		
			$message = sprintf(__('Your password has been reset for %s, your information is listed below', true), $this->settings['app']['name']) .":\n\n";
			$subject = __('Reset Password', true);
		
			$message .= __('Username', true) .": ". $user['User']['username'] ."\n";
			$message .= __('Password', true) .": ". $password ."\n\n";
			$message .= __('Please change your password once logged in.', true);
		
			$this->Controller->Email->to = $this->Controller->data[$this->Controller->modelClass][$this->settings['fields']['username']] .' <'. $this->controller->data[$this->controller->modelClass][$this->settings['fields']['email']] .'>';
			$this->Controller->Email->from = $this->settings['app']['name'] .' <'. $this->settings['app']['email'] .'>';
			$this->Controller->Email->subject = $this->settings['app']['name'] .' - '. $subject;
			// TODO create reset template $this->Email->template = 'reset';
			$this->Controller->Email->send($message);
			return true;
		} else {
			return false;
		}
	}
}
?>