<?php
class Controller_Home extends Controller_Template
{
	// 全ページ要認証
	public function before()
	{
		parent::before();
		if (! Auth::check()) {
			Response::redirect('/login');
		}
	}

	/**
	 * マイページ /home GET
	 */
	public function action_index()
	{
		$this->template->title = 'マイページ';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
		];
		$this->template->content = View::forge('home/index');

		$username = Auth::get_screen_name();
		$this->template->content->games_list = Model_GamesScores::get_list_for_home($username);
		$this->template->content->owner_games_list = Model_GamesScores::get_list_for_home_owner($username);
		$this->template->content->guest_games_list = Model_GamesScores::get_list_for_home_guest();
	}

	/**
	 * プロフィール編集 /home/edit_profile GET
	 */
	public function get_edit_profile()
	{
		$this->template->title = 'プロフィール編集';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
			'/home/edit' => 'プロフィール編集',
		];
		$this->template->content = View::forge('home/edit_profile');

		$username = Auth::get_screen_name();
		$this->template->content->error_fields = [];
		Asset::js(['home_edit_profile.js'], [], 'add_js');
		$this->template->content->data = Model_Users::get_by_user_id($username);
	}

	/**
	 * プロフィール編集 /home/edit_profile POST
	 */
	public function post_edit_profile()
	{
		$this->get_edit_profile();

		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}

		// 空要素を詰める
		$occupations = Input::post('occupations', []);
		$occupations = array_map('trim', $occupations);
		$occupations = array_values(array_filter($occupations, 'strlen'));
		$minor_improvements = Input::post('minor_improvements', []);
		$minor_improvements = array_map('trim', $minor_improvements);
		$minor_improvements = array_values(array_filter($minor_improvements, 'strlen'));
		$this->template->content->occupations = $occupations;
		$this->template->content->minor_improvements = $minor_improvements;

		$val = self::validation_edit_profile($occupations, $minor_improvements);

		if (! $val->run(['occupations' => $occupations, 'minor_improvements' => $minor_improvements])) {
			$this->template->errors = [];
			foreach ($val->error() as $key => $error) {
				$this->template->errors[] = $error->get_message();
				$this->template->content->error_fields[] = $key;
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

		Session::set_flash('messages', 'プロフィールの更新に成功しました');
		Response::redirect('/users/view/' . Auth::get_screen_name());
	}

	/**
	 * プロフィール編集 Validation
	 * @param  array $occupations        好きな職業card_idの配列
	 * @param  array $minor_improvements 好きな小進歩card_idの配列
	 * @return Validation
	 */
	private static function validation_edit_profile($occupations, $minor_improvements)
	{
		$val = Validation::forge();
		$val->add_callable('ValidationRule');
		$val->add('screen_name', '表示名')
			->add_rule('required');
		$val->add('twitter', 'Twitter')
			->add_rule('valid_twitter');
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

	/**
	 * アイコン変更 /home/edit_icon GET
	 */
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

	/**
	 * アイコン変更 /home/edit_icon POST
	 */
	public function post_edit_icon()
	{
		$this->get_edit_icon();
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}
		$config = [
			'path' => DOCROOT . 'assets/img/upload/users/',
			'ext_whitelist' => ['jpg', 'jpeg', 'png', 'gif'],
			'new_name' => Auth::get_screen_name(),
			'auto_rename' => false,
			'overwrite' => true,
			'max_size' => 5 * 1024 * 1024, // 5MB
			'create_path' => true,
		];
		Upload::process($config);
		if (! Upload::is_valid()) {
			$errors = Upload::get_errors('icon')['errors'];
			$this->template->errors = array_column($errors, 'message');
			return;
		}
		Upload::save();
		Auth::update_user([
			'icon' => 'upload/users/' . Upload::get_files('icon')['saved_as'],
		]);

		Session::set_flash('messages', 'アイコンの更新に成功しました');
		Response::redirect('/users/view/' . Auth::get_screen_name());
	}

	/**
	 * パスワード変更 /home/change_password GET
	 */
	public function get_change_password()
	{
		$this->template->title = 'パスワード変更';
		$this->template->breadcrumbs = [
			'/home' => 'マイページ',
			'/home/change_password' => 'パスワード変更',
		];
		$this->template->content = View::forge('home/change_password');
	}

	/**
	 * パスワード変更 /home/change_password POST
	 */
	public function post_change_password()
	{
		$this->get_change_password();
		if (! Security::check_token()) {
			$this->template->errors = '再度送信してください';
			return;
		}
		$val = self::validation_change_password();
		if (! $val->run()) {
			$this->template->errors = array_column($val->error(), 'message');
			return;
		}
		try {
			Auth::update_user([
				'old_password' => Input::post('old_password'),
				'password' => Input::post('new_password'),
			]);
		} catch (SimpleUserWrongPassword $e) {
			$this->template->errors = '古いパスワードが違います';
			return;
		}
		Session::set_flash('messages', 'パスワードを変更しました');
		Response::redirect('home');
	}

	/**
	 * パスワード変更 Vaidation
	 * @return Validation
	 */
	private static function validation_change_password()
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