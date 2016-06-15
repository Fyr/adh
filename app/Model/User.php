<?php
App::uses('AppModel', 'Model');
class User extends AppModel {
	
	public $validate = array(
		'username' => array(
			'checkNotEmpty' => array(
				'rule' => 'notBlank',
				'message' => 'Field is mandatory',
			),
			'checkNameLen' => array(
				'rule' => array('between', 8, 50),
				'message' => 'The name must be between 8 and 50 characters'
			)
		),
		'key' => array(
			'checkNotEmpty' => array(
				'rule' => 'notBlank',
				'message' => 'Field is mandatory',
			),
			'checkNameLen' => array(
				'rule' => array('between', 8, 32),
				'message' => 'Value of key must be between 8 and 32 characters'
			),
			'checkIsUnique' => array(
				'rule' => 'isUnique',
				'required' => 'create',
				'message' => 'That key has already been used'
			)
		),
		'email' => array(
			'checkEmail' => array(
				'rule' => 'email',
				'message' => 'Email is incorrect'
			)
		),
		'password' => array(
			'checkNotEmpty' => array(
				'rule' => array('notBlank'),
				'message' => 'Field is mandatory'
			),
			'checkPswLen' => array(
				'rule' => array('between', 4, 15),
				'message' => 'The password must be between 4 and 15 characters'
			),
			'checkMatchPassword' => array(
				'rule' => array('matchPassword'),
				'message' => 'Your password and its confirmation do not match',
			)
		),
		'password_confirm' => array(
			'notempty' => array(
				'rule' => array('notBlank'),
				'message' => 'Field is mandatory',
			)
		)
	);

	public function matchPassword($data){
		if($data['password'] == $this->data['User']['password_confirm']){
			return true;
		}
		$this->invalidate('password_confirm', 'Your password and its confirmation do not match');
		return false;
	}
	
	public function beforeValidate($options = array()) {
		if (Hash::get($options, 'validate')) {
			if (!Hash::get($this->data, 'User.password')) {
				$this->validator()->remove('password');
				$this->validator()->remove('password_confirm');
			}
		}
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['User']['password'])) {
			if ($this->data['User']['password']) {
				$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			} else {
				unset($this->data['User']['password']);
			}
		}
		return true;
	}

}
