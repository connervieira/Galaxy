<?php
// This script manages the Galaxy configuration. It is loaded on all Galaxy pages.


// Initialize the configuration database.
if (file_exists("./configdatabase.txt") == false) { // If the database file doesn't exist, create it.
    $config_file = fopen("./configdatabase.txt", "w") or die("Unable to create configuration database file. Please make sure PHP has write permissions in the root directory."); // Create the file.
    fwrite($config_file, "a:0:{}"); // Set the contents of the database file to a blank database.
    fclose($config_file); // Close the database file.

    // Set the default configuration values.
    $config["theme"] = "light";
    $config["instance_name"] = "Galaxy";
    $config["storage_location"] = "/var/www/protected/galaxy/";
    $config["credit_level"] = "low";
    $config["admin_user"] = "";
    $config["login_page"] = "../dropauth/signin.php";
    $config["logout_page"] = "../dropauth/signout.php";
    $config["allowed_extensions"] = array("zip");
    $config["allowed_users"] = array();
    $config["max_file_size"] = 1 * 1024 * 1024 * 1024;
    $config["user_storage"] = 5 * 1024 * 1024 * 1024;

    file_put_contents("./configdatabase.txt", serialize($config)); // Write the configuration database to disk.

} else { // Otherwise, the file exists, so load the configuration database from disk.
    $config = unserialize(file_get_contents('./configdatabase.txt')); // Load the configuration database from the disk.
}



?>
