<?php
class Controller_Users extends Controller_Template
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

	public function action_index()
	{
		$data = Model_Users::get_list();
		$this->template->content = View::forge('users/index');
		$this->template->content->data = $data;
		$this->template->title = 'メンバー';
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
		];
	}

	public function action_view($user_id)
	{
		$this->template->content = View::forge('users/view');
		$this->template->messages = Session::get_flash('users_edit_message', []);

		$user_data = Model_Users::get_by_user_id($user_id);
		if (! $user_data) {
			throw new HttpNotFoundException;
		}
		$this->template->content->user_data = $user_data;
		$this->template->title = '['.$user_id.'] '.$user_data['screen_name'];
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
			'/members/view/'.$user_id => $this->template->title,
		];

		$occupations = Model_CardsMaster::get_list_by_card_ids($user_data['occupations'] ?? []);
		$minor_improvements = Model_CardsMaster::get_list_by_card_ids($user_data['minor_improvements'] ?? []);
		$this->template->content->occupations = $occupations;
		$this->template->content->minor_improvements = $minor_improvements;
	}

	public function get_edit()
	{
		$this->template->title = '編集';
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
			'/users/edit' => '編集',
		];
		$this->template->content = View::forge('users/edit');

		$username = Auth::get_screen_name();
		if (! $username) {
			Response::redirect('/login');
		}
		$this->template->content->classes = [];
		Asset::js(['users_edit.js'], [], 'add_js');
		$this->template->content->data = Model_Users::get_by_user_id($username);
	}

	public function post_edit()
	{
		$this->get_edit();

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

		$val = $this->validation_edit($occupations, $minor_improvements);

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

	public function get_icon()
	{
		$this->template->title = '編集';
		$this->template->breadcrumbs = [
			'/users' => 'メンバー',
			'/users/icon' => 'アイコン登録',
		];
		$this->template->content = View::forge('users/icon');

		$username = Auth::get_screen_name();
		if (! $username) {
			Response::redirect('/login');
		}
		$this->template->content->classes = [];
		Asset::js(['users_edit.js'], [], 'add_js');
		$this->template->content->data = Model_Users::get_by_user_id($username);
	}

	public function post_icon()
	{
		$this->get_icon();
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

	private function validation_edit($occupations, $minor_improvements)
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
}