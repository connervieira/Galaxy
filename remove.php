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


if (sizeof($config["allowed_users"]) > 0) { // Check to see if a user whitelist is set.
    if (in_array($username, $config["allowed_users"]) or $username == $config["admin_user"]) { // Check to see if the current user's username matches an enty in the whitelist.
        echo "Permission denied"; // If not, deny the user access to this page.
        exit(); // Quit loading the rest of the page.
    }
}


$file = $_GET["file"]; // This is the file to delete.
$confirmation = intval($_GET["confirm"]); // This is the user's confirmation that they want to delete the specified file.

$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.
include "./load_upload_database.php"; // Load the upload database.


// Check to make sure the file upload directory exists.
if (is_dir($config["storage_location"]) == false) { // Check to see if this instance's upload directory has been created yet.
    echo "<p>The global upload directory does not exist.</p>";
    exit();
}
if (is_dir($upload_directory) == false) { // Check to see if this user's upload directory has been created yet.
    echo "<p>The user upload directory does not exist.</p>";
    exit();
}


// Sanitize the file inputs.
if (preg_match("([^a-zA-Z0-9\.\ \-])", $file)) { // Make sure the download path doesn't have any malicious characters.
    echo "<p>The file name was malformed.</p>";
    exit();
}

if (strpos($file, "..") !== false) { // Check to make sure the download path doesn't have any parent directory characters.
    echo "<p>The file name was malformed.</p>";
    exit();
}

if (isset($upload_database[$username][$file]) == false) { // Check to see if an entry for this file exist in the user's upload database.
    echo "<p>The indicated file doesn't exist in the database.</p>";
    exit();
}

if (file_exists($upload_directory . "/" . $file) == false) { // Make sure the download path actually exists.
    echo "<p>The indicated file doesn't exist in the file system.</p>";
    exit();
}

if (is_dir($upload_directory . "/" . $file) == true) { // Check to see if the specified file is actually a directory.
    echo "<p>The file path is malformed.</p>";
    exit();
}



?>


<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config["instance_name"]; ?> - Remove</title>
        <link rel="stylesheet" href="styles/fonts/lato/latofonts.css">
        <link rel="stylesheet" type="text/css" href="./styles/main.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
    </head>

    <body>
        <h1>Remove</h1> 
        <hr><br>
        <main>
            <?php
            if ((time() - $confirmation) < 0) {
                echo "<p>The confirmation timestamp is in the future. It's possible someone sent you a malicious link an attempt to get you to delete a file.</p>";
                echo "<p>" . $file . " has not been modified.</p>";
                echo "<a class='button' href='./view.php'>Back</a>";
            } else if ((time() - $confirmation) < 30) {
                unlink($upload_directory . $file); // Delete the file from the disk.
                unset($upload_database[$username][$file]); // Remove the file from the upload database.
                file_put_contents($upload_database_file, serialize($upload_database)); // Save the upload database information to disk.
                
                echo "<p>" . $file . " has been deleted.</p>";
                echo "<a class='button' href='./view.php'>Back</a>";
            } else {
                echo "<p>Are you sure you want to delete " . $file . "?</p>";
                echo "<a class='button' href='./remove.php?confirm=" . time() . "&file=" . $file . "'>Confirm</a>";
                echo "<a class='button' href='./view.php'>Back</a>";
            }
            ?>
        </main>
    </body>
</html>
