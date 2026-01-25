<?php
require_once(dirname(__FILE__) . '/common.php');

// CSRF対策
if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
  header('Location: 404.html');
  exit();
}

header('Content-Type: text/html; charset=UTF-8');

// トークンを引き継ぐ
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
$likeItem = isset($_POST['likeItem']) && is_array($_POST['likeItem'])
  ? array_map('html_esc', $_POST['likeItem'])
  : [];
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
      内容を修正してください。<br>ブラウザの戻るアイコンを使わず、下部ボタンにて操作してください。
    </p>
    <ul class="flow">
      <li class="spot">入力</li>
      <li class="spot">確認</li>
      <li class="none">送信</li>
    </ul>

    <form method="post" action="confilm.php">
      <input type="hidden" name="csrf_token" value="<?= $token; ?>">

      <div class="formbox">
        <label for="name1">ご氏名<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="name1" id="name1" value="<?= $name1; ?>">
        </div>
      </div>

      <div class="formbox">
        <label for="name2">フリガナ<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="name2" id="name2" value="<?= $name2; ?>">
        </div>
      </div>

      <div class="formbox">
        <label for="email">メールアドレス<span class="need">必須</span></label>
        <div class="inputText">
          <input type="email" name="email" id="email" value="<?= $email; ?>">
          <p class="text12">※半角英数でアドレスを入力してください。</p>
        </div>
      </div>

      <div class="formbox">
        <label for="telno">電話番号<span class="need">必須</span></label>
        <div class="inputText">
          <input type="tel" id="telno" name="telno" value="<?= $tel; ?>">
          <p class="text12">※ハイフン無しで入力して下さい。</p>
        </div>
      </div>

      <div class="formbox">
        <label for="place">住所<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="place" id="place" value="<?= $place; ?>">
        </div>
      </div>

      <div class="formbox">
        <label>ご用件<span class="need">必須</span></label>
        <div class="inputText">
          <p class="radio">
            <input type="radio" value="イベント・キャンペーンのご相談・お見積" name="subject" <?= $subject === 'イベント・キャンペーンのご相談・お見積' ? 'checked' : '' ?>>イベント・キャンペーンのご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="サンプリングのご相談・お見積" name="subject" <?= $subject === 'サンプリングのご相談・お見積' ? 'checked' : '' ?>>サンプリングのご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="スタッフ派遣のご相談・お見積" name="subject" <?= $subject === 'スタッフ派遣のご相談・お見積' ? 'checked' : '' ?>>スタッフ派遣のご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="業務提携に関して" name="subject" <?= $subject === '業務提携に関して' ? 'checked' : '' ?>>業務提携に関して
          </p>
          <p class="radio">
            <input type="radio" value="その他" name="subject" <?= $subject === 'その他' ? 'checked' : '' ?>>その他
          </p>
        </div>
      </div>

      <div class="formbox">
        <label>チェックボックス<span class="need">必須</span></label>
        <div class="inputText">
          <p class="radio">
            <input type="checkbox" name="likeItem[]" value="うに" <?= in_array('うに', $likeItem) ? 'checked' : '' ?>>うに
            <input type="checkbox" name="likeItem[]" value="ほこ" <?= in_array('ほこ', $likeItem) ? 'checked' : '' ?>>ほこ
            <input type="checkbox" name="likeItem[]" value="なまこ" <?= in_array('なまこ', $likeItem) ? 'checked' : '' ?>>なまこ
          </p>
        </div>
      </div>

      <div class="formbox">
        <label>セレクトボックス<span class="need">必須</span></label>
        <div class="inputText">
          <select name="selectbox">
            <option value="">選択してください</option>
            <option value="うに" <?= $select_box === 'うに' ? 'selected' : '' ?>>うに</option>
            <option value="ほこ" <?= $select_box === 'ほこ' ? 'selected' : '' ?>>ほこ</option>
            <option value="なまこ" <?= $select_box === 'なまこ' ? 'selected' : '' ?>>なまこ</option>
          </select>
        </div>
      </div>

      <div class="formbox">
        <label for="personal">お問合せの内容を具体的にご記入ください。<br><span class="need">必須</span></label>
        <div class="inputText">
          <textarea rows="8" id="personal" name="personal"><?= $ask; ?></textarea>
        </div>
      </div>

      <p class="confi"><input type="submit" value="確認画面へ"></p>
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