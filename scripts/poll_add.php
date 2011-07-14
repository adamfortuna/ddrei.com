<?php
	if(isset($Go))
	{
		include("database.php");
		$con = @mysql_connect($host, $login, $pass);
		mysql_select_db($database);

		$query="SELECT votes, ips FROM poll WHERE id=$id";
		$result = mysql_query($query);
		$r=@mysql_fetch_array($result);
		$tmp_total = $r["votes"];
		$tmp_total++;
		$new_ips=$r["ips"];


		if(!strpos($new_ips, $REMOTE_ADDR))
		{
			//poll add
			$new_ips="$new_ips $REMOTE_ADDR";
			$query2 = "UPDATE poll set votes='$tmp_total', ips='$new_ips' WHERE id=$id";
			$result2 = mysql_query($query2);

			//increment the voted option
			$query="SELECT total FROM poll2 WHERE id=$vote1";
			$result = mysql_query($query);
			$s=@mysql_fetch_array($result);
			$s_total=$s["total"];
			$s_total++;
			$query2 = "UPDATE poll2 set total='$s_total' WHERE id=$vote1";
			$result2 = mysql_query($query2);
		}
		header("Location:http://ddrei.com/polls.php?id=$id");
	}
	else { header("Location:http://ddrei.com"); }
?>