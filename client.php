<?php
session_start();
require_once('function.php');
if (sessionGet('Client')) {
    header('location: server.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Client | Connexion</title>
    <link rel="stylesheet" href="assets/css/bootstrap4.css">
    <style>
        body {
            color: #fff;
            background: #3598dc;
        }

        .form-control {
            min-height: 50px;
        }

        .form-control:focus {
            background: #e2e2e2;
        }

        .form-control,
        .btn {
            border-radius: 2px;
        }

        .login-form {
            width: 550px;
            margin: 150px auto;
            text-align: center;
        }

        .login-form h2 {
            margin: 10px 0 25px;
        }

        .login-form form {
            color: #7a7a7a;
            border-radius: 3px;
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form .btn {
            min-height: 45px;
            font-size: 16px;
            font-weight: bold;
            background: #3598dc;
            border: none;
            outline: none !important;
        }

        .login-form .btn:hover,
        .login-form .btn:focus {
            background: #2389cd;
        }

        .login-form a {
            color: #fff;
            text-decoration: underline;
        }

        .login-form a:hover {
            text-decoration: none;
        }

        .login-form form a {
            color: #7a7a7a;
            text-decoration: none;
        }

        .login-form form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        login();
    } else {
    ?>
        <div class="login-form">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                <h2 class="text-center">Connexion</h2>
                <div class="form-group has-error">
                    <input type="text" class="form-control <?php if (sessionHas('Error_Pseudo')) echo 'is-invalid'; ?>" name="Pseudo" placeholder="Pseudo Nom" value="<?php if (sessionHas('Old_Pseudo')) echo sessionGet('Old_Pseudo'); ?>">
                </div>
                <?php if (sessionHas('Error_Pseudo')) { ?>
                    <p class="float-left text-danger">
                        <?php echo sessionGet('Error_Pseudo'); ?>
                    </p>
                <?php } ?>
                <div class="form-group">
                    <input type="password" class="form-control <?php if (sessionHas('Error_Password')) echo 'is-invalid'; ?>" name="Password" placeholder="Mot de Passe">
                </div>
                <?php if (sessionHas('Error_Password')) { ?>
                    <p class="float-left text-danger">
                        <?php echo sessionGet('Error_Password'); ?>
                    </p>
                <?php } ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Connexion</button>
                </div>
                <p><a href="registre.php">Créer un compte</a></p>
            </form>
            <?php
            sessionRemove('Old_Pseudo');
            sessionRemove('Error');
            sessionRemove('Error_Pseudo');
            sessionRemove('Error_Password');
            ?>
        </div>
    <?php
    }
    ?>
</body>

</html>