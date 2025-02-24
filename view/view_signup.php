<!DOCTYPE html>
<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>Inscription</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="lib/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.0/jquery.validate.min.js" type="text/javascript"></script>
        <script src="js/index.js" type="text/javascript"></script>
    </head>
    <body>

        <div class="container" style="width:350px;margin:5% auto;">
            <div class="row">
                <h1 class="text-center"><strong>Inscription</strong></h1>
                <br>
                <form action="main/signup" method="post" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="signupForm">
                    <div class="form-group">
                        <input type="text" name="fullname"  class="form-control my-input" id="fullname" placeholder="Nom complet" value="<?= $fullname ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="username"  class="form-control my-input" id="username" placeholder="Pseudo"  value="<?= $username ?>">
                        <p class="errors" id="errUsername"></p>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email"  class="form-control my-input" id="email" placeholder="Email"  value="<?= $email ?>">
                        <p class="errors" id="errEmail"></p>
                    </div>
                    <div class="form-group">
                        <input type="date" name="birthdate" id="birthdate"  class="form-control my-input" placeholder="Date de naissance"  value="<?= $birthdate ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password"  class="form-control my-input" id="passwordSignup" placeholder="Mot(phrase!) de passe">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirm"  class="form-control my-input" id="password_confirm" placeholder="Répétez votre mot(phrase!) de passe">
                        <p class="errors" id="errConfirm"></p>
                    </div>
                    <br>
                    <div class="text-center">
                        <button type="submit" class=" btn btn-block send-button tx-tfm btn-success" style="margin:auto;width:150px;">
                            Valider
                        </button>
                        <a href="main/index" style="position:absolute;bottom:-30px;left:127px;">Déjà inscrit.e?</a>
                    </div>

                </form>

            </div>
            <br><br>
            <div class='text-danger text-left'>
                <?php if ($errors !== []): ?>
                    <p>Erreur(s) à corriger:</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
    </body>
</html>
