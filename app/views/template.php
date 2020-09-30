<?php

use App\controllers\FrontController;

?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport"
         content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title><?= $page ?></title>
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="container">

   <?php
   FrontController::render_page($page, $adding_result); ?>

</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/validations.js"></script>
<script src="js/main.js"></script>
</body>
</html>