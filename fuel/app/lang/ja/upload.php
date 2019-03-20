<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.5
 * @author     Yuki Yokoyama
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

return array(
    'error_'.\Upload::UPLOAD_ERR_OK						=> 'ファイルのアップロードに成功しました',
	'error_'.\Upload::UPLOAD_ERR_INI_SIZE				=> 'アップロードファイルのサイズが php.ini の upload_max_filesize ディレクティブ設定値を超えています',
	'error_'.\Upload::UPLOAD_ERR_FORM_SIZE				=> 'アップロードファイルのサイズが HTML フォームの MAX_FILE_SIZE ディレクティブ設定値を超えています',
	'error_'.\Upload::UPLOAD_ERR_PARTIAL					=> 'ファイルの一部しかアップロードされませんでした',
	'error_'.\Upload::UPLOAD_ERR_NO_FILE					=> 'ファイルはアップロードされませんでした',
	'error_'.\Upload::UPLOAD_ERR_NO_TMP_DIR				=> 'アップロード用一時フォルダの設定がありません',
	'error_'.\Upload::UPLOAD_ERR_CANT_WRITE				=> 'アップロードファイルがディスクに書き込めませんでした',
	'error_'.\Upload::UPLOAD_ERR_EXTENSION				=> 'インストールされている PHP の 拡張機能 (extension) によりアップロードが防止されました',
	'error_'.\Upload::UPLOAD_ERR_MAX_SIZE				=> 'アップロードファイルのサイズが既定値上限を超えています',
	'error_'.\Upload::UPLOAD_ERR_EXT_BLACKLISTED			=> 'この拡張子でのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_EXT_NOT_WHITELISTED		=> 'この拡張子でのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_TYPE_BLACKLISTED		=> 'このファイルタイプでのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_TYPE_NOT_WHITELISTED	=> 'このファイルタイプでのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_MIME_BLACKLISTED		=> 'この mime タイプでのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_MIME_NOT_WHITELISTED	=> 'この mime タイプでのファイルアップロードは許可されていません',
	'error_'.\Upload::UPLOAD_ERR_MAX_FILENAME_LENGTH		=> 'アップロードファイル名の長さが既定値上限を超えています',
	'error_'.\Upload::UPLOAD_ERR_MOVE_FAILED				=> 'アップロードファイルを一時フォルダから指定フォルダに移動できません',
	'error_'.\Upload::UPLOAD_ERR_DUPLICATE_FILE			=> 'アップロードファイルと同じ名前のファイルが既に存在します',
	'error_'.\Upload::UPLOAD_ERR_MKDIR_FAILED			=> 'アップロード先の指定フォルダが作成できません',
);