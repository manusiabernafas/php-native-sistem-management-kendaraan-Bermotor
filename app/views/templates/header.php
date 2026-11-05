<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= isset($title) ? $title : APP_NAME ?></title>
    
    <!-- PWA Manifest & Meta -->
    <link rel="manifest" href="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/manifest.json">
    <meta name="theme-color" content="#2ECC71">
    <link rel="icon" href="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/img/icon.svg">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS (Neo-Brutalism) -->
    <link rel="stylesheet" href="<?= str_replace('/public/index.php?url=', '', BASE_URL) ?>/public/css/style.css?v=<?= time() ?>">
</head>
<body class="<?= isset($dark_mode) && $dark_mode ? 'dark-mode' : '' ?>">
    <div class="container">
