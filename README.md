# Destiny 2 Character Viewer
 The Destiny 2 Character Viewer is a web application designed for Destiny 2 players who want to quickly and easily view their character items.
 
## How it Works
Created in PHP, the application utilizes requests made to the Bungie API to retrive player data and weapon images. Furthermore, it employs the use of proper input parsing to guarantee that sound requests are made to the API, ensuring that no runtime errors arise. The entire design is packaged in nicely formatted front-end HTML/CSS frames so that the users have a pleasant experience navigating the website.

## API Requests
Below is an example of how a POST request is made to the API throughout the project. cURL is utilized to create and adjust said requests:
```
$ch = curl_init();

        $post_data = "{
        \"displayName\": \"$display_name\", \"displayNameCode\": $display_number}";

        curl_setopt($ch, CURLOPT_URL, "https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayerByBungieName/All/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $apiKey));
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
```

## Utilizing the Server Replies
The API creates responses in the form of JSON objects. Given objects are then decoded to utilize the information encoded inside:
```
$json1 = json_decode(curl_exec($ch));

            for($i = 0; $i < 4; $i++){
                if($json1->Response->profiles[$i]->isCrossSavePrimary){
                    $membershipid = $json1->Response->profiles[$i]->membershipId;
                    $membership_type = $json1->Response->profiles[$i]->membershipType;
                    break;
                }
            }

```

## Integrating the Logic into the UI
Elegant UI design is made to retrieve user data and responses. After information is retrieved, appropriate responses are displayed in a seamless, organized fashion.
### Character Selection Form
```
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
```
### Character Items Display Table
```
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
```
