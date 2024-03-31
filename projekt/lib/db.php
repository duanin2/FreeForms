<?php
require_once "common.php";

# Loads the database from the file $db_location into memory
function load_db($db_location) {
    if (!isset($db_location) or $db_location === "") {
        throw new Exception("Location cannot be empty.");
    } else if (!file_exists($db_location)) {
        $contents = array();

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

    $contents = explode("\n", file_get_contents($db_location));

    $data = array();
    foreach ($contents as $line) {
        $line = explode(";", $line);
        $dataLine = array();

        foreach ($line as $pair) {
            $keyValue = explode(":", $pair);

            if (count($keyValue) != 2) {
                continue;
            }

            $key = $keyValue[0];
            $value = $keyValue[1];

            $dataLine[$key] = $value;
        }
        array_push($data, $dataLine);
    }

    return $data;
}

# Saves loaded database $db_data into the file $db_location
function save_db($db_location, $db_data) {
    if (!isset($db_location) or $db_location === "") {
        throw new Exception("Location cannot be empty.");
    } else if (!is_writeable($db_location)) {
        throw new Exception("$db_location isn't writeable.");
    } else if (is_dir($db_location)) {
        throw new Exception("$db_location is a directory.");
    }

    $contents = "";

    foreach ($db_data as $data) {
        if ($data == array()) {
            continue;
        }
        foreach ($data as $key => $value) {
            if (!isset($key) || $key == "") {
                continue;
            }

            $contents .= "$key:$value;";
        }
        $contents .= "\n";
    }

    try {
        file_put_contents($db_location, $contents);
    } catch (Exception $ex) {
        throw new Exception("Cannot create the database $db_location because '" . $ex->getMessage() . "'.");
    }

    return;
}
?>