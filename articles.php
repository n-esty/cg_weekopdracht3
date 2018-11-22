<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    $click_order = "ASC";
    $order_by = "DESC";
    if (isset($_GET["order"])) {
        $order = htmlspecialchars($_GET["order"]);
        if ($order === "ASC") {
            $order_by = "ASC";
            $click_order = "DESC";
        }
    }
    $articles = mysqli_query($link,"SELECT * FROM articles ORDER BY created_at $order_by");
?>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 800px; padding: 20px;margin-left:auto;margin-right:auto; }
            table td th{padding:5px}
            table {width:100%}
        </style>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <?php     
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                    echo '<a class="btn btn-primary" href="submit.php">+ Maak nieuw artikel</a>';
                }
            ?>
            <h1> Articles: </h1>
            <table border='1'>
                <tr>
                <th style='padding:10px'>auteur</th>
                <th style='padding:10px'>titel</th>
                <th style='padding:10px'><a href='?order=<?php echo     $click_order ?>'>tijd sinds gepost</a></th>
                </tr>
                <?php 
                while($row = mysqli_fetch_array($articles))
                {    
                    $post_date = new DateTime($row['created_at']);
                    $current_date = new DateTime(date('Y-m-d H:i:s'));
                    $interval = $current_date->diff($post_date);
                    if($interval->format('%Y')==0){
                        if($interval->format('%m')==0) {
                            if($interval->format('%d')==0) {
                                if($interval->format('%H')==0) {
                                    $time_since = $interval->format('%i minutes') . " and " . $interval->format('%s seconds');
                                } else {
                                    $time_since = $interval->format('%H hours') . " and " . $interval->format('%i minutes');
                                }
                            } else {
                                $time_since = $interval->format('%d days') . " and " . $interval->format('%H hours');
                            }
                        } else {
                            $time_since = $interval->format('%m months') . " and " . $interval->format('%d days');
                        }
                    } else {
                        $time_since = $interval->format('%Y years') . " and " . $interval->format('%m months');
                    }
                
                    $user_id = $row['author'];
                    $users = mysqli_query($link,"SELECT * FROM users WHERE id='$user_id'");
                    $user_info = mysqli_fetch_array($users);
                    echo "<tr>
                    <td style='padding:10px'>" . $user_info['username'] . "</td>
                    <td style='padding:10px'><a href='article.php?id=" . $row['id'] . "'>" . $row['title'] . "</a></td>
                    <td style='padding:10px'>" . $time_since . "</td>
                    </tr>";
                };
                    echo "</table>";
                    // Close connection
                 mysqli_close($link);
                ?>
        </div>
    </body>
</html>