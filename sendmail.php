<?php
//リファラーチェック
$host = $_SERVER['HTTP_REFERER'];
$str02 = parse_url($host);
if(!stristr($str02['path'], '移動前のページ')){
	header('Location: 404.html');
    exit();
}

header('Content-Type: text/html; charset=UTF-8');
//XCC
function html_esc($word){
  return htmlspecialchars($word,ENT_QUOTES,'UTF-8');
}

$name1 = html_esc($_POST['name1']);
$name2 = html_esc($_POST['name2']);
$email = html_esc($_POST['email']);
$tel = html_esc($_POST['telno']);
$place = html_esc($_POST['place']);
$ask = html_esc($_POST['personal']);
$item = html_esc($_POST['likeItem']);
$subject = html_esc($_POST['subject']);
$select_box = html_esc($_POST['selectbox']);

//自分のメール
$mailtext = <<<EOM
このメールは自動返信です。
送信内容は以下の通りです。
【名前】{$name1}
【フリガナ】{$name2}
【メールアドレス】{$email}
【電話番号】{$tel}
【住所】{$place}
【チェックボックステスト】{$subject}
【好きなもの】{$item}
【セレクトボックス】{$select_box}
【コメント内容】{$ask}
EOM;

//受け付け自動返信
$thankstext = <<<EOM
{$name1}様
このメールは自動返信です。
送信内容を受け付けました。
EOM;

//headerに日本語を入れるにはmb_encode_mimeheader（）
$from_name = mb_encode_mimeheader('PHPテストサイト');
// 送信元メールアドレス
$from_mail = 'no-repleyメールアドレス';
$to_member = 'メールアドレス1,メールアドレス2';//複数はカンマで区切る

//迷惑メール対策
//お手軽にSPFレコードに登録されているFQDNのメールアドレスをReturn-Pathとして強制的に設定
//-fオプション（エンベローブ・フロム・アドレス設定オプション）のパラメータ
//https://qiita.com/ka215/items/e5d21fe91a30fa968a2a
$add_params = '-f'. $from_mail;

//お問い合わせ日時を日本時間
date_default_timezone_set('Asia/Tokyo');

//自分へのメール文
$title = 'PHP開発室よりメールが入りました';

// 送信者情報の設定
$headers = '';
$headers .= 'MIME-Version: 1.0 \r\n';
$headers .= 'Content-Transfer-Encoding: 7bit\r\n';
$headers .= 'Content-Type: text/plain; charset=ISO-2022-JP\r\n';
$headers .= 'Return-Path:' . $from_mail . '\r\n';
$headers .= 'Organization: ' . $from_name . ' \r\n';
$headers .='From: ' . $from_name . '<' . $from_mail . '>\n';

$mailtext = html_entity_decode($mailtext,ENT_QUOTES,'UTF-8');
$thankstext = html_entity_decode($thankstext,ENT_QUOTES,'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');

//自分へのメール設定
$title = 'PHP開発室よりメールが入りました';
//第5引数にReturn-PathアドレスをPHP側から強制化
mb_send_mail($to_member,$title,$mailtext,$headers,$add_params);

//お客様への返信メール設定
$title='フォーム送信テストで送信者への自動返信です';
mb_send_mail($email,$title,$thankstext,$headers,$add_params);
//送信できたらthanksページへとばす
if(mb_send_mail($email,$title,$thankstext,$headers,$add_params) == TRUE){
  header('Location: thanks.html');
}

