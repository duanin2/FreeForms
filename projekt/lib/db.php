<?php
require_once "common.php";

# Loads the database from the file $db_location into memory
function load_db($db_location) {
    if (!isset($db_location) or $db_location === "") {
        throw new Exception("Location cannot be empty.");
    } else if (!file_exists($db_location)) {
        $contents = serialize(array());

        try {
            file_put_contents($db_location, "");
        } catch (Exception $ex) {
            throw new Exception("Cannot create the database $db_location because '" . $ex->getMessage() . "'.");
        }
    } else if (!is_readable($db_location)) {
        throw new Exception("$db_location isn't readable.");
    } else if (is_dir($db_location)) {
        throw new Exception("$db_location is a directory.");
    }

    return unserialize(file_get_contents($db_location));
}

# Saves loaded database $db_data into the file $db_location
function save_db($db_location, $db_data) {
    echo is_writeable($db_location);
    if (!isset($db_location) or $db_location === "") {
        throw new Exception("Location cannot be empty.");
    } else if (!is_writeable($db_location)) {
        throw new Exception("$db_location isn't writeable.");
    } else if (is_dir($db_location)) {
        throw new Exception("$db_location is a directory.");
    }

    try {
        file_put_contents($db_location, serialize($db_data));
    } catch (Exception $ex) {
        throw new Exception("Cannot create the database $db_location because '" . $ex->getMessage() . "'.");
    }

    return;
}
?>