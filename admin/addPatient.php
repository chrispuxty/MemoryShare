<?php include "../config.php";
$query = mysqli_query($db,"select * from patients");
$patients = Array();
	$temp = mysqli_fetch_assoc($query);
	while ($temp != false) {array_push($patients,$temp); $temp = mysqli_fetch_assoc($query);}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dementia Project - Edit Configuration</title>
</head>

<body>
<a href="index.html">Return to Menu</a><br/>
<form action="editConfig.php" method="post">
<input type="text" name="name" placeholder="Name" />

</form>
</body>
</html>