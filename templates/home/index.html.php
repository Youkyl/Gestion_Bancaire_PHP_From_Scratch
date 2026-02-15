<?php
   // dd($comptes)
   //dd($transac)
   //dd($comptesBloq)
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord | Admin Bancaire</title>
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
                <h2>Admin Bancaire</h2>
                <p>Espace Administrateur</p>
            </div>

            <nav class="menu">
                <a href="<?php echo WEB_ROOT; ?>/home/index" class="active">
                    <i class="fa-solid fa-chart-line"></i> Tableau de bord
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create">
                    <i class="fa-solid fa-user-plus"></i> Créer un compte
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/index">
                    <i class="fa-solid fa-users"></i> Afficher les comptes
                </a>
                <a href="<?php echo WEB_ROOT; ?>/transaction/create">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i> Transactions
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
            <h1>Tableau de bord</h1>
            <p>Vue d'ensemble de la gestion bancaire</p>
        </div>

        <!-- STATS -->
        <section class="stats">
        <?php if (empty($comptes)): ?>

            <!-- EMPTY STATE -->
            <div class="empty">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <h3>Aucun compte disponible</h3>
                <p>Créez d'abord un compte.</p>
            </div>

            <?php else: ?>
            
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Solde total</span>
                        <div class="stat-icon icon-blue">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <h2><?= number_format($totalSolde, 2, ',', ' ') ?> FCFA</h2>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Total comptes</span>
                        <div class="stat-icon icon-green">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <h2><?php echo count($comptes)?> compte(s) trouvé(s)</h2>
                </div>
                
                <?php if (empty($transac)): ?>

                <!-- EMPTY STATE -->
                <div class="empty">
                        <div class="stat-card">
                            <div class="stat-header">
                                <span>Transactions</span>
                                <div class="stat-icon icon-purple">
                                    <i class="fa-solid fa-right-left"></i>
                                </div>
                            </div>
                            <p></p>
                            <!--<i class="fa-solid fa-triangle-exclamation"></i>-->
                            <h5>Aucune transaction disponible</h5>
                            <p>Créez d'abord une transaction.</p>
                            <h2></h2>
                        </div>
                </div>

                <?php else: ?> 

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Transactions</span>
                        <div class="stat-icon icon-purple">
                            <i class="fa-solid fa-right-left"></i>
                        </div>
                    </div>
                    <h2><?php echo count($transac)?></h2>
                </div>
                <?php endif  ?>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Comptes bloqués</span>
                        <div class="stat-icon icon-orange">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>
                    <h2><?= $comptesBloq ?></h2>
                </div>
                
 

        </section>

        <!-- RÉPARTITION -->
        <section class="section">
            <h3>Répartition des comptes</h3>

            <div class="accounts">
                <div class="account-card savings">
                    <div class="account-card-header">
                        <h4>Comptes Épargne</h4>
                        <div class="account-icon">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </div>
                    </div>
                    <h2><?= $totalEpargne?></h2>
                    <p><?= $totalEpargne?> bloqués</p>
                </div>

                <div class="account-card checking">
                    <div class="account-card-header">
                        <h4>Comptes Chèque</h4>
                        <div class="account-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                    </div>
                    <h2><?= $totalCheque?></h2>
                    <p>Frais : 0.8% par opération</p>
                </div>
            </div>
        </section>  
        <?php endif  ?>
    </main>

</div>

</body>
</html>
