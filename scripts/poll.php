<?php


	//include("security/database.php");
	$con = @mysql_connect($host, $login, $pass);
	@mysql_select_db($database);

	$query="SELECT * FROM poll order by date desc limit 0,1";
	$result = @mysql_query($query);
	$s=@mysql_fetch_array($result);
	$title=$s["name"];
	$total=$s["votes"];
	$ips=$s["ips"];
	$highest=$s["id"];

	$done = strpos($ips, $REMOTE_ADDR);
	$color="#B1BCD3";
	echo "<table border=0 cellpadding=4 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=109><tr bgcolor=$color><td colspan=2><font face=arial size=2>";

	if(!$done)
	{

		$res_query="SELECT name, id FROM poll2 WHERE p_id=$highest";
		$res_result=@mysql_query($res_query);

		$cur=0;
		while($t=@mysql_fetch_array($res_result))
		{

			$name[$cur] = $t["name"];
			$r_id[$cur] = $t["id"];
			$cur++;
		}


		echo "$title<br>";
		echo "<form method=POST action=scripts/poll_add.php>";
		echo "<input type=hidden name=id value=$highest></td</tr>";



		for($k=0; $k<$cur; $k++) { echo "<tr height=5 bgcolor=$color><td><font face=arial size=2><input type=radio value=\"$r_id[$k]\" name=\"vote1\">$name[$k]</td></tr>\n"; }

		echo "<tr height=5 bgcolor=$color><td align=center><br><input type=\"submit\" value=\"Go\" name=\"Go\"></td></tr></form>";

	}
	else if($done)
	{
		$res_query="SELECT name, total FROM poll2 WHERE p_id=$highest";
		$res_result=@mysql_query($res_query);

		$cur=0;
		while($t=@mysql_fetch_array($res_result))
		{

			$name[$cur] = $t["name"];
			$votes[$cur] = $t["total"];
			if(!$votes[$cur]) { $votes[$cur]=0; }
			$r_id[$cur] = $t["id"];
			$cur++;
		}

		echo "$title ($total)<br><Br>";
		$high=0;
		for($j=0; $j<$cur; $j++) { $top = $votes[$j]/$total; if($top>$high) { $high=$top; } }

		$width=30/$high;

		for($k=0; $k<$cur; $k++) { $ttotal = $width*($votes[$k]/$total); echo "<tr height=5 bgcolor=$color><td width=70><font face=arial size=1>$name[$k] ($votes[$k])</td><td width=37><font face=arial size=1><img src=images/template/vote_lcap.gif><img src=images/template/voting_bar.gif height=12 width=".($ttotal-6)."><img src=images/template/vote_rcap.gif></td></tr>"; }

		echo "<tr bgcolor=$color><td colspan=2><br><font face=arial size=2>Total Votes: $total<br></td></tr>";

	}

	echo "</table>";
	//echo "<br>BAHHHHHH  it'll be fixed soon";
?>



























