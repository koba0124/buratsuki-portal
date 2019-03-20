<?php
class Controller_Register extends Controller_Template
{
	private const FIELDS = [
		'username',
		'screen_name',
		'email',
		'password',
		'password_check',
		'watchword',
	];

	public function get_index()
	{
		$this->template->title = 'ユーザ登録';
		$this->template->breadcrumbs = [
			'/register' => 'ユーザ登録',
		];
		$this->template->content = View::forge('register');
		$this->template->content->classes = [];
		foreach (self::FIELDS as $field) {
			$this->template->content->classes[$field] = 'validate';
		}
	}

	public function post_index()
	{
		$this->get_index();
		if (! Security::check_token()) {
			$this->template->errors = ['再度送信してください'];
			return;
		}
		$val = $this->create_val();
		if (! $val->run()) {
			$errors = $val->error();
			$this->template->errors = [];
			$error_keys = [];
			foreach ($errors as $key => $error) {
				$this->template->errors[] = $error->get_message();
				$error_keys[] = $key;
			}
			foreach (self::FIELDS as $field) {
				if (in_array($field, $error_keys)) {
					$this->template->content->classes[$field] = 'invalid validate';
				} else {
					$this->template->content->classes[$field] = 'valid validate';
				}
			}
			return;
		}
		try {
			$result = Auth::create_user(
				Input::post('username'),
				Input::post('password'),
				Input::post('email'),
				1,
				[
					'screen_name' => Input::post('screen_name'),
					'icon' => 'noimage.png',
				]
			);
		} catch (SimpleUserUpdateException $e) {
			$result = false;
		}
		if ($result === false) {
			$this->template->errors = ['すでに登録されているユーザIDまたはメールアドレスです'];
			return;
		}
		Auth::login(Input::post('username'), Input::post('password'));
		Session::set_flash('login_message', '登録完了しました');
		Response::redirect('home');
	}

	private function create_val()
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		$val->add('username', 'ユーザID')
			->add_rule('required')
			->add_rule('min_length', 4)
			->add_rule('valid_username');
		$val->add('screen_name', '表示名')
			->add_rule('required');
		$val->add('email', 'メールアドレス')
			->add_rule('required')
			->add_rule('valid_email');
		$val->add('password', 'パスワード')
			->add_rule('required')
			->add_rule('min_length', 6);
		$val->add('password_check', 'パスワード(確認)')
			->add_rule('required')
			->add_rule('match_field', 'password');
		$val->add('watchword', '合言葉')
			->add_rule('required')
			->add_rule('valid_watchword');
		return $val;
	}
}