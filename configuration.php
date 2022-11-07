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
        echo "<p>Permissions denied</p>"; // If not, deny the user access to this page.
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


if ($display_advanced_tools == "on") { $display_advanced_tools = true; } else { $display_advanced_tools = false; } // Convert the 'display advanced tools' setting to a bool.
if ($backup_overwriting == "on") { $backup_overwriting = true; } else { $backup_overwriting = false; } // Convert the 'backup overwriting' setting to a bool.


if ($theme != null) { // Check to see if information was input through the form.
    $config["theme"] = $theme;
    $config["storage_location"] = $storage_location;
    $config["credit_level"] = $credit_level;
    $config["admin_user"] = $admin_user;
    $config["login_page"] = $login_page;
    $config["logout_page"] = $logout_page;
    file_put_contents("./configdatabase.txt", serialize($config)); // Write database changes to disk.
}


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
                <option value='rainbow' <?php if ($config["theme"] == "rainbow") { echo "selected"; } ?>>Rainbow</option>
                <option value='contrast' <?php if ($config["theme"] == "contrast") { echo "selected"; } ?>>Contrast</option>
                <option value='metallic' <?php if ($config["theme"] == "metallic") { echo "selected"; } ?>>Metallic</option>
            </select>
            <br><br>
            <label for="storage_location">Storage Location: </label><input id="storage_location" name="storage_location" type="text" value="<?php echo $config["storage_location"]; ?>" placeholder="Storage Location">
            <br><br>
            <label for='creditlevel'>Credit Level:</label>
            <select id='creditlevel' name='creditlevel'>
                <option value='high' <?php if ($config["credit_level"] == "high") { echo "selected"; } ?>>High</option>
                <option value='low' <?php if ($config["credit_level"] == "low") { echo "selected"; } ?>>Low</option>
                <option value='off' <?php if ($config["credit_level"] == "off") { echo "selected"; } ?>>Off</option>
            </select>
            <br><br>
            <label for="admin_user">Admin User: </label><input id="admin_user" name="admin_user" type="text" value="<?php echo $config["admin_user"]; ?>" placeholder="Admin User">
            <br><br>
            <label for="login_page">Login Page: </label><input id="login_page" name="login_page" type="text" value="<?php echo $config["login_page"]; ?>" placeholder="Login Page">
            <br><br>
            <label for="logout_page">Logout Page: </label><input id="logout_page" name="logout_page" type="text" value="<?php echo $config["logout_page"]; ?>" placeholder="Logout Page">
            <br><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>
