<?php
session_start();
include ('DBConn.php');
include ('function.php');

 

// check connection
if (!$conn ){
	die("connection failed:".mysqli_connect_error());
}
else
{
	if ($conn==true)
	{

	}
}
$sql = "INSERT INTO tbluser(id, username, userlastname, student_number, password)
VALUES ('Alicia', 'Mavunda', '101222222','123abc456dfghjk')";

if (mysqli_query($conn,$sql))

	{
	echo "congratulations your record has been added";
	
	}
	else
	{
	echo "Error:".$sql."<br>".mysqli_error($conn);
	}
mysqli_close($conn);
