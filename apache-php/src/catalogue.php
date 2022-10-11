<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
    <link rel="stylesheet" href="../css/table.css" type="text/css" />
</head>

<body>
    <div id="wblock">
        <h1>Каталог</h1>
        <?php
        require_once '_helper.php';
        $mysqli = openmysqli();
        $mysqli->set_charset('utf8mb4');
        $result = $mysqli->query("select * from " . 'drinks');
        ?>
        <table cellspacing="0">
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            <?php if ($result->num_rows > 0) foreach ($result as $drink) {
                echo "
            <tr>
                <td>" . $drink['title'] . "</td>
                <td>" . $drink['description'] . "</td>
                <td>" . $drink['cost'] . " руб</td>
            </tr>
            ";
            }
            else echo ''; ?>
        </table>
        <br><a href="../dynamic/about.php">На главную</a>
    </div>
    <?php $mysqli->close(); ?>
</body>

</html>