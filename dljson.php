<?php
$dir = $_GET['dir'];

//prevent malicious use to gain access to unauthorised directories
if(strpos($dir,"/../")) die('Malicious Use');
if(strpos($dir,"/./")) die('Malicious Use');
//easiest way to prevent is to ensure the $_GET['dir'] var starts with a given directory
//you may also prepend the $_GET['dir] variable with a directory and pass "/" in the directory widget options
if(!preg_match("(^files/)",$dir)) die('Malicious Use');

if(is_file($dir)) {
	$stat = stat($dir);
	$size = $stat['size'];
	$mod = $stat['mtime'];
	$ext = end(explode('.', $dir));
	$name = end(explode('/', $dir));
	$contentsarray[] = array("size"=>$size,"ext"=>$ext,"mod"=>date("d-m-Y",$mod),"name"=>$name);
	echo json_encode($contentsarray);
}
else if($dir) {
	if ($handle = opendir($dir)) {
	    while (false !== ($entry = readdir($handle))) {
			$dName = $dir . $entry;
			if(is_file($dName)) $ext = end(explode('.', $entry));
			else $ext = '';
	        if(!in_array($entry,array('.','..')) && substr($entry, 0, 1) != '.') $contentsarray[] = array("name"=>$entry,"ext"=>$ext);
	    }
	
		echo json_encode($contentsarray);

		closedir($handle);
	}
}
?>