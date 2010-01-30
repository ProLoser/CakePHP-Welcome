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
		if (!empty($settings)) {
			foreach ($settings['fields'] as $field => $value) {
				$this->fields[$field] = $value;
			}	
		}
	}
	
	/**
	 * Cake trigger used to bind extra validation rules to the model
	 * 
	 * @return boolean
	 */
	function beforeValidate() {
		$this->_bindValidation();
		return true;
	}
	
	/**
	 * Binds extra validation rules to the model 
	 *
	 * @return void
	 */
	function _bindValidation() {
		if (!isset($this->model->validate[$this->fields['username']]['isUnique'])) {
			$this->model->validate[$this->fields['username']]['isUnique'] = array(
				'rule' => array('isUnique'),
				'message' => 'This username has already been taken.',
			);
		}
		if (!isset($this->model->validate[$this->fields['username']]['minLength'])) {
			$this->model->validate[$this->fields['username']]['minLength'] = array(
				'rule' => array('minLength', 4),
				'message' => 'Username must be at least 4 characters'
			);
		}
		if (!isset($this->model->validate[$this->fields['confirm_password']]['confirmPassword'])) {
			$this->model->validate[$this->fields['confirm_password']]['confirmPassword'] = array(
				'rule' => array('confirmPassword', array()),
				'message' => 'Passwords do not match.'
			);
		}
		if (!isset($this->model->validate[$this->fields['confirm_password']]['minLength'])) {
			$this->model->validate[$this->fields['confirm_password']]['minLength'] = array(
				'rule' => array('minLength', 4),
				'message' => 'Password must be at least 4 characters'
			);
		}
		if (!isset($this->model->validate[$this->fields['password']]['hashPassword'])) {
			$this->model->validate[$this->fields['password']]['hashPassword'] = array(
				'rule' => array('hashPassword', array()),
			);
			
		}
		if (!isset($this->model->validate[$this->fields['old_password']]['oldPassword'])) {
			$this->model->validate[$this->fields['old_password']]['oldPassword'] = array(
				'rule' => array('oldPassword', array()),
				'message' => 'The old password is incorrect.'
			);
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
		if (Security::hash($this->model->data[$this->model->name][$this->fields['old_password']], null, true) == $this->model->field($this->fields['password'])) {
			return true;
		} else {
			return false;
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
		if (!isset($this->model->data[$this->model->name][$this->fields['username']])) {
			$this->model->data[$this->model->name][$this->fields['password']] =  Security::hash($this->model->data[$this->model->name][$this->fields['password']], null, true);
		}
		return true;
	}
	
	/**
	 * Validation function compares the two password fields to one another
	 *
	 * @return boolean
	 * @author Dean Sofer
	 */
	function confirmPassword() {
		if (Security::hash($this->model->data[$this->model->name][$this->fields['confirm_password']], null, true) == $this->model->data[$this->model->name][$this->fields['password']]) {
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
	function generatePassword ($params = array()) {
		if (!isset($params['charset'])) {
			$charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		} else {
			$charset = $params['charset'];
		}
		if (!isset($params['length'])) {
			$length = 6;
		} else {
			$lenght = $params['length'];
		}
		if (!isset($params['unique'])) {
			$unique = true;
		} else {
			$unique = $params['unique'];
		}
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
}
?>