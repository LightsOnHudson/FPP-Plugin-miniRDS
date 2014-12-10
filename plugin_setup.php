<?php
//$DEBUG=true;
$miniRDSSettingsFile = "/home/pi/media/plugins/miniRDSText.settings";
if(isset($_POST['submit']))
{
    $name = htmlspecialchars($_POST['station']);
   
    $rt_text_path= htmlspecialchars($_POST['rt_text_path']);
  
		//echo "Station Id set to: ".$name;

		$miniRDSSettings = fopen($miniRDSSettingsFile, "w") or die("Unable to open file!");
		$txt = "STATION_ID=".$name."\r\n";
	
		$txt .= "RT_TEXT_PATH=".$rt_text_path."\r\n";
	
		fwrite($miniRDSSettings, $txt);
		fclose($miniRDSSettings);
		$STATION_ID=$name;
	

		$RT_TEXT_PATH= $rt_text_path;
	//add the ability for GROWL to show changes upon submit :)
	//	$.jGrowl("Station Id: $STATION_ID");	
        
  
 

} else {

	if($DEBUG)
		echo "READING FILE: <br/> \n";
	//try to read the settings file if available

	$file_handle = fopen($miniRDSSettingsFile, "r");
	while (!feof($file_handle)) 
	{
   		$filedata = fgets($file_handle);
	}

	$filedata=file_get_contents($miniRDSSettingsFile);
	if($filedata !="" )
	{
		$settingParts = explode("\r",$filedata);
		$configParts=explode("=",$settingParts[0]);
		$STATION_ID = $configParts[1];
		
		
	
                $configParts=explode("=",$settingParts[1]);
                $RT_TEXT_PATH= $configParts[1];
	
	
	}
	fclose($file_handle);

}
        if($DEBUG) {
		echo "STATION: ".$STATION_ID."<br/> \n";
		
		echo "RT TEXT PATH: ".$RT_TEXT_PATH."<br/> \n";
                echo "IP: ".$IP."<br/> \n";
              
                }
?>

<html>
<head>
</head>

<div id="miniRDSText" class="settings">
<fieldset>
<legend>miniRDS Text File Support Instructions</legend>

<p>Known Issues:
<ul>
<li>NONE</li>
</ul>

<form method="post" action="http://<? echo $_SERVER['SERVER_NAME']?>/plugin.php?plugin=BetaBrite&page=plugin_setup.php">
Manually Set Station ID<br>
<p><label for="station_ID">Station ID:</label>
<input type="text" value="<? if($STATION_ID !="" ) { echo $STATION_ID; } else { echo "";};?>" name="station" id="station_ID"></input>
(Expected format: up to 8 characters)
</p>


RT TEXT PATH:
<input type="text" value="<? if($RT_TEXT_PATH !="" ) { echo $RT_TEXT_PATH; } else { echo "";};?>" name="rt_text_path" size="64" id="rt_text_path"></input>

<p/>
<input id="submit_button" name="submit" type="submit" class="buttons" value="Save Config">
</form>


<p>To report a bug, please file it against the miniRDS Text plugin project on Git: https://github.com/LightsOnHudson/FPP-Plugin-miniRDS

</fieldset>
</div>
<br />
</html>
