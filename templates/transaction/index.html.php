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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSS_ROOT; ?>/indexTransac.css">
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

        <?php if (empty($comptes)): ?>

            <!-- ÉTAT 1 : AUCUN COMPTE -->
            <div class="empty">
                <i class="fa-solid fa-list"></i>
                <h3>Aucun compte disponible</h3>
            </div>

        <?php else: ?>

            <div class="form-group">
                
                <label>Rechercher un compte</label>
                <div class="autocomplete-container">
                    <input type="text" 
                        id="compte-search" 
                        placeholder="Tapez un numéro de compte..." 
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