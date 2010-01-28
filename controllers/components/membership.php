<?php
/**
 * Membership Component
 *
 * @author Dean Sofer (proloser@hotmail.com)
 * @version 1.0
 * @package User Plugin
 **/
class MembershipComponent extends Object {
	
	
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}
	
	function beforeFilter() {
		$this->controller->Auth->allow(array('add', 'reset'));
		$this->controller->Auth->deny(array('edit', 'password'));
	}
	
	function login() {
		
	}
	
	function logout() {
		
	}
	
	// @TODO Send Email after registering
	function register() {
		if (!empty($this->controller->data)) {
			$this->controller->User->create();
			if ($this->controller->User->save($this->data)) {
				$this->controller->Session->setFlash('');
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
		if (empty($this->controller->data)) {
			$this->controller->data = $this->controller->User->read(null, $id);
		}
	}
	
	// @TODO Code emailing of new password to the user
	function reset() {
		
	}
}
?>