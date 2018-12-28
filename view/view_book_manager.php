<!DOCTYPE html>

<html>
    <head>
        <link style="width:50%;" rel="shortcut icon" href="img/bibli_logo.ico">
        <meta charset="UTF-8">
        <title>books Manager!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>

        <nav> 
            <?php include('menu.html'); ?>
        </nav>
       
        
         <form class="" method="post"  action="book/index">
             <legend class="text-center"><h1 >Location de livres</h1></legend>
       <div class="container">
	<div class="row">
           <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="text" class="  search-query form-control" placeholder="Search" name="search"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit" value="rechercher">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
	</div>
</div>
         </form>

        <div class="container">
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                    <tr >
                        <th scope="col">ISNB</th>
                        <th scope="col">TITLE</th>
                        <th scope="col">AUTHOR</th>
                        <th scope="col">EDITOR</th>
                        <th scope="col">PICTURE</th>
                        <th scope="col">ACTION</th>
                    </tr>
                </thead>

                <?php foreach ($books as $book): ?>
                    <tr >
                        <td><?= $book->isbn ?></td>
                        <td><?= $book->title ?></td>
                        <td><?= strtoupper($book->author) ?></td>
                        <td><?= $book->editor ?></td>
                        <td><?= $book->picture ?></td>
                         <?php if($profile->role == "admin"):?>
                          <td>
                            <form  method="post" action="book/edit_book">
                                <input type="hidden" name="editbook" value="<?= $book->id ?>">
                                <button type="submit" name="idsubmit" class="btn btn-info"><span >editer</span ></button>
                            </form>
                        </td>
                       
                       <?php else: ?>
                        <td>
                            <form  method="post" action="book/book_detail">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button type="submit" name="idsubmit" class="btn btn-info"><span >apercu</span ></button>
                            </form>
                        </td>
                        <?php endif; ?>
                        <?php if ($profile->role == "admin"): ?>
                            <td>
                                <form  method="post" action="book/delete_book">
                                    <input type="hidden" name="delbook" value="<?= $book->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-danger"><span >supprimer</span ></button>
                                </form>
                            </td>
                        <?php endif; ?>
                        <td> 
                            <form  method="post" action="book/add_rental">
                                <input type="hidden" name="idbook" value="<?= $book->id ?>">
                                <button type="submit"  name="idsubmit" class="btn btn-success"><span>panier</span></button>
                            </form>
                        </td>
                        </td>
                    </tr>
                <?php endforeach; ?>

                    <td style="color: red;"><?= strtoupper($msg) ?></td>

            </table>
        </div>
        <div class="container text-right">
            <form method="get" action="book/create_book">
             <button type="submit"  name="idsubmit" class="btn btn-success"><span>creer un livre</span></button>
            </form>
        </div>
        <br>
        <br>

        <div class="container">
            <table class="table table-striped table-condensed">
                <thead class="thead-dark">
                <legend><h1>Votre panier de location</h1></legend>
                <tr >
                    <th scope="col">ISNB</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">AUTHOR</th>
                    <th scope="col">EDITOR</th>
                    <th scope="col">PICTURE</th>
                    <th scope="col">ACTION</th>
                </tr>
                </thead>
                <?php if (!empty($UserRentals)): ?>
                    <?php foreach ($UserRentals as $rent): ?>
                        <tr>
                            <td><?= $rent->isbn ?></td>
                            <td><?= $rent->title ?></td>
                            <td><?= strtoupper($rent->author) ?></td>
                            <td><?= $rent->editor ?></td>
                            <td><?= $rent->picture ?></td>
                            <td>
                                <form  method="post" action="book/book_detail">
                                    <input type="hidden" name="idbook" value="<?= $rent->id ?>">
                                    <button type="submit" name="idsubmit" class="btn btn-info"><span >apercu</span ></button>
                                </form>
                            </td>
                            <td> 

                                <form  method="post" action="book/del_one_rent">
                                    <input type="hidden" name="delrent" value="<?= $rent->id ?>">
                                    <button type="submit"  name="idsubmit" class="btn btn-danger"><span >supprimer du panier</span></button>
                                </form>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
         

          
            
                <form class="form-horizontal " method="post" action="book/add_rental_for_user">
                   <?php if ($profile->role == "admin" || $profile->role == "manager" ): ?>
                 
                        <label>le panier est pour: </label>
                      
                            <select id="selectbasic" name="member_rent" class="form-control">
                                
                                <?php foreach ($members as $member): ?>
                                <option  value="<?php $member->id?>"><?= $member->username?></option>
                                
                                <?php endforeach; ?>
                            </select>
                        
                    
                  <?php endif; ?>
                        <br>
                        <br>
                        <div class="text-right">
                        <input type="hidden" name="value" value="<?php $UserRentals ?>">
                        <button class="btn btn-success" class="form-group " ><span class="glyphicon glyphicon-check"> Louer</span></button>
                <button class="btn btn-danger" class="form-group"><pan class="glyphicon glyphicon-remove"> vider</pan></button>
                        </div>
              </form>
          
            <br>
            <br>
        </div>
    </body>
</html>
