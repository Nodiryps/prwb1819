<!DOCTYPE html>     
<html>
     <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <title>membres</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style>
            legend, td{text-align: center;}
        </style>
    <body>
         <nav class="menu">
            <?php include('menu.html'); ?>
            <div class="title" style="position:absolute;top:20px;right:10px;">
                <strong>  <?= $utilisateur->fullname ?>'s profile! (<?= $utilisateur->role ?>) </strong>
            </div>
        </nav>

        <div class="container">Update Your Profile</div>
        <div class="container">
            <strong>Edition du membre : <?= $member->fullname ?>.</strong>
            
            <br><br>
            <form action="user/edit_profile" method="post" class="form-group">
                <table>

                    <tr>
                        <td>username:</td>
                        <td><input  name="username" type="text" value="<?= $username ?>"></td>

                    <tr>
                        <td>fullname:</td>
                        <td><input  name="fullname" type="text" value="<?= $fullname ?>"></td>
                    </tr>
                    <tr>
                        <td>email:</td>
                        <td><input  name="email" type="text" value="<?= $email ?>"></td>
                    </tr>
                    <tr>
                        <td>birthdate</td>
                        <td><input  name="birthdate" type="date" value="<?= $birthdate ?>"></td>
                    </tr> 
                    <?php if (!$utilisateur->is_manager()): ?>
                        <tr>
                            <td>Role</td>
                            <td><select name="role" value="<?= $role ?>">
                                    <option value="<?= $role ?>"><?= $role?></option>
                                    <option value="admin">admin</option>
                                    <option value="manager">manager</option>
                                    <option value="member">member</option>

                                </select></td>
                        </tr>

                    <?php endif; ?> 
                    <?php // if (($utilisateur->is_admin() || $utilisateur->is_manager()) && $utilisateur->username === $member->username): ?>
                    <tr>
                        <td>Password</td>
                        <td><input  name="password" type="password" ></td>
                    </tr>

                    <tr>
                        <td>Confirmation du password</td>
                        <td><input  name="confirm_password" type="password" ></td>
                    </tr>
                    <?php //endif; ?>




                </table>
                <input type="hidden" name="idmember" value="<?= $member->id ?>">
                <button class="btn btn-success" type="submit" name="valider" value="Valider">
                    <span>Valider</span>
                </button>
            </form>

            <br> <a href="user/user_list">Retour a la liste des membres</a>
        </div>
        <?php foreach ($error as $e): ?>
            <div class='errors'><?= $e ?></div>
        <?php endforeach; ?>

    </body>
</html>

