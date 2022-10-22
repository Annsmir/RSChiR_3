<?php require_once '../_helper.php';
// Mode
if (array_key_exists('mode', $_GET)) {
    // try {
    generatePass('iamadmin', 'mypass');
    switch ($_GET['mode']) {
        case 'add':
            addUser();
            break;
        case 'remove':
            removeUser();
            break;
        case 'update':
            updateUserPassword();
            break;
        case 'get':
            getUserByID();
            break;
    }

} else {
    echo 'Invalid mode';
};

function addUser() {
    $mysqli = openMysqli();
    $usrName = $_GET['name'];
    $usrPass = $_GET['pass'];
    $result = $mysqli->query("SELECT * FROM users WHERE name = '{$usrName}';");
    if ($result->num_rows === 1) {
        $message = 'User '. $usrName . ' already exists';
        outputStatus(1, $message);
    } else {
        $usrPass = generatePass($usrName, $usrPass);
        $query = "INSERT INTO users (name, password)
        VALUES ('" . $usrName . "', '" . $usrPass . "');";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Added user ' . $usrName;
        outputStatus(0, $message);
    }
}
function removeUser()
{
    $mysqli = openMysqli();
    $usrName = $_GET['name'];
    $result = $mysqli->query("SELECT * FROM users WHERE name = '{$usrName}';");
    if ($result->num_rows === 1) {
        $query = "DELETE FROM users WHERE name = '" . $usrName . "';";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Removed user ' . $usrName;
        outputStatus(0, $message);
    } else {
        $message = 'User ' . $usrName . ' does not exist';
        outputStatus(1, $message);
    }
}
function updateUserPassword()
{
    $mysqli = openMysqli();
    $usrName = $_GET['name'];
    $usrPass = $_GET['pass'];
    $result = $mysqli->query("SELECT * FROM users WHERE name = '{$usrName}';");
    if ($result->num_rows === 1) {
        $usrPass = generatePass($usrName, $usrPass);
        $query = "UPDATE users SET password = '" . $usrPass . "' WHERE name = '" . $usrName . "';";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Changed password for ' . $usrName;
        outputStatus(0, $message);
    } else {
        $message = $usrName . ' does not exist';
        outputStatus(1, $message);
    }
}
function getUserByID()
{
    $mysqli = openMysqli();
    $usrID = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM users WHERE ID = '{$usrID}';");
    if ($result->num_rows === 1) {
        foreach ($result as $info) {
            echo "{status: 0, name: '" . $info['name'] . "}";
        }
        $mysqli->close();
    } else {
        $message = 'User ID '. $usrID . ' does not exist';
        outputStatus(1, $message);
    }
}
function generatePass($usrName, $usrPass) {
    $cmd = "htpasswd -nb {$usrName} {$usrPass}";
    exec($cmd, $output);
    $str = implode('', $output);
    $str = preg_replace('/^' . $usrName . ':/', '', $str);
    return $str;
}