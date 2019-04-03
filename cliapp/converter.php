<?php
require('XLSXReader.php');
$root = dirname(__FILE__);
$dir_in = $root . '\\' . 'in';
$dir_out = $root . '\\' . 'out';

// Define column numbers
define("BRANCH_ID", 46);
define("GROUP_ID", 36);
define("CATEGORY", 18);
define("NAME", 28);
define("AMOUNT", 12);
define("PONUM", 42);

// Print header
print("Rydoo -> Khameleon DFCS\n");
print("Mason Wray 2019 - v0.3a\n");
print("\n");

// Verify data directories
if(!file_exists($dir_in)){
    mkdir($dir_in);
}

if(!file_exists($dir_out)){
    mkdir($dir_out);
}

// Search directory for XLSX files
printf("Searching '%s' for Rydoo data files.\n", $dir_in);
$dir = scandir($dir_in);
$files = array();
foreach($dir as $file){
    if(pathinfo($file, PATHINFO_EXTENSION) == "xlsx"){
        $xlsx = new XLSXReader($dir_in . "\\" . $file);
        if(in_array("Pigott", $xlsx->getSheetNames())){
            $data = $xlsx->getSheetData('Pigott');
            $head = $data[0];
            if($head[BRANCH_ID] === 'Branch ID ' 
            && $head[GROUP_ID] == 'GroupId ' 
            && $head[CATEGORY] == 'Category number '
            && $head[NAME] == 'Employee '
            && $head[AMOUNT] == 'Amount '
            && $head[PONUM] == 'Project Order #'){
                array_push($files, $file);
                printf("  %-32s  OK\n", $file);
            }
            else{
                printf("  %-32s  INVALID HEADER\n", $file);
            }
        }
    }
}
print("\n");
printf("Discovered %d file(s).\n", sizeof($files));

// Extract data from XLSX files
// iterate through files in directory
foreach($files as $file){
    print("\n");
    printf("Manual date entry required for file '%s'\n", $file);
    $date_statement = readline("  Statement date: ");
    $date_accounting = readline("  Accounting date: ");
    $xlsx = new XLSXReader($dir_in . "\\" . $file);
    $data = $xlsx->getSheetData('Pigott');
    $out = "";
    // iterate through lines in file
    for($i = 1; $i < sizeof($data); $i++){
        $line_in = $data[$i];
        $out = $out . khamline($line_in[BRANCH_ID], $line_in[GROUP_ID], $line_in[CATEGORY], $line_in[NAME], $date_statement, $date_accounting, $line_in[AMOUNT]);
    }

    write($dir_out, pathinfo($file, PATHINFO_FILENAME), $out);
}

print("\n");
printf("Wrote %d file(s) to %s\n", sizeof($files), $dir_out);
readline("Press 'enter' to close the application.");

// Mapping function
function khamline($branch_id, $group_id, $category, $name, $stmt_date, $acct_date, $amount){
    // map fields
    $s_date = excdate($date);
    $kham_id = "517000000";
    $dept_id = $branch_id . $group_id;
    $gl_id = $category;
    $desc_short = explode(" ", $name)[0][0] . explode(" ", $name)[1] . " CC";
    $desc_long = explode(" ", $name)[0][0] . explode(" ", $name)[1] . " CC " . $stmt_date;
    $amount = $amount;
    $acct_date = $acct_date;
    $type = "1";

    // add GL special cases
    if($gl_id == "147050"){
        $desc_long = $desc_long . " Employee Rec"; 
    }

    if($gl_id == "210150"){
        $dec_long = $desc_long . " proj 12345.001";
    }

    return sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n", $kham_id, $dept_id, $gl_id, $desc_short, $desc_long, $amount, $acct_date, $type);
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