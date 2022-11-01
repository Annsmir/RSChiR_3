<?php require_once '../_helper.php';

generatePass('iamadmin', 'mypass');
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        addUser();
        break;
    case 'DELETE':
        removeUser();
        break;
    case 'PATCH':
        updateUserPassword();
        break;
    case 'GET':
        getUserByID();
        break;
}

function addUser()
{
    $mysqli = openMysqli();
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $usrName = $data['name'];
    $usrPass = $data['pass'];
    $result = $mysqli->query("SELECT * FROM users WHERE name = '{$usrName}';");
    if ($result->num_rows === 1) {
        $message = 'User ' . $usrName . ' already exists';
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
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $usrName = $data['name'];
    $usrPass = $data['pass'];
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
    if (isset($_GET['id'])) {
        $usrID = $_GET['id'];
        $result = $mysqli->query("SELECT * FROM users WHERE ID = '{$usrID}';");
        if ($result->num_rows === 1) {
            foreach ($result as $info) {
                echo "{status: 0, name: '" . $info['name'] . "}";
            }
            $mysqli->close();
        } else {
            $message = 'User ID ' . $usrID . ' does not exist';
            outputStatus(1, $message);
        }
    } else {
        $result = $mysqli->query("SELECT * FROM users;");
        foreach ($result as $info) {
            echo "{status: 0, name: '" . $info['name'] . "}";
        }
        $mysqli->close();
    }

}

function generatePass($usrName, $usrPass)
{
    $cmd = "htpasswd -nb {$usrName} {$usrPass}";
    exec($cmd, $output);
    $str = implode('', $output);
    $str = preg_replace('/^' . $usrName . ':/', '', $str);
    return $str;
}