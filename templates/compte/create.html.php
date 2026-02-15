<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte | Admin Bancaire</title>
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
                <h2>Admin Bancaire</h2>
                <p>Espace Administrateur</p>
            </div>

            <nav class="menu">
                <a href="<?php echo WEB_ROOT; ?>/home/index" >
                    <i class="fa-solid fa-chart-line"></i> Tableau de bord
                </a>
                <a href="<?php echo WEB_ROOT; ?>/compte/create" class="active">
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

        <div class="page-header">
            <h1>Créer un nouveau compte</h1>
            <p>Ajoutez un nouveau compte bancaire au système</p>
        </div>

        <div class="card">
            <div class="card-title">
                <div class="icon"><i class="fa-solid fa-plus"></i></div>
                <h3>Informations du compte</h3>
            </div>

            <form method="POST" action="<?php echo WEB_ROOT ?>/compte/store">
                <div class="form-group">
                    <label>Nom du titulaire</label>
                    <input type="text" name="titulaire" placeholder="Ex : Jean Dupont" required>
                </div>

                <div class="form-group">
                    <label>Type de compte</label>
                    <select name="type" id="typeCompte" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="EPARGNE">Compte Épargne</option>
                        <option value="CHEQUE">Compte Chèque</option>
                    </select>
                </div>

                <div class="alert" id="alertCheque">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Les opérations sur un compte chèque sont facturées à 0.8%</span>
                </div>

                <div id="blocageEpargne" class="form-group">
                    <div class="mb-3">
                        <label class="form-label">Durée de blocage (en mois)</label>
                        <input type="number" class="form-control" value="12" min="1" name="dureeBlocage">
                    </div>

                    <small class="text-muted d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle"></i>
                        Durant cette période, les retraits ne seront pas autorisés
                    </small>
                </div>

                <div class="form-group">
                    <label>Solde initial (FCFA)</label>
                    <input type="number" name="solde" placeholder="0.00" min="0" required>
                </div>

                <button class="btn-submit">
                    <i class="fa-solid fa-building-columns"></i>
                    Créer le compte
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