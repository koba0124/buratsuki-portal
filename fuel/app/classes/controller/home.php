<?php
class Controller_Home extends Controller_Template
{
	const STRING_FIELDS = [
		'screen_name',
		'twitter',
		'comment',
	];

	const ARRAY_FIELDS = [
		'occupations',
		'minor_improvements',
	];

	const CHANGE_PASSWORD_FIELDS = [
		'old_password',
		'new_password',
		'new_password_check',
	];

	public function before()
	{
		parent::before();
		if (! Auth::check()) {
			Response::redirect('/login');
		}
	}

	public function action_index()
	{
		$login_message = Session::get_flash('login_message');
		if ($login_message) {
			$this->template->messages = [$login_message];
		}
		$this->template->title = 'マイページ';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
		];
		$this->template->content = View::forge('home/index');
	}

	public function get_edit_profile()
	{
		$this->template->title = 'プロフィール編集';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
			'/home/edit' => 'プロフィール編集',
		];
		$this->template->content = View::forge('home/edit_profile');

		$username = Auth::get_screen_name();
		$this->template->content->classes = [];
		Asset::js(['home_edit_profile.js'], [], 'add_js');
		$this->template->content->data = Model_Users::get_by_user_id($username);
	}

	public function post_edit_profile()
	{
		$this->get_edit_profile();

		if (! Security::check_token()) {
			$this->template->errors = ['再度送信してください'];
			return;
		}

		$occupations = Input::post('occupations', []);
		$occupations = array_values(array_filter($occupations, 'strlen'));
		$minor_improvements = Input::post('minor_improvements', []);
		$minor_improvements = array_values(array_filter($minor_improvements, 'strlen'));
		$this->template->content->occupations = $occupations;
		$this->template->content->minor_improvements = $minor_improvements;

		$val = $this->validation_edit_profile($occupations, $minor_improvements);

		if (! $val->run(['occupations' => $occupations, 'minor_improvements' => $minor_improvements])) {
			$errors = $val->error();
			$this->template->errors = [];
			$error_keys = [];
			foreach ($errors as $key => $error) {
				$this->template->errors[] = $error->get_message();
				$error_keys[] = $key;
			}
			foreach (self::STRING_FIELDS as $field) {
				if (in_array($field, $error_keys)) {
					$this->template->content->classes[$field] = 'invalid validate';
				} else {
					$this->template->content->classes[$field] = 'valid validate';
				}
			}
			if (in_array('occupations', $error_keys)) {
				foreach ($occupations as $key => $occupation) {
					$this->template->content->classes['occupations'][$key] = 'invalid validate';
				}
			} else {
				foreach ($occupations as $key => $minor_improvement) {
					if (in_array('occupations.'.$key, $error_keys)) {
						$this->template->content->classes['occupations'][$key] = 'invalid validate';
					} else {
						$this->template->content->classes['occupations'][$key] = 'valid validate';
					}
				}
			}
			if (in_array('minor_improvements', $error_keys)) {
				foreach ($minor_improvements as $key => $minor_improvement) {
					$this->template->content->classes['minor_improvements'][$key] = 'invalid validate';
				}
			} else {
				foreach ($minor_improvements as $key => $minor_improvement) {
					if (in_array('minor_improvements.'.$key, $error_keys)) {
						$this->template->content->classes['minor_improvements'][$key] = 'invalid validate';
					} else {
						$this->template->content->classes['minor_improvements'][$key] = 'valid validate';
					}
				}
			}
			return;
		}

		Auth::update_user([
			'screen_name' => Input::post('screen_name'),
			'twitter' => Input::post('twitter'),
			'comment' => Input::post('comment'),
			'occupations' => $occupations,
			'minor_improvements' => $minor_improvements,
		]);

		Session::set_flash('users_edit_message', ['プロフィールの更新に成功しました']);
		Response::redirect('/users/view/' . Auth::get_screen_name());
	}

	public function get_edit_icon()
	{
		$this->template->title = 'アイコン変更';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
			'/home/edit_icon' => 'アイコン変更',
		];
		$this->template->content = View::forge('home/edit_icon');

		$username = Auth::get_screen_name();

		$this->template->content->classes = [];
		$this->template->content->data = Model_Users::get_by_user_id($username);
	}

	public function post_edit_icon()
	{
		$this->get_edit_icon();
		if (! Security::check_token()) {
			$this->template->errors = ['再度送信してください'];
			return;
		}
		$config = [
			'path' => DOCROOT.'assets/img/upload/users/',
			'ext_whitelist' => ['jpg', 'jpeg', 'png', 'gif'],
			'new_name' => Auth::get_screen_name(),
			'auto_rename' => false,
			'overwrite' => true,
			'max_size' => 5 * 1024 * 1024,
			'create_path' => true,
		];
		Upload::process($config);
		if (! Upload::is_valid()) {
			$errors = Upload::get_errors('icon')['errors'];
			$this->template->errors = [];
			foreach ($errors as $error) {
				$this->template->errors[] = $error['message'];
			}
			return;
		}
		Upload::save();
		Auth::update_user([
			'icon' => 'upload/users/' . Upload::get_files('icon')['saved_as'],
		]);

		Session::set_flash('users_edit_message', ['アイコンの更新に成功しました']);
		Response::redirect('/users/view/' . Auth::get_screen_name());
	}

	public function get_change_password()
	{
		$this->template->title = 'パスワード変更';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
			'/home/change_password' => 'パスワード変更',
		];
		$this->template->content = View::forge('home/change_password');
	}

	public function post_change_password()
	{
		$this->get_change_password();
		if (! Security::check_token()) {
			$this->template->errors = ['再度送信してください'];
			return;
		}
		$val = self::validation_change_password();
		if (! $val->run()) {
			$errors = $val->error();
			$this->template->errors = [];
			foreach ($errors as $key => $error) {
				$this->template->errors[] = $error->get_message();
			}
			return;
		}
		try {
			Auth::update_user([
				'old_password' => Input::post('old_password'),
				'password' => Input::post('new_password'),
			]);
		} catch (SimpleUserWrongPassword $e) {
			$this->template->errors = ['古いパスワードが違います'];
			return;
		}
		Session::set_flash('login_message', 'パスワードを変更しました');
		Response::redirect('home');
	}

	private function validation_edit_profile($occupations, $minor_improvements)
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		$val->add('screen_name', '表示名')
			->add_rule('required');
		$val->add('twitter', 'Twitter');
		$val->add('comments', 'ひとこと');
		$val->add('occupations', '好きな職業')
			->add_rule('array_unique');
		foreach ($occupations as $key => $occupation) {
			$val->add('occupations.' . $key, '好きな職業' . ($key + 1))
				->add_rule('valid_occupation_id', $occupation);
		}
		$val->add('minor_improvements', '好きな小進歩')
			->add_rule('array_unique');
		foreach ($minor_improvements as $key => $minor_improvement) {
			$val->add('minor_improvements.' . $key, '好きな小進歩' . ($key + 1))
				->add_rule('valid_minor_improvement_id', $minor_improvement);
		}
		return $val;
	}

	private function validation_change_password()
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		$val->add('new_password', '新パスワード')
			->add_rule('required')
			->add_rule('min_length', 6);
		$val->add('new_password_check', '新パスワード(確認)')
			->add_rule('required')
			->add_rule('match_field', 'new_password');
		return $val;
	}
}