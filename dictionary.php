<?php

	function output_tr($for_color, $on_color, $link, $height)
	{
		if($link)
		{
			echo "\n<tr onclick=\"javascript:location.href='$link';\" onmouseout=\"this.style.backgroundColor='$for_color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='$on_color'; this.style.cursor = 'hand'; window.status = 'Visit their page!'; return true;\" bgcolor=".$for_color;
			if($height!=0) echo " height=$height";
			echo ">";
		}
		else
		{
			echo "\n<tr onmouseout=\"this.style.backgroundColor='$for_color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='$on_color';  return true;\" bgcolor=".$for_color;
			if($height!=0) echo " height=$height";
			echo ">";
		}

	}

	include("header.php");
	if(!$con)
	{
		include("scripts/database.php");
		$con = @mysql_connect($host, $login, $pass);
	}

	echo "<strong><font face=Arial color=#000080 size=2>Dictionary</font></strong><br>";
	//  output the data

	if($con)
	{
		$font="\"Arial\"";
		echo "<br><br><table border=1 cellpadding=0 cellspacing=0 style=\"border:1px solid #c0c0c0; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>";
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top align=center height=1 width=175 colspan=2><font face=$font size=3><strong>Dictionary</strong></font></td></tr>\n";

		$query = "SELECT * from dictionary order by name";
		$result = mysql_db_query($database, $query);
		while ($r= mysql_fetch_array($result))
		{
			extract($r);
			if(!$flags) { $flags++; $color="#EEF1F5"; }
			else { $flags=0; $color="#FFFFFF"; }
			$ilink="#$d_id";
			output_tr($color, "#E4E9F2", $ilink, 0);

			echo "<td vAlign=top width=120 height=4><font face=$font size=2>";
			if($link) { echo "<a href=$link>"; }
			echo "$name";
			if($link) { echo "</a>"; }
			echo "</font></td>";
			$info=str_replace("\n", "<br>", $info);
			$info=str_replace("\t", "&nbsp&nbsp&nbsp&nbsp&nbsp", $info);
			echo "<td width=320><font face=$font size=2><a name=\"#$d_id\">$info</a></font></td></tr>\n";
		}
		echo "</table>";
	}
	else
	{
		echo "<p align=center>The database is currently down.  Come back later</p>";
	}

	include("footer.php");
?>