<?php

$char0 = $_GET['char0'];
$char1 = $_GET['char1'];
$char2 = $_GET['char2'];
$ids = $_GET['ids'];
$type = $_GET['type'];
$membership = $_GET['membership'];

$name = '';
$emblem = '';
$power = '';
$primary = '';
$power_weapon = '';
$primary_pic = 'https://www.bungie.net/common/destiny2_content/icons/0f3e38e82bac5e8c78d1a47be53341e0.jpg';
$power_pic = "https://www.bungie.net/common/destiny2_content/icons/4d46c45f1b4b48eced9ff1f766d6e8b4.jpg";


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $apiKey = "6708401f544e40c8b81d1f46bbb189f3";

    $character = $_POST['character'];


    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$type/Profile/$membership/Character/$character/?components=200,205");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $json0 = json_decode(curl_exec($ch));

    $emblem = "https://www.bungie.net/".$json0->Response->character->data->emblemBackgroundPath;

    $power = $json0->Response->character->data->light;

    $weapons_ids = $json0->Response->equipment->data->items;
    $primary_id = $weapons_ids[0]->itemHash;
    $power_id = $weapons_ids[2]->itemHash;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/Manifest/DestinyInventoryItemDefinition/$primary_id/");

    $primary = json_decode(curl_exec($ch))->Response->displayProperties->name;
    $primary_pic = "https://www.bungie.net/".json_decode(curl_exec($ch))->Response->displayProperties->icon;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/Manifest/DestinyInventoryItemDefinition/$power_id/");

    $power_weapon = json_decode(curl_exec($ch))->Response->displayProperties->name;
    $power_pic = "https://www.bungie.net/".json_decode(curl_exec($ch))->Response->displayProperties->icon;

    curl_close($ch);
}

?>

<html>
<head>
    <style>
        th{
            border: 1px solid cadetblue;
            padding: 5px;
        }
        td{
            border: 1px solid cadetblue;
            padding: 7px;
            text-align: center;
        }
        table {
            border-spacing: 0px;
            margin: auto;
        }
        .left{
            padding-left: 30px;
            padding-top: 30px;
        }
        body{
            background-color: lightslategrey;
            font-family: SansSerif;
        }
    </style>
</head>
<body>
<div class="left">
    <h1>
        Character View
    </h1>
    <br>
    <form method="POST" action="">
        <label for = "character"</label>Select Character:
        <select name = "character" id = "character">
            <option value = <?=$ids[0]?> > <?=$char0[0]?> (<?=$char0[1]?>)</option>
            <option value = <?=$ids[1]?> > <?=$char1[0]?> (<?=$char1[1]?>)</option>
            <option value = <?=$ids[2]?> > <?=$char2[0]?> (<?=$char2[1]?>)</option>
        </select>
        <br>
        <br>
        <input type="submit" value="Enter">
    </form>
    <br>
    <br>
</div>
<table>
    <thead>
    <tr>
        <th>Emblem</th>
        <th>Current Power</th>
        <th>Primary Weapon</th>
        <th>Highest Power Heavy Weapon</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><img src=<?="$emblem"?>></td>
        <td><?=$power?></td>
        <td><?=$primary?></td>
        <td><?=$power_weapon?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td><img src=<?="$primary_pic"?> alt = ""></td>
        <td><img src=<?="$power_pic"?> alt = ""></td>
    </tr>
    </tbody>
</table>
<br>
<div style="padding-left: 30px">
    <button><a href="DestinyProj1.php">Back to User Selection</a></button>
</div>
</body>
</html>



