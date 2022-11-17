<?php
include "./config.php"; // Import the configuration library.



// Check to see if the user is signed in.
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
	header("Location: login.php"); // Redirect the user to the login page if they are not signed in.
	exit(); // Terminate the script.
}


$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.

// Load the upload database.
include "./load_upload_database.php";
?>


<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config["instance_name"]; ?> - View</title>
        <link rel="stylesheet" href="styles/fonts/lato/latofonts.css">
        <link rel="stylesheet" type="text/css" href="./styles/main.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
    </head>

    <body>
        <div class="button-container">
            <a class="button" href="./index.php">Back</a>
        </div>
        <h1>View</h1> 
        <hr><br>
        <main>
            <?php
            if (isset($upload_database[$username])) {
                $database_file_list = $upload_database[$username]; // This is the list of all files in the user's upload database.
            } else {
                $database_file_list = array();
            }


            if (sizeof($database_file_list) > 0) { // Check to see if there are any files in the upload directory.
                echo "<div class='mainfilecontainer'>";
                    foreach ($database_file_list as $key => $file) {
                        echo "<div class='individualfile'>";
                        echo "<p>Title: " . $file["title"] . "</p>";
                        echo "<p>Description: " . $file["description"] . "</p>";
                        echo "<p>File: " . $key . "</p>";
                        //echo "<p>Users: " . print_r($file["authorized"]) . "</p>";
                        echo "<br>";
                        echo "<a class='button' href='download.php?file=" . $key . "&user=" . $username . "'>Download</a>";
                        echo "<a class='button' href='remove.php?file=" . $key . "'>Remove</a>";
                        echo "<br><br>";
                        echo "</div>";
                    }
                echo "</div>";
            } else {
                echo "<p>There are no uploaded files.</p>";
            }
            ?>
        </main>
    </body>
</html>
