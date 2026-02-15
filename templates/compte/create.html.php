<!DOCTYPE html>
<html lang="<?= htmlspecialchars(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('compte.create.page_title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/CreateAcc.css"> 
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
                <a href="<?php echo WEB_ROOT; ?>/home/index" >
                    <i class="fa-solid fa-chart-line"></i> <?= t('menu.dashboard') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create" class="active">
                    <i class="fa-solid fa-user-plus"></i> <?= t('menu.create_account') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/index">
                    <i class="fa-solid fa-users"></i> <?= t('menu.list_accounts') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/transaction/create">
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
            <h1><?= t('compte.create.title') ?></h1>
            <p><?= t('compte.create.subtitle') ?></p>
        </div>

        <div class="card">
            <div class="card-title">
                <div class="icon"><i class="fa-solid fa-plus"></i></div>
                <h3><?= t('compte.create.card_title') ?></h3>
            </div>

            <form method="POST" action="<?php echo WEB_ROOT ?>/compte/store">
                <div class="form-group">
                    <label><?= t('compte.create.holder_name') ?></label>
                    <input type="text" name="titulaire" placeholder="<?= t('compte.create.holder_placeholder') ?>" required>
                </div>

                <div class="form-group">
                    <label><?= t('compte.create.account_type') ?></label>
                    <select name="type" id="typeCompte" required>
                        <option value=""><?= t('compte.create.select_placeholder') ?></option>
                        <option value="EPARGNE"><?= t('compte.create.savings') ?></option>
                        <option value="CHEQUE"><?= t('compte.create.checking') ?></option>
                    </select>
                </div>

                <div class="alert" id="alertCheque">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?= t('compte.create.checking_alert') ?></span>
                </div>

                <div id="blocageEpargne" class="form-group">
                    <div class="mb-3">
                        <label class="form-label"><?= t('compte.create.block_duration') ?></label>
                        <input type="number" class="form-control" value="12" min="1" name="dureeBlocage">
                    </div>

                    <small class="text-muted d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle"></i>
                        <?= t('compte.create.block_info') ?>
                    </small>
                </div>

                <div class="form-group">
                    <label><?= t('compte.create.initial_balance') ?></label>
                    <input type="number" name="solde" placeholder="0.00" min="0" required>
                </div>

                <button class="btn-submit">
                    <i class="fa-solid fa-building-columns"></i>
                    <?= t('compte.create.submit') ?>
                </button>
            </form>
        </div>

    </main>

</div>

<!-- JS léger -->
<script>
     <?php include_once __DIR__ . '/../../public/js/CreateAcc.js'; ?>
</script>

</body>
</html>