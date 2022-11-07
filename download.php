<?php
include "./config.php";

// Check to see if the user is signed in.
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
	header("Location: login.php"); // Redirect the user to the login page if they are not signed in.
	exit(); // Terminate the script.
}


$file = $_GET["file"]; // This is the file to delete.
$confirmation = intval($_GET["confirm"]); // This is the user's confirmation that they want to delete the specified file.

$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.

if (is_dir($config["storage_location"]) == false) { // Check to see if this instance's upload directory has been created yet.
    echo "<p>The global upload directory does not exist.</p>";
    exit();
}
if (is_dir($upload_directory) == false) { // Check to see if this user's upload directory has been created yet.
    echo "<p>The user upload directory does not exist.</p>";
    exit();
}




// Validate the inputs.
if (preg_match("([^a-zA-Z0-9\.\ \-])", $file)) { // Make sure the download path doesn't have any malicious characters.
    echo "<p>The filename was malformed.</p>";
    exit();
}

if (strpos($file, "..") !== false or strpos($file, "~")) { // Check to make sure the download path doesn't have any parent directory characters.
    echo "<p>The file name was malformed.</p>";
    exit();
}
if (file_exists($upload_directory . "/" . $file) == false) { // Make sure the download path actually exists.
    echo "<p>The download path doesn't exist.</p>";
    exit();
}

$file = str_replace("//", "/", $file); // Remove any double slashes from the file path.



function download($file) { // Define the function to download a file without revealing the URL.
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
}


download($upload_directory . "/" . $file); // Download the file.

?>
