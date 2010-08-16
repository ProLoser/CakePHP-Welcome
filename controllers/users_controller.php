<?php
/**
 * UsersController
 * 
 * [Short Description]
 *
 * @package default
 * @author Dean Sofer
 * @version $Id$
 * @copyright Art Engineered
 **/
class UsersController extends WelcomeAppController {
	var $name = 'Users';
	
	var $helpers = array('Html', 'Form');
	var $components = array('Auth');
	var $uses = array('User');

	function beforeFilter() {
		$this->Auth->deny(array('settings', 'close_account'));
		$this->Auth->allow(array('login', 'register', 'reset'));
	}

	public function login() {
		if (!isset($this->User->actsAs['Welcome.Membership']['remember_me']) || $this->User->actsAs['Welcome.Membership']['remember_me']) {
			$this->set('rememberMe', true);
		}
	}
	
	public function register() {
		if (!isset($this->User->actsAs['Welcome.Membership']['captcha']) || $this->User->actsAs['Welcome.Membership']['captcha']) {
			$this->set('captcha', true);
		}
	}
	
	public function reset() {
		
	}
	
	public function settings() {
		
	}
	
	public function close_account() {
		
	}
}
?>