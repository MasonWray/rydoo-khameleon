<?php
require('XLSXReader.php');
$root = dirname(__FILE__);

// Print header
print("Rydoo -> Khameleon DFCS\n");
print("Mason Wray 2019 - v0.1a\n");
print("\n");

// Search directory for XLSX files
print("Searching script directory for Rydoo data files...");
$dir = scandir($root);
$files = array();
foreach($dir as $file){
    if(pathinfo($file, PATHINFO_EXTENSION) == "xlsx"){
        $xlsx = new XLSXReader($root . "\\" . $file);
        if(in_array("Pigott", $xlsx->getSheetNames())){
            $data = $xlsx->getSheetData('Pigott');
            $head = $data[0];
            if($head[45] === 'Branch ID ' && $head[36] == 'GroupId ' && $head[18] == 'Category number ' && $head[12] == 'Amount ' && $head[15] == 'Date completed '){
                array_push($files, $file);
            }
        }
    }
}
print("done.\n");
// var_dump($files);

// Extract data from XLSX files
print("Writing new data...");
// iterate through files in directory
foreach($files as $file){
    $xlsx = new XLSXReader($root . "\\" . $file);
    $data = $xlsx->getSheetData('Pigott');
    $out = "";
    // iterate through lines in file
    for($i = 1; $i < sizeof($data); $i++){
        $line_in = $data[$i];
        $out = $out . khamline($line_in[45], $line_in[36], $line_in[18], "s_desc", "l_desc", $line_in[12], $line_in[15]);
    }

    write($root, pathinfo($file, PATHINFO_FILENAME), $out);
    // print($out);
}
print("done\n");

// Mapping function
function khamline($branch_id, $group_id, $category, $desc_short, $desc_long, $amount, $date){
    $s_date = excdate($date);
    return sprintf("517000000,%s,%s,%s,%s,%s,%s,1\n", $branch_id . $group_id, $category, $desc_short, $desc_long, $amount, $s_date);
}

// CSV file write
function write($root, $name, $data){
    $path = sprintf("%s\%s.csv", $root, $name);
    $file = fopen($path, "w") or die("Unable to open file!");
    fwrite($file, $data);
    fclose($file);
}

// Converts an Excel date format to a formatted string
function excdate($input){
    $days = sprintf("%d", $input) - 2;
    $exc_start = new DateTime('1900-01-01T00:00:00.0');
    date_add($exc_start, date_interval_create_from_date_string($days . " days"));
    return $exc_start->format('n/d/Y');
}
?>