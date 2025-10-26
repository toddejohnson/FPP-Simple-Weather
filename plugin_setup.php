<?php


include_once "/opt/fpp/www/common.php";
include_once 'functions.inc.php';
include_once 'version.inc';

$pluginName = basename(dirname(__FILE__));


$logFile = $settings['logDirectory']."/".$pluginName.".log";

$showOpenWeatherMapDiv="display:none";
$showAmbientWeatherDiv="display:none";
$showPirateWeatherDiv="display:none";
$showCityDiv="display:none";
$showLatLonDiv="display:none";
if (isset($pluginSettings['WEATHER_API'])){
	$weatherApi=$pluginSettings['WEATHER_API'];
	if ($weatherApi == 'OpenWeatherMap'){
		$showOpenWeatherMapDiv="display:block";
		if (isset($pluginSettings['LOOKUP_TYPE'])){
			$lookupType=$pluginSettings['LOOKUP_TYPE'];
		  if($lookupType=0){
				$showLatLonDiv="display:block";
			}else{
				$showCityDiv="display:block";
			}
		}
	}elseif($weatherApi == 'PirateWeather'){
		$showPirateWeatherDiv="display:block";
	}elseif($weatherApi == 'AmbientWeather'){
		$showAmbientWeatherDiv="display:block";
	}
}


$gitURL = "https://github.com/toddejohnson/FPP-Simple-Weather.git";


?>

<html>
<head>
<style>

* {
  box-sizing: border-box;
}

.subheader {
  background-color: #f1f1f1;
  padding: 20px;
  text-align: center;
}

.col-1 {width: 8.33%;}
.col-2 {width: 16.66%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%;}
.col-5 {width: 41.66%;}
.col-6 {width: 50%;}
.col-7 {width: 58.33%;}
.col-8 {width: 66.66%;}
.col-9 {width: 75%;}
.col-10 {width: 83.33%;}
.col-11 {width: 91.66%;}
.col-12 {width: 100%;}

[class*="col-"] {
  float: left;
  padding: 15px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}

@media screen and (max-width: 1000px) {
  div.graphic {
    display: none;
  }
}

.matrix-tool-bottom-panel {
	padding-top: 0px !important;
}

.red {
	background: #ff0000;
}

.green {
	background: #00ff00;
}

.blue {
	background: #0000ff;
}

.white {
	background: #ffffff;
}

.black {
	background: #000000;
}

.colorButton {
	-moz-transition: border-color 250ms ease-in-out 0s;
	transition: border-color 250ms ease-in-out 0s;
	background-clip: padding-box;
	border: 2px solid rgba(0, 0, 0, 0.25);
	border-radius: 50% 50% 50% 50%;
	cursor: pointer;
	display: inline-block;
	height: 20px;
	margin: 1px 2px;
	width: 20px;
}

#currentColor {
    border: 2px solid #000000;
}
#scroll-container {
	width: 1000px;
  	border: 3px solid black;
  	border-radius: 5px;
  	overflow: hidden;
}

#scroll-text {	
	font-weight: bold; 
	font-size: 30px;
	
  /* animation properties */
  -moz-transform: translateX(100%);
  -webkit-transform: translateX(100%);
  transform: translateX(100%);
  
  -moz-animation: my-animation 7s linear infinite;
  -webkit-animation: my-animation 7s linear infinite;
  animation: my-animation 7s linear infinite;
}

/* for Firefox */
@-moz-keyframes my-animation {
  from { -moz-transform: translateX(100%); }
  to { -moz-transform: translateX(-100%); }
}

/* for Chrome */
@-webkit-keyframes my-animation {
  from { -webkit-transform: translateX(100%); }
  to { -webkit-transform: translateX(-100%); }
}

@keyframes my-animation {
  from {
    -moz-transform: translateX(100%);
    -webkit-transform: translateX(100%);
    transform: translateX(100%);
  }
  to {
    -moz-transform: translateX(-50%);
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
  }
}

</style>
</head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="subheader">
	<h1><?php echo $pluginName . " Version: ". $pluginVersion;?> Installation Instructions</h1>
</div>
<div class="row">
	<div class="col-12">	
		<h4>Configuration:</h4>
		<ul>
			<li>Configure your City & 2 Character State & Separator Character to display</li>
			<li>Not all City and State combinations are supported. If not, you can select the Other Country option</br>
			and use your Latitude and Longitude settings to get your local weather.<p>
			<p>Link to settings: <a href=settings.php#settings-localization> System tab</a></p>
			<li>Visit <a href="http://home.openweathermap.org/" target="_blank">http://home.openweathermap.org/</a> to sign up for an API KEY</li>
		</ul>
		<h4>Operation:</h4>
		<ul>
			<li>The Simple Weather is triggered by an FPP Command (Run Simple Weather)</li>
			<li>The Weather will display one time per FPP Command</li>
			<li>If you want a repeating Weather, you can create a repeating schedule</li>
			<li>Just make sure that you put a pause in your playlist</li>
			<li>Refer to the FPP Manual for more information</li>
			<li><a href="https://falconchristmas.github.io/FPP_Manual.pdf" target="_blank">FPP Manual</a></li>
		</ul>
	</div>
</div>			
<div class="row">
	<div class="col-12">
		<p>ENABLE PLUGIN: <?PrintSettingCheckbox("Event Date Plugin", "ENABLED", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "", $changedFunction=""); ?> </p>
		<p>Pre Text: <?  PrintSettingTextSaved("PRE_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "It is", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbspxx days xx hours</p>
		<p>Post Text <?  PrintSettingTextSaved("POST_TEXT", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "now", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>Weather API: <? PrintSettingSelect("WEATHER_API", "WEATHER_API", 0, 0, "", Array("OpenWeatherMap" => "OpenWeatherMap", "AmbientWeather" => "AmbientWeather","Pirate Weather" => "Pirate Weather"), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
	
		<div id ="showOpenWeatherMap" style= "<? echo $showOpenWeatherMapDiv; ?>">
			<p><a href="https://api.openweathermap.org/">Open Weather Map API</a> Key: <?  PrintSettingTextSaved("OpenWeatherMap", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>Lookup Type: <? PrintSettingSelect("LOOKUP_TYPE", "LOOKUP_TYPE", 0, 0, "", Array("Lat/Lon" => 0, "City/State" => 1), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
			<div id ="showCity" style= "<? echo $showCityDiv; ?>">
				<p>City: <?  PrintSettingTextSaved("CITY", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
				<p>State: <?  PrintSettingTextSaved("STATE", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
				<p>Country: <?  PrintSettingTextSaved("COUNTRY", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			</div>
			<div id ="showLatLon" style= "<? echo $showLatLonDiv; ?>">
				<p>Lat: <?  PrintSettingTextSaved("Lat", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
				<p>Lon: <?  PrintSettingTextSaved("Lon", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			</div>
		</div>
		<div id ="showPirateWeather" style= "<? echo $showPirateWeatherDiv; ?>">
			<p><a href="https://docs.pirateweather.net/">Pirate Weather API</a> Key: <?  PrintSettingTextSaved("PirateWeather", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>Lat: <?  PrintSettingTextSaved("Lat", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>Lon: <?  PrintSettingTextSaved("Lon", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		</div>
		<div id ="showAmbientWeather" style= "<? echo $showAmbientWeatherDiv; ?>">
			<p><a href="https://ambientweather.com/faqs/question/view/id/1811/?srsltid=AfmBOoppEAQYKPD651SXgUIbzCzx3tEhTbJ7GpkMWMCuQfvRgrPGl3nc">AmbientWeather</a> API Key: <?  PrintSettingTextSaved("AmbientWeatherAPI", 0, 0, $maxlength = 70, $size = 70, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>AmbientWeather APP Key: <?  PrintSettingTextSaved("AmbientWeatherAPP", 0, 0, $maxlength = 70, $size = 70, $pluginName, $defaultValue = "", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
			<p>AmbientWeather Device: <?  PrintSettingTextSaved("AWDevice", 0, 0, $maxlength = 32, $size = 32, $pluginName, $defaultValue = "0", $callbackName = "updateOutputText", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		</div>
		
	
		<p>Include Temp: <?PrintSettingCheckbox("INCLUDE_TEMP", "INCLUDE_TEMP", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputText", $changedFunction = ""); ?> </p>
		<p>Temp Units: <? PrintSettingSelect("TEMP_UNITS", "TEMP_UNITS", 0, 0, "", Array("C" => "C", "F" => "F"), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
		<p>Include Wind: <?PrintSettingCheckbox("INCLUDE_WIND", "INCLUDE_WIND", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputText", $changedFunction = ""); ?> </p>
		<p>Wind Units: <? PrintSettingSelect("WIND_UNITS", "WIND_UNITS", 0, 0, "", Array("mph" => "mph", "m/s" => "m/s", "km/h" => "km/h", "kn" => "kn"), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
		<p>Include Humidity: <?PrintSettingCheckbox("INCLUDE_HUMIDITY", "INCLUDE_HUMIDITY", 0, 0, "ON", "OFF", $pluginName ,$callbackName = "updateOutputText", $changedFunction = ""); ?> </p>
		<p>Your message will appear as:</p>
		<div id="scroll-container" >
			<div id="scroll-text">Weather </div>
		</div>
		
		<br /><div>Font: <? PrintSettingSelect("fontSelect", "FONT", 0, 0, $defaultValue="", getFontsInstalled(), $pluginName, $callbackName = "updateFont", $changedFunction = ""); ?>
		Font Size: <? PrintSettingSelect("FONT_SIZE", "FONT_SIZE", 0, 0, $defaultValue="20", getFontSizes(), $pluginName, $callbackName = "", $changedFunction = ""); ?>
		Anti-Aliased: <?PrintSettingCheckbox("FONT_ANTIALIAS", "FONT_ANTIALIAS", 0, 0, "1", "", $pluginName , ""); ?></div> 
		
		<div id= "divCanvas" class='ui-tabs-panel matrix-tool-bottom-panel'>
			<table border=0>
				<tr><td valign='top'>
				<div>
					<table border=0>
						<tr><td valign='top'>Pallette:</td>
							<td><div class='colorButton red' onClick='setColor("#ff0000");'></div>
								<div class='colorButton green' onClick='setColor("#00ff00");'></div>
								<div class='colorButton blue' onClick='setColor("#0000ff");'></div>
								<div class='colorButton white' onClick='setColor("#ffffff");'></div>
								<div class='colorButton black' onClick='setColor("#000000");'></div>
							</td>
						</tr>
						<tr><td>Current Color:</td><td><span id='currentColor'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td></tr>
						<tr><td colspan='2'>Show Color Picker: <? PrintSettingCheckbox("Show Color Picker", "ShowColorPicker", 0, 0, "1", "0", $pluginName, "ShowColorPicker"); ?></td></tr>
						<tr><td valign='top' colspan='2'>
						<div id="colpicker"></div>
						</td></tr>
					</table>
				</div>
				</td></tr>
			</table>
		</div>
		<p><b>If you set the scroll speed to 0, then the message will display on the center of the matrix <br/>
		for the number of seconds set in the Duration</b></p> 
		Scroll Speed: <? PrintSettingSelect("SCROLL_SPEED", "SCROLL_SPEED", 0, 0, $defaultValue="20", getScrollSpeed(), $pluginName, $callbackName = "ShowDuration", $changedFunction = ""); ?> </p>
		<div id="showDuration" style= "<? echo $showScrollDiv; ?>">
			Duration: <? PrintSettingSelect("DURATION", "DURATION", 0, 0, $defaultValue="10", getDuration(), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
		</div>
		
		<p>Matrix Name: <? PrintSettingSelect("OVERLAY_MODEL", "OVERLAY_MODEL", 0, 0, $defaultValue="", $values = GetOverlayList(), $pluginName, $callbackName = "", $changedFunction = ""); ?>
		If this is blank, then you need to configure the correct Pixel Overlay Model</p>
		<p>Overlay Mode: <? PrintSettingSelect("OVERLAY_MODE", "OVERLAY_MODE", 0, 0, "", Array("Full Overlay" => "1", "Transparent" => "2", "Transparent RGB" => "3"), $pluginName, $callbackName = "", $changedFunction = ""); ?> </p>
		<p><h3>The Overlay mode determines how you want your message to display.</h3>
		<ul>
			<li>Full Overlay- This will blank out the model and only display your message</li>
			<li>Transparent- This will display your message over the top of whatever is displaying on your matrix <br/>
			but the colors will blend slightly with what is currently being displayed</li>
			<li>Transparent RGB- This will display your message over the top of whatever is displaying on your matrix <br/>
			the colors will override what is currently being displayed</li> 
		</ul>
		
		<p>To report a bug, please file it on the Simple Weather plugin project on Git:<a href= "<? echo $gitURL;?>" target=blank>Simple Weather Repository</a> </p>
		<p>Host Location: <?  PrintSettingTextSaved("HOST_LOCATION", 0, 0, $maxlength = 16, $size = 16, $pluginName, $defaultValue = "127.0.0.1", $callbackName = "", $changedFunction = "", $inputType = "text", $sData = array());?> </p>
		<p>The default location of 127.0.0.1 is used if you want to display your Weather on an Overlay Model directly connected to this device. <br />
		You can send the Weather text to another FPP device by entering that IP address for the Host Location. The Host location will need <br />
		to have the Pixel Overlay Model defined and this FPP will need to have the Pixel Overlay Model defined exactly as the Host FPP Device</p>
	</div>
</div>

<script>
updateOutputText();
ShowColorPicker();

function ShowColorPicker() {
	if ($('#ShowColorPicker').is(':checked')) {
            $('#colpicker').show();
    } else {
            $('#colpicker').hide();
    }
}

function setColor(color, updateColpicker = true) {
	if (color.substring(0,1) != '#')
		color = '#' + color;
    pluginSettings['COLOR'] = color;
    SetPluginSetting('<?php echo $pluginName; ?>', 'COLOR', color, 0, 0);
    $('#currentColor').css('background-color', color);
	currentColor = color;
    if (updateColpicker)
		$('#colpicker').colpickSetColor(color);
		
}
    var colpickTimer = null;
	$('#colpicker').colpick({
		flat: true,
		layout: 'rgbhex',
		color: '#ff0000',
		submit: false,
		onChange: function(hsb,hex,rgb,el,bySetColor) {
            if (colpickTimer != null)
                clearTimeout(colpickTimer);

            colpickTimer = setTimeout(function() { setColor('#'+hex, false); }, 500);
		}
	});

    if (pluginSettings.hasOwnProperty('COLOR') && pluginSettings['COLOR'] != '') {
        currentColor = pluginSettings['COLOR'];
        $('#currentColor').css('background-color', currentColor);
    }
	
function updateOutputText(){
	var messageText= getMessageText();
	document.getElementById("scroll-text").innerHTML = messageText;
}

function updateFont(){
	var fontStyle= document.getElementById("FONT").value;
	document.getElementById('scroll-text').style.fontFamily = fontStyle;	
	updateOutputText();
}

function getMessageText(){
	var preText = document.getElementById("PRE_TEXT").value;
	var postText = document.getElementById("POST_TEXT").value;
	var incTemp = document.getElementById("INCLUDE_TEMP").checked;
	var tempUnits = document.getElementById("TEMP_UNITS").value;
	var temp = 0;
	var incWind = document.getElementById("INCLUDE_WIND").checked;
	var windUnits = document.getElementById("WIND_UNITS").value;
	var wind = 'N 2'
	var incHumidity = document.getElementById("INCLUDE_HUMIDITY").checked;
	var humidity = 30;
	var messageText;
				
	messageText = preText + " ";
	
	if(incTemp == true){
		messageText += "Temp: "+temp+"°"+tempUnits+" ";
	}
	if(incWind == true){
		messageText += "Wind: "+wind+windUnits+" ";
	}
  if(incHumidity == true){
		messageText += "Humidity: "+humidity+" ";
	}
	messageText +=postText;
	return messageText;
}

function ShowDuration(){
	var scrollSpeed = document.getElementById('SCROLL_SPEED').value;
	if (scrollSpeed ==0){
		document.getElementById('showDuration').style.display = "block";
	}else{
		document.getElementById('showDuration').style.display = "none";
	}
}


</script>
</html>