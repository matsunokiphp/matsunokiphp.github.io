<?php
// 保存ファイル
$file = 'data.txt';

// 投稿処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '名無し', ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars($_POST['text'] ?? '', ENT_QUOTES, 'UTF-8');

    if ($text !== '') {
        $post = date('Y-m-d H:i:s') . "\t" . $name . "\t" . $text . "\n";
        file_put_contents($file, $post, FILE_APPEND | LOCK_EX);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// 投稿取得
$posts = [];
if (file_exists($file)) {
    $lines = array_reverse(file($file, FILE_IGNORE_NEW_LINES));
    foreach ($lines as $line) {
        list($time, $name, $text) = explode("\t", $line);
        $posts[] = ['time' => $time, 'name' => $name, 'text' => $text];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ミニ掲示板</title>
<style>
body {
    font-family: sans-serif;
    background: #e6ecf0;
    margin: 0;
}
.container {
    max-width: 600px;
    margin: 0 auto;
    background: #fff;
    padding: 15px;
}
h1 {
    font-size: 20px;
    border-bottom: 2px solid #1da1f2;
    padding-bottom: 5px;
}
form {
    margin-bottom: 20px;
}
input[type="text"], textarea {
    width: 100%;
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}
button {
    background: #1da1f2;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
}
button:hover {
    background: #0d95e8;
}
.post {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}
.name {
    font-weight: bold;
    color: #1da1f2;
}
.time {
    font-size: 12px;
    color: #666;
}
.text {
    margin-top: 5px;
    white-space: pre-wrap;
}
</style>
</head>
<body>
<div class="container">
    <h1>ミニ掲示板（初期Twitter風）</h1>
    <form method="post">
        <input type="text" name="name" placeholder="名前（省略可）">
        <textarea name="text" placeholder="いまどうしてる？" rows="3"></textarea>
        <button type="submit">ツイート</button>
    </form>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="name"><?= $post['name'] ?></div>
            <div class="time"><?= $post['time'] ?></div>
            <div class="text"><?= $post['text'] ?></div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
