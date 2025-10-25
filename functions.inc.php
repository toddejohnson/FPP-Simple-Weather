<?php

include_once("/opt/fpp/www/common.php");
$pluginName = basename(dirname(__FILE__));
$logFile = $settings['logDirectory']."/".$pluginName.".log";
$pluginConfigFile = $settings['configDirectory'] . "/plugin." .$pluginName;

if (file_exists($pluginConfigFile)){
	$pluginSettings = parse_ini_file($pluginConfigFile);
}else{
	$pluginSettings = array(); //There have been no settings saved by the user, create empty array
}


function GetOverlayList() { 
	$modelsList = GetModels("");
	for($i=0;$i<=count($modelsList)-1;$i++) {
        $OverlayModels[trim($modelsList[$i]["Name"])]=trim($modelsList[$i]["Name"]);
	}
	return $OverlayModels;
}
function GetModels($host) {
    if ($host == "") {
        $host = "localhost";
    }
    $ch = curl_init("http://" . $host . "/api/overlays/models");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return json_decode($data, true);
}

function getFontsInstalled() {
	$host = "localhost";
    $ch = curl_init("http://" . $host . "/api/overlays/fonts");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    $fontsList= json_decode($data, true);
	for($i=1;$i<=count($fontsList)-1;$i++) {
		$installedFonts[$fontsList[$i]]=$fontsList[$i];
	}
return $installedFonts;        
}

function getFontSizes(){
	$maxFontSize = 80;
	
	for($i=5; $i<=$maxFontSize; $i++) {
		$fontSize[$i]=$i;
    }
return $fontSize;	
}
function getScrollSpeed(){
	$MAX_PIXELS_PER_SECOND = 100;

        for($i=0;$i<=$MAX_PIXELS_PER_SECOND;$i++) {
			$scrollSpeed[$i]= $i;
        }
	return $scrollSpeed;
}
function getDuration(){
	$MAX_DURATION = 300;

        for($i=1;$i<=$MAX_DURATION;$i++) {
			$maxDuration[$i]= $i;
        }
	return $maxDuration;
}

function ScrollText($host="127.0.0.1", $model, $msg, $Position, $Font, $FontSize="20", $color="#ffffff", $PPS="20", $AntiAlias="", $Duration="10", $autoenable="true") {
        
    $data["command"] = "Overlay Model Effect";
    
    $args = array();
    $args[] = $model;
    $args[] = "" . $autoenable;
    $args[] = "Text";
    $args[] = $color;
    $args[] = $Font;
    $args[] = "" . $FontSize;
    $args[] = "" . $AntiAlias;
    $args[] = $Position;
    $args[] = "" . $PPS;
    $args[] = "" . $Duration;
    $args[] = $msg;
    $data["args"] = $args;
    echo json_encode($data);
    $data = json_encode($data);
    
    if ($host == "") {
        $host = "localhost";
    }
    $ch = curl_init("http://" . $host . "/api/command");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data)));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

    $data = curl_exec($ch);
    curl_close($ch);
}


function logEntry($data,$logLevel=1) {
	global $logFile,$myPid, $LOG_LEVEL;
	
	if($logLevel <= $LOG_LEVEL) 
		return
		
	$data = $_SERVER['PHP_SELF']." : [".$myPid."] ".$data;		
	$logWrite= fopen($logFile, "a") or die("Unable to open file!");
	fwrite($logWrite, date('Y-m-d h:i:s A',time()).": ".$data."\n");
	fclose($logWrite);
}

function getOpenWeatherMap($api_key, $lat=null, $lon=null, $city=null, $state=null, $country=null){
    $url = "http://api.openweathermap.org/data/2.5/weather?";
    if(isset($city) && isset($state) && isset($country)){
      $url .= "q=$city,$state,$country";
    }elseif(isset($lat) && isset($lon)){
      $url .= "lat=$lat&lon=$lon";
    }else{
      logEntry("weather: lat/lon or city/state/country required");
      exit(1);
    }
    $url .= "&APPID=".$api_key;
    logEntry( "weather url: ".$url);

    $ch = curl_init();
    // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);

    logEntry( "weather result: ".$result);
    $weatherData= json_decode($result,true);
	$temp = round((($weatherData['main']['temp']-273.15)*1.8)+32,1);
	$humidity = $weatherData['main']['humidity'];
	$wind = $weatherData['wind'];

    return array(
        'temp' => $temp,
        'humidity' => $humidity,
        'wind' => $wind,
    );
}
function getAmbientWeather($api_key, $app_key, $device){
    $url = "https://api.ambientweather.net/v1/devices?";
    $url .= "applicationKey=$app_key&apiKey=$api_key";
    logEntry( "weather url: ".$url);

    $ch = curl_init();
    // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);

    logEntry( "weather result: ".$result);
    $weatherData= json_decode($result,true);
	$temp = $weatherData[$device]['lastData']['tempf'];
	$humidity = $weatherData[$device]['lastData']['humidity'];
	$wind = array(
        "speed" => $weatherData[$device]['lastData']['windspeedmph'],
        "deg" => $weatherData[$device]['lastData']['winddir'],
    );

    return array(
        'temp' => $temp,
        'humidity' => $humidity,
        'wind' => $wind,
    );
}

function tempF2C($temp){
    return ($temp-32)*5/9;
}

function windMPH2MS($speed){
    return 0.44704*$speed;
}

function windMPH2KMH($speed){
    return 1.609344*$speed;
}

function windMPH2KN($speed){
    return 0.8689762*$speed;
}

?>
