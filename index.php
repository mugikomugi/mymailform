<?php
require_once(dirname(__FILE__) . '/common.php');

$token = generateCSRFToken();
header('Content-Type: text/html; charset=UTF-8');
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
      送信フォームのテストページです。入力、確認、送信の3ステップで構成されています。<br>ブラウザの戻るアイコンを使わず、下部ボタンにて操作してください。
    </p>
    <ul class="flow">
      <li class="spot">入力</li>
      <li class="none">確認</li>
      <li class="none">送信</li>
    </ul>

    <form method="post" action="confilm.php">
      <input type="hidden" name="csrf_token" value="<?= html_esc($token); ?>">

      <div class="formbox">
        <label for="name1">ご氏名<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="name1" id="name1">
        </div>
      </div>

      <div class="formbox">
        <label for="name2">フリガナ<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="name2" id="name2">
        </div>
      </div>

      <div class="formbox">
        <label for="email">メールアドレス<span class="need">必須</span></label>
        <div class="inputText">
          <input type="email" name="email" id="email">
          <p class="text12">※半角英数でアドレスを入力してください。</p>
        </div>
      </div>

      <div class="formbox">
        <label for="telno">電話番号<span class="need">必須</span></label>
        <div class="inputText">
          <input type="tel" id="telno" name="telno" placeholder="0000000000">
          <p class="text12">※ハイフン無しで入力して下さい。</p>
        </div>
      </div>

      <div class="formbox">
        <label for="place">住所<span class="need">必須</span></label>
        <div class="inputText">
          <input type="text" name="place" id="place">
        </div>
      </div>

      <div class="formbox">
        <label>ご用件<span class="need">必須</span></label>
        <div class="inputText">
          <p class="radio">
            <input type="radio" value="イベント・キャンペーンのご相談・お見積" name="subject">イベント・キャンペーンのご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="サンプリングのご相談・お見積" name="subject">サンプリングのご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="スタッフ派遣のご相談・お見積" name="subject">スタッフ派遣のご相談・お見積
          </p>
          <p class="radio">
            <input type="radio" value="業務提携に関して" name="subject">業務提携に関して
          </p>
          <p class="radio">
            <input type="radio" value="その他" name="subject">その他
          </p>
        </div>
      </div>

      <div class="formbox">
        <label>チェックボックス<span class="need">必須</span></label>
        <div class="inputText">
          <p class="radio">
            <input type="checkbox" name="likeItem[]" value="うに">うに
            <input type="checkbox" name="likeItem[]" value="ほこ">ほこ
            <input type="checkbox" name="likeItem[]" value="なまこ">なまこ
          </p>
        </div>
      </div>

      <div class="formbox">
        <label>セレクトボックス<span class="need">必須</span></label>
        <div class="inputText">
          <select name="selectbox">
            <option value="">選択してください</option>
            <option value="うに">うに</option>
            <option value="ほこ">ほこ</option>
            <option value="なまこ">なまこ</option>
          </select>
        </div>
      </div>

      <div class="formbox">
        <label for="personal">お問合せの内容を具体的にご記入ください。<br><span class="need">必須</span></label>
        <div class="inputText">
          <textarea rows="8" id="personal" name="personal"></textarea>
        </div>
      </div>

      <p class="confi"><input type="submit" value="確認画面へ"></p>
    </form>

    <div id="telBottom">
      <h3 class="textCenter">システムについて</h3>
      <p class="note">このフォームは、PHPを使用したシンプルなメールフォームテンプレートです。<br>
        基本的なバリデーションとCSRF対策が実装されています。修正記入は別ページに飛ばしています。</p>
    </div>

  </section>
  <div class="copy">
    <p>Copyright 2026 mugikomugi</p>
  </div>
</body>

</html>