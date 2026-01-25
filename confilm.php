<?php
require_once 'common.php';

// CSRF対策
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
  header('Location: 404.html');
  exit();
}

header('Content-Type: text/html; charset=UTF-8');

// トークンを引き継ぐ（新規生成しない）
$token = html_esc($_POST['csrf_token']);

// フォームデータ取得
$name1 = isset($_POST['name1']) ? html_esc($_POST['name1']) : '';
$name2 = isset($_POST['name2']) ? html_esc($_POST['name2']) : '';
$email = isset($_POST['email']) ? html_esc($_POST['email']) : '';
$tel = isset($_POST['telno']) ? html_esc($_POST['telno']) : '';
$place = isset($_POST['place']) ? html_esc($_POST['place']) : '';
$ask = isset($_POST['personal']) ? html_esc($_POST['personal']) : '';
$subject = isset($_POST['subject']) ? html_esc($_POST['subject']) : '';
$select_box = isset($_POST['selectbox']) ? html_esc($_POST['selectbox']) : '';

// チェックボックス
$likeItem = isset($_POST['likeItem']) && is_array($_POST['likeItem']) ? $_POST['likeItem'] : [];
$item = $likeItem ? implode('、', array_map('html_esc', $likeItem)) : '';

// バリデーション実行
$errors = validate_form($_POST);

// エラーメッセージ生成
$errs = [];
foreach (['name1', 'name2', 'email', 'tel', 'place', 'subject', 'checkitem', 'select_box', 'ask'] as $key) {
  $errs[$key] = isset($errors[$key]) ? '<p class="red">' . html_esc($errors[$key]) . '</p>' : '';
}

// 送信ボタンの表示制御
$send_btn = empty($errors)
  ? '<p class="confi"><input type="submit" value="送信"></p>'
  : '<p class="textCenter red">※エラーを修正してください</p>';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>フォームテンプレート</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <section id="contact">
    <h1>フォームテンプレート</h1>
    <p class="intro">
      現在、確認状態です。<br>ブラウザの戻るアイコンを使わず、下部ボタンにて操作してください。
    </p>
    <ul class="flow">
      <li class="spot">入力</li>
      <li class="spot">確認</li>
      <li class="none">送信</li>
    </ul>

    <form method="post" action="sendmail.php">
      <input type="hidden" name="csrf_token" value="<?= $token; ?>">

      <div class="formbox">
        <label>ご氏名<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $name1; ?></p>
          <?= $errs['name1']; ?>
          <input type="hidden" name="name1" value="<?= $name1; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>フリガナ<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $name2; ?></p>
          <?= $errs['name2']; ?>
          <input type="hidden" name="name2" value="<?= $name2; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>メールアドレス<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $email; ?></p>
          <?= $errs['email']; ?>
          <input type="hidden" name="email" value="<?= $email; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>電話番号<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $tel; ?></p>
          <?= $errs['tel']; ?>
          <input type="hidden" name="telno" value="<?= $tel; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>住所<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $place; ?></p>
          <?= $errs['place']; ?>
          <input type="hidden" name="place" value="<?= $place; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>ご用件<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $subject; ?></p>
          <?= $errs['subject']; ?>
          <input type="hidden" name="subject" value="<?= $subject; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>チェックボックス<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $item; ?></p>
          <?= $errs['checkitem']; ?>
          <input type="hidden" name="likeItem" value="<?= $item; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>セレクトボックス<span class="need">必須</span></label>
        <div class="inputText">
          <p><?= $select_box; ?></p>
          <?= $errs['select_box']; ?>
          <input type="hidden" name="selectbox" value="<?= $select_box; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>お問合せの内容を具体的にご記入ください。<br><span class="need">必須</span></label>
        <div class="inputText">
          <p><?= nl2br($ask); ?></p>
          <?= $errs['ask']; ?>
          <input type="hidden" name="personal" value="<?= $ask; ?>">
        </div>
      </div>

      <?= $send_btn; ?>
    </form>

    <!-- 戻るボタン -->
    <form action="alteration.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= $token; ?>">
      <input type="hidden" name="name1" value="<?= $name1; ?>">
      <input type="hidden" name="name2" value="<?= $name2; ?>">
      <input type="hidden" name="email" value="<?= $email; ?>">
      <input type="hidden" name="telno" value="<?= $tel; ?>">
      <input type="hidden" name="place" value="<?= $place; ?>">
      <input type="hidden" name="subject" value="<?= $subject; ?>">
      <?php if (!empty($likeItem)): ?>
        <?php foreach ($likeItem as $v): ?>
          <input type="hidden" name="likeItem[]" value="<?= html_esc($v); ?>">
        <?php endforeach; ?>
      <?php endif; ?>
      <input type="hidden" name="selectbox" value="<?= $select_box; ?>">
      <input type="hidden" name="personal" value="<?= $ask; ?>">
      <p class="confi"><input type="submit" value="戻る"></p>
    </form>

    <div id="telBottom">
      <p class="textCenter">メールフォームテンプレート</p>
    </div>

  </section>
  <div class="copy">
    <p>Copyright 2026 mugikomugi</p>
  </div>
</body>

</html>