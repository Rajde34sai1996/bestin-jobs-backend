<?php
$arrayUrls = ["administration"=>600,"therapist"=>14400];
$aUrl = "";
$url = $_SERVER['REDIRECT_URL'];
$explod = explode("/",$_SERVER['REDIRECT_URL']);
$autoLog = 300;

foreach($arrayUrls as $m=>$v){
    if($explod[2] ==  $m){
        $aUrl  = $m;
        $autoLog = $v;
    }
}
// print_r($aUrl);
// print_r($arrayUrls);
// print_r($explod);die;
return [
    'adminEmail' => 'admin@example.com',
    'adminslug' => $aUrl,
    "loginType" => "backend"
];
