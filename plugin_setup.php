<?php

$skipJSsettings = 1;

include_once '/opt/fpp/www/common.php';

$pluginName = "miniRDS";

include_once 'functions.inc.php';
include_once 'commonFunctions.inc.php';


$pluginUpdateFile = $settings['pluginDirectory']."/".$pluginName."/"."pluginUpdate.inc";


$logFile = $settings['logDirectory']."/".$pluginName.".log";

$myPid = getmypid();

$gitURL = "https://github.com/LightsOnHudson/FPP-Plugin-miniRDS.git";

logEntry("plugin update file: ".$pluginUpdateFile);


if(isset($_POST['updatePlugin']))
{
	$updateResult = updatePluginFromGitHub($gitURL, $branch="master", $pluginName);


}

if(isset($_POST['submit']))
{



	$STATION_ID=trim($_POST["STATION_ID"]);
	$RT_TEXT_PATH=trim($_POST["RT_TEXT_PATH"]);

	$ENABLED=$_POST["ENABLED"];

	//	echo "Writring config fie <br/> \n";

	WriteSettingToFile("STATION_ID",urlencode($STATION_ID),$pluginName);
	WriteSettingToFile("RT_TEXT_PATH",urlencode($RT_TEXT_PATH),$pluginName);


	WriteSettingToFile("ENABLED",$ENABLED,$pluginName);


}
$STATION_ID=$pluginSettings['STATION_ID'];
$RT_TEXT_PATH=$pluginSettings['RT_TEXT_PATH'];
$ENABLED = $pluginSettings['ENABLED'];

?>

<html>
<head>
</head>

<div id="miniRDS" class="settings">
<fieldset>
<legend>miniRDS Text File Support Instructions</legend>

<p>Known Issues:
<ul>
<li>NONE</li>
</ul>

<p>Install:
<ul>
<li>Give your station a name for PS_TEXT.txt</li>
<li>Save the configuration</li>
<li>Restart FPPD</li>
<li>Run a playlist with a media file with ID3 tags in it</li>

<form method="post" action="http://<? echo $_SERVER['SERVER_NAME']?>/plugin.php?plugin=miniRDSText&page=plugin_setup.php">


<?

echo "ENABLE PLUGIN: ";

if($ENABLED == "on" || $ENABLED == 1) {
	echo "<input type=\"checkbox\" checked name=\"ENABLED\"> \n";
	//PrintSettingCheckbox("Radio Station", "ENABLED", $restart = 0, $reboot = 0, "ON", "OFF", $pluginName = $pluginName, $callbackName = "");
} else {
	echo "<input type=\"checkbox\"  name=\"ENABLED\"> \n";
}
echo "<p/>\n";
?>
<p/>
Manually Set Station ID<br>
<p><label for="station_ID">Station ID:</label>
<input type="text" value="<? if($STATION_ID !="" ) { echo $STATION_ID; } else { echo "";};?>" name="STATION_ID" id="STATION_ID"></input>
(Expected format: up to 8 characters)
</p>


RT TEXT PATH:
<input type="text" value="<? if($RT_TEXT_PATH !="" ) { echo $RT_TEXT_PATH; } else { echo "/home/fpp/media/plugins/";};?>" name="RT_TEXT_PATH" size="64" id="RT_TEXT_PATH"></input>

<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">
<?
 if(file_exists($pluginUpdateFile))
 {
 	//echo "updating plugin included";
	include $pluginUpdateFile;
}
?>
</form>

<p>To report a bug, please file it against the miniRDS Text plugin project on Git: https://github.com/LightsOnHudson/FPP-Plugin-miniRDS

</fieldset>
</div>
<br />
</html>