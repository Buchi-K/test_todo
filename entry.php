<?php
require("./dbconnect.php");
session_start();

if (!empty($_POST)) {
  /* 入力情報を確認 */
  if ($_POST['email'] === "") {
    $error['email'] = 'blank';
  }
  if ($_POST['password'] === "") {
    $error['password'] = 'blank';
  }
  /* メールアドレスの重複の確認 */
  if (!isset($error)) {
    $member = $db->prepare("SELECT COUNT(*) as cnt FROM members WHERE email=?");
    $member->execute(array(
      $_POST['email']
    ));
  }
  $record = $member->fetch();
  if ($record['cnt'] > 0) {
    $error['email'] = 'duplicate';
  }
  /* エラーがなければcheck.phpへ */
  if (!isset($error)) {
    $_SESSION['join'] = $_POST;
    header('Location:check.php');
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員登録</title>

  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="content">
    <form action="" method="POST">
      <h1>アカウントを作成</h1>
      <p>当サービスをご利用するために、次のフォームに必要事項をご記入してください。</p>
      <br>

      <div class="control">
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name">
      </div>

      <div class="control">
        <label for="email">メールアドレス<span class="required">必須</span></label>
        <input id="email" type="email" name="email">
        <?php if (!empty($error['email']) && $error['email'] === 'blank') : ?>
        <p class="error">＊ メールアドレスを入力してください</p>
        <?php elseif (!empty($_error['email']) && $error['email'] === 'duplicate') : ?>
          <p class="error">＊ このメールアドレスはすでに登録済みです</p>
        <?php endif; ?>

      </div>

      <div class="control">
        <label for="password">パスワード<span class="required">必須</span></label>
        <input id="password" type="password" name="password">
        <?php if (!empty($error['password']) && $error['password'] === 'blank') : ?>
          <p class="error">パスワードを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="control">
        <button type="submit" class="btn">確認する</button>
      </div>
    </form>
  </div>
</body>
</html>