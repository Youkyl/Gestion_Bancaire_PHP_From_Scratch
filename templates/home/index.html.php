<?php
   // dd($comptes)
   //dd($transac)
   //dd($comptesBloq)
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('home.title') ?> | <?= t('app.admin_title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/styles.css">
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
                <a href="<?php echo WEB_ROOT; ?>/home/index" class="active">
                    <i class="fa-solid fa-chart-line"></i> <?= t('menu.dashboard') ?>
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create">
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
        <div class="header">
            <h1><?= t('home.title') ?></h1>
            <p><?= t('home.subtitle') ?></p>
        </div>

        <!-- STATS -->
        <section class="stats">
        <?php if (empty($comptes)): ?>

            <!-- EMPTY STATE -->
            <div class="empty">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <h3><?= t('home.empty_accounts_title') ?></h3>
                <p><?= t('home.empty_accounts_sub') ?></p>
            </div>

            <?php else: ?>
            
                <div class="stat-card">
                    <div class="stat-header">
                        <span><?= t('home.total_balance') ?></span>
                        <div class="stat-icon icon-blue">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <h2><?= number_format($totalSolde, 2, ',', ' ') ?> FCFA</h2>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span><?= t('home.total_accounts') ?></span>
                        <div class="stat-icon icon-green">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <h2><?= t('home.accounts_found', ['count' => count($comptes)]) ?></h2>
                </div>
                
                <?php if (empty($transac)): ?>

                <!-- EMPTY STATE -->
                <div class="empty">
                        <div class="stat-card">
                            <div class="stat-header">
                                <span><?= t('home.transactions') ?></span>
                                <div class="stat-icon icon-purple">
                                    <i class="fa-solid fa-right-left"></i>
                                </div>
                            </div>
                            <p></p>
                            <!--<i class="fa-solid fa-triangle-exclamation"></i>-->
                            <h5><?= t('home.no_transactions') ?></h5>
                            <p><?= t('home.create_transaction') ?></p>
                            <h2></h2>
                        </div>
                </div>

                <?php else: ?> 

                <div class="stat-card">
                    <div class="stat-header">
                        <span><?= t('home.transactions') ?></span>
                        <div class="stat-icon icon-purple">
                            <i class="fa-solid fa-right-left"></i>
                        </div>
                    </div>
                    <h2><?php echo count($transac)?></h2>
                </div>
                <?php endif  ?>

                <div class="stat-card">
                    <div class="stat-header">
                        <span><?= t('home.blocked_accounts') ?></span>
                        <div class="stat-icon icon-orange">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>
                    <h2><?= $comptesBloq ?></h2>
                </div>
                
 

        </section>

        <!-- RÉPARTITION -->
        <section class="section">
            <h3><?= t('home.accounts_split') ?></h3>

            <div class="accounts">
                <div class="account-card savings">
                    <div class="account-card-header">
                        <h4><?= t('home.savings_accounts') ?></h4>
                        <div class="account-icon">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </div>
                    </div>
                    <h2><?= $totalEpargne?></h2>
                    <p><?= t('home.blocked_count', ['count' => $totalEpargne]) ?></p>
                </div>

                <div class="account-card checking">
                    <div class="account-card-header">
                        <h4><?= t('home.checking_accounts') ?></h4>
                        <div class="account-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                    </div>
                    <h2><?= $totalCheque?></h2>
                    <p><?= t('home.checking_fee') ?></p>
                </div>
            </div>
        </section>  
        <?php endif  ?>
    </main>

</div>

</body>
</html>
