<?php
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

$result = mysqli_query($conn, "SELECT * FROM herkansing");
if(!$result){
	die ('Can\'t use herkansing : ' . mysql_error());
}

echo "<table border='1'>
<tr>
<th>Leerling</th>
<th>Achternaam</th>
<th>Voornaam</th>
<th>studie</th>
<th>vak_toets</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['Leerling'] . "</td>";
echo "<td>" . $row['Achternaam'] . "</td>";
echo "<td>" . $row['Voornaam'] . "</td>";
echo "<td>" . $row['studie'] . "</td>";
echo "<td>" . $row['vak_toets'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
?>
