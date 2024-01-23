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


$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.

// Load the upload database.
include "./load_upload_database.php";




function human_filesize($bytes, $decimals = 2) {
    $size_character = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $size_character[$factor];
}

function format_array_list($array) {
    $formatted_array = "";
    if (sizeof($array) > 0) {
        foreach ($array as $user) { // Iterate through all entires in the array.
            $formatted_array .= ", " . $user; // Add this entry to the list with a comma separator.
        }
        //echo "<script>alert('" . sizeof($array). "');</script>";
        return substr($formatted_array, 2); // Remove the first 2 characters, since it will always be a comma and a space.
    } else {
        return "<i>None</i>"; // A return a placeholder, since the array was empty.
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
            if (isset($upload_database[$username])) {
                $database_file_list = $upload_database[$username]; // This is the list of all files in the user's upload database.
            } else {
                $database_file_list = array();
            }


            if (sizeof($database_file_list) > 0) { // Check to see if there are any files in the upload directory.
                echo "<div class='mainfilecontainer'>";
                    foreach ($database_file_list as $key => $file) {
                        echo "<div class='individualfile'>";
                        echo "<p><b>Title:</b> " . $file["title"] . "</p>";
                        echo "<p><b>Description:</b> " . $file["description"] . "</p>";
                        echo "<p><b>Shared:</b> " . format_array_list($file["authorized"]) . "</p>";
                        echo "<p><b>Size:</b> " . human_filesize(filesize($config["storage_location"] . "/" . $username . "/" . $key)) . "</p>";
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
