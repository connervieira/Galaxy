<?php
include "./config.php"; // Import the configuration library.



// Check to see if the user is signed in.
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
    $username = "";
    if (sizeof($config["allowed_users"]) > 0) { // Check to see if a user whitelist is set.
        header("Location: login.php"); // Redirect the user to the login page if they are not signed in.
        exit(); // Terminate the script.
    }
}

if (sizeof($config["allowed_users"]) > 0) { // Check to see if a user whitelist is set.
    if (in_array($username, $config["allowed_users"]) == false and $username !== $config["admin_user"]) { // Check to see if the current user's username matches an enty in the whitelist.
        echo "Permission denied"; // If not, deny the user access to this page.
        exit(); // Quit loading the rest of the page.
    }
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config["instance_name"]; ?></title>
        <link rel="stylesheet" href="styles/fonts/lato/latofonts.css">
        <link rel="stylesheet" type="text/css" href="./styles/main.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
    </head>

    <body>
        <div class="button-container">
            <?php
                if ($config["admin_user"] == "" or $config["admin_user"] == null or $config["admin_user"] == $username) { // Check to see if the current user is authorized to configure this instance.
                    echo '<a class="button" href="configuration.php">Configure</a>'; // Display the configuration link.
                }
                if ($username !== "" and $username !== null) { // Check to see if the user is currently signed in.
                    echo '<a class="button" href="' . $config["logout_page"] . '">Logout</a>'; // Display the logout link.
                }
            ?>
        </div>
        <h1 class="title"><?php echo $config["instance_name"]; ?></h1> 
        <h3 class="subtitle">Independently host and share files</h3>
        <?php
            if ($config["credit_level"] == "high") {
                echo '<div style="position:fixed;right:0;bottom:0;margin-right:10px;margin-bottom:10px;padding-left:5px;padding-right:5px;border-radius:5px;background:rgba(0, 0, 0, 0.75);"><p style="margin-bottom:7px;margin-top:7px;"><a href="https://v0lttech.com/madebyv0lt.php" style="text-decoration:underline;color:white;">Made by V0LT</a></p></div>';
            } else if ($config["credit_level"] == "low") {
                echo '<p style="font-size:15px;color:#cccccc;margin-top:30px;margin-bottom:30px;text-align:center;"><a href="https://v0lttech.com/madebyv0lt.php" style="text-decoration:underline;color:inherit;">Made By V0LT</a></p>';
            }
        ?>
        <hr><br>

        <?php
        if ($username !== "" and $username !== null) { // Check to see if the user is currently signed in.
            echo '<a class="button" href="./upload.php">Upload</a><br><br><br><a class="button" href="./view.php">View</a>'; // Display the file management links.
        } else {
            echo '<a class="button" href="' . $config["login_page"] . '">Login</a>'; // Display the login link.
        }
        ?>
    </body>
</html>
