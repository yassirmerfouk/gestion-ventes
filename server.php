<?php
session_start();
ob_start();
require_once('function.php');
if (!sessionHas('Client')) {
    header('location: client.php');
    exit();
}
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    logout();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['avoir-facture'])) {
    getFacturePdf();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>GestionCom.com</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container px-4">
            <a class="navbar-brand" href="server.php">GestionCom.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php $client = sessionGet('Client'); ?>
                            <?php echo $client['Nom']; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <a class="dropdown-item" href=""><?php echo $client['Nom']; ?></a>
                            <a class="dropdown-item" href=""><?php echo $client['Pseudo']; ?></a>
                            <a class="dropdown-item" href="server.php?logout=true">Se deconnecter</a>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header-->
    <header class="bg-primary bg-gradient text-white">
        <div class="container px-4 text-center">
            <h1 class="fw-bolder">Gestion Commercial</h1>
            <p class="lead">Gestion des Commandes</p>
        </div>
    </header>
    <div class="m-5" id="">
        <div class="">
            <div class="row">
                <div class="col-sm-6 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <h5 class="text-center">Récapitulatif de Commandes</h5>
                                <form class="row mt-3" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div class="col-3">
                                        <label for="inputPassword6" class="col-form-label">A partir de</label>
                                    </div>
                                    <div class="col-7">
                                        <input type="date" class="form-control <?php if (sessionHas('Error_Date')) echo 'is-invalid'; ?>" name="date">
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" class="btn btn-primary" name="chercher" value="chercher">
                                    </div>
                                    <?php if (sessionHas('Error_Date')) { ?>
                                        <p class="float-left text-danger mt-1" style="padding-left: 27%;">
                                            <?php echo sessionGet('Error_Date'); ?>
                                        </p>
                                    <?php } ?>
                                    <?php
                                    sessionRemove('Error_Date');
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <h5 class="text-center">Factures de Commandes</h5>
                                <form class="" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div class="">
                                        <div class="d-flex justify-content-center">
                                            <input type="submit" class="btn btn-primary" name="facture" value="Consulter les factures">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <h5 class="text-center">Passation de Commandes</h5>
                                <form class="" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div class="">
                                        <div class="d-flex justify-content-center">
                                            <input type="submit" class="btn btn-primary" name="commander" value="Passer une commande">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <?php
                        if (isset($_POST['chercher'])) {
                            if (empty($_POST['date'])) {
                                sessionSet('Error_Date', 'Ce champ est obligatoire');
                                header('location: server.php');
                                exit();
                            }
                            $commandes = getOrders($client['Code_Client'], $_POST['date']);
                        ?>
                            <div class="mb-3 text-center">
                                <h4 class="">Récapitulatif à partir de : <?= $_POST['date'] ?></h4>
                            </div>
                            <?php if (empty($commandes)) {
                            ?>
                                <div class="alert alert-warning text-center" role="alert">
                                    <strong>Vous n'avez aucune commnde à partir de la date donnée</strong>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="m-3">
                                    <table class="table table-bordered border border-5">
                                        <thead>
                                            <tr class="border border-5">
                                                <th class="border border-5">N° Commande</th>
                                                <th class="border border-5">Date</th>
                                                <th class="border border-5">Code Produit</th>
                                                <th class="border border-5">Désignation</th>
                                                <th class="border border-5">PU</th>
                                                <th class="border border-5">Qte</th>
                                                <th class="border border-5">Total Produit</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-5">
                                            <?php
                                            $prix_total = 0;
                                            foreach ($commandes as $commande) {
                                            ?>
                                                <tr class="">
                                                    <td class="border border-5"><?= $commande['Numéro_Commande'] ?></td>
                                                    <td class="border border-5"><?= $commande['Date'] ?></td>
                                                    <td class="border border-5">
                                                        <table>
                                                            <?php foreach ($commande['Produits'] as $produit) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $produit['Code_Produit'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                    <td class="border border-5">
                                                        <table>
                                                            <?php foreach ($commande['Produits'] as $produit) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $produit['Désignation'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                    <td class="border border-5">
                                                        <table>
                                                            <?php foreach ($commande['Produits'] as $produit) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $produit['Prix_Unitaire'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                    <td class="border border-5">
                                                        <table>
                                                            <?php foreach ($commande['Produits'] as $produit) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $produit['Qte'] ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </td>
                                                    <td class="border border-5">
                                                        <table>
                                                            <?php
                                                            $prix_qte_produit = 0;
                                                            $prix_qte_produit_total = 0;
                                                            foreach ($commande['Produits'] as $produit) {
                                                                $prix_qte_produit = $produit['Prix_Unitaire'] * $produit['Qte'];
                                                                $prix_qte_produit_total += $prix_qte_produit;

                                                            ?>
                                                                <tr>
                                                                    <td><?= number_format(($prix_qte_produit), 2) ?></td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            $prix_total += $prix_qte_produit_total;
                                                            ?>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #00FFFF;">
                                                    <td colspan="6" class="text-end border border-5"><strong>Total Commande N° <?= $commande['Numéro_Commande'] ?></strong></td>
                                                    <td><strong><?= number_format(($prix_qte_produit_total), 2) ?></strong></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="text-end fs-5">
                                        <ins>
                                            <p class="fw-bold">Chiffre d'affaire du client (<?= $client['Nom'] ?>) : <span style="color:red;"><?= number_format(($prix_total), 2) ?></span></p>
                                        </ins>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        if (isset($_POST['commander'])) {
                            $produits = getProducts();
                        ?>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card-body">
                                        <h5 class="text-center">Liste des Produits</h5>
                                        <table style="width:100%" class="table border">
                                            <thead>
                                                <tr>
                                                    <th>Code Produit</th>
                                                    <th>Désignation</th>
                                                    <th>PU</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($produits as $produit) {
                                                ?>
                                                    <tr>
                                                        <td><?= $produit['Code_Produit'] ?></td>
                                                        <td><?= $produit['Désignation'] ?></td>
                                                        <td><?= $produit['Prix_Unitaire'] ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card-body">
                                        <h5 class="text-center">Passer une Commande</h5>
                                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="Post">
                                            <div class="row g-3 align-items-center">
                                                <div class="form-group col-8">
                                                    <label class="form-control-label mb-1">Produit *</label>
                                                    <select id="product_id" class="form-select" name="code_produit[]">
                                                        <?php
                                                        foreach ($produits as $produit) {
                                                        ?>
                                                            <option value="<?= $produit['Code_Produit'] ?>"><?= $produit['Désignation'] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-4">
                                                    <label class="form-control-label mb-1">Quantité *</label>
                                                    <input type="number" class="form-control" name="quantite[]" placeholder="quantité" min="1" required>
                                                </div>
                                            </div>

                                            <div id="divAddProduct"></div>

                                            <div class="">
                                                <button id="newsectionbtn" onclick="addInputs()" type="button" class="btn btn-primary mt-3">Ajouter un produit</button>
                                            </div>
                                            <div class="">
                                                <div class="float-end">
                                                    <input type="submit" class="btn btn-primary" name="effectuer" value="Effectuer la commande">
                                                </div>
                                            </div>
                                        </form>
                                        <?php
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        if (isset($_POST['effectuer'])) {
                            makeOrder();
                        }
                        if (isset($_POST['facture'])) {
                            $commandes = getOrders($client['Code_Client'], '1000-1-1');
                            arsort($commandes);
                        ?>
                            <div class="col-12">
                                <div class="card-body">
                                    <h5 class="text-center">Liste des Factures</h5>
                                    <table style="width:100%" class="table border">
                                        <thead>
                                            <tr>
                                                <th>Numéro Commande</th>
                                                <th>Date Commande</th>
                                                <th>Facture</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($commandes as $commande) {
                                            ?>
                                                <tr>
                                                    <td><?= $commande['Numéro_Commande'] ?></td>
                                                    <td><?= $commande['Date'] ?></td>
                                                    <td>
                                                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                                                            <input type="hidden" name="numéro_commande" value="<?= $commande['Numéro_Commande'] ?>">
                                                            <input type="submit" class="btn btn-success" name="avoir-facture" value="facture" />
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php
            if (sessionHas('Success')) {
            ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <strong> <?= sessionGet('Success') ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
                sessionRemove('Success');
            }
            ?>
        </div>
    </div>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container px-4">
            <p class="m-0 text-center text-white">&copy; Merfouk Yassir 2022</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script>
        function addInputs() {
            var product_id = document.getElementById('product_id');
            var form_row = document.createElement('div');
            var select_col_6 = document.createElement('div');
            var quantity_col_6 = document.createElement('div');
            var select_form_control_label = document.createElement('label');
            var quantity_form_control_label = document.createElement('label');
            var custom_select = document.createElement('select');
            var input_quantity = document.createElement('input');
            var divAddProduct = document.getElementById('divAddProduct');
            for (let i = 0; i < product_id.length; i++) {
                console.log(product_id.options[i].text);
                var option = document.createElement('option');
                option.setAttribute('value', product_id.options[i].value);
                option.appendChild(document.createTextNode(product_id.options[i].text));
                custom_select.appendChild(option);
            }

            form_row.setAttribute('class', 'row g-3 align-items-center mt-1');
            select_col_6.setAttribute('class', 'form-group col-8');
            quantity_col_6.setAttribute('class', 'form-group col-4');
            select_form_control_label.setAttribute('class', 'form-control-label mb-1');
            quantity_form_control_label.setAttribute('class', 'form-control-label mb-1');
            custom_select.setAttribute('class', 'form-select');
            custom_select.setAttribute('name', 'code_produit[]');
            input_quantity.setAttribute('name', 'quantite[]');
            input_quantity.setAttribute('required', 'required');
            input_quantity.setAttribute('placeholder', 'quantité');
            input_quantity.setAttribute('class', 'form-control');
            select_form_control_label.appendChild(document.createTextNode('Produit *'));
            select_col_6.appendChild(select_form_control_label);
            select_col_6.appendChild(custom_select);
            quantity_form_control_label.appendChild(document.createTextNode('Quantité *'));
            quantity_col_6.appendChild(quantity_form_control_label);
            quantity_col_6.appendChild(input_quantity);
            form_row.appendChild(select_col_6);
            form_row.appendChild(quantity_col_6);
            divAddProduct.appendChild(form_row);

        }
    </script>
    <script src="assets/js/bootstrap5.js"></script>
    <!-- Core theme JS-->
</body>

</html>