<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Import reader
require('XLSXReader.php');

// Define column numbers
define("BRANCH_ID", 46);
define("GROUP_ID", 36);
define("CATEGORY", 18);
define("NAME", 28);
define("AMOUNT", 12);
define("PONUM", 42);

// Validate file
if(pathinfo($_FILES['infile']['name'], PATHINFO_EXTENSION) == 'xlsx'){
    $xlsx = new XLSXReader($_FILES['infile']['tmp_name']);
    if(in_array("Pigott", $xlsx->getSheetNames())){
        $data = $xlsx->getSheetData('Pigott');
        $head = $data[0];
        if($head[BRANCH_ID] === 'Branch ID ' 
        && $head[GROUP_ID] == 'GroupId ' 
        && $head[CATEGORY] == 'Category number '
        && $head[NAME] == 'Employee '
        && $head[AMOUNT] == 'Amount '
        && $head[PONUM] == 'Project Order #'){
            // Parse file
            if(isset($_POST['stmtDate']) && strlen($_POST['stmtDate']) > 0){
                if(isset($_POST['acctDate']) && strlen($_POST['acctDate']) > 0){
                    $data = $xlsx->getSheetData('Pigott');
                    $out = "";
                    // iterate through lines in file
                    for($i = 1; $i < sizeof($data); $i++){
                        $line_in = $data[$i];
                        $out = $out . khamline($line_in[BRANCH_ID], $line_in[GROUP_ID], $line_in[CATEGORY], $line_in[NAME], $_POST['stmtDate'], $_POST['acctDate'], $line_in[AMOUNT]);
                    }

                    // Send file to client
                    if(sizeof(explode(".", $_FILES['infile']['name'])) > 2){
                        error("Filenames containing dots are not yet supported. This is a bug. Please rename your file and try again.");
                    }

                    $filename = explode(".", $_FILES['infile']['name'])[0] . ".csv";
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="'. $filename .'"');
                    flush();
                    print($out);
                    exit;
                }
                else{
                    error("Please enter an accounting date");
                }
            }
            else{
                error("Please enter a statement date");
            }
        }
        else{
            error("Invalid File (Header)");
        }
    }
    else{
        error("Invalid File (Sheet Name)");
    }
}
else{
    error("Invalid File Type or FIle Not found");
}

function error($msg){
    $_SESSION['msg'] = $msg;
    echo require("./index.php");
    exit;
}

// Mapping function
function khamline($branch_id, $group_id, $category, $name, $stmt_date, $acct_date, $amount){
    // map fields
    // $s_date = excdate($date);
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

// Converts an Excel date format to a formatted string
function excdate($input){
    $days = sprintf("%d", $input) - 2;
    $exc_start = new DateTime('1900-01-01T00:00:00.0');
    date_add($exc_start, date_interval_create_from_date_string($days . " days"));
    return $exc_start->format('n/d/Y');
}

// if(TRUE){
//     $filename = "dlfile.csv";
//     $filebody = "c1r1,c2r1,c3r1\nc1r2,c2r2,c3r2\n";
//     header('Content-Type: text/csv');
//     header('Content-Disposition: attachment; filename="'. $filename .'"');
//     flush();
//     print $filebody;
//     exit;
// }
// else{
//     echo file_get_contents("./index.php");
// }
?>