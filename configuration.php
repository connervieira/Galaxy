<?php
include "./config.php"; // Import the configuration library.


// Check to see if the user is signed in.
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
    $username = "";
}

if ($config["admin_user"] !== "" and $config["admin_user"] !== null) { // Check to see if the current user is authorized to configure this instance.
    if ($username != $config["admin_user"]) { // Check to see if the current user's username matches the admin user.
        echo "<p>Permission denied</p>"; // If not, deny the user access to this page.
        exit(); // Quit loading the rest of the page.
    }
}



// Collect any information from the form that may have been submitted.
$theme = $_POST["theme"]; // This is the interface theme.
$storage_location = $_POST["storage_location"]; // This is the directory path that Galaxy will store uploaded files.
$credit_level = $_POST["creditlevel"]; // This is the level of credit given to V0LT on the main page.
$admin_user = $_POST["admin_user"]; // This is the username of the admin for this instance.
$login_page = $_POST["login_page"]; // This is a link to the login page for this platform.
$logout_page = $_POST["logout_page"]; // This is a link to the logout page for this platform.
$allowed_extensions = explode(",", $_POST["allowed_extensions"]); // This is the array of permitted extensions.
$allowed_users = explode(",", $_POST["allowed_users"]); // This is the array of users who can use this Galaxy instance.
$max_file_size = floatval($_POST["max_file_size"]) * 1024 * 1024 * 1024; // This is the maximum allowed file size.
$user_storage = floatval($_POST["user_storage"]) * 1024 * 1024 * 1024; // This is how much storage each user is permitted to use.

if ($display_advanced_tools == "on") { $display_advanced_tools = true; } else { $display_advanced_tools = false; } // Convert the 'display advanced tools' setting to a bool.
if ($backup_overwriting == "on") { $backup_overwriting = true; } else { $backup_overwriting = false; } // Convert the 'backup overwriting' setting to a bool.

foreach ($allowed_extensions as $key => $extension) { // Iterate through all users in the list of permitted extensions.
    $allowed_extensions[$key] = trim($extension); // Trim any leading or trailing blank spaces for each extension.
    if ($allowed_extensions[$key] == "") { // Check to see if this entry is empty.
        unset($allowed_extensions[$key]); // Remove this entry.
    }
}
foreach ($allowed_users as $key => $user) { // Iterate through all users in the list of permitted users.
    $allowed_users[$key] = trim($user); // Trim any leading or trailing blank spaces for each user.
    if ($allowed_users[$key] == "") { // Check to see if this entry is empty.
        unset($allowed_users[$key]); // Remove this entry.
    }
}

if ($theme != null) { // Check to see if information was input through the form.
    $config["theme"] = $theme;
    $config["storage_location"] = $storage_location;
    $config["credit_level"] = $credit_level;
    $config["admin_user"] = $admin_user;
    $config["login_page"] = $login_page;
    $config["logout_page"] = $logout_page;
    $config["allowed_extensions"] = $allowed_extensions;
    $config["allowed_users"] = $allowed_users;
    $config["max_file_size"] = $max_file_size;
    $config["user_storage"] = $user_storage;
    file_put_contents("./configdatabase.txt", serialize($config)); // Write database changes to disk.
}





$formatted_allowed_extensions = "";
foreach ($config["allowed_extensions"] as $extension) { // Iterate through all users in the list of permitted extensions.
    $formatted_allowed_extensions = $formatted_allowed_extensions . "," . $extension; // Add this extension to the list with a comma separator.
}
$formatted_allowed_extensions = substr($formatted_allowed_extensions, 1); // Remove the first character, since it will always be a comma.

$formatted_allowed_users = "";
foreach ($config["allowed_users"] as $user) { // Iterate through all users in the list of permitted extensions.
    $formatted_allowed_users = $formatted_allowed_users . "," . $user; // Add this user to the list with a comma separator.
}
$formatted_allowed_users = substr($formatted_allowed_users, 1); // Remove the first character, since it will always be a comma.



?>


<!DOCTYPE html>
<html lang="en">
    <head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $config["instance_name"]; ?> - Configuration</title>
        <link rel="stylesheet" href="styles/fonts/lato/latofonts.css">
        <link rel="stylesheet" type="text/css" href="./styles/main.css">
        <link rel="stylesheet" type="text/css" href="./styles/themes/<?php echo $config["theme"]; ?>.css">
    </head>

    <body>
        <div class="button-container">
            <a class="button" href="./index.php">Back</a>
        </div>
        <form method="POST">
            <label for='theme'>Theme:</label>
            <select id='theme' name='theme'>
                <option value='light' <?php if ($config["theme"] == "light") { echo "selected"; } ?>>Light</option>
                <option value='dark' <?php if ($config["theme"] == "dark") { echo "selected"; } ?>>Dark</option>
                <option value='galaxy' <?php if ($config["theme"] == "galaxy") { echo "selected"; } ?>>Galaxy</option>
                <option value='contrast' <?php if ($config["theme"] == "contrast") { echo "selected"; } ?>>Contrast</option>
                <option value='metallic' <?php if ($config["theme"] == "metallic") { echo "selected"; } ?>>Metallic</option>
            </select>
            <br><br>
            <label for="storage_location">Storage Location: </label><input id="storage_location" name="storage_location" type="text" value="<?php echo $config["storage_location"]; ?>" placeholder="/var/www/protected/galaxy/">
            <br><br>
            <label for='creditlevel'>Credit Level:</label>
            <select id='creditlevel' name='creditlevel'>
                <option value='high' <?php if ($config["credit_level"] == "high") { echo "selected"; } ?>>High</option>
                <option value='low' <?php if ($config["credit_level"] == "low") { echo "selected"; } ?>>Low</option>
                <option value='off' <?php if ($config["credit_level"] == "off") { echo "selected"; } ?>>Off</option>
            </select>
            <br><br>
            <label for="admin_user">Admin User: </label><input id="admin_user" name="admin_user" type="text" value="<?php echo $config["admin_user"]; ?>" placeholder="admin">
            <br><br>
            <label for="login_page">Login Page: </label><input id="login_page" name="login_page" type="text" value="<?php echo $config["login_page"]; ?>" placeholder="/login.php">
            <br><br>
            <label for="logout_page">Logout Page: </label><input id="logout_page" name="logout_page" type="text" value="<?php echo $config["logout_page"]; ?>" placeholder="/logout.php">
            <br><br>
            <label for="allowed_etensions">Allowed Extensions: </label><input id="allowed_extensions" name="allowed_extensions" type="text" value="<?php echo $formatted_allowed_extensions; ?>" placeholder="zip,png,jpg">
            <br><br>
            <label for="allowed_users">Allowed Users: </label><input id="allowed_users" name="allowed_users" type="text" value="<?php echo $formatted_allowed_users; ?>" placeholder="user1,user2">
            <br><br>
            <label for="max_file_size">Max File Size (GB): </label><input id="max_file_size" name="max_file_size" type="number" step="any" value="<?php echo $config["max_file_size"]/1024**3; ?>" placeholder="1">
            <br><br>
            <label for="user_storage">User Storage (GB): </label><input id="user_storage" name="user_storage" type="number" step="any" value="<?php echo $config["user_storage"]/1024**3; ?>" placeholder="5">
            <br><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
