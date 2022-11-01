<?php require_once '../_helper.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        addItem();
        break;
    case 'DELETE':
        removeItemByName();
        break;
    case 'PATCH':
        updateItemCostByName();
        break;
    case 'GET':
        getItemByName();
        break;
}

function addItem()
{
    $mysqli = openMysqli();
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $drinkName = $data['name'];
    $drinkDesc = $data['desc'];
    $drinkCost = $data['cost'];
    $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
    if ($result->num_rows === 1) {
        $message = $drinkName . ' already exists';
        outputStatus(1, $message);
    } else {
        $query = "INSERT INTO drinks (title, description, cost)
        VALUES ('" . $drinkName . "', '" . $drinkDesc . "', " . $drinkCost . ");";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Added ' . $drinkName . ' with cost of ' . $drinkCost;
        outputStatus(0, $message);
    }
}

function removeItemByName()
{
    $mysqli = openMysqli();
    $drinkName = $_GET['name'];
    $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
    if ($result->num_rows === 1) {
        $query = "DELETE FROM drinks WHERE title = '" . $drinkName . "';";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Removed ' . $drinkName;
        outputStatus(0, $message);
    } else {
        $message = $drinkName . ' does not exist';
        outputStatus(1, $message);
    }
}

function updateItemCostByName()
{
    $mysqli = openMysqli();
    $data = file_get_contents('php://input');
    $data = json_decode($data, true);
    $drinkName = $data['name'];
    $drinkCost = $data['cost'];
    $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
    if ($result->num_rows == 1) {
        $query = "UPDATE drinks SET cost = " . $drinkCost . " WHERE title = '" . $drinkName . "';";
        $mysqli->query($query);
        $mysqli->close();
        $message = 'Updated ' . $drinkName . ' with cost of ' . $drinkCost;
        outputStatus(0, $message);
    } else {
        $message = $drinkName . ' does not exist';
        outputStatus(1, $message);
    }
}

function getItemByName()
{
    $mysqli = openMysqli();
    if (!isset($_GET['name'])) {
        $result = $mysqli->query("SELECT * FROM drinks;");
        foreach ($result as $drink) {
            echo "{status: 0, name: '" . $drink['title'] . "', description: '" . $drink['description'] . "', cost: " . $drink['cost'] . "}";
        }
        $mysqli->close();
    } else {
        $drinkName = $_GET['name'];
        $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
        if ($result->num_rows === 1) {
            foreach ($result as $drink) {
                echo "{status: 0, name: '" . $drink['title'] . "', description: '" . $drink['description'] . "', cost: " . $drink['cost'] . "}";
            }
            $mysqli->close();
        } else {
            $message = $drinkName . ' does not exist';
            outputStatus(1, $message);
        }
    }
}

?>