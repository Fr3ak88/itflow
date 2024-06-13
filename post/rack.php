<?php

/*
 * ITFlow - GET/POST request handler for client racks
 */

if (isset($_POST['add_rack'])) {

    validateTechRole();

    $client_id = intval($_POST['client_id']);
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $type = sanitizeInput($_POST['type']);
    $model = sanitizeInput($_POST['model']);
    $depth = sanitizeInput($_POST['depth']);
    $units = intval($_POST['units']);
    $physical_location = sanitizeInput($_POST['physical_location']);
    $location = intval($_POST['location']);
    $notes = sanitizeInput($_POST['notes']);

    mysqli_query($mysqli,"INSERT INTO racks SET rack_name = '$name', rack_description = '$description', rack_type = '$type', rack_model = '$model', rack_depth = '$depth', rack_units = $units, rack_location_id = $location, rack_physical_location = '$physical_location', rack_notes = '$notes', rack_client_id = $client_id");

    $rack_id = mysqli_insert_id($mysqli);

    // Add Photo
    if ($_FILES['file']['tmp_name'] != '') {
        if ($new_file_name = checkFileUpload($_FILES['file'], array('jpg', 'jpeg', 'gif', 'png'))) {

            $file_tmp_path = $_FILES['file']['tmp_name'];

            // directory in which the uploaded file will be moved
            if (!file_exists("uploads/clients/$client_id")) {
                mkdir("uploads/clients/$client_id");
            }
            $upload_file_dir = "uploads/clients/$client_id/";
            $dest_path = $upload_file_dir . $new_file_name;
            move_uploaded_file($file_tmp_path, $dest_path);

            mysqli_query($mysqli,"UPDATE racks SET rack_photo = '$new_file_name' WHERE rack_id = $rack_id");
        }
    }

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Create', log_description = '$session_name created rack $name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Rack <strong>$name</strong> created";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_POST['edit_rack'])) {

    validateTechRole();

    $rack_id = intval($_POST['rack_id']);
    $client_id = intval($_POST['client_id']);
    $name = sanitizeInput($_POST['name']);
    $description = sanitizeInput($_POST['description']);
    $type = sanitizeInput($_POST['type']);
    $model = sanitizeInput($_POST['model']);
    $depth = sanitizeInput($_POST['depth']);
    $units = intval($_POST['units']);
    $physical_location = sanitizeInput($_POST['physical_location']);
    $location = intval($_POST['location']);
    $notes = sanitizeInput($_POST['notes']);

    mysqli_query($mysqli,"UPDATE racks SET rack_name = '$name', rack_description = '$description', rack_type = '$type', rack_model = '$model', rack_depth = '$depth', rack_units = $units, rack_location_id = $location, rack_physical_location = '$physical_location', rack_notes = '$notes' WHERE rack_id = $rack_id");

    // Add Photo
    if ($_FILES['file']['tmp_name'] != '') {
        if ($new_file_name = checkFileUpload($_FILES['file'], array('jpg', 'jpeg', 'gif', 'png'))) {

            $file_tmp_path = $_FILES['file']['tmp_name'];

            // directory in which the uploaded file will be moved
            if (!file_exists("uploads/clients/$client_id")) {
                mkdir("uploads/clients/$client_id");
            }
            $upload_file_dir = "uploads/clients/$client_id/";
            $dest_path = $upload_file_dir . $new_file_name;
            move_uploaded_file($file_tmp_path, $dest_path);

            mysqli_query($mysqli,"UPDATE racks SET rack_photo = '$new_file_name' WHERE rack_id = $rack_id");
        }
    }

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Rack', log_action = 'Edit', log_description = '$session_name edited rack $name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $rack_id");

    $_SESSION['alert_message'] = "Rack <strong>$name</strong> edited";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['archive_rack'])) {

    validateTechRole();

    $asset_id = intval($_GET['archive_asset']);

    // Get Asset Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT asset_name, asset_client_id FROM assets WHERE asset_id = $asset_id");
    $row = mysqli_fetch_array($sql);
    $asset_name = sanitizeInput($row['asset_name']);
    $client_id = intval($row['asset_client_id']);

    mysqli_query($mysqli,"UPDATE assets SET asset_archived_at = NOW() WHERE asset_id = $asset_id");

    //logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Asset', log_action = 'Archive', log_description = '$session_name archived asset $asset_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $asset_id");

    $_SESSION['alert_type'] = "error";
    $_SESSION['alert_message'] = "Asset <strong>$asset_name</strong> archived";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['unarchive_rack'])) {

    validateTechRole();

    $asset_id = intval($_GET['unarchive_asset']);

    // Get Asset Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT asset_name, asset_client_id FROM assets WHERE asset_id = $asset_id");
    $row = mysqli_fetch_array($sql);
    $asset_name = sanitizeInput($row['asset_name']);
    $client_id = intval($row['asset_client_id']);

    mysqli_query($mysqli,"UPDATE assets SET asset_archived_at = NULL WHERE asset_id = $asset_id");

    //logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Asset', log_action = 'Unarchive', log_description = '$session_name Unarchived asset $asset_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $asset_id");

    $_SESSION['alert_message'] = "Asset <strong>$asset_name</strong> Unarchived";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}

if (isset($_GET['delete_rack'])) {

    validateAdminRole();

    $asset_id = intval($_GET['delete_asset']);

    // Get Asset Name and Client ID for logging and alert message
    $sql = mysqli_query($mysqli,"SELECT asset_name, asset_client_id FROM assets WHERE asset_id = $asset_id");
    $row = mysqli_fetch_array($sql);
    $asset_name = sanitizeInput($row['asset_name']);
    $client_id = intval($row['asset_client_id']);

    mysqli_query($mysqli,"DELETE FROM assets WHERE asset_id = $asset_id");

    // Delete Interfaces
    mysqli_query($mysqli,"DELETE FROM asset_interfaces WHERE interface_asset_id = $asset_id"); 

    //Logging
    mysqli_query($mysqli,"INSERT INTO logs SET log_type = 'Asset', log_action = 'Delete', log_description = '$session_name deleted asset $asset_name', log_ip = '$session_ip', log_user_agent = '$session_user_agent', log_client_id = $client_id, log_user_id = $session_user_id, log_entity_id = $asset_id");

    $_SESSION['alert_type'] = "error";
    $_SESSION['alert_message'] = "Asset <strong>$asset_name</strong> deleted";

    header("Location: " . $_SERVER["HTTP_REFERER"]);

}
