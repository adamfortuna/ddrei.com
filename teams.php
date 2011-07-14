<?php
	function get_st($ab)
	{
		$ab = strtoupper($ab);
		if($ab=='AL') { return 'Alabama'; }
		else if($ab=='AK') { return 'Alaska'; }
		else if($ab=='AZ') { return 'Arizona'; }
		else if($ab=='AR') { return 'Arkansas'; }
		else if($ab=='CA') { return 'California'; }
		else if($ab=='CO') { return 'Colorado'; }
		else if($ab=='CT') { return 'Connecticut'; }
		else if($ab=='DE') { return 'Delaware'; }
		else if($ab=='DC') { return 'Washington DC'; }
		else if($ab=='FL') { return 'Florida'; }
		else if($ab=='GA') { return 'Georgia'; }
		else if($ab=='HI') { return 'Hawaii'; }
		else if($ab=='ID') { return 'Idaho'; }
		else if($ab=='IL') { return 'Illinois'; }
		else if($ab=='IN') { return 'Indiana'; }
		else if($ab=='IA') { return 'Iowa'; }
		else if($ab=='KS') { return 'Kansas'; }
		else if($ab=='KY') { return 'Kentucky'; }
		else if($ab=='LA') { return 'Louisiana'; }
		else if($ab=='ME') { return 'Maine'; }
		else if($ab=='MD') { return 'Maryland'; }
		else if($ab=='MA') { return 'Massachusetts'; }
		else if($ab=='MI') { return 'Michigan'; }
		else if($ab=='MN') { return 'Minnesota'; }
		else if($ab=='MS') { return 'Mississippi'; }
		else if($ab=='MO') { return 'Missouri'; }
		else if($ab=='MT') { return 'Montana'; }
		else if($ab=='NE') { return 'Nebraska'; }
		else if($ab=='NV') { return 'Nevada'; }
		else if($ab=='NH') { return 'New Hampshire'; }
		else if($ab=='NJ') { return 'New Jersy'; }
		else if($ab=='NM') { return 'New Mexico'; }
		else if($ab=='NY') { return 'New York'; }
		else if($ab=='NC') { return 'North Carolina'; }
		else if($ab=='ND') { return 'North Dakota'; }
		else if($ab=='OH') { return 'Ohio'; }
		else if($ab=='OK') { return 'Oklahoma'; }
		else if($ab=='OR') { return 'Oregon'; }
		else if($ab=='PA') { return 'Pennsylvania'; }
		else if($ab=='PR') { return 'Puerto Rico'; }
		else if($ab=='RI') { return 'Rhode Island'; }
		else if($ab=='SC') { return 'South Carolina'; }
		else if($ab=='SD') { return 'South Dakota'; }
		else if($ab=='TN') { return 'Tennessee'; }
		else if($ab=='TX') { return 'Texas'; }
		else if($ab=='UT') { return 'Utah'; }
		else if($ab=='VT') { return 'Vermont'; }
		else if($ab=='VA') { return 'Virginia'; }
		else if($ab=='WA') { return 'Washington'; }
		else if($ab=='WV') { return 'West Virginia'; }
		else if($ab=='WI') { return 'Wisconsin'; }
		else if($ab=='WY') { return 'Wyoming'; }
		else { return ucfirst(strtolower($ab)); }
	}

	function prof_say($width1, $width2, $left, $right)
	{
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>$left:</strong></td>";
		echo "<td vAlign=top width=$width2>$right</td></tr>";
	}

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


	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }
	$connect = 1;


		$query = "SELECT * FROM team WHERE 1 ORDER BY state, namet";
		$result = mysql_db_query($database, $query);
		if ($result)
		{
			$curt=0;
			while ($r = mysql_fetch_array($result))
			{
//				$type[1]=1; $title="Events"; $path="Teams";
				if(($tmpid=$r["t_id"]))
				{
					$tmid[$curt]=$tmpid;
					$tmnamet[$curt]=$r["namet"];
					$tmst[$curt]=$r["state"];
					$tmlocal[$curt]=$r["local"];
					$tmweb[$curt]=$r["webt"];
					$curt++;
				}
			}
			$type[1]=1; $path="Teams";
		}
//	}

	include("header.php");

	if($connect == 1 )
	{

		echo "<strong><font face=Arial color=#000080 size=2>$path</font></strong><BR><br>";
		//  Main teams
		if($type[1])
		{
			$font="\"Arial\"";

			for($j=0; $j<$curt; $j++)
			{
				$last=$j-1;
				if($j==0 || $tmst[$j]!=$tmst[$last])
				{
					if($j!=0)
					{
						echo "</table><br>";
					}
					$tmpstate=get_st($tmst[$j]);
					echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border:1px solid #c0c0c0; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
					echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top align=left height=1 width=200><font face=$font size=2><strong>$tmpstate Teams</strong></font></td>\n";
					echo "<td valign=top align=left height=1 width=195><font face=$font size=2><strong>Local</strong></td></font></font></tr>\n";
					$flags=0;
				}
				if(!$flags) { $flags++; $color="#EEF1F5"; }
				else { $flags=0; $color="#FFFFFF"; }

				if($tmweb[$j]) {
					if(substr_count($tmweb[$j], "http://"))
					{ $link=$tmweb[$j]; }
					else if (substr_count($tmweb[$j], "@"))
					{ $link="mailto:$tmweb[$j]"; }
					else
					{ $link="http://$tmweb[$j]"; }
				}

				output_tr($color, "#E4E9F2", $link, 0);

				echo "<td vAlign=top height=0>&nbsp&nbsp&nbsp<font face=$font size=2>";

				if($tmweb[$j]) {
					if(substr_count($tmweb[$j], "http://"))
					{ echo "<a href=$tmweb[$j]>"; }
					else if (substr_count($tmweb[$j], "@"))
					{ echo "<a href=mailto:$tmweb[$j]>"; }
					else
					{ echo "<a href=http://$tmweb[$j]"; }
				}

				echo "$tmnamet[$j]";

				if($tmweb[$j]) { echo "</a>"; }

				echo "</font></td>\n";

				if($tmlocal[$j] && $tmst[$j])
				{ echo "<td vAlign=top height=0><font face=$font size=2>$tmlocal[$j], $tmst[$j]</td></font></tr>"; }
				else if($tmlocal[$j] && !$tmst[$j])
				{ echo "<td vAlign=top height=0><font face=$font size=2>$tmlocal[$j]</td></font></tr>"; }
				else if (!$tmlocal[$j] && $tmst[$j])
				{ echo "<td vAlign=top height=0><font face=$font size=2>$tmst[$j]</td></font></tr>"; }
				else
				{ echo "<td>&nbsp</td><tr>"; }
				$link="";
			}
			echo "</table>\n";

			echo "<br><center>";
			echo "Want to be posted here?  Add your team to this list <a href=teams2.php>here</a>.";
			echo "</center>";
		}
		else
		{
			echo "Error... Come back later";
		}
	}
	else
	{
		echo "<center>The profiles database is currently down.<br><br>";
		echo "<a href=players2.htm>Want to be posted here?</a><br>";
        	echo "<a href=players3.htm>Want your team posted too?</a><br></center>";
	}

	?>

<?php include("footer.php"); ?>