<?php

	include("header.php");

	if(isset($Go))
	{
		$query="SELECT votes, ips FROM poll WHERE id=$id";
		$result = mysql_db_query($database, $query);
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
			echo "Thank you.  You can view the results of the poll <a href=polls.php?id=$id>here</a>.";
		}
	}
	else if($id)
	{
		$query="SELECT * FROM poll WHERE id=$id";
		$result=@mysql_db_query($database, $query);
		$s=@mysql_fetch_array($result);
		$name2=$s["name"];
		$ips=$s["ips"];
		$lock=$s["lock"];

		$path="<a href=polls.php>Polls</a> :: $name2";
		$done = strpos($ips, $REMOTE_ADDR);

		if(!$done) $done = $lock;

		if(!$done) // haven't voted or not locked
		{
			$res_query="SELECT * FROM poll2 WHERE p_id=$id";
			$res_result=@mysql_db_query($database, $res_query);
			$cur=0;
			while($t=@mysql_fetch_array($res_result))
			{
				$name[$cur] = $t["name"];
				$r_id[$cur] = $t["id"];
				$cur++;
			}
			$type[3]=1;
		}
		else
		{
			$res_query="SELECT * FROM poll2 WHERE p_id=$id";
			$res_result=@mysql_db_query($database, $res_query);
			$cur=0; $totalvotes=0;
			while($t=@mysql_fetch_array($res_result))
			{

				$name[$cur] = $t["name"];
				$votes[$cur] = $t["total"];
				$r_id[$cur] = $t["id"];
				$totalvotes+=$votes[$cur];
				$cur++;
			}
			$type[4]=1;
		}
	}
	else
	{
		$query="SELECT * FROM poll order by date desc";
		$result=@mysql_db_query($database, $query);

		$cur=0;
		while($r=@mysql_fetch_array($result))
		{
			$id[$cur]=$r["id"];
			$name[$cur]=$r["name"];
			$day[$cur]=$r["day"];
			$votes[$cur]=$r["votes"];
			$locked[$cur]=$r["lock"];
			$ips=$r["ips"];

			$done[$cur] = strpos($ips, $REMOTE_ADDR);
			$cur++;
		}
		$type[0]=1; $path="Polls";
	}

	echo "<strong><font face=Arial color=#000080>$path</font></strong><BR>";

	if($type[0])
	{

		echo "<table border=0 cellpadding=5 cellspacing=5 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495><ul type=\"circle\">";
		for($k=0; $k<$cur; $k++)
		{
			echo "<li><b><a href=polls.php?id=$id[$k]>$name[$k]</a></b><br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(started $day[$k]) $votes[$k] votes";
			if($locked[$k]) echo " (locked)";
			if($done[$k]) echo " (already voted)";
			echo "</li>";
		}
		echo "</ul></table>";
	}
	else if($type[3])
	{
		echo "<br>";
		echo "<form method=POST action=polls.php>";
		echo "<input type=hidden name=id value=$id>";
		echo "<p align=center><table border=0 cellpadding=5 cellspacing=5 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\"><tr><td>";
		for($k=0; $k<$cur; $k++) { echo "<input type=radio value=\"$r_id[$k]\" name=\"vote1\">$name[$k]<br>"; }
		echo "<p align=center><input type=\"submit\" value=\"Vote\" name=\"Go\"></center></td></tr></table></form>";
	}
	else if($type[4])
	{
		echo "<Br>";

		for($k=0; $k<$cur; $k++) { if($top<(495*($votes[$k]/$totalvotes))) { $top = (495*($votes[$k]/$totalvotes)); } }
		echo "<p align=center><table border=0 cellpadding=5 cellspacing=5 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=$top><tr><td>";
		for($k=0; $k<$cur; $k++) { $ttotal = 490*($votes[$k]/$totalvotes); echo "$name[$k] ($votes[$k])<br><img src=images/template/vote_lcap.gif><img src=images/template/voting_bar.gif height=12 width=$ttotal><img src=images/template/vote_rcap.gif><br>"; }
		echo "<b>Total Votes:</b> $totalvotes <br></tr></td></table>";
	}

?>

<?php include("footer.php"); ?>

