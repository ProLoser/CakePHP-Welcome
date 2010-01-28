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
		$this->fields = array();
		foreach ($settings as $field => $options) {
			
		}
	}
	
	/**
	 * Cake trigger used to bind extra validation rules to the model
	 * 
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
		$this->model->validate[$this->fields['username']] = array(
			'rule' => array('isUnique'),
			'message' => 'This username has already been taken.',
		);
		$this->model->validate[$this->fields['confirm_password']] = array_merge(
			array('confirm' => array(
				'rule' => array('confirmPassword', array()),
				'message' => 'Passwords do not match.'
			)),			
			$this->model->validate[$this->fields['confirm_password']]
		);
		$this->model->validate[$this->fields['old_password']] = array(
			'old' => array(
				'rule' => array('oldPassword', array()),
				'message' => 'The old password is incorrect.'
			),
		);
	}
	
	/**
	 * Validation function that checks to see if the password entered matches
	 * the password stored in the database
	 *
	 * @param string $data 
	 * @return boolean 
	 * @author Dean Sofer
	 */
	function oldPassword($data) {
		if (Security::hash($this->data[$this->model->className][$this->fields['old_password']], null, true) == $this->field($this->fields['password'])) {
			return true;
		} else {
			$this->resetPasswords();
			return false;
		}
	}
	
	/**
	 * Validation function compares the two password fields to one another
	 *
	 * @param string $data 
	 * @return boolean
	 * @author Dean Sofer
	 */
	function confirmPassword($data) {
		if (Security::hash($data[$this->model->className][$this->fields['confirm_password']], null, true) == $this->data[$this->model->className][$this->fields['password']]) {
			return true;
		} else {
			$this->resetPasswords();
			return false;
		}
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function resetPasswords() {
		$this->model->data[$this->model->className][$this->fields['old_password']] = '';
		$this->model->data[$this->model->className][$this->fields['password']] = '';
		$this->model->data[$this->model->className][$this->fields['confirm_password']] = '';
	}
	
	/**
	 * undocumented function
	 *
	 * @return string
	 */
	function generatePassword ($length = 8) {
        // initialize variables
        $password = "";
        $i = 0;
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";
 
        // add random characters to $password until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
 
            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }
        return $password;
    }
}
?>