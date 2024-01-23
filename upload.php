<?php
include "./config.php"; // Import the configuration library.



// Check to see if the user is signed in.
session_start();
if ($_SESSION['authid'] == "dropauth") { // Check to see if the user is already signed in with DropAuth.
	$username = $_SESSION['username'];
} else {
    header("Location: " . $config["login_page"]); // Redirect the user to the login page if they are not signed in.
	exit(); // Terminate the script.
}


if (sizeof($config["allowed_users"]) > 0) { // Check to see if a user whitelist is set.
    if (in_array($username, $config["allowed_users"]) or $username == $config["admin_user"]) { // Check to see if the current user's username matches an enty in the whitelist.
        echo "Permission denied"; // If not, deny the user access to this page.
        exit(); // Quit loading the rest of the page.
    }
}

$uploaded_file = $_FILES["upload_file"]; // This is the file uploaded through the form.
$authorized_users = filter_var($_POST["authorized_users"], FILTER_SANITIZE_STRING); // This is the list of users authorized to access this file.
$file_description = filter_var($_POST["description"], FILTER_SANITIZE_STRING); // This is a short user-defined description of the file.





// Define the function generate a random string of characters.
function random_string($length = 10, $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
    $string = ""; // Set the generated string to a blank placeholder.
    for ($i = 0; $i < $length; $i++) { // Loop for each character in the specified length of the string to be generated.
        $index = rand(0, strlen($characters) - 1); // Pick a random character from the set.
        $string .= $characters[$index]; // Append the random character selected.
    }
    return $string; // Return the randomly generated string.
}

function directory_size($path) {
    $total_size = 0;
    $files = scandir($path);
    $clean_path = rtrim($path, '/'). '/';
    foreach($files as $t) {
        if ($t<>"." && $t<>"..") {
            $current_file = $clean_path . $t;
            if (is_dir($current_file)) {
                $size = foldersize($current_file);
                $total_size += $size;
            } else {
                $size = filesize($current_file);
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}










$authorized_users = explode(",", $authorized_users); // Convert the list of users into an array.
foreach ($authorized_users as $key => $user) { // Iterate through all users in the list of authorized users.
    $authorized_users[$key] = trim($user); // Trim any leading or trailing blank space for each user.
    if ($authorized_users[$key] == "") { // Check to see if this entry is empty.
        unset($authorized_users[$key]); // Remove this entry.
    }
}



// Load the upload database.
include "./load_upload_database.php";



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
                if (($uploaded_file["size"] + directory_size($upload_directory)) <= $config["user_storage"]) { // Check to make sure this file will fit, given the user's remaining allowed storage.
                    if (strlen($uploaded_file["name"]) < 200) { // Check to make sure the file name is a reasonable length.
                        if (preg_match("([^a-zA-Z0-9\.\ \-])", $uploaded_file["name"]) == false) {
                            $upload_destination_name = strval(time()) . random_string() . "." . pathinfo($uploaded_file["name"], PATHINFO_EXTENSION); // Generate a random name for this file with the current time.
                            $upload_destination_path = $upload_directory . "/" . $upload_destination_name; // Derive the upload path for this file.
                            if (move_uploaded_file($uploaded_file["tmp_name"], $upload_destination_path) == true) { // Finalize the upload by attempting to save the uploaded file.
                                $upload_database[$username][$upload_destination_name]["title"] = $uploaded_file["name"]; // This is a human-readable title of the file.
                                $upload_database[$username][$upload_destination_name]["description"] = $file_description; // This is a user defined description of the file.
                                $upload_database[$username][$upload_destination_name]["authorized"] = $authorized_users; // This is a list of the users authorized to view this file.
                                file_put_contents($upload_database_file, serialize($upload_database)); // Save the upload database information to disk.

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
                    echo "<p>There is not enough storage remaining on your account to store this file.</p>";
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
                <label for="description">Description: </label><input type="text" name="description" id="description"><br>
                <label for="authorized_users">Authorized Users: </label><input type="text" name="authorized_users" id="authorized_users"><br>
                <input type="submit" value="Upload">
            </form>
        </main>
    </body>
</html>
