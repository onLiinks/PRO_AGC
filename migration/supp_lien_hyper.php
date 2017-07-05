<?php
// Open file for read and string modification
$file = "candidat_prosper1.sql";
$fh = fopen($file, 'r+');
$contents = fread($fh, filesize($file));

$pattern = "/#Z(.*?)#/";
$new_contents = preg_replace($pattern, '', $contents); 

echo $new_contents;

fclose($fh);

/*
$fh = fopen($file, 'r+');
fwrite($fh, $new_contents);
fclose($fh);*/
?>