<?php
/**
SET THE VARIABLES TO SUIT THE SCRIPTS BEHAVIOR:
1) SIZE TO ONLY ANALYZE
2) PATH WHERE TO LIST ALL THE FILES
3) MAX LINES 
*/
$file_size_threshold = .001; //in MB
$max_lines = 10; 
$dir = '/Path/to/logs/';

$d = dir($dir);
while($entry = $d->read()){
	if( $entry != '.' && $entry != '..'){
		$fsize = filesize($dir . $entry);
		$format = 'filename: %s, file size: %.03d MB';
		$finfo = new finfo(FILEINFO_MIME);		
		
		if($fsize >= ($file_size_threshold * 1024 * 1024)){
			//echo sprintf($format, $entry, ($fsize / 1024 / 1024)) . "\n";
			//echo $finfo->file($dir . $entry) . "\n";
			$mime = explode("; ",$finfo->file($dir . $entry));
			//echo $mime[0] . "\n";
			//only split with text/plain mime
			if($mime[0] === 'text/plain'){
				echo "file is text: $entry\n";
			
				//read the file
				$handle = fopen($dir . $entry, 'r') or die("unable to open the file");
				$j = 0;	
				while(! feof($handle)){

					$log = fopen($dir . "log_split_" . $j . ".log", "a");
					for($i = 0; $i <= $max_lines; $i++){
						fwrite($log, fgets($handle));
						$i++;									
					}			
					fclose($log);
					$j++;
				}
				fclose($handle);
			}
		}	
		
	}
}
$d->close();
	
