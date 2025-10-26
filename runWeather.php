#!/usr/bin/php
<?

$pluginName = basename(dirname(__FILE__));

$skipJSsettings = 1;
include_once("/opt/fpp/www/common.php");
include_once("functions.inc.php");

if (isset($pluginSettings['ENABLED'])){
    $enabled = $pluginSettings['ENABLED'];
}else{
    $enabled ="";
	logEntry("Plugin has not been enabled, exiting");
	exit(0);
}

if (isset($pluginSettings['WEATHER_API'])){
    $weatherApi = $pluginSettings['WEATHER_API'];
}else{
	$weatherApi="";
	logEntry("Weather API not defined, exiting");
	exit(1);
}

if($weatherApi=="OpenWeatherMap"){
  if (isset($pluginSettings['OpenWeatherMap'])){
		$openWeatherMap=$pluginSettings['OpenWeatherMap'];
	}else{
		$openWeatherMap="";
	  logEntry("OpenWeatherMap API key not defined, exiting");
	  exit(1);
	}
	if (isset($pluginSettings['LOOKUP_TYPE'])){
			$lookupType = intval($pluginSettings['LOOKUP_TYPE']);
	}else{
		$lookupType = 0;
		logEntry("Lookup type not specifically defined, using default");
	}
	logEntry("Lookup type: $lookupType");
  if($lookupType==0){
		if (isset($pluginSettings['LAT'])){
				$LAT = $pluginSettings['LAT'];
		}else{
			$LAT = "";
			logEntry("LAT not specifically defined, using default");
		}
		if (isset($pluginSettings['LON'])){
				$LON = $pluginSettings['LON'];
		}else{
			$LON = "";
			logEntry("LON not specifically defined, using default");
		}
	}else{
		if (isset($pluginSettings['CITY'])){
				$CITY = $pluginSettings['CITY'];
		}else{
			$CITY = "";
			logEntry("CITY not specifically defined, using default");
		}
		if (isset($pluginSettings['STATE'])){
				$STATE = $pluginSettings['STATE'];
		}else{
			$STATE = "";
			logEntry("STATE not specifically defined, using default");
		}
		if (isset($pluginSettings['COUNTRY'])){
				$COUNTRY = $pluginSettings['COUNTRY'];
		}else{
			$COUNTRY = "";
			logEntry("COUNTRY not specifically defined, using default");
		}
	}
}elseif($weatherApi=="PirateWeather"){
  if (isset($pluginSettings['PirateWeather'])){
		$pirateWeather=$pluginSettings['PirateWeather'];
	}else{
		$pirateWeather="";
	  logEntry("PirateWeather API key not defined, exiting");
	  exit(1);
	}
	if (isset($pluginSettings['LAT'])){
		$LAT = $pluginSettings['LAT'];
	}else{
		$LAT = "";
		logEntry("LAT not specifically defined, using default");
	}
	if (isset($pluginSettings['LON'])){
			$LON = $pluginSettings['LON'];
	}else{
		$LON = "";
		logEntry("LON not specifically defined, using default");
	}
}elseif($weatherApi=="AmbientWeather"){
  if (isset($pluginSettings['AmbientWeatherAPI'])){
		$ambientWeatherApi=$pluginSettings['AmbientWeatherAPI'];
	}else{
		$ambientWeatherApi="";
	  logEntry("AmbientWeather API key not defined, exiting");
	  exit(1);
	}
  if (isset($pluginSettings['AmbientWeatherAPP'])){
		$ambientWeatherApp=$pluginSettings['AmbientWeatherAPP'];
	}else{
		$ambientWeatherApp="";
	  logEntry("AmbientWeather APP key not defined, exiting");
	  exit(1);
	}
  if (isset($pluginSettings['AWDevice'])){
		$AWDEVICE=intval($pluginSettings['AWDevice']);
	}else{
		$AWDEVICE=0;
	  logEntry("AmbientWeather Device not specifically defined, using default");
	}
}


if (isset($pluginSettings['PRE_TEXT'])){
    $preText = $pluginSettings['PRE_TEXT'];
}else{
	$preText = "It is";
	logEntry("Pre Text not specifically defined, using default");
}

if (isset($pluginSettings['POST_TEXT'])){
    $postText = $pluginSettings['POST_TEXT'];
}else{
	$postText = "now";
	logEntry("Post Text not specifically defined, using default");
}


if (isset($pluginSettings['INCLUDE_TEMP'])){
    $includeTemp = $pluginSettings['INCLUDE_TEMP'];
}else{
	$includeTemp = "";
	logEntry("Include temp not specifically defined, using default");
}
if (isset($pluginSettings['TEMP_UNITS'])){
    $tempUnits = $pluginSettings['TEMP_UNITS'];
}else{
	$tempUnits = "C";
	logEntry("Temp Units not specifically defined, using default");
}

if (isset($pluginSettings['INCLUDE_WIND'])){
    $includeWind = $pluginSettings['INCLUDE_WIND'];
}else{
	$includeWind = "";
	logEntry("Include wind not specifically defined, using default");
}
if (isset($pluginSettings['WIND_UNITS'])){
    $windUnits = $pluginSettings['WIND_UNITS'];
}else{
	$windUnits = "m/s";
	logEntry("Wind Units not specifically defined, using default");
}

if (isset($pluginSettings['INCLUDE_HUMIDITY'])){
    $includeHumidity = $pluginSettings['INCLUDE_HUMIDITY'];
}else{
	$includeHumidity = "";
	logEntry("Include humidity not specifically defined, using default");
}

if (isset($pluginSettings['HOST_LOCATION']))
    $hostLocation = $pluginSettings['HOST_LOCATION'];

if (isset($pluginSettings['OVERLAY_MODEL'])){
    $overlayModel = urldecode($pluginSettings['OVERLAY_MODEL']);
}else{
    $overlayModel = reset(GetOverlayList());
	if ($overlayModel ==""){
		logEntry("****ERROR**** Overlay Model not specifically defined and there are no Pixel Overlay Models defined");
	}
    logEntry("Overlay Model not specifically defined, using default Overlay instead");
}


if (isset($pluginSettings['OVERLAY_MODE'])){
    $overlayMode = urldecode($pluginSettings['OVERLAY_MODE']);
	switch ($overlayMode){
		case "1":
			$auto="Enabled";
			break;
		case "2":
			$auto="Transparent";
			break;
		case "3":
			$auto="TransparentRGB";
			break;
		default:
			$auto="Enabled";
	}	
}else{
    $auto="Enabled";
	logEntry("Overlay Mode not specifically defined, using default Full Overlay instead");
}

if (isset($pluginSettings['FONT'])){
    $font = urldecode($pluginSettings['FONT']);
}else{
    $font = reset(getFontsInstalled());
    logEntry("Font not specifically defined, using default of first font found");
}

if (isset($pluginSettings['FONT_SIZE'])){
    $fontSize = urldecode($pluginSettings['FONT_SIZE']);
}else{
    $fontSize = "20";
    logEntry("Font Size not specifically defined, using default size of 20 instead");
}

if (isset($pluginSettings['FONT_ANTIALIAS'])){
    $fontAntialias = urldecode($pluginSettings['FONT_ANTIALIAS']);
}else{
    $fontAntialias = "";
    logEntry("AntiAlias not specifically defined, using default no AntiAlias instead");
}

if (isset($pluginSettings['SCROLL_SPEED'])){
    $scrollSpeed = urldecode($pluginSettings['SCROLL_SPEED']);
}else{
    $scrollSpeed = "20";
    logEntry("Scroll Speed not specifically defined, using default of 20 instead");
}

$Position="R2L";
if ($scrollSpeed=="0"){
	$Position="Center";
}

if (isset($pluginSettings['DURATION'])){
    $duration = urldecode($pluginSettings['DURATION']);
}else{
    $duration = "20";
    logEntry("Duration not specifically defined, using default of 20 instead");
}

if (isset($pluginSettings['COLOR'])){
    $color = urldecode($pluginSettings['COLOR']);
}else{
    $color = "#ffffff";
    logEntry("Color not specifically defined, using default of White instead");
}

if($weatherApi=="OpenWeatherMap"){
	if($lookupType==0){
		$weather = getOpenWeatherMap($openWeatherMap,$LAT,$LON);
	}elseif($lookupType==1){
		$weather = getOpenWeatherMap($openWeatherMap,null,null,$CITY,$STATE,$COUNTRY);
	}
}else if($weatherApi=="PirateWeather"){
	$weather = getPirateWeather($pirateWeather,$LAT,$LON);
}else if($weatherApi=="AmbientWeather"){
	$weather = getAmbientWeather($ambientWeatherApi,$ambientWeatherApp,$AWDEVICE);
}else{
	logEntry("Unknown weatherAPI:$weatherApi");
	exit(1);
}

$messageText = "$preText ";
if($includeTemp == "ON"){
	if($tempUnits=="C"){
		$messageText .= "Temp: ".(($weather['temp']-32)*5/9)."°C ";
	}else{
		$messageText .= "Temp: ".$weather['temp']."°F ";
	}
}
if($includeWind == "ON"){
	$windDeg = $weather['wind']['deg'];
	if($windDeg > 348 && $windDeg <=359) {
		$windDir = "N";
  } elseif($windDeg >=0 && $windDeg <=45) {
    $windDir = "N";
	} elseif($windDeg > 45 && $windDeg <=68) {
		$windDir = "NE";
	} elseif($windDeg > 68 && $windDeg <= 112) {
		$windDir = "E";
	} elseif($windDeg > 112 && $windDeg <=168) {
		$windDir = "SE";
	} elseif($windDeg > 168 && $windDeg <= 192) {
		$windDir = "S";
	} elseif($windDeg > 192 && $windDeg <= 258) {
		$windDir = "SW";
	} elseif($windDeg > 258 && $windDeg <= 282) {
		$windDir = "W";
	} elseif($windDeg > 282 && $windDeg <= 348) {
		$windDir = "NW";
	} 
	if($windUnits=='mph'){
		$windSpeed = $weather['wind']['speed']."mph";
	}elseif($windUnits=='km/h'){
		$windSpeed = windMPH2KMH($weather['wind']['speed'])."km/h";
	}elseif($windUnits=='kn'){
		$windSpeed = windMPH2KN($weather['wind']['speed'])."kn";
	}else{
		$windSpeed = windMPH2MS($weather['wind']['speed'])."m/s";
	}

	$messageText .= "Wind: $windDir $windSpeed ";
}
if($includeHumidity == "ON"){
	$messageText .= "Humidity: ".$weather['humidity']." ";
}

$messageText .=$postText;
logEntry("messageText= ".$messageText);
logEntry("ScrollText options-hostLocation=  ".$hostLocation. " overlayModel= ".$overlayModel. " Position= " .$Position. " Font = " .$font. " fontsize= " .$fontSize. " fontColor= " .$color. " scrollSpeed= " .$scrollSpeed. " Auto= " .$auto." duration= " .$duration);

ScrollText($hostLocation, $overlayModel, $messageText, $Position, $font, $fontSize, $color, $scrollSpeed, $fontAntialias, $duration, $auto);

exit(0); 
?>
