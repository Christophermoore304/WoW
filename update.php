<?
$servername = 'https://github.com/Christophermoore304/WoW';
$username = 'Christophermoore304@gmail.com';
$password = '$Android7;';
$schema = 'DBName';
$apiKey = '24djxsry4cxfhbbe2vgtvs8zsgmsjgpd';
$realmName = 'Stormrage';
$realmRegion = 'NA';

$conn = new mysqli($servername, $username, $password, $schema); $conn->set_charset('utf8');
 if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }

 $response = file_get_contents('https://'. $realmRegion .'.api.battle.net/wow/auction/data/'. $realmName .'?locale=en_GB&apikey=' . $apiKey); $responseObject = json_decode($response, true);

$a=json_decode($response,true);

$sql = "INSERT INTO status (realm) VALUES(".$responseObject['files'][0]['lastModified'].");";
//$conn->query($sql);



$checkdate="select realm from status where realm=(select max(realm) from status);";

$result3 = $conn->query($checkdate);



if ($result3->num_rows > 0) {
    // output data of each row
    while($row = $result3->fetch_assoc())

	$lastentry=$row["realm"];


if ($lastentry==$responseObject['files'][0]['lastModified'])
{
echo '<br>';

}

  else          {
		$conn ->query("TRUNCATE TABLE auctions");
		$conn ->query("TRUNCATE TABLE status");

		$response = file_get_contents('https://'. $realmRegion .'.api.battle.net/wow/auction/data/'. $realmName .'?locale=en_GB&apikey=' . $apiKey); $responseObject = json_decode($response, true);
		$a=json_decode($response,true);
		$sql = "INSERT INTO status (realm) VALUES(".$responseObject['files'][0]['lastModified'].");";
		$conn->query($sql);

		$response = file_get_contents('https://'. $realmRegion .'.api.battle.net/wow/auction/data/'. $realmName .'?locale=en_GB&apikey=' . $apiKey); $responseObject = json_decode($response, true);
		$auctionsFile = file_get_contents($responseObject['files'][0]['url']); $auctionsArray = json_decode($auctionsFile, true)['auctions'];
		foreach ($auctionsArray as $auction) { $sql = "INSERT INTO auctions (auc, item, owner, buyout, quantity) VALUES(" . $auction['auc'].",". $auction['item'].",'".$auction['owner']."',".$auction['buyout'].",".$auction['quantity'].");"; $conn->query($sql); }
		$conn ->query("delete from auctions where buyout=0");


		}

} else {
    echo "0 results";
}
?>
