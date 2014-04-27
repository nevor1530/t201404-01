<?php

class UpdatePasswordForm extends CFormModel
{
	public $password;
	public $confirm;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('password, confirm', 'required'),
			
			// password needs to be authenticated
			array('confirm', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password' => '新密码',
			'confirm' => '确认密码'
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			if ($this->password !== $this->confirm) {
				$this->addError('confirm','两次输入密码不一致.');
			}
		}
	}

	public function updatePassword()
	{
		return false;
	}
}
