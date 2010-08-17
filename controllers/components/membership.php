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
			'old_password' => 'old_password',
			'confirm_password' => 'confirm_password',
			'email' => 'email',
		),
		'from' => 'no-reply@example.com',
		'appName' => 'Example.com',
		'sendAs' => 'both', // text, html, both
		'delivery' => 'mail', // mail, smtp (requires options), debug
		'smtpOptions' => array() // port, host, timeout, username, password, client
	);
	
	
	function initialize(&$controller, $settings = array()) {
		// Environment based Default Settings
		$this->settings['from'] = 'no-reply@' . $_SERVER['HTTP_HOST'];
		$this->settings['appName'] = Inflector::humanize(APP_DIR);
		
		$this->settings = array_merge($this->settings, $settings);
		
		// saving the controller reference for later use
		$this->controller =& $controller;
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
	
	/**
	 * Performs a spam check for any form submission
	 *
	 * @return boolean If the submission fails the spam check
	 * @author Dean
	 */
	function captcha() {
		if (!empty($_REQUEST['name']) || !empty($_REQUEST['email']) || !empty($_REQUEST['username'])) {
			return false;
		} else {
			return true;
		}
	}
	
	// @TODO Code emailing of new password to the user
	function reset() {
		if ($user = $this->User->forgotRetrieval($this->data)) {
			$password = $this->Controller->User->generatePassword();
			$this->Controller->User->id = $this->Controller->data[$this->Controller->modelClass]['id'];
			$this->saveField($this->settings['fields']['password'], $this->Controller->Auth->password($password));
		
			// Send email		
			$message = sprintf(__('Your password has been reset for %s, your information is listed below', true), $this->settings['appName']) .":\n\n";
			$subject = __('Reset Password', true);
		
			$message .= __('Username', true) .": ". $user['User']['username'] ."\n";
			$message .= __('Password', true) .": ". $password ."\n\n";
			$message .= __('Please change your password once logged in.', true);
		
			$this->Email->to = $this->Controller->data[$this->Controller->modelClass][$this->settings['fields']['username']] .' <'. $this->controller->data[$this->controller->modelClass][$this->settings['fields']['email']] .'>';
			$this->Email->from = $this->settings['appName'] .' <'. $this->settings['app']['email'] .'>';
			$this->Email->subject = $this->settings['appName'] .' - '. $subject;
			$this->Email->send($message);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Sends an email for the specified template
	 *
	 * @param string $type 
	 * @param string $data 
	 * @return void
	 * @author Dean
	 */
	function email($type, $data = null) {
		if (!$data) {
			$data = $this->Controller->data;
		}
		
		$this->Email->to = $data[$this->controller->modelClass][$this->settings['fields']['email']];
		$this->Email->from = $this->settings['from'];
			
		switch ($type) {
			case 'register':
				$this->Email->subject = 'Thank you for registering at ' . $this->settings['appName'];
				break;
			case 'reset':
				$this->Email->subject = 'Thank you for registering at ' . $this->settings['appName'];
				break;
			case 'activation':
				$this->Email->subject = 'Thank you for registering at ' . $this->settings['appName'];
				break;
		}
		
		$this->Email->template = $type;
		$this->Email->sendAs = 'both';
		
		return $this->Email->send($message);
	}
}
?>