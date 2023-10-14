<?php

function login()
{
    $pseudo = filter_var($_POST['Pseudo'], FILTER_SANITIZE_STRING);
    $password = $_POST['Password'];
    sessionSet('Old_Pseudo', $pseudo);
    if (empty($pseudo) || empty($password)) {
        if (empty($pseudo))
            sessionSet('Error_Pseudo', 'Ce champ est obligatoire');
        if (empty($password))
            sessionSet('Error_Password', 'Ce champ est obligatoire');
        header('location: client.php');
        exit();
    } else {
        $client = findClient($pseudo, $password);
        if ($client) {
            sessionSet('Client', [
                'Code_Client' => $client['Code_Client'],
                'Nom' => $client['Nom'],
                'Pseudo' => $client['Pseudo']
            ]);
            sessionRemove('Old_Pseudo');
            header('location: server.php');
            exit();
        } else {
            sessionSet('Error_Pseudo', 'Verfier votre Pseudo ou mot de passe');
            sessionSet('Error_Password', 'Verfier votre Pseudo ou mot de passe');
            header('location: client.php');
            exit();
        }
    }
}

function registre()
{
    $nom = filter_var($_POST['Nom'], FILTER_SANITIZE_STRING);
    $pseudo = filter_var($_POST['Pseudo'], FILTER_SANITIZE_STRING);
    $password = $_POST['Password'];
    sessionSet('Old_Nom', $nom);
    sessionSet('Old_Pseudo', $pseudo);
    if (empty($nom) || empty($pseudo) || empty($password)) {
        if (empty($nom))
            sessionSet('Error_Nom', 'Ce champ est obligatoire');
        if (empty($pseudo))
            sessionSet('Error_Pseudo', 'Ce champ est obligatoire');
        if (empty($password))
            sessionSet('Error_Password', 'Ce champ est obligatoire');
        header('location: registre.php');
        exit();
    } else {
        if (isClient($pseudo)) {
            sessionSet('Error_Pseudo', 'Ce Pseudo est déjà utilisé');
            header('location: registre.php');
            exit();
        } else {
            createClient($nom, $pseudo, $password);
            sessionSet('Success', 'Votre compte est créer avec succes');
            sessionRemove('Old_Nom');
            sessionRemove('Old_Pseudo');
            header('location: registre.php');
            exit();
        }
    }
}

function logout()
{
    session_destroy();
    session_regenerate_id();
    header('location: client.php');
    exit();
}

function makeOrder()
{
    $products_quantity = [];
    for ($i = 0; $i < count($_POST['code_produit']); $i++) {
        array_push($products_quantity, [
            'Code_Produit' => $_POST['code_produit'][$i],
            'Quantite' => $_POST['quantite'][$i]
        ]);
    }
    $client = sessionGet('Client');
    $date = Date('Y-m-d');
    createOrder($client['Code_Client'], $date, $products_quantity);
    sessionSet('Success', 'Votre commande a bien été enregistrée');
    header('location: server.php');
    exit();
}

function getFacturePdf()
{
    $client = sessionGet('Client');
    $commande = findOrderByClient($_POST['numéro_commande'], $client['Code_Client']);
    if (!$commande) {
        header('location: server.php');
        exit();
    }
    $produits = [];
    $ligne_commandes = getOrderProducts($commande['Numéro_Commande']);
    foreach ($ligne_commandes as $ligne) {
        $produit = findProduct($ligne['Code_Produit']);
        array_push($produits, array_merge($ligne, $produit));
    }
    require_once('fpdf/fpdf.php');
    /*A4 width : 219mm*/

    $pdf = new FPDF('P', 'mm', 'A4');

    $pdf->AddPage();
    /*output the result*/

    /*set font to arial, bold, 14pt*/
    $pdf->SetFont('Arial', 'B', 20);

    /*Cell(width , height , text , border , end line , [align] )*/

    $pdf->Cell(71, 10, '', 0, 0);
    $pdf->Cell(59, 5, 'Facture', 0, 0);
    $pdf->Cell(59, 10, '', 0, 1);

    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(71, 5, 'GestionCom.com', 0, 0);
    $pdf->Cell(59, 5, '', 0, 0);
    $pdf->Cell(59, 5, 'Details', 0, 1);

    $pdf->SetFont('Arial', '', 10);

    $pdf->Cell(130, 5, 'Gestion Commercial', 0, 0);
    $pdf->Cell(25, 5, 'Code Client:', 0, 0);
    $pdf->Cell(34, 5, $client['Code_Client'], 0, 1);

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(35, 5, 'Date de Commande:', 0, 0);
    $pdf->Cell(34, 5, $commande['Date'], 0, 1);

    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(40, 5, 'Numero de Commande:', 0, 0);
    $pdf->Cell(34, 5, $commande['Numéro_Commande'], 0, 1);


    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(59, 5, '', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(189, 10, '', 0, 1);



    $pdf->Cell(50, 10, '', 0, 1);

    $pdf->SetFont('Arial', 'B', 10);
    /*Heading Of the table*/
    $pdf->Cell(30, 6, 'Code Produit', 1, 0, 'C');
    $pdf->Cell(80, 6, 'Designation', 1, 0, 'C');
    $pdf->Cell(23, 6, 'Quantite', 1, 0, 'C');
    $pdf->Cell(30, 6, 'PU', 1, 0, 'C');
    $pdf->Cell(25, 6, 'Total', 1, 1, 'C');/*end of line*/
    /*Heading Of the table end*/
    $pdf->SetFont('Arial', '', 10);
    $total = 0;
    foreach ($produits as $produit) {
        $pdf->Cell(30, 6, $produit['Code_Produit'], 1, 0);
        $pdf->Cell(80, 6, $produit['Désignation'], 1, 0);
        $pdf->Cell(23, 6, $produit['Qte'], 1, 0, 'R');
        $pdf->Cell(30, 6, $produit['Prix_Unitaire'], 1, 0, 'R');
        $pdf->Cell(25, 6, number_format(($produit['Prix_Unitaire'] * $produit['Qte']), 2), 1, 1, 'R');
        $total += $produit['Prix_Unitaire'] * $produit['Qte'];
    }


    $pdf->Cell(118, 6, '', 0, 0);
    $pdf->Cell(25, 6, 'Total', 0, 0);
    $pdf->Cell(45, 6, number_format($total, 2), 1, 1, 'R');


    $pdf->Output('D');
}

/* ---------------   SESSION FUNCTIONS   --------------- */

function sessionSet($key, $value)
{
    $_SESSION[$key] = $value;
}

function sessionGet($key)
{
    if (sessionHas($key))
        return $_SESSION[$key];
    else return null;
}

function sessionHas($key)
{
    return array_key_exists($key, $_SESSION);
}

function sessionRemove($key)
{
    if (sessionHas($key))
        unset($_SESSION[$key]);
}

/* ---------------   END SESSION FUNCTIONS    ---------------*/

/* ****************************************************************** */
/* ------------  DAO FUNCTIONS   ------------- */

function connect()
{
    $host = '127.0.0.1';
    $dbname = 'php_gestion_ventes';
    $username = 'root';
    $password = '';
    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    try {
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (Exception $e) {
        echo "ERROR IN DATABASE CONNECTION";
        exit();
    }
}

function findClient($pseudo, $password)
{
    $stmt = connect()->prepare('SELECT * FROM client WHERE Pseudo = :Pseudo AND Password = :Password');
    $stmt->execute(['Pseudo' => $pseudo, 'Password' => $password]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) return $result;
    else return null;
}

function isClient($pseudo)
{
    $stmt = connect()->prepare('SELECT Pseudo from client WHERE Pseudo = :Pseudo');
    $stmt->execute([':Pseudo' => $pseudo]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) return $result;
    else return null;
}

function createClient($nom, $pseudo, $password)
{
    $stmt = connect()->prepare('INSERT INTO client(Nom, Pseudo, Password) VALUES ( :Nom, :Pseudo, :Password)');
    return $stmt->execute([':Nom' => $nom, ':Pseudo' => $pseudo, ':Password' => $password]);
}

function getOrders($id_client, $date)
{
    $stmt = connect()->prepare('SELECT * FROM commande WHERE Code_Client = :Code_Client AND Date >= :Date');
    $stmt->execute([':Code_Client' => $id_client, ':Date' => $date]);
    $table = [];
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $ligne_commandes = getOrderProducts($row['Numéro_Commande']);
        $produits = [];
        foreach ($ligne_commandes as $ligne) {
            $produit = findProduct($ligne['Code_Produit']);
            array_push($produits, array_merge($ligne, $produit));
        }
        array_push($table, [
            'Numéro_Commande' => $row['Numéro_Commande'],
            'Date' => $row['Date'],
            'Produits' => $produits
        ]);
    }
    return $table;
}

function getOrderProducts($id_commande)
{
    $stmt = connect()->prepare('SELECT * FROM ligne_commande WHERE Numéro_Commande = ?');
    $stmt->execute([$id_commande]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findProduct($id_produit)
{
    $stmt = connect()->prepare('SELECT * FROM produit WHERE Code_Produit = ?');
    $stmt->execute([$id_produit]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProducts()
{
    $stmt = connect()->prepare('SELECT * FROM produit');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createOrder($id_client, $date, $products_quantity)
{
    $stmt = connect()->prepare('INSERT INTO commande(Code_Client,Date) values(?,?)');
    $stmt->execute([$id_client, $date]);
    $stmt = connect()->prepare('SELECT Max(Numéro_Commande) as Numéro_Commande FROM commande');
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    foreach ($products_quantity as $produit) {
        if (!findLigneOrder($order['Numéro_Commande'], $produit['Code_Produit'])) {
            $stmt = connect()->prepare('INSERT INTO ligne_commande (Numéro_Commande, Code_Produit, Qte) VALUES (?,?,?)');
            $stmt->execute([$order['Numéro_Commande'], $produit['Code_Produit'], $produit['Quantite']]);
        }
    }
}

function findOrderByClient($id_commande, $id_client)
{
    $stmt = connect()->prepare('SELECT * FROM commande WHERE Numéro_Commande = ? AND Code_Client = ?');
    $stmt->execute([$id_commande, $id_client]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findLigneOrder($id_commande, $id_produit)
{
    $stmt = connect()->prepare('SELECT * FROM ligne_commande WHERE Numéro_Commande = ? AND Code_Produit = ?');
    $stmt->execute([$id_commande, $id_produit]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
