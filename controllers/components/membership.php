<?php
/**
 * Membership Component
 *
 * @author Dean Sofer (proloser@hotmail.com)
 * @version 1.0
 * @package User Plugin
 **/
class MembershipComponent extends Object {

	/**
	 * Default fieldnames
	 *
	 * @var array
	 */
	var $fields = array(
		'username' => 'username',
		'password' => 'password',
		'old_password' => 'old_password',
		'confirm_password' => 'confirm_password',
	);
	
	
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
		if (!empty($settings)) {
			foreach ($settings['fields'] as $field => $value) {
				$this->fields[$field] = $value;
			}	
		}
	}
	
	/**
	 * Standard auth configuration for the users controller
	 *
	 * @return void
	 * @author Dean Sofer
	 */
	function authAllow() {
		$this->controller->Auth->allow(array('add', 'reset'));
		$this->controller->Auth->deny(array('edit', 'password', 'delete'));
	}
	
	/**
	 * Blanks all form data when save fails
	 *
	 * @return void
	 * @author Dean Sofer
	 */
	function resetPasswords() {
		if (isset($this->controller->data[$this->controller->modelClass][$this->fields['old_password']])) {
			$this->controller->data[$this->controller->modelClass][$this->fields['old_password']] = '';
		}
		if (isset($this->controller->data[$this->controller->modelClass][$this->fields['password']])) {
			$this->controller->data[$this->controller->modelClass][$this->fields['password']] = '';
		}
		if (isset($this->controller->data[$this->controller->modelClass][$this->fields['confirm_password']])) {
			$this->controller->data[$this->controller->modelClass][$this->fields['confirm_password']] = '';
		}
	}
	
	function login() {
		
	}
	
	function logout() {
		
	}
	
	// @TODO Send Email after registering
	function register() {
		if (!empty($this->controller->data) || !empty($_REQUEST['name']) || !empty($_REQUEST['email']) || !empty($_REQUEST['username'])) {
			$this->controller->User->create();
			if ($this->controller->User->save($this->data)) {
				$this->controller->Session->setFlash('Thank you for registering');
			} else {
				$this->controller->Session->setFlash('Failed to register, please try again.');
			}
		}
	}
	
	// @TODO Code email confirmation activation function
	function activate() {
		
	}
	
	
	function edit() {
		if (!empty($this->controller->data)) {
			if ($this->controller->User->save($this->data)) {
				return true;
			} else {
				return false;
			}
		}
		if (empty($this->controller->data)) {
			$this->controller->data = $this->controller->User->read(null, $id);
		}
	}
	
	function password() {
		if (!empty($this->controller->data)) {
			if ($this->controller->User->save($this->controller->data)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// @TODO Code emailing of new password to the user
	function reset() {
		
	}
}
?>