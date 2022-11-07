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





// Upload the file.
if ($uploaded_file["name"] !== "" and $uploaded_file["name"] !== null) { // Check to see if a file was uploaded.
    if (is_dir($upload_directory) == true) { // Check to make sure the upload directory is actually a directory.
        if (in_array(pathinfo($uploaded_file["name"], PATHINFO_EXTENSION), $config["allowed_extensions"]) == true) {
            if ($uploaded_file["size"] <= $config["max_file_size"]) { // Check to make sure the file is under the maximum allowed size.
                if (strlen($uploaded_file["name"]) < 200) { // Check to make sure the file name is a reasonable length.
                    if (preg_match("([^a-zA-Z0-9\.\ \-])", $uploaded_file["name"]) == false) {
                        if (move_uploaded_file($uploaded_file["tmp_name"], $upload_directory . $uploaded_file["name"]) == true) { // Finalize the upload.
                            echo "<p>The file was successfully uploaded.</p>";
                        } else {
                            echo "<p>An unknown error occurred and the file couldn't be uploaded.</p>";
                        }
                    } else {
                        echo "<p>The uploaded file name contains disallowed characters.</p>";
                    }
                } else {
                    echo "<p>The file name is longer than the maximum allowed size.</p>";
                }
            } else {
                echo "<p>The uploaded file is larger than the maximum allowed size.</p>";
            }
        } else {
            echo "<p>The uploaded file wasn't in the list of permitted extensions.</p>";
        }
    } else {
        echo "<p>The upload directory doesn't exist. This is a server side problem, and should never occur during normal operation. It's possible there is a configuration issue.</p>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config["instance_name"]; ?> - Upload</title>
        <link rel="stylesheet" href="styles/fonts/lato/latofonts.css">
        <link rel="stylesheet" type="text/css" href="./styles/main.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
    </head>

    <body>
        <div class="button-container">
            <a class="button" href="./index.php">Back</a>
        </div>
        <h1>Upload</h1> 
        <hr><br>
        <main>
            <form class="form-container" method="post" enctype="multipart/form-data">
                <label for="upload_file">File: </label><input type="file" name="upload_file" id="upload_file" accept="*" required><br>
                <label for="authorized_users">Authorized Users: </label><input type="text" name="authorized_users" id="authorized_users"><br>
                <input type="submit" value="Upload">
            </form>
        </main>
    </body>
</html>
