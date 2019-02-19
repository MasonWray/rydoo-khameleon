<?php
require('XLSXReader.php');
$root = dirname(__FILE__);
$file = $root . '\rtest.xlsx';
$xlsx = new XLSXReader($file);
$sheets = $xlsx->getSheetNames();
$data = $xlsx->getSheetData('Pigott');

// // Extract headers
$i = 0;
$head = $data[0];
foreach($head as $col){
    printf("%3d - %s\n", $i, $col);
    $i++;
}

// var_dump($data);

// CSV file write

// write($root, "newfile", "test data");

// function write($root, $name, $data){
//     $path = sprintf("%s\%s.csv", $root, $name);
//     $file = fopen($path, "w") or die("Unable to open file!");
//     fwrite($file, $data);
//     fclose($file);
// }
?>