<?php

class RegisterForm extends CFormModel
{
	public $username;
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
			array('username, password, confirm', 'required'),
			array('username', 'email'),
			
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
			'username' => '账号',
			'password' => '密码',
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

	public function register()
	{
		$userModel = new UserModel();
		$userModel->username = $this->username;
		$userModel->password = md5($this->password);
	
		if ($userModel->validate() && $userModel->save()) {
			return true;
		}
		
		return false;
	}
}
