<?php
   // dd($comptes)
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('transaction.title') ?> | <?= t('app.admin_title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/indexTransac.css">
</head>

<body>

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <h2><?= t('app.admin_title') ?></h2>
                <p><?= t('app.admin_space') ?></p>
                <div class="lang-switch">
                    <a href="<?= lang_switch_url('fr') ?>">FR</a>
                    <span>|</span>
                    <a href="<?= lang_switch_url('en') ?>">EN</a>
                </div>
            </div>

            <nav class="menu">
                <a href="<?php echo WEB_ROOT; ?>/home/index">
                    <i class="fa-solid fa-chart-line"></i> <?= t('menu.dashboard') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create">
                    <i class="fa-solid fa-user-plus"></i> <?= t('menu.create_account') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/index">
                    <i class="fa-solid fa-users"></i> <?= t('menu.list_accounts') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/transaction/create" class="active">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i> <?= t('menu.transactions') ?>
                </a>
            </nav>
        </div>

        <div class="logout">
            <a href="#">
                <!-- <i class="fa-solid fa-right-from-bracket"></i> Déconnexion -->
            </a>
        </div>
    </aside>

<!-- MAIN -->
<main class="main">

<div class="page-header">
            <h1><?= t('transaction.title') ?></h1>
            <p><?= t('transaction.subtitle') ?></p>
        </div>

        <div class="tabs">
            <a href="<?php echo WEB_ROOT; ?>/transaction/create">
                <div class="tab">
                    <?= t('transaction.tab_add') ?>
                </div>
            </a>
            <a href="<?php echo WEB_ROOT; ?>/transaction/index">
                <div class="tab active">
                    <?= t('transaction.tab_list') ?>
                </div>
            </a>
        </div>

        <?php if (empty($comptes)): ?>

            <!-- ÉTAT 1 : AUCUN COMPTE -->
            <div class="empty">
                <i class="fa-solid fa-list"></i>
                <h3><?= t('transaction.empty_accounts') ?></h3>
            </div>

        <?php else: ?>

            <div class="form-group">
                
                <label><?= t('transaction.search_account') ?></label>
                <div class="autocomplete-container">
                    <input type="text" 
                        id="compte-search" 
                        placeholder="<?= t('transaction.search_placeholder') ?>" 
                        value="<?= $numeroDeCompte ?? '' ?>"
                        autocomplete="off">
                    
                    <div id="autocomplete-list" class="autocomplete-items"></div>
                </div>



                <script>
                    <?php include_once __DIR__ . '/../../public/js/indexTransac.js'; ?>
                </script>

            </div>

        <?php endif; ?>

    </div>

</main>

</div>
</body>
</html>