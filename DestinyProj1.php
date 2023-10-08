<?php
session_start();
$error = '';
$temp_id = '';
$apiKey = $_SESSION['theapikey'];

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $classes = ['Titan','Hunter','Warlock'];

    $temp_id = htmlspecialchars($_POST['id']);



    if(preg_match('/.{2,26}#[0-9]{4}/',trim($temp_id))){

        $display_name = explode('#',trim($temp_id))[0];
        $display_number = explode('#',trim($temp_id))[1];

        $ch = curl_init();

        $post_data = "{
        \"displayName\": \"$display_name\", \"displayNameCode\": $display_number}";

        curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayerByBungieName/All/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
        $json0 = json_decode(curl_exec($ch));

        if($json0->Response != []){

            $membershipid = $json0->Response[0]->membershipId;
            $membership_type = $json0->Response[0]->membershipType;

            curl_setopt($ch,CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$membership_type/Profile/$membershipid/LinkedProfiles/");
            $json1 = json_decode(curl_exec($ch));

            for($i = 0; $i < 4; $i++){
                if($json1->Response->profiles[$i]->isCrossSavePrimary){
                    $membershipid = $json1->Response->profiles[$i]->membershipId;
                    $membership_type = $json1->Response->profiles[$i]->membershipType;
                    break;
                }
            }

            curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/$membership_type/Profile/$membershipid/?components=100,200");
            $json2 = json_decode(curl_exec($ch));

            $user_characters = $json2->Response->profile->data->characterIds;

            if($user_characters[0]){
                $char0type = $classes[$json2->Response->characters->data->{$user_characters[0]}->classType];
                $char0light = $json2->Response->characters->data->{$user_characters[0]}->light;
            }
            if($user_characters[1]){
                $char1type = $classes[$json2->Response->characters->data->{$user_characters[1]}->classType];
                $char1light = $json2->Response->characters->data->{$user_characters[1]}->light;
            }
            if($user_characters[2]){
                $char2type = $classes[$json2->Response->characters->data->{$user_characters[2]}->classType];
                $char2light = $json2->Response->characters->data->{$user_characters[2]}->light;
            }

            curl_close($ch);

            header("Location: CharacterSelect.php?char0[]=$char0type&char0[]=$char0light&char1[]=$char1type&char1[]=$char1light&char2[]=$char2type&char2[]=$char2light&ids[]=$user_characters[0]&ids[]=$user_characters[1]&ids[]=$user_characters[2]&type=$membership_type&membership=$membershipid");
            exit();

        }
        else{
            $error = "<br><br>".'This Bungie Name does not exist';
        }
    }
    else{
        $error = "<br><br>".'Invalid format, please try again';
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        .center {
            text-align: center;
            padding-top: 200px;
            margin: auto;
            font-size:45px;
        }
        .tab {
            tab-size: 4px;
        }
        .pad{
            padding-top:200px;
        }
        .center2 {
            margin: auto;
            width: 20%;
            font-size: 25px;
        }
    </style>
</head>
<body style="background-color: darkcyan">

<div class="center">
    <img src="https://www.freepnglogos.com/uploads/destiny-logo-png/image-destiny-logo-white-rwby-fanon-wiki-fandom-5.png" width="50" height="50"> Destiny 2 Character Viewer
</div>
<br>
<div class="center2">
    <form method="POST" action="">
        <label for = "id">Enter Bungie Name:</label>
        <input type="text" name = "id" id = "id" required value=<?=$temp_id?>>
        <input type="submit" value="Submit" style="font-size: 16px">
        <p style="font-size: 18px">(Format: Name#7777)</p>
    </form>
    <p style="font-size: 20px"><?=$error?></p>
</div>

</body>
</html>
