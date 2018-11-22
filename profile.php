<?php
    // Initialize the session
    session_start();
    require_once "config.php";
    $admin = false;
    $user_id = $_SESSION["id"];
    if (isset($_GET['user'])){
        $user_id = $_GET['user'];
    }
    
    // Get user with id = $id
    $sql = "SELECT account_type, username, created_at FROM users WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            $param_id = $user_id;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $a_type, $u_name, $c_at);
                mysqli_stmt_fetch($stmt); 
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }   
    mysqli_stmt_close($stmt);
    
    // Checking account type
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $account_type = $_SESSION["account_type"];
        if($user_id == $_SESSION["id"] || $account_type == "a"){
            $admin = true;
        }
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
            body{ font: 14px sans-serif; }
            .wrapper{ width: 800px; padding: 20px;margin-left:auto;margin-right:auto; }
            table td th{padding:5px}
        </style>
        <script>
            function deleteUsr() {
                var delA = confirm("Weet je zeker dat je dit wilt verwijderen?");
                if (delA == true) {
                    window.location.href='deleteuser.php?id=<?php echo $user_id ?>'
                }
            }
        </script>
    </head>
    <body>
        <?php include 'header.php' ?>
        <div class="wrapper">
            <?php
                echo "<h1> $u_name </h1>
                <p><i> $a_type </i></p>
                <p>$c_at</p>";
                if($admin){
                    echo "<br><br><br>
                    <a class=\"btn btn-danger\" onclick='deleteUsr()'>DELETE USER</a>
                    &nbsp;&nbsp;
                    <a class=\"btn btn-default\" href='edituser.php?id=$user_id'>EDIT USER</a>";
                }
            ?>
        </div>
    </body>
</html>