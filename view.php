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

$uploaded_file = $_FILES["upload_file"]; // This is the file uploaded through the form.
$authorized_users = $_POST["authorized_users"]; // This is the list of users authorized to access this file.


$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.

$authorized_users = explode(",", $authorized_users); // Convert the list of users into an array.
foreach ($authorized_users as $key => $user) { // Iterate through all users in the list of authorized users.
    $authorized_users[$key] = trim($user); // Trim any leading or trailing blank space for each user.
}

if (is_dir($config["storage_location"]) == false) { // Check to see if this instance's upload directory has been created yet.
    try {
        mkdir($config["storage_location"]); // Create the upload directory.
    } catch (Exception $e) {
        echo "<p>The global upload directory could not be created. This is likely server-side issue.</p>";
        exit();
    }
}
if (is_dir($upload_directory) == false) { // Check to see if this user's upload directory has been created yet.
    try {
        mkdir($upload_directory); // Create the upload directory.
    } catch (Exception $e) {
        echo "<p>The user upload directory could not be created. This is likely server-side issue.</p>";
        exit();
    }
}

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
            $file_list = array_diff(scandir($upload_directory, 1), array(".", "..", ".DS_Store"));
            if (sizeof($file_list) > 0) { // Check to see if there are any files in the upload directory.
                echo "<div class='mainfilecontainer'>";
                    foreach ($file_list as $file) {
                        echo "<div class='individualfile'>";
                        echo "<p>" . $file . "</p>";
                        echo "<br>";
                        echo "<a class='button' href='download.php?file=" . $file . "'>Download</a>";
                        echo "<a class='button' href='remove.php?file=" . $file . "'>Remove</a>";
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
