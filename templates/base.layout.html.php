<!DOCTYPE html>
<html lang="<?= htmlspecialchars(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('app.title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


</head>
<body>
    <?php echo $contentForView; ?>
    <script src="<?= JS_ROOT ?>/mobile-menu.js"></script>
</body>
</html>