<?php
//リファラーチェック、指定ページ以外からきた場合は404へ
$host = $_SERVER['HTTP_REFERER'];
$str02 = parse_url($host);
//var_dump($str02['path']);
if(!stristr($str02['path'], '移動前のページ')){
	header('Location: 404.html');
    exit();
}

//サーバのPHP Ver7.3.8より前はエラー表示

header('Content-Type: text/html; charset=UTF-8');
//XCC
function html_esc($word){
    return htmlspecialchars($word,ENT_QUOTES,'UTF-8');
}
//テキストの文字数制限
function checkwords($word,$length){
    if(mb_strlen($word) === 0 || mb_strlen($word) > $length){
        return FALSE;
    } else {
        return TRUE;
    }
}

//もっと簡易版'/\A([a-z0-9_\-\+\/\?]+)@([a-z0-9\-]+\.)+[a-z]{2,6}\z/i'
$pattern = '/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/';
//電話番号
//ハイフン入り'^0([0-9]-[0-9]{4}|[0-9]{2}-[0-9]{3}|[0-9]{3}-[0-9]{2}|[0-9]{4}-[0-9])-[0-9]{4}$'
$numcheck = '/^[0-9]+$/';
$errs = [];

//必須項目じゃなかったら初期値を表示
if(isset($_POST['name1'])){
    $name1 = html_esc($_POST['name1']);
} else {
    $name1 = '';
}
if(isset($_POST['name2'])){
    $name2 = html_esc($_POST['name2']);
} else {
    $name2 = '';
}
if(isset($_POST['email'])){
    $email = html_esc($_POST['email']);
} else {
    $email = '';
}
if(isset($_POST['telno'])){
    $tel = html_esc($_POST['telno']);
} else {
    $tel = '';
}
if(isset($_POST['place'])){
    $place = html_esc($_POST['place']);
} else {
    $place = '';
}
if(isset($_POST['personal'])){
    $ask = html_esc($_POST['personal']);
} else {
    $ask = '';
}
//チェックボックスとラジオボタンとセレクトボックスはエラーチェックと一緒のif文

//もう一つのcheckbox書き出し
/*$Item = '';
for($i = 0; $i < count($likeItem); $i++){
    $Item .= $likeItem[$i];
    if($i !=count($likeItem)-1){
        $Item .='、';
    }
}*/
//var_dump(implode('と',$likeItem));

//エラーチェック
if(checkwords($name1,20)){
    $errs['name1'] = '';
} else {
    $errs['name1'] = '<p class="red">名前の入力は必須で20字以内のバリデーションをかけてます。</p>';
}
if(checkwords($name2,20)){
    $errs['name2'] = '';
} else {
    $errs['name2'] = '<p class="red">名前（フリガナ）の入力は必須で20字以内のバリデーションをかけてます。</p>';
}
if(preg_match($pattern,$email)){
    $errs['email'] = '';
} else {
    $errs['email'] = '<p class="red">メールアドレス形式ではありません、入力は必須でバリデーション
をかけてます。</p>';
}
if(preg_match($numcheck,$tel)){
    $errs['tel'] = '';
} else {
    $errs['tel'] = '<p class="red">半角数字で入力してください。</p>';
}
if(checkwords($place,40)){
    $errs['place'] = '';
} else {
    $errs['place'] = '<p class="red">入力は必須で40字以内のバリデーションをかけてます。</p>';
}
//チェックボックス
if(isset($_POST['likeItem'])){
    $errs['checkitem'] = '';
    $item = implode('、',$_POST['likeItem']);
    $item = html_esc($item);
} else {
    $errs['checkitem'] = '<p class="red">選択されていません。</p>';
    $item = '';
}
//ラジオボタン
if(isset($_POST['subject'])){
    $errs['subject'] = '';
    $subject = $_POST['subject'];
    $subject = html_esc($subject);
} else {
    $errs['subject'] = '<p class="red">選択されていません。</p>';
    $subject = '';
}
//セレクトボックス
if($_POST['selectbox'] != ''){
    $errs['select_box'] = '';
    $select_box = html_esc($_POST['selectbox']);
} else {
    $errs['select_box'] = '<p class="red">選択されていません。</p>';
    $select_box = '';
}

if(checkwords($ask,200)){
    $errs['ask'] = '';
} else {
    $errs['ask'] = '<p class="red">入力は必須で200字以内のバリデーションをかけてます。</p>';
}
//送信ボタン表示
if(checkwords($name1,20) && checkwords($name2,20) && preg_match($pattern,$email) && preg_match($numcheck,$tel) && checkwords($place,40) && checkwords($ask,200) && $item !=FALSE && $subject !=FALSE && $select_box !=FALSE){
    $send_btn = '<p class="confi"><input type="submit" value="送信"></p>';
} else {
    $send_btn = '<p class="textCenter red">※エラーが無かったら送信ボタン表示</p>';
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>フォームテンプレート</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body>
<section id="contact">
    <h1>フォームテンプレート</h1>
    <form method="post" action="sendmail.php">
        <div class="formbox">
            <label>ご氏名<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $name1; ?></p>
                <?php echo $errs['name1']; ?>
                <input type="hidden" name="name1" value="<?php echo $name1; ?>">
            </div>
        </div>
        <div class="formbox">
            <label>名前（フリガナ）<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $name2; ?></p>
                <?php echo $errs['name2']; ?>
                <input type="hidden" name="name2" value="<?php echo $name2; ?>">
            </div> 
        </div>
        <div class="formbox">
            <label>メールアドレス<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $email; ?></p>
                <?php echo $errs['email']; ?>
                <input type="hidden" name="email" value="<?php echo $email; ?>">
            </div> 
        </div>
        <div class="formbox">
            <label>電話番号<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $tel; ?></p>
                <?php echo $errs['tel']; ?>
                <input type="hidden" name="telno" value="<?php echo $tel; ?>">
            </div> 
        </div>
        <div class="formbox">
            <label>住所<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $place; ?></p>
                <?php echo $errs['place']; ?>
                <input type="hidden" name="place" value="<?php echo $place; ?>">
            </div> 
        </div>
        <div class="formbox">
            <label>ご用件<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $subject; ?></p>
                <?php echo $errs['subject']; ?>
                <input type="hidden" value="<?php echo $subject; ?>" name="subject">
            </div> 
        </div>
        <div class="formbox">
            <label>チェックボックス<span class="need">必須</span></label>
            <div class="inputText">
                <p><?php echo $item; ?></p>
                <?php echo $errs['checkitem']; ?>
                <input type="hidden" value="<?php echo $item; ?>" name="likeItem">
            </div>
        </div>

        <div class="formbox">
            <label>セレクトボックス<span class="need">必須</span></label>
            <div class="inputText">
            <p><?php echo $select_box; ?></p>
                <?php echo $errs['select_box']; ?>
                <input type="hidden" value="<?php echo $select_box; ?>" name="selectbox">
            </div>
        </div>

        <div class="formbox">
            <label for="personal">お問合せの内容を具体的に
ご記入ください。<br><span class="need">必須</span></label>
           <div class="inputText">
                <p><?php echo $ask; ?></p>
                <?php echo $errs['ask']; ?>
                <input type="hidden" value="<?php echo $ask; ?>" name="personal">
            </div> 
        </div>
        <!--送信ボタン-->
        <?php echo $send_btn; ?>
        <!-- 戻るボタン -->
        <p class="confi"><input type="bottun" onclick="history.back()" value="戻る"></p>
    </form>

    <div id="telBottom">
        <h3 class="textCenter">お電話でのお問合せも受け付けております。</h3>
        <p><a href="#">03-000-0000</a></p>
        <p><span class="sm">※タップすると電話できます。</span>受付時間 9:00 - 18:00 （ 土・日・祝日除く）</p>
    </div>

</section><!--id="telBottom" -->
</body>
</html>