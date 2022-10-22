<?php require_once '../_helper.php';
// Mode
if (array_key_exists('mode', $_GET)) {
    // try {
        switch ($_GET['mode']) {
            case 'add':
                addItem();
                break;
            case 'remove':
                removeItemByName();
                break;
            case 'update':
                updateItemCostByName();
                break;
            case 'get':
                getItemByName();
                break;
        }
    /*
    }
    catch (Exception $e) {
        $message = $e->getMessage();
        outputStatus(2, $message);
    }
    */
} else {
    echo 'Invalid mode';
};

function addItem()
{
    $mysqli = openMysqli();
    $drinkName = $_GET['name'];
    $drinkDesc = $_GET['desc'];
    $drinkCost = $_GET['cost'];
    $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
    if ($result->num_rows === 1) {
        $message = $drinkName . ' already exists';
        outputStatus(1, $message);
    }
    else {
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
    $drinkName = $_GET['name'];
    $drinkCost = $_GET['cost'];
    $result = $mysqli->query("SELECT * FROM drinks WHERE title = '{$drinkName}';");
    if ($result->num_rows === 1) {
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
?>