<?php
require('php-excel-reader/excel_reader2.php');

require('SpreadsheetReader.php');

$servername = "localhost";
$username = "root";
$password = "M0nsters";
$database = "herkansingen";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
} 

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $uploadOk = 1;
}
// Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "xls" && $imageFileType != "xlsx" ) {
    echo "Sorry '" . $imageFileType . "'' is not allowed, only XLS & XLSB files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

        // delete
        $result = mysqli_query($conn, "DELETE FROM herkansing");
        if(!$result){
            die ('Can\'t delete herkansing : ' . mysql_error());
        }

        // load

        $Reader = new SpreadsheetReader($target_file);
        $Sheets = $Reader -> Sheets();

        foreach ($Sheets as $Index => $Name)
        {
            if ($Name == "Blad1") {
                $Reader -> ChangeSheet($Index);

                echo "<hr/>Imported data:<hr/>";
                echo "<table>";
                echo "<tr>";
                echo "<th>Leerling</th><th>Achternaam</th><th>Voornaam</th><th>Studie</th><th>Vak / Toets</th>";
                echo "</tr>";

                foreach ($Reader as $Row)
                {
                    $leerling = $Row[0];
                    $achternaam = $Row[1];
                    $voornaam = $Row[2];
                    $studie = $Row[3];
                    $vak_toets = $Row[4];

                    echo "<tr>";
                    if (is_numeric($leerling)) {
                        echo "<td>".$leerling."</td><td>".$achternaam."</td><td>".$voornaam."</td><td>".$studie."</td><td>".$vak_toets."</td>";

                        $stmt = $conn->prepare("INSERT INTO herkansing (Leerling,Achternaam,Voornaam,studie,vak_toets)VALUES(?,?,?,?,?);");
                        $stmt->bind_param("issss", $leerling, $achternaam, $voornaam, $studie, $vak_toets);
                        $stmt->execute();
                    }
                    echo "</tr>";
                }

                echo "</table>";
            }
        }

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
