<?php
$format=true;


$keyword = "nothing selected";

$name = dirname(__FILE__);
scan($name, $keyword);
function scan($name, $keyword){
	$scan = scandir($name);
	$base=basename(__FILE__);
	global $format;
	foreach($scan as $file){
		if(is_file($name.'/'.$file) && $file!=$base){
			$asu=explode('.', $file);
			$ext=trim(strtolower(end($asu)));
			if(is_array($format) && in_array($ext, $format))check($name.'/'.$file, $keyword);
			else if($format===true)check($name.'/'.$file, $keyword);
			
			//$dir = dirname($file);
			//echo $name."/".$file."\n";
		}else if(is_dir($name.'/'.$file) && $file!='.' && $file!='..'){
			scan($name.'/'.$file, $keyword);
			
			//echo $a." ".$file."\n";
			//scan($name."/".$file, $keyword);
		}
	}
}
function check($file, $keyword){
	$fp = fopen($file, 'r')or die("homo");
	$i=1;
	while(!feof($fp)){
		$data=fgets($fp);
		if(preg_match("{".preg_quote($keyword)."}is", $data)){
			//echo "[!]Found '.$keyword."' in ".$file." line ".$i." : ".$data;
			echo "[!]Found '$keyword' in ".$file." line ".$i." : ".trim($data)."\n";
		}
		$i++;
	}
	fclose($fp);
	
}
?>
