<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Rydoo-Kham Converter </title>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-light bg-light">
            <span class="navbar-brand">
                <img src="./assets/Pigott_Logo_BrightRed.png" height="30">
            </span>
        </nav>
        <div class="container" style="padding-top: 50px;">
            <!-- Title -->
            <h2> Rydoo / Khameleon Converter </h2><br><br>

            <?php
            if(isset($_SESSION['msg']) && strlen($_SESSION['msg']) > 0){
                echo "<div class='alert alert-danger' role='alert'>" . $_SESSION['msg'] . "</div>";
                $_SESSION['msg'] = "";
                unset($_SESSION['msg']);
            }
            ?>

            <form action="handler.php" method="POST" enctype = "multipart/form-data">
                <!-- File -->
                <div class="row">
                    <div class="form-group col-sm">
                        <label for="infile">Rydoo Data File</label>
                        <input type="file" class="form-control-file" id="infile" name="infile">
                    </div>
                </div>

                <!-- Dates -->
                <div class="row"> 
                    <div class="form-group col-sm-6">
                        <label for="stmtDate">Statement Date</label>
                        <input type="text" class="form-control" id="stmtDate" name="stmtDate" aria-describedby="stmtDate" placeholder="DD-MM-YYYY">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="acctDate">Accounting Date</label>
                        <input type="text" class="form-control" id="acctDate" name="acctDate" aria-describedby="acctDate" placeholder="DD-MM-YYYY">
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary"> Convert </button>
            </form>
        </div>
    </body>
</html>