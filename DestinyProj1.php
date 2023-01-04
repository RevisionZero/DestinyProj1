<?php

$name = '';
$emblem = '';
$power = '';
$primary = '';
$power_weapon = '';
$error = '';
$new = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $apiKey = "6708401f544e40c8b81d1f46bbb189f3";

    $characters = ["Titan","Hunter","Warlock"];

    $temp_id1 = htmlspecialchars($_POST['id1']);
    $temp_id2 = htmlspecialchars($_POST['id2']);
    $temp_type = htmlspecialchars($_POST['type']);
    $temp_bungo = htmlspecialchars($_POST['bungo']);





    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$temp_type/Profile/$temp_id1/Character/$temp_id2/?components=200,205");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $json0 = json_decode(curl_exec($ch));

    $name =  $characters[$json0->Response->character->data->classType];

    $emblem = "https://www.bungie.net/".$json0->Response->character->data->emblemBackgroundPath;

    $power = $json0->Response->character->data->light;

    $weapons_ids = $json0->Response->equipment->data->items;
    $primary_id = $weapons_ids[0]->itemHash;
    $power_id = $weapons_ids[2]->itemHash;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/Manifest/DestinyInventoryItemDefinition/$primary_id/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $primary = json_decode(curl_exec($ch))->Response->displayProperties->name;
    $primary_pic = "https://www.bungie.net/".json_decode(curl_exec($ch))->Response->displayProperties->icon;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/Manifest/DestinyInventoryItemDefinition/$power_id/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $power_weapon = json_decode(curl_exec($ch))->Response->displayProperties->name;
    $power_pic = "https://www.bungie.net/".json_decode(curl_exec($ch))->Response->displayProperties->icon;

    $post_data = ['displayName' => $temp_bungo, 'displayNameCode' => 8833];
    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayerByBungieName/1/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);

    $new = json_decode(curl_exec($ch))->Response->displayName;



}



?>

<!DOCTYPE html>
<html>
<body>
<header>
    <h1>
        Display Character
    </h1>


    <form method="POST" action="">
        MembershipID: <input type="text" name = "id1" required> <br> <br>
        CharacterID: <input type="text" name = "id2" required > <br> <br>
        Membership Type: <input type="text" name = "type" required> <br> <br>
        Bungie ID: <input type="text" name = "bungo" required> <br> <br>
        <input type="submit" value="Submit">
    </form>

    <style>
        table, th, td {
            border: 1px solid cadetblue;
            padding: 5px;
        }
        table {
            border-spacing: 15px;
        }
    </style>


    <br>
    <br>
    <?= $error?>
    <br>
    <br>
    <table>
        <thead>
        <tr>
            <th>Class</th>
            <th>Emblem</th>
            <th>Current Power</th>
            <th>Primary Weapon</th>
            <th>Highest Power Heavy Weapon</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$name?></td>
            <td><img src=<?="$emblem"?>></td>
            <td><?=$power?></td>
            <td><?=$primary?></td>
            <td><?=$power_weapon?></td>
        </tr>
        <tr>
            <td><?=$new?></td>
            <td></td>
            <td></td>
            <td><img src=<?="$primary_pic"?>></td>
            <td><img src=<?="$power_pic"?>></td>
        </tr>
        </tbody>
    </table>
</header>

</body>
</html>
