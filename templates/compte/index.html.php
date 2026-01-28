<?php
   // dd($comptes)
   //dd($transac)
   //dd($nbrTransac)
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/ListerCompptes.css">

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <h2>Admin Bancaire</h2>
                <p>Espace Administrateur</p>
            </div>

            <nav class="menu">
                <a href="<?php echo WEB_ROOT; ?>/?controller=home&action=index">
                    <i class="fa-solid fa-chart-line"></i> Tableau de bord
                </a>
                <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=create">
                    <i class="fa-solid fa-user-plus"></i> Créer un compte
                </a>
                <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index" class="active">
                    <i class="fa-solid fa-users"></i> Afficher les comptes
                </a>
                <a href="<?php echo WEB_ROOT; ?>/?controller=transaction&action=create">
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
            <h1>Liste des comptes</h1>
            <p><?php echo count($comptes) ?> compte(s) enregistré(s)</p>
        </div>

        <div class="card">

            <?php if (empty($comptes)) : ?>

                <!-- EMPTY STATE -->
                <div class="empty">
                    <i class="fa-solid fa-credit-card"></i>
                    <h3>Aucun compte créé</h3>
                    <p>Créez votre premier compte pour commencer</p>
                </div>

            <?php else : ?>

                <!-- TABLE -->
                <table>
                    <thead>
                        <tr>
                            <th>TITULAIRE</th>
                            <th>NUMÉRO DE COMPTE</th>
                            <th>TYPE</th>
                            <th>STATUT</th>
                            <th>SOLDE</th>
                            <th>NOMBRE DE TRANSACTIONS</th>
                            <th>DUREE DE BLOCAGE (mois)</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comptes as $key => $compte): ?>
                            <tr>
                                <td>Youan HOUNKPATIN</td>
                                <td><?php echo $compte->getNumeroDeCompte() ?></td>
                                <td>
                                    <span class="badge badge-blue">
                                        <?php echo $compte->getType()->value ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-green">Actif</span>
                                </td>
                                <td><?= number_format($compte->getSolde(),2,',',' ') ?> FCFA</td>

                                <?php if (empty($nbrTransac)) : ?>
                                    <td>Aucune transaction sur ce compte</td>
                                <?php else : ?>
                                    
                                    <td><?php echo ($nbrTransac[$compte->getNumeroDeCompte()] ?? 0) ?></td>

                                <?php endif; ?>

                                <td><?= $compte->getDureeDeblocage()  ?? "none"  ?> </td>
                                <td><a href="<?php echo WEB_ROOT; ?>/?controller=transaction&action=list&numeroDeCompte=<?= $compte->getNumeroDeCompte() ?>" 
                               class="pagination-btn">  Voir les transactions</a></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                     <!-- ✅ PAGINATION -->
                <?php if (isset($nbrPage) && $nbrPage > 1): ?>
                <div class="pagination-container">
                    <nav class="pagination">
                        
                        <!-- Précédent -->
                        <?php if ($pageEnCours > 1): ?>
                            <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index&page=<?= $pageEnCours - 1 ?>" 
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
                                <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index&page=1" 
                                   class="pagination-number">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Pages autour de la page actuelle -->
                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index&page=<?= $i ?>" 
                                   class="pagination-number <?= $i == $pageEnCours ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <!-- Dernière page -->
                            <?php if ($end < $nbrPage): ?>
                                <?php if ($end < $nbrPage - 1): ?>
                                    <span class="pagination-dots">...</span>
                                <?php endif; ?>
                                <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index&page=<?= $nbrPage ?>" 
                                   class="pagination-number"><?= $nbrPage ?></a>
                            <?php endif; ?>
                        </div>

                        <!-- Suivant -->
                        <?php if ($pageEnCours < $nbrPage): ?>
                            <a href="<?php echo WEB_ROOT; ?>/?controller=compte&action=index&page=<?= $pageEnCours + 1 ?>" 
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
