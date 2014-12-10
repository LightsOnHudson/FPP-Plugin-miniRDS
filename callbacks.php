#!/usr/bin/php
<?
error_reporting(0);
//require 'config/config.inc';
include 'config/functions.inc';
$miniRDSSettings = "/home/pi/media/plugins/miniRDSText.settings";
include 'config/php_serial.class.php';
//arg0 is  the program
//arg1 is the first argument in the registration this will be --list
//$DEBUG=true;
$logFile = "/home/pi/media/logs/miniRDSText.log";

$callbackRegisters = "media\n";

switch ($argv[1])
	{
		case "--list":
			echo $callbackRegisters;
			logEntry("FPPD List Registration request: responded:". $callbackRegisters);
			exit(0);

		case "--type":
			//we got a register request message from the daemon
			processCallback($argv);	
			break;

		default:
			logEntry($argv[0]." called with no parameteres");
			break;	
	}
exit(0);

function processCallback($argv) {

	global $DEBUG;

	if($DEBUG)
		print_r($argv);
	//argv0 = program
		
	//argv2 should equal our registration // need to process all the rgistrations we may have, array??
	//argv3 should be --data
	//argv4 should be json data

	$registrationType = $argv[2];
	$data =  $argv[4];

	logEntry($registrationType . " registration requestion from FPPD daemon");

	switch ($registrationType) 
	{
		case "media":
			if($argv[3] == "--data")
			{
				$data=trim($data);
				logEntry("DATA: ".$data);
				$obj = json_decode($data);
				$songTitle = $obj->{'title'};
				$songArtist = $obj->{'artist'};
				logEntry("Song Title: ".$songTitle." Artist: ".$songArtist);

				sendMessage($songTitle,$songArtist);

			}	
		break;

	}

}

function logEntry($data) {

	global $logFile;

	$logWrite= fopen($logFile, "a") or die("Unable to open file!");
		fwrite($logWrite, date('Y-m-d h:i:s A',time()).": ".$data."\n");
		fclose($logWrite);
}


//function send the message

function sendMessage($songTitle,$songArtist) {

	global $miniRDSSettingsFile;

//read in the configuration
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
		
		
	
                $configParts=explode("=",$settingParts[3]);
                $RT_TEXT_PATH= $configParts[1];
	
		
	}
	fclose($file_handle);

	logEntry("reading config file");
	logEntry("Station_ID: ".$STATION_ID." DEVICE: ".$DEVICE." DEVICE_CONNECTION_TYPE: ".$DEVICE_CONNECTION_TYPE." RT TEXT PATH: ".$RT_TEXT_PATH." IP: ".$IP. " PORT: ".$PORT." LOOPMESSAGE: ".$LOOPMESSAGE." Color: ".$cl_color);


       

		
		$f = fopen($RT_TEXT_PATH."PS_TEXT.txt", "w"); 
		fwrite($f, $STATION_ID); 
		fclose($f);

                $f = fopen($RT_TEXT_PATH."RT_TEXT.txt", "w");
                fwrite($f, $songTitle." - ".$songArtist);
                fclose($f);
		
	

}
?>
