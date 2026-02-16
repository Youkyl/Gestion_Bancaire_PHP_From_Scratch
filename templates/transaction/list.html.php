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
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/ListTransac.css">
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
        <br>

            <?php if (isset($comptes)): ?>

                <div class="info">
                    <div>
                        <small><?= t('common.account_holder') ?></small><br>
                        <strong>John Doe</strong>
                    </div>
                    <div>
                        <small><?= t('common.current_balance') ?></small><br>
                        <strong><?= number_format($comptes->getSolde(),2,',',' ') ?> FCFA</strong>
                    </div>
                </div>

                <?php if (empty($transactions)): ?>

                    <!-- ÉTAT 2 : AUCUNE TRANSACTION -->
                    <div class="empty">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <p><?= t('transaction.list.no_transactions') ?></p>
                    </div>

                <?php else: ?>

                    <!-- ÉTAT 3 : TABLE -->
                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th><?= t('transaction.list.date') ?></th>
                                    <th><?= t('transaction.list.type') ?></th>
                                    <th><?= t('transaction.list.amount') ?></th>
                                    <th><?= t('transaction.list.fees') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($transactions as $t): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($t->getDate())) ?></td>
                                    <td>
                                        <span class="badge <?= $t->getType()->value === 'Entree' ? 'green' : 'red' ?>">
                                            <?= $t->getType()->value  ?>
                                        </span>
                                    </td>
                                    <td class="<?= $t->getType()->value === 'Entree' ? 'amount-plus' : 'amount-minus' ?>">
                                        <?= $t->getType()->value === 'Entree'? '+' : '-' ?><?= number_format($t->getMontant(),2,',',' ') ?> FCFA
                                    </td>
                                    <td><?= number_format($t->getFrais(),2,',',' ') ?> FCFA</td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

                <!-- ✅ PAGINATION -->
                <?php if (isset($nbrPage) && $nbrPage > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination">
                        
                        <!-- Précédent -->
                        <?php if ($pageEnCours > 1): ?>
                            <a href="<?php echo WEB_ROOT; ?>/transaction/index?numeroDeCompte=<?= $numeroDeCompte ?>&page=<?= $pageEnCours - 1 ?>" 
                               class="pagination-btn">
                                <i class="fa-solid fa-chevron-left"></i> <?= t('common.previous') ?>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn disabled">
                                <i class="fa-solid fa-chevron-left"></i> <?= t('common.previous') ?>
                            </span>
                        <?php endif; ?>

                        <!-- Numéros de pages -->
                        <div class="pagination-numbers">
                            <?php
                            $start = max(1, $pageEnCours - 2);
                            $end = min($nbrPage, $pageEnCours + 2);
                            
                            // Première page
                            if ($start > 1): ?>
                                <a href="<?php echo WEB_ROOT; ?>/transaction/list?numeroDeCompte=<?= $numeroDeCompte ?>&page=1" 
                                   class="pagination-number">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Pages autour de la page actuelle -->
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <a href="<?php echo WEB_ROOT; ?>/transaction/list?numeroDeCompte=<?= $numeroDeCompte ?>&page=<?= $i ?>" 
                                   class="pagination-number <?= $i == $pageEnCours ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <!-- Dernière page -->
                            <?php if ($end < $nbrPage): ?>
                                <?php if ($end < $nbrPage - 1): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                                <a href="<?php echo WEB_ROOT; ?>/transaction/list?numeroDeCompte=<?= $numeroDeCompte ?>&page=<?= $nbrPage ?>" 
                                   class="pagination-number"><?= $nbrPage ?></a>
                            <?php endif; ?>
                        </div>

                        <!-- Suivant -->
                        <?php if ($pageEnCours < $nbrPage): ?>
                            <a href="<?php echo WEB_ROOT; ?>/transaction/list?numeroDeCompte=<?= $numeroDeCompte ?>&page=<?= $pageEnCours + 1 ?>" 
                               class="pagination-btn">
                                <?= t('common.next') ?> <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn disabled">
                                <?= t('common.next') ?> <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                        
                    </nav>
                    
                    <!-- Info pagination -->
                    <p class="pagination-info">
                        <?= t('common.page_info', ['current' => $pageEnCours, 'total' => $nbrPage]) ?>
                    </p>
                </div>
                <?php endif; ?>

            <?php endif; ?>

    </div>

</main>

</div>
</body>
</html>