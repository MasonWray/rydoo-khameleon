<?php
// Load file
require('XLSXReader.php');
$root = dirname(__FILE__);
$file = $root . '\testfile.xlsx';
$xlsx = new XLSXReader($file);
$sheets = $xlsx->getSheetNames();
$data = $xlsx->getSheetData('Pigott');

// Extract headers
printf("Searching file: %s\n", $file);
$i = 0;
$head = $data[0];
foreach($head as $col){
    printf("%3d - %s\n", $i, $col);
    $i++;
}
?>