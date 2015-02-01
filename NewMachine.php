<html>
<body>
<?php

$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
//gets location, brand, and max from NewMachine.html
$location = $_POST["location"];
$brand = $_POST["brand"];
$max = $_POST["max"];
//uses today's date for dateAdded
$dateAdded = date("Y").'/'.date("m").'/'.date("d");

//adds new popMachine and balance rows
$query = 'INSERT INTO PopMachine VALUES (machine_sequence.nextval, :location, TO_DATE(:dateAdded, \'yyyy/mm/dd\'), :brand, :max)';
$query2 = 'INSERT INTO Balance VALUES (bal_sequence.nextval, machine_sequence.currval, 0, 0, 0, 0, 0)';
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ':location', $location);
oci_bind_by_name($stid, ':dateAdded', $dateAdded);
oci_bind_by_name($stid, ':brand', $brand);
oci_bind_by_name($stid, ':max', $max);

$r = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

$stid = oci_parse($conn, $query2);
$s = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

//prints success statement if both rows are sucessfully inserted
if ($r and $s)
{
   echo 'Pop Machine Successfully Added'.'<br>';
}
else
{
   echo 'Machine not successfully added'.'<br>';
}
oci_close($conn);
?>

<!Link back to main page>
<a href=StartPage.php> Main Page</a>
<br><br>
<!Link to the New Machine page>
<a href=NewMachine.html> New Machine</a>
</body>
</html>
</body>
</html>
