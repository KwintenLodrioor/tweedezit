<?php
session_start();

include_once("Classes/User.class.php");
include_once ("Classes/List.class.php");
$feedback = "";

if (!empty($_POST)) {


    $list = new Lists();
    $list->setName($_POST['list']);
    $list->showList();




    if (empty($_POST['list'])) {
        $feedback = "Please give your list a name ";
    } else {
        $list->AddList();

    }


}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>

<a href="logout.php">logout</a>

<div class="AddList">
<form action="" method="post">
    <input type="text" name="list" id="list" placeholder="New list">
    <input type="submit" VALUE="Add list">
</form>
</div>

<div class="feedback">
    <p><?php echo $feedback ?></p>
</div>

<div class="lists">
    <div class="article-container">

        <?php $conn=Db::getInstance(); // voor het moment is de feedhardcoded samen met de friendlist deze gaat nog naar oop omgezetworden ?>
        <?php $q="SELECT * FROM lists";?>
        <?php $statement=$conn->prepare($q);?>
        <?php $statement->execute();?>
        <?php while ($res = $statement->fetch(PDO::FETCH_ASSOC)):?>
            <p><?php echo $res['naam']?></p>



        <?php endwhile;?>


    </div>
</div>




</body>
</html>