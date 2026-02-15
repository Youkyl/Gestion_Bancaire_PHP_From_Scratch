<?php
   // dd($comptes)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des transactions | Admin Bancaire</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/AddTransac.css">
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
                <div class="tab active">
                    Ajouter une transaction
                </div>
            </a>
            <a href="<?php echo WEB_ROOT; ?>/transaction/index">
                <div class="tab">
                    Lister les transactions
                </div>
            </a>
        </div>

        <div class="card">

            <?php if (empty($comptes)): ?>

                <!-- EMPTY STATE -->
                <div class="empty">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <h3>Aucun compte disponible</h3>
                    <p>Créez d'abord un compte.</p>
                </div>

            <?php else: ?>

                <div class="card-title">
                    <div class="icon">
                        <i class="fa-solid fa-arrow-down-up-across-line"></i>
                    </div>
                    <h3>Nouvelle transaction</h3>
                </div>

                <form method="POST" action="<?php echo WEB_ROOT ?>/transaction/store">

                    <label>Rechercher un compte</label>
                    <div class="autocomplete-container">
                        <input type="text" 
                            id="compte-search" 
                            placeholder="Tapez un numéro de compte..." 
                            value="<?= $numeroDeCompte ?? '' ?>"
                            autocomplete="off"
                            name="numeroDeCompte" required>
                        
                        <div id="autocomplete-list" class="autocomplete-items"></div>
                    </div>

                    <div class="form-group">
                        <label>Type de transaction</label>
                        <div class="type-buttons">
                            <button type="button" class="type-btn deposit active" id="btnDepot" name ="type">
                                <i class="fa-solid fa-arrow-down"></i> DEPOT
                            </button>
                            <button type="button" class="type-btn withdraw" id="btnRetrait" name ="type">
                                <i class="fa-solid fa-arrow-up"></i> RETRAIT
                            </button>
                        </div>
                        <input type="hidden" name="type" id="typeTransaction" value="DEPOT">
                    </div>

                    <div class="form-group">
                        <label>Montant (FCFA)</label>
                        <input type="number" min="1" name="montant" placeholder="0.00" required>
                    </div>

                    <div class="form-group">
                        <label>Description (optionnel)</label>
                        <input type="text" name="description" placeholder="Ex : Achat supermarché">
                    </div>

                    <button class="btn-submit btn-green" id="btnSubmit">
                        <i class="fa-solid fa-check"></i> Valider la transaction
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </main>

</div>

<script>
    <?php include_once __DIR__ . '/../../public/js/AddTransac.js'; ?>

</script>

</body>
</html>