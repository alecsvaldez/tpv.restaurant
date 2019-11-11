<?php
// get remote sha
$url = 'https://api.github.com/repos/alecsvaldez/tpv.restaurant/branches/master';
$ch = curl_init();
//The repo we want to get
curl_setopt($ch, CURLOPT_URL, $url);
//To comply with https://developer.github.com/v3/#user-agent-required
//curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
//curl_setopt($ch,CURLOPT_USERAGENT,'alecsvaldez');

//Skip verification (kinda insecure)
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'alecsvaldez:e61e2e130e5ed05656cace4cdac80420c8a52d26');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// curl_setopt($ch, CURLOPT_HTTPHEADER, [
//     //'Accept: application/vnd.github.v3+json',
//     //'User-Agent: tpvrestaurant',
//     'Authorization: alecsvaldez:e61e2e130e5ed05656cace4cdac80420c8a52d26'
//   ]);

//Get the response
$response = curl_exec($ch);
$data = json_decode($response, true);
// echo '<pre>';print_r($data);echo'</pre>';

$hash = file_get_contents( sprintf( './../.git/refs/heads/%s', 'master' ) )
?>





<pre>
local:  <?php echo $hash; ?>

remoto: <?php echo $data['commit']['sha']; ?>
<br>
fecha: <?php echo $data['commit']['commit']['author']['date']; ?>
</pre>

<?php
if (trim($hash) != trim($data['commit']['sha'])){
    echo 'Necesita actualizar.';
} else {
    echo 'Up to date!';
}
?>