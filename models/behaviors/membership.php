<?php
/**
 * Membership Behavior
 * 
 * Handles common tasks associated with user models, such as password and 
 * username management.
 * 
 * @package Membership Plugin
 * @author Dean Sofer (proloser@hotmail.com)
 */
class MembershipBehavior extends ModelBehavior {
	
	/**
	 * Default settings
	 *
	 * @var array
	 */
	var $_settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'password',
			'old_password' => 'old_password',
			'confirm_password' => 'confirm_password',
			'token' => 'token',
		),
	);
	
	function beforeSave($data) {
		$fields = $this->_settings['fields'];
		if ($fields['token']) {
			$this->model->data[$this->model->name][$fields['token']] = '';
		}
		return true;
	}
	
	/**
	 * Setup the behavior. It stores a reference to the model, merges the 
	 * default options with the options for each field, and setup the 
	 * validation rules.
	 *
	 * @param $model Object
	 * @param $settings Array[optional]
	 * @return null
	 * @author Vinicius Mendes
	 */
	function setup(&$model, $settings = array()) {
		$this->model = $model;
		$this->_settings = array_merge($this->_settings, $settings);
	}
	
	/**
	 * Cake trigger used to bind extra validation rules to the model
	 * 
	 * @return boolean
	 */
	function beforeValidate() {
		debug($this->model->data);
		$this->hashPassword();
		debug($this->model->data);
		$this->_bindValidation();
		return true;
	}
	
	/**
	 * Binds extra validation rules to the model 
	 *
	 * @return void
	 */
	function _bindValidation() {
		$fields = $this->_settings['fields'];
		if (!isset($this->model->validate[$fields['username']]['isUnique'])) {
			$this->model->validate[$fields['username']]['isUnique'] = array(
				'rule' => 'isUnique',
				'message' => 'The ' . Inflector::humanize($fields['username']) . ' has already been taken.',
			);
		}
		if (!isset($this->model->validate[$fields['confirm_password']]['confirmPassword'])) {
			$this->model->validate[$fields['confirm_password']]['confirmPassword'] = array(
				'rule' => 'confirmPassword',
				'message' => Inflector::humanize(Inflector::pluralize($fields['password'])) . ' do not match.'
			);
		}
		if (!isset($this->model->validate[$fields['old_password']]['oldPassword'])) {
			$this->model->validate[$fields['old_password']]['oldPassword'] = array(
				'rule' => 'oldPassword',
				'message' => 'The old ' . Inflector::humanize($fields['password']) . ' is incorrect.'
			);
		}
	}

	/**
	 * Checks to see if the password has already been hashed and then hashes it.
	 * Necessary due to a discrepency in the core where the password field is 
	 * only hashed by the Auth component if the username field is present also.
	 *
	 * @return boolean
	 */
	function hashPassword()	{
		$fields = $this->_settings['fields'];
		if (!isset($this->model->data[$this->model->name][$fields['username']])) {
			$this->model->data[$this->model->name][$fields['password']] = Security::hash($this->model->data[$this->model->name][$fields['password']], null, true);
		}
	}
	
	/**
	 * Validation function that checks to see if the password entered matches
	 * the password stored in the database
	 *
	 * @param string $data 
	 * @return boolean 
	 * @author Dean Sofer
	 */
	function oldPassword() {
		$fields = $this->_settings['fields'];
		if (
			Security::hash($this->model->data[$this->model->name][$fields['old_password']], null, true) 
			== $this->model->field($fields['password'])
		) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Validation function compares the two password fields to one another
	 * Requires hashPassword to already be run
	 *
	 * @return boolean
	 * @author Dean Sofer
	 */
	function confirmPassword() {
		$fields = $this->_settings['fields'];
		if (
			Security::hash($this->model->data[$this->model->name][$fields['confirm_password']], null, true) 
			== $this->model->data[$this->model->name][$fields['password']]
		) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Randomly generates a new password
	 *
	 * @param array $params charset, length, unique
	 * @return string new random password
	 */
	function generatePassword($params = array()) {
		$defaults = array(
			'charset' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'length' => 6,
			'unique' => true,
		);
		$params = array_merge($defaults, $params);
        $password = '';
        $i = 0;
 
        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($charset, mt_rand(0, strlen($charset)-1), 1);
 
            // we don't want this character if it's already in the password
            if (!$unique || !strstr($charset, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }

	/**
	 * Retrieve and reset information for a forgotten password.
	 *
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function forgot($data) {
		$user = $this->find('first', array(
			'conditions' => array(
				array(
					$this->fields['email'] => $data[$this->model->name][$this->fields['email']],
					$this->fields['username'] => $data[$this->model->name][$this->fields['username']]
				)
			)
		));
		
		if (empty($user)) {
			$this->invalidate('username', 'No user was found with either of those credentials');
			return false;
		}
		
		return $user;
	}
	
	/**
	 * Uses the token to confirm the account registration
	 *
	 * @param string $token 
	 * @return boolean $success
	 */
	public function confirm($token) {
		if (true) {
			$success = true;
		} else {
			$success = false;
		}
		
		return $success;
	}
}
?>