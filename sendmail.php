<?php
require_once(dirname(__FILE__) . '/common.php');
require_once(dirname(__FILE__) . '/config.php');

// Composerのオートローダーを読み込み
require(dirname(__FILE__) . '/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// CSRF対策
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
  header('Location: 404.html');
  exit();
}

header('Content-Type: text/html; charset=UTF-8');

// フォームデータ取得
$name1 = isset($_POST['name1']) ? html_esc($_POST['name1']) : '';
$name2 = isset($_POST['name2']) ? html_esc($_POST['name2']) : '';
$email = isset($_POST['email']) ? html_esc($_POST['email']) : '';
$tel = isset($_POST['telno']) ? html_esc($_POST['telno']) : '';
$place = isset($_POST['place']) ? html_esc($_POST['place']) : '';
$ask = isset($_POST['personal']) ? html_esc($_POST['personal']) : '';
$subject = isset($_POST['subject']) ? html_esc($_POST['subject']) : '';
$select_box = isset($_POST['selectbox']) ? html_esc($_POST['selectbox']) : '';
$item = isset($_POST['likeItem']) ? html_esc($_POST['likeItem']) : '';


// バリデーション実行（念のため再チェック）
$_POST['likeItem'] = $item ? explode('、', $item) : [];
$errors = validate_form($_POST);

// バリデーション再実行
$errors = validate_form($_POST);
if (!empty($errors)) {
  // デバッグ用：エラー内容を表示
  /*echo '<pre>';
  print_r($errors);
  echo '</pre>'; */
  header('Location: 404.html');
  exit();
}

$mail = new PHPMailer(true);

try {
  // --- サーバ設定 定数を使用 ---
  //エックスサーバーでは、SMTP認証に使用するメールアドレス(Username)と送信元(setFrom)は同じにする必要がある
  $mail->SMTPDebug = 0; // デバッグ出力無効 有効は2
  $mail->isSMTP();
  $mail->Host       = SMTP_HOST;
  $mail->SMTPAuth   = true;
  $mail->Username   = SMTP_USERNAME;
  $mail->Password   = SMTP_PASSWORD;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = SMTP_PORT;
  $mail->CharSet    = 'UTF-8';

  // --- 送信者・宛先設定 ---
  $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
  $mail->addAddress(ADMIN_EMAIL);

  // 複数の管理者に送信 送信先のメールアドレスを追加
  //$mail->addAddress('admin1@company.co.jp');
  //$mail->addAddress('admin2@company.co.jp');

  $mail->addReplyTo($email, $name1); // 返信先をユーザーのアドレスに

  // --- 管理者宛メール本文作成 ---
  $datetime = date('Y年m月d日 H:i:s');
  $mail->Subject = '【管理者宛】お問い合わせが入りました';
  $mail->Body    = "送信日時: {$datetime}\n\n"
    . "【名前】{$name1}\n"
    . "【メールアドレス】{$email}\n"
    . "【電話番号】{$tel}\n"
    . "【住所】{$place}\n"
    . "【ご用件】{$subject}\n"
    . "【【チェックボックス】{$item}\n"
    . "【セレクトボックス】{$select_box}\n"
    . "【お問合せ内容】\n{$ask}";

  $mail->send();

  // --- 自動返信メール送信 ---
  $mail->clearAddresses(); // 送信先をクリア
  $mail->addAddress($email);
  $mail->Subject = 'お問い合わせを受け付けました【自動返信】';
  $mail->Body    = "{$name1} 様\n\nお問い合わせありがとうございます。\n\n"
    . "【名前】{$name1}\n"
    . "【メールアドレス】{$email}\n"
    . "【電話番号】{$tel}\n"
    . "【住所】{$place}\n"
    . "【ご用件】{$subject}\n"
    . "【【チェックボックス】{$item}\n"
    . "【セレクトボックス】{$select_box}\n"
    . "【お問合せ内容】\n{$ask}";

  $mail->send();

  // 成功時
  unset($_SESSION['csrf_token']);
  header('Location: thanks.html');
  exit();
} catch (Exception $e) {
  // エラーログを記録して404へ
  error_log("Mailer Error: {$mail->ErrorInfo}");
  header('Location: 404.html');
  exit();
}
