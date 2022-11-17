<?php
$upload_database_file = $config["storage_location"] . "/" . "uploaddata.txt"; // This is the file path to the upload database.
$upload_directory = $config["storage_location"] . "/" . $username . "/"; // This is the directory that the file will be uploaded to.


if (file_exists($upload_database_file) == false) { // Check to see if the upload database file exists.
    try {
        file_put_contents($upload_database_file, serialize(array())); // Create the upload database with a blank placeholder array.
    } catch (Exception $e) {
        echo "<p>The upload database file could not be created. This is likely server-side issue.</p>";
        exit();
    }
}


if (is_writable($upload_database_file) == false) { // Check to see if there are permission problems with the upload database.
    echo "<p>The upload database isn't writable. This is likely server-side issue.</p>";
    exit();
}


$upload_database = unserialize(file_get_contents($upload_database_file)); // Load the upload database from disk.
?>
