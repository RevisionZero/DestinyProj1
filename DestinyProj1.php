<?php

$name = '';
$emblem = '';
$power = '';
$primary = '';
$power_weapon = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $apiKey = "6708401f544e40c8b81d1f46bbb189f3";

    $characters = ["Titan","Hunter","Warlock"];

    $temp_id1 = htmlspecialchars($_POST['id1']);
    $temp_id2 = htmlspecialchars($_POST['id2']);
    $temp_type = htmlspecialchars($_POST['type']);



    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$temp_type/Profile/$temp_id1/Character/$temp_id2/?components=200");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $json0 = json_decode(curl_exec($ch));

    $name =  $characters[$json0->Response->character->data->classType];

    $emblem = "https://www.bungie.net/".$json0->Response->character->data->emblemBackgroundPath;

    $power = $json0->Response->character->data->light;


    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$temp_type/Profile/$temp_id1/Character/$temp_id2/?components=205");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $weapons_ids = json_decode(curl_exec($ch))->Response->equipment->data->items;
    $primary_id = $weapons_ids[0]->itemHash;
    $power_id = $weapons_ids[2]->itemHash;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny/Definitions/DestinyInventoryItemDefinition/$primary_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $primary = json_decode(curl_exec($ch))->Response->displayProperties->name;

    curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny/Definitions/DestinyInventoryItemDefinition/$power_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));

    $power_weapon = json_decode(curl_exec($ch))->Response->displayProperties->name;

}



?>

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
        <input type="submit" value="Searche">
    </form>
    <br>
    <br>
    <?= ?>
    <br>
    <br>
    <table>
        <thead>
        <tr>
            <th>Class</th>
            <th>Emblem</th>
            <th>Current Power</th>
            <th>Primary Weaponn</th>
            <th>Highest Power Heavy Weapon</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?=$name?></td>
            <td><?=$emblem?></td>
            <td><?=$power?></td>
            <td><?=$primary?></td>
            <td><?=$power_weapon?></td>
        </tr>
        </tbody>
    </table>
</header>

</body>
</html>
