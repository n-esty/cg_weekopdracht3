<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    $click_order = "ASC";
    $order_by = "DESC";
    $order_col = "created_at";
    $up = "&#11205;";
    $down = "&#11206;";
    $direction = $down;
    $uid_sort = $uname_sort = $reg_sort = "";

    if (isset($_GET["order"])) {
        $order = htmlspecialchars($_GET["order"]);
        if ($order === "ASC") {
            $order_by = "ASC";
            $direction = $up;
            
            $click_order = "DESC";
        }
    }
    
    if (isset($_GET["by"])) {
        $by = htmlspecialchars($_GET["by"]);
        if ($by === "id" || $by === "username") {
            if ($by === "id") {
                $uid_sort = $direction;
            } elseif ($by === "username") {
                $uname_sort = $direction;
            }
            $order_col = $by;
            if (isset($_GET["order"]) && $_GET["order"] === "ASC") {
                $click_order = "DESC";
            }
        }
    } else {
        $reg_sort = $direction;
    }
    
    $users = mysqli_query($link,"SELECT * FROM users ORDER BY $order_col $order_by");
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
            table td {width:150px}
        </style>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
<a class="btn btn-primary" href="articles.php">&#11207; Terug naar blog overzicht</a>

            <h1> Users: </h1>
            <table border='1'>
                <tr>
                <th style='padding:10px'><a href='?by=id&order=<?php echo     $click_order ?>'>User ID <?php echo $uid_sort ?></a></th>
                <th style='padding:10px'><a href='?by=username&order=<?php echo     $click_order ?>'>Username <?php echo $uname_sort ?></a></th>
                <th style='padding:10px'><a href='?order=<?php echo     $click_order ?>'>Registratie Datum <?php echo $reg_sort ?></a></th>
                </tr>
                <?php 
                while($row = mysqli_fetch_array($users))
                {  
                    echo "<tr>
                    <td style='padding:10px'>" . $row['id'] . "</td>
                    <td style='padding:10px'><a href='profile.php?user=" . $row['id'] . "'>" . $row['username'] . "</a></td>
                    <td style='padding:10px'>" . $row['created_at'] . "</td>
                    </tr>";
                };
                    echo "</table>";
                    // Close connection
                 mysqli_close($link);
                ?>
        </div>
    </body>
</html>