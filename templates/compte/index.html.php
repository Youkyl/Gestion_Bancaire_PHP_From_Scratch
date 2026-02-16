<?php
   // dd($comptes)
   //dd($transac)
   //dd($nbrTransac)
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('compte.index.title') ?> | <?= t('app.admin_title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/ListerCompptes.css">
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
                <a href="<?php echo WEB_ROOT; ?>/compte/index" class="active">
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
            <h1><?= t('compte.index.title') ?></h1>
            <p><?= t('compte.index.subtitle', ['count' => $totalComptes]) ?></p>
        </div>

        <div class="card">

            <?php if (empty($comptes)) : ?>

                <!-- EMPTY STATE -->
                <div class="empty">
                    <i class="fa-solid fa-credit-card"></i>
                    <h3><?= t('compte.index.empty_title') ?></h3>
                    <p><?= t('compte.index.empty_sub') ?></p>
                </div>

            <?php else : ?>

                <!-- TABLE -->
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th><?= t('compte.index.th_holder') ?></th>
                                <th><?= t('compte.index.th_number') ?></th>
                                <th><?= t('compte.index.th_type') ?></th>
                                <th><?= t('compte.index.th_status') ?></th>
                                <th><?= t('compte.index.th_balance') ?></th>
                                <th><?= t('compte.index.th_transactions') ?></th>
                                <th><?= t('compte.index.th_block_duration') ?></th>
                                <th><?= t('compte.index.th_action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comptes as $key => $compte): ?>
                                <tr>
                                    <td>John Doe</td>
                                    <td><?php echo $compte->getNumeroDeCompte() ?></td>
                                    <td>
                                        <span class="badge badge-blue">
                                            <?php echo $compte->getType()->value ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-green"><?= t('compte.index.status_active') ?></span>
                                    </td>
                                    <td><?= number_format($compte->getSolde(),2,',',' ') ?> FCFA</td>

                                    <?php if (empty($nbrTransac)) : ?>
                                        <td><?= t('compte.index.no_transactions') ?></td>
                                    <?php else : ?>
                                        
                                        <td><?php echo ($nbrTransac[$compte->getNumeroDeCompte()] ?? 0) ?></td>

                                    <?php endif; ?>

                                    <td><?= $compte->getDureeDeblocage()  ?? "none"  ?> </td>
                                    <td><a href="<?php echo WEB_ROOT; ?>/transaction/list?numeroDeCompte=<?= $compte->getNumeroDeCompte() ?>" 
                                   class="pagination-btn">  <?= t('compte.index.view_transactions') ?></a></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

                     <!-- ✅ PAGINATION -->
                <?php if (isset($nbrPage) && $nbrPage > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination">
                        
                        <!-- Précédent -->
                        <?php if ($pageEnCours > 1): ?>
                            <a href="<?php echo WEB_ROOT; ?>/compte/index?page=<?= $pageEnCours - 1 ?>" 
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
                                <a href="<?php echo WEB_ROOT; ?>/compte/index?page=1" 
                                   class="pagination-number">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Pages autour de la page actuelle -->
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <a href="<?php echo WEB_ROOT; ?>/compte/index?page=<?= $i ?>" 
                                   class="pagination-number <?= $i == $pageEnCours ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <!-- Dernière page -->
                            <?php if ($end < $nbrPage): ?>
                                <?php if ($end < $nbrPage - 1): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                                <a href="<?php echo WEB_ROOT; ?>/compte/index?page=<?= $nbrPage ?>" 
                                   class="pagination-number"><?= $nbrPage ?></a>
                            <?php endif; ?>
                        </div>

                        <!-- Suivant -->
                        <?php if ($pageEnCours < $nbrPage): ?>
                            <a href="<?php echo WEB_ROOT; ?>/compte/index?page=<?= $pageEnCours + 1 ?>" 
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
