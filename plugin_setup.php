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

	
	if (file_exists($miniRDSSettingsFile)) {
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

<p>Install:
<ul>
<li>Create folder for RDS text on /home/pi/media/RDS/ (For example) DO NOT USE ROOT TO CREATE THE ACCOUNT. Use the PI account</li>
<li>Give your station a name for PS_TEXT.txt</li>
<li>Enter the path to the RDS TEXT below '/home/pi/media/RDS/ (must include trailing slash at this point)</li>
<li>Save the configuration</li>
<li>Restart FPPD</li>
<li>Run a playlist with a media file with ID3 tags in it</li>
<li>Mount a drive on your miniRDS windows machine to point to the fpphost</li>
<li>Point your mniniRDS dynamic radio text file to \\<fpphost>\path\RT_TEXT.txt for RT Text (in windows format)</li>
<li>Point your mniniRDS dynamic radio text file to \\<fpphost>\path\PS_TEXT.txt for PS Text (in windows format)</li>
</ul>

You need to run a media file first to create the initial RT_TEXT and PS_TEXT files. Will update it so it will create shells at the beginning of plugin.

<form method="post" action="http://<? echo $_SERVER['SERVER_NAME']?>/plugin.php?plugin=miniRDSText&page=plugin_setup.php">
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
