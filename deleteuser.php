<?php
    // Initialize the session
    session_start();
     
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    require_once "config.php";
    $id = $_GET["id"];
    $account_type = $_SESSION['account_type'];
    $user_id = $_SESSION["id"];
    
    if($id == $_SESSION["id"] || $account_type == "a"){
        $sql = "DELETE FROM users WHERE id=?";
        if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_id);
                
                // Set parameters
                $param_id = $id;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    if($id == $_SESSION["id"]) {
                        header("location: logout.php");
                    } else {
                        header("location: articles.php");
                    }
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }
             
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            header("location: articles.php");
        }
        
        // Close connection
        mysqli_close($link);
        
?>