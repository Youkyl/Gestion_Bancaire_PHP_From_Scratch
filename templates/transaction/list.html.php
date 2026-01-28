<?php
   // dd($comptes)
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des transactions | Admin Bancaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/ListTransac.css">
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
                <a href="<?php echo WEB_ROOT; ?>/home/index">
                    <i class="fa-solid fa-chart-line"></i> Tableau de bord
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create">
                    <i class="fa-solid fa-user-plus"></i> Créer un compte
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/index">
                    <i class="fa-solid fa-users"></i> Afficher les comptes
                </a>
                <a href="<?php echo WEB_ROOT; ?>/transaction/create" class="active">
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

<div class="page-header">
            <h1>Gestion des transactions</h1>
            <p>Ajouter des dépôts/retraits et consulter l'historique</p>
        </div>

        <div class="tabs">
            <a href="<?php echo WEB_ROOT; ?>/transaction/create">
                <div class="tab">
                    Ajouter une transaction
                </div>
            </a>
            <a href="<?php echo WEB_ROOT; ?>/transaction/index">
                <div class="tab active">
                    Lister les transactions
                </div>
            </a>
        </div>
        <br>

            <?php if (isset($comptes)): ?>

                <div class="info">
                    <div>
                        <small>Titulaire</small><br>
                        <strong>HOUNKPATIN Youan</strong>
                    </div>
                    <div>
                        <small>Solde actuel</small><br>
                        <strong><?= number_format($comptes->getSolde(),2,',',' ') ?> FCFA</strong>
                    </div>
                </div>

                <?php if (empty($transactions)): ?>

                    <!-- ÉTAT 2 : AUCUNE TRANSACTION -->
                    <div class="empty">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <p>Aucune transaction enregistrée</p>
                    </div>

                <?php else: ?>

                    <!-- ÉTAT 3 : TABLE -->
                    <table>
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>TYPE</th>
                                <th>DESCRIPTION</th>
                                <th>MONTANT</th>
                                <th>FRAIS</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?= $t->getDate() ?></td>
                                <td>
                                    <?= $t->getType()->value  ?>
                                </td>
                                <td>None</td>
                                <td >
                                    <?= number_format($t->getMontant(),2,',',' ') ?> FCFA
                                </td>
                                <td><?= number_format($t->getFrais(),2,',',' ') ?> FCFA</td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>

                <?php endif; ?>

                <!-- ✅ PAGINATION -->
                <?php if (isset($nbrPage) && $nbrPage > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination">
                        
                        <!-- Précédent -->
                        <?php if ($pageEnCours > 1): ?>
                            <a href="<?php echo WEB_ROOT; ?>/transaction/index?numeroDeCompte=<?= $numeroDeCompte ?>&page=<?= $pageEnCours - 1 ?>" 
                               class="pagination-btn">
                                <i class="fa-solid fa-chevron-left"></i> Précédent
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn disabled">
                                <i class="fa-solid fa-chevron-left"></i> Précédent
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
                                Suivant <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn disabled">
                                Suivant <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        <?php endif; ?>
                        
                    </nav>
                    
                    <!-- Info pagination -->
                    <p class="pagination-info">
                        Page <strong><?= $pageEnCours ?></strong> sur <strong><?= $nbrPage ?></strong>
                    </p>
                </div>
                <?php endif; ?>

            <?php endif; ?>

    </div>

</main>

</div>
</body>
</html>