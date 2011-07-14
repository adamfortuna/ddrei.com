<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php
	echo ereg("([a|b])*(ab)([c])*",$_POST["str"]) ? "Valid" : "Invalid";


	echo  preg_match("/([a|b])*(ab)([c])*/",$_POST["str"]) ? "Valid2" : "Invalid2";
?>

<form> Letter: <input type = "text" name = "str" /><input type = "submit" /></form>

</body>

</html>
