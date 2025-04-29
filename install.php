<?php
function load_settings($file) {
    return parse_ini_file($file, true);
}
function save_settings($file, $settings) {
    $content = "";
    foreach ($settings as $section => $values) {
        $content .= "[$section]\n";
        foreach ($values as $key => $value) {
            $content .= "$key = \"$value\"\n";
        }
    }
    file_put_contents($file, $content);
}

$config_file = 'cms-settings/config.ini';
$install_file = 'cms/install.php';
$htaccess_path = '../.htaccess';

$settings = load_settings($config_file);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обновляем настройки
    $settings['website_info']['index_title'] = $_POST['index_title'];
    $settings['website_info']['index_description'] = $_POST['index_description'];
    $settings['website_info']['favicon_path'] = $_POST['favicon_path'];
    $settings['website_info']['admin_email'] = $_POST['admin_email'];
    $settings['website_info']['admin_name'] = $_POST['admin_name'];
    $settings['website_info']['admin_password'] = $_POST['admin_password'];
    $settings['site_settings']['site_domain'] = $_POST['site_domain'];

    save_settings($config_file, $settings);

    // Удаляем install.php
    $install_path = __FILE__;
if (file_exists($install_path)) {
    unlink($install_path);
}

    // Меняем содержимое .htaccess на запрет индексации
    $htaccess_content = "# Это файл .htaccess. Он позволяет управлять настройками сайта или отдельной папки.\n";
    file_put_contents($htaccess_path, $htaccess_content);

    // Определяем домен для редиректа
    $domain = trim($settings['site_settings']['site_domain']);
    if (empty($domain)) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $domain = "$protocol://$host";
    }

    // Обновляем конфиг, чтобы запомнить домен
    $settings['site_settings']['site_domain'] = $domain;
    save_settings($config_file, $settings);

    // Перенаправляем на страницу логина
    header("Location: $domain/cms/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8" />
<title>Настройки сайта</title>
<style>
/* стили без изменений */
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    margin: 50px;
    padding: 20px;
    background-color: #fff;
    color: #444;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
h1 {
    text-align: center;
    font-size: 24px;
    color: #333;
}
label {
    display: block;
    margin: 10px 0 5px;
}
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccd9e4;
    border-radius: 4px;
    background-color: #fff;
    color: rgb(54, 59, 64);
}
input[type="text"]:focus, input[type="password"]:focus {
    border: 1px solid #0071a1; 
    box-shadow: 0 0 0 2px rgba(0, 113, 161, 0.5);
    outline: none;
}
input[type="checkbox"] {
    margin-left: 15px;
    margin-top: 12px;
}
.checkbox-label {
    display: flex; 
    align-items: center; 
}
.button-container {
    margin-top: 20px;
}
input[type="submit"] {
    background-color: #f3f5f6;
    color: #0071a1;
    border: 1px solid #0071a1;
    border-radius: 4px;
    padding: 10px 15px;
    cursor: pointer;
}
input[type="submit"]:hover {
    background: #f1f1f1;
    border-color: #016087;
    color: #016087;
}
.welcome {
    color: #667;
    border-bottom: 1px solid #ddd;
}
</style>
</head>
<body>
<div class="welcome">
<h1>Добро пожаловать!</h1>
</div><br>
<form method="post" action="">
<label for="index_title">Заголовок сайта:</label>
<input type="text" id="index_title" name="index_title" value="<?= htmlspecialchars($settings['website_info']['index_title']); ?>" required>

<label for="index_description">Описание сайта:</label>
<input type="text" id="index_description" name="index_description" value="<?= htmlspecialchars($settings['website_info']['index_description']); ?>" required>

<label for="admin_email">Email администратора:</label>
<input type="text" id="admin_email" name="admin_email" value="<?= htmlspecialchars($settings['website_info']['admin_email']); ?>" required>

<label for="admin_name">Имя администратора:</label>
<input type="text" id="admin_name" name="admin_name" value="<?= htmlspecialchars($settings['website_info']['admin_name']); ?>" required>

<label for="admin_password">Пароль администратора:</label>
<input type="password" id="admin_password" name="admin_password" value="<?= htmlspecialchars($settings['website_info']['admin_password']); ?>" required>

<div class="checkbox-label">
<label for="disable_indexing">Отключить сайт от индексации:</label>
<input type="checkbox" id="disable_indexing" name="disable_indexing" value="1" <?= ($settings['website_info']['disable_indexing'] ?? '0') === '1' ? 'checked' : ''; ?>>
</div>

<div class="button-container">
<input type="submit" value="Сохранить настройки">
</div>
</form>
</body>
</html>
