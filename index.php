<?php
require_once 'cms/settings.php';

$favicon_path = $cms_settings['website_info']['favicon_path'];
?>
<!DOCTYPE html>
<html lang="<?= $cms_settings['seo_settings']['site_language']; ?>">
<head>
    <title><?= $cms_settings['website_info']['index_title']; ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $cms_settings['website_info']['index_description']; ?>">
    <link rel="icon" href="cms/<?= $favicon_path; ?>" type="image/x-icon">
    <link rel="stylesheet" href="/themes/<?= $cms_settings['site_settings']['site_theme']; ?>/style.css">
</head>
<body>
    <h1><?= $cms_settings['website_info']['index_title']; ?></h1>
    <p><?= $cms_settings['seo_settings']['site_author']; ?></p>
    <h2>Текущий язык:</h2>
    <p><?= $cms_settings['seo_settings']['site_language']; ?></p>
    <script src="/themes/<?= $cms_settings['site_settings']['site_theme']; ?>/script.js"></script>

</body>
</html>
