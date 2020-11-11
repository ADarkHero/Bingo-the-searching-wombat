<?php
	//List all files in a directory
	$fileList = glob('lists/*');
	
	//Cycles throught all files
	foreach($fileList as $filename){
		$url = "ssl.bing.com/webmaster/api.svc/pox/SubmitUrlBatch?apikey=" . "INSERT API KEY HERE";
		$input_xml = '
	<SubmitUrlBatch xmlns="http://schemas.datacontract.org/2004/07/Microsoft.Bing.Webmaster.Api">
		<siteUrl>https://www.loechel-industriebedarf.de</siteUrl>
		<urlList>';
		
		$handle = fopen($filename, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$input_xml = $input_xml . '<string xmlns="http://schemas.microsoft.com/2003/10/Serialization/Arrays">' . $line . '</string>';
			}

			fclose($handle);
		} else {
			echo "error opening the file.";
		} 
		
		$input_xml = $input_xml . '</urlList>
	</SubmitUrlBatch>';

		//Send xml to api
		$ch = curl_init();
		  curl_setopt( $ch, CURLOPT_URL, $url );
		  curl_setopt( $ch, CURLOPT_POST, true );
		  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		  curl_setopt( $ch, CURLOPT_POSTFIELDS, $input_xml );
		  $result = curl_exec($ch);
		  curl_close($ch);

		//convert the XML result into array
		$array_data = json_decode(json_encode(simplexml_load_string($result)), true);

		//Show result
		print_r('<pre>');
		print_r($array_data);
		print_r('</pre>');
		
		//Deletes file after using it
		if (!unlink($filename)) {  
			echo ("$filename cannot be deleted due to an error");  
		}  
		else {  
			echo ("$filename has been deleted");  
		}  
  
	}

	