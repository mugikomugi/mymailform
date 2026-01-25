<?php
// 共通関数ファイル - 必要最低限版

session_start();

// XSS対策
function html_esc($word)
{
  return htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
}

// CSRFトークン生成
function generateCSRFToken()
{
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

// CSRFトークン検証
function validateCSRFToken($post_token)
{
  return !empty($_SESSION['csrf_token'])
    && !empty($post_token)
    && hash_equals($_SESSION['csrf_token'], $post_token);
}

// 文字数制限チェック
function checkwords($word, $length)
{
  if (mb_strlen($word) === 0 || mb_strlen($word) > $length) {
    return FALSE;
  } else {
    return TRUE;
  }
}

// 日本語入力必須チェック
function letter_check($word)
{
  $pattern_text = '/[ぁ-んァ-ヶ一-龠]/u';
  if (preg_match($pattern_text, $word)) {
    return TRUE;
  } else {
    return FALSE;
  }
}

// カタカナ・ひらがなチェック
function kana($word)
{
  $pattern_kana = '/^[ァ-ヶー]+$/u';
  $pattern_hira = '/^[ぁ-んー]+$/u';
  if (preg_match($pattern_kana, $word) || preg_match($pattern_hira, $word)) {
    return TRUE;
  } else {
    return FALSE;
  }
}

// 空文字・スペースのみ不可
function not_empty_or_space($word)
{
  $pattern_space = '/\A[[:space:]]+\z/u';
  if (preg_match($pattern_space, $word)) {
    return FALSE;
  } else {
    return TRUE;
  }
}

// メールヘッダーインジェクション対策
function is_safe_text($str)
{
  return !preg_match("/[\r\n\0]/", $str);
}

// メールアドレス形式チェック
function is_valid_email($email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false
    && is_safe_text($email);
}

// 電話番号チェック
function is_valid_phone($tel)
{
  return preg_match('/^\d{10,11}$/', $tel) === 1;
}

// バリデーション実行 インクルード先で引数入ってる validate_form($_POST)
function validate_form($data)
{
  $errors = [];

  // 名前
  if (
    !isset($data['name1'])
    || !not_empty_or_space($data['name1'])
    || !checkwords($data['name1'], 20)
    || !letter_check($data['name1'])
    || !is_safe_text($data['name1'])
  ) {
    $errors['name1'] = '名前を正しく入力してください（20字以内、日本語必須）';
  }

  // フリガナ
  if (
    !isset($data['name2'])
    || !not_empty_or_space($data['name2'])
    || !checkwords($data['name2'], 20)
    || !kana($data['name2'])
    || !is_safe_text($data['name2'])
  ) {
    $errors['name2'] = 'フリガナを正しく入力してください（20字以内、カナのみ）';
  }

  // メールアドレス
  if (
    !isset($data['email'])
    || !not_empty_or_space($data['email'])
    || !is_valid_email($data['email'])
  ) {
    $errors['email'] = 'メールアドレスを正しく入力してください';
  }

  // 電話番号
  if (
    !isset($data['telno'])
    || !not_empty_or_space($data['telno'])
    || !is_valid_phone($data['telno'])
  ) {
    $errors['tel'] = '電話番号を正しく入力してください（半角数字のみ）';
  }

  // 住所
  if (
    !isset($data['place'])
    || !not_empty_or_space($data['place'])
    || !checkwords($data['place'], 40)
    || !letter_check($data['place'])
  ) {
    $errors['place'] = '住所を正しく入力してください（40字以内、日本語必須）';
  }

  // ラジオボタン
  if (empty($data['subject']) || !not_empty_or_space($data['subject'])) {
    $errors['subject'] = 'ご用件を選択してください';
  }

  // チェックボックス
  if (empty($data['likeItem']) || !is_array($data['likeItem'])) {
    $errors['checkitem'] = 'チェックボックスを選択してください';
  }

  // セレクトボックス
  if (empty($data['selectbox']) || !not_empty_or_space($data['selectbox'])) {
    $errors['select_box'] = 'セレクトボックスを選択してください';
  }

  // お問合せ内容
  if (
    !isset($data['personal'])
    || !not_empty_or_space($data['personal'])
    || !checkwords($data['personal'], 200)
    || !letter_check($data['personal'])
  ) {
    $errors['ask'] = 'お問合せ内容を正しく入力してください（200字以内、日本語必須）';
  }

  return $errors;
}
