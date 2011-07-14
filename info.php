<?php
	include("scripts/database.php");
//	include("scripts/dl.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }
	$connect = 1;

if($game)
{
	if ($songs)
	{
		$type[4]=1;
		// songs is the mix id
		// mid is the id number inside all_type to usewith
		if(!$mid) { $mid=0; }

		$query= "SELECT all_type, full, abrv from versions, arcade_games WHERE id=$songs and versions.a_id=arcade_games.v_id";
		$result = mysql_db_query($database, $query);
		if ($r = mysql_fetch_array($result))
		{
			$types=$r["all_type"];
			$full=$r["full"];
			$abrv=$r["abrv"];

			$differents=explode(",", $types);

			$total=explode(".", $differents[$mid]);
			for($k=1; $k<count($total); $k++)
			{
				$next=$k+1;
				if(substr_count($total[$k], "#")) {
					//sets the color
					$colors[]="$total[$k]";
					//sets the columns to get
					if(!$flag) {
						$flag=1;
						$cols_to_get="$total[$next]";
						$allcols[]="$total[$next]";
					}
					else {
						$cols_to_get="$cols_to_get, $total[$next]";
						$allcols[]="$total[$next]";
					}
					$next++;
					//sets the lower columns
					$lower_cols[]="$total[$next]";
				}
				//sets the headers that will be displayed
				if(!$total[$k]) {
					$header[]="$total[$next]";
					$next=$next-2;
					$length[]="$total[$next]";
				}
			}
			$title=$total[0];
		}

		if($game)  //totality - place holder
		{
			$query = "SELECT name, songs from versions WHERE id=$songs";
			$result = mysql_db_query($database, $query);
			if ($result)
			{
				if($r = mysql_fetch_array($result))
				{
					$name=$r["name"];
					$song=$r["songs"];
				}
				$title="$name Songs";
				$type[4]=1;

				$allsongs=explode(",", $song);
				$total=count($allsongs);

				//check to see if there are colors
				if($allsongs[0]=='c') $allcolors=1;

				$terminal=$total-1;

				//if allcolors increment by 2
				if($allcolors)
				{
					$terminal=$total-2;
					for($k=1; $k<$total; $k+=2)
					{
						if($k!=$terminal) $songstoget="$songstoget id=$allsongs[$k] or";
						else $songstoget="$songstoget id=$allsongs[$k]";

						$cur_song=$allsongs[$k];
						$next=$k+1;
						$next_color=$allsongs[$next];
						//parse the color into a hex value
						if($next_color=='r') $next_color='#cc0000';
						else if($next_color=='b') $next_color='#336699';
						else if($next_color=='g') $next_color='#006600';
						else if($next_color=='y') $next_color='#999966';
						else if($next_color=='p') $next_color='#9900cc';
						else if($next_color=='') $next_color='';
						$colorss[$cur_song]=$next_color;
						//echo "colors: $colors[$cur_song] <br>";
					}
				}
				else
				{
					for($k=0; $k<$total; $k++)
					{
						if($k!=$terminal) $songstoget="$songstoget id=$allsongs[$k] or";
						else  $songstoget="$songstoget id=$allsongs[$k]";
					}
				}
			}
			if($cols_to_get) { $query2="SELECT id, name, artist, $cols_to_get from songs_".$abrv." WHERE $songstoget order by name"; }
			else { $query2="SELECT id, name, artist from songs_".$abrv." WHERE $songstoget order by name"; }
			$result2 = mysql_db_query($database, $query2);
	//		echo "query2 = $query2 ";
			if ($result2)
			{
				$current=0;
				while($s = mysql_fetch_array($result2))
				{
					$s_name[$current]=$s["name"];
					$s_artist[$current]=$s["artist"];
					$s_id[$current]=$s["id"];
					for($k=0; $k<count($allcols); $k++)
					{
						$cor_col=$allcols[$k];
						$row="row_$current";
						$col["$row"][$k]=$s["$cor_col"];
						if(!$col["$row"][$k]) { $col["$row"][$k]=""; }
					}
					$current++;
				}
			}

			$flag=0;
			if($aa) $mid=10;
			for($k=0; $k<count($differents); $k++)
			{
				$result=explode(".", $differents[$k]);
				if($mid==$k) { $links=""; }
				else { $links="<a href=info.php?game=$game&songs=$songs&mid=$k>"; }
				if($k==0) { $options="$links $result[0]</a>"; }
				else { $options="$options :: $links $result[0]</a>"; }
			}
			$flag=0;
			$type[4]=1;




			//only for max
			if($aa)
			{
				$query2="SELECT * from songs_ddr WHERE $songstoget order by name";
				$result2 = mysql_db_query($database, $query2);
				if ($result2)
				{
					$current=0;
					while($s = mysql_fetch_array($result2))
					{
						$row="row_$current";
						$s_name[$current]=$s["name"];
						$s_artist[$current]=$s["artist"];
						$s_id[$current]=$s["id"];
						$col["$row"][0]=get_aa($s["basic_steps"], $s["basic_freeze"]);
						$col["$row"][1]=get_aa($s["ano_steps"], $s["ano_freeze"]);
						$col["$row"][2]=get_aa($s["ssr_steps"], $s["ssr_freeze"]);
						$col["$row"][3]=get_aa($s["d_basic_steps"], $s["d_basic_freeze"]);
						$col["$row"][4]=get_aa($s["d_ano_steps"], $s["d_ano_freeze"]);
						$col["$row"][5]=get_aa($s["d_ssr_steps"], $s["d_ssr_freeze"]);
						$current++;
					}
				}
			}
		}
		$path="<a href=info.php>Music Games</a> :: <a href=info.php?game=$game>$full Versions</a> :: <a href=info.php?game=$game&id=$songs>$name</a> :: Songs";
	}

	else if ($id)
	{
		//get an individual songs info
		//assume $game=arcade_game.v_id

		$query = "SELECT * from versions, arcade_games WHERE versions.a_id=arcade_games.v_id and id=$id";
		$result = mysql_db_query($database, $query);
		if ($result) { extract(mysql_fetch_array($result)); }

		$title="$name";
		$path="<a href=info.php>Music Games</a> :: <a href=info.php?game=$game>$full Versions</a> :: $name";
		$type[2]=1;
		if(!$result) { $type[3]=1; }
	}
	else if ($sid)
	{
		//get an individual songs info
		//assume $game=arcade_game.v_id

		$quer="SELECT abrv, full FROM arcade_games where v_id=$game";
		$resul = mysql_db_query($database, $quer);
		if($r=mysql_fetch_array($resul))
		{
			$abrv=$r["abrv"];
			$full_name=$r["full"];
		}

		$query = "SELECT * from songs_".$abrv." WHERE id=$sid";
		$result = mysql_db_query($database, $query);
		if ($result) { extract(mysql_fetch_array($result)); }
		/*
		if ($r = mysql_fetch_array($result))
		{
			$sname=$r["name"];
			$sartist=$r["artist"];
			$sbpm=$r["bpm"];
			$solyrics=$r["o_lyrics"];
			$sdlyrics=$r["d_lyrics"];
			$type[5]=1; $path="<a href=info.php>Music Games</a> :: <a href=info.php?game=$game>$full_name Versions</a> :: <a href=info.php?game=$game&allsongs=1>All Songs</a> :: $sname"; $title="$sname";
		}
		*/
		$type[5]=1;
		$path="<a href=info.php>Music Games</a> :: <a href=info.php?game=$game>$full_name Versions</a> :: <a href=info.php?game=$game&allsongs=1>All Songs</a> :: $name";
		$title="$name";
		//get mixes the song is on
		$query="SELECT id, name, songs, sys from versions WHERE a_id=$game";
		$result = mysql_db_query($database, $query);
		$totalson=0;
		while ($s=mysql_fetch_array($result))
		{
			$tmp_songs=$s["songs"];
			$tmp_id=$s["id"];
			$tmp_name=$s["name"];
			$tmp_sys=$s["sys"];
			if($tmp_songs)
			{
				$ext_songs=explode(",", $tmp_songs);

				for($k=0; $k<count($ext_songs); $k++)
				{
					if($ext_songs[$k]==$sid)
					{
						$s_id[$totalson]=$tmp_id;
						$s_name[$totalson]=$tmp_name;
						$s_sys[$totalson]=$tmp_sys;
						$totalson++;
					}
				}
			}
		}
	}
	else if ($allsongs)
	{
		//assume $game = the id of the game you all are getting allsongs from

		$quer="SELECT abrv, full FROM arcade_games where v_id=$game";
		$resul = mysql_db_query($database, $quer);
		if($s=mysql_fetch_array($resul))
		{
			$abrv=$s["abrv"];
			$full_name=$s["full"];
		}
		$query="SELECT id, name, artist FROM songs_".$abrv." order by name";
		$result = mysql_db_query($database, $query);
		$cur=0;
		while($r=@mysql_fetch_array($result))
		{
			$a_id[$cur]=$r["id"];
			$a_name[$cur]=$r["name"];
			$a_art[$cur]=$r["artist"];
			$cur++;
		}

		$type[6]=1; $path="<a href=info.php>Music Games</a> :: <a href=info.php?game=$game>$full_name Versions</a> :: Songs"; $title="All Songs";
	}
	else if($about)
	{
		$query="SELECT full, about FROM arcade_games where v_id=$game";
		$result = mysql_db_query($database, $query);
		if($s=mysql_fetch_array($result))
		{
			$full=$s["full"];
			$about=$s["about"];
		}
		$type[8]=1;
		$title="About $full"; $path="<a href=info.php>Music Games</a> :: About $full";
	}
	else
	{
		//assume $game=arcade_game.v_id
		$query = "SELECT name, id, songs, sys, full, v_id from versions, arcade_games where versions.a_id=arcade_games.v_id and versions.a_id=$game order by sys";
		$result = mysql_db_query($database, $query);
		$row=0;
		if ($result)
		{
			while($r = mysql_fetch_array($result))
			{
				$game=$r["v_id"];
				$full=$r["full"];
				$ids[$row]=$r["id"];
				$names[$row]=$r["name"];
				$songs[$row]=$r["songs"];
				$sys[$row]=$r["sys"];
				$row++;
				$nosongs=1;
			}
		}
		if($nosongs!=1)
		{
			$t_query="SELECT full FROM arcade_games WHERE v_id=$game";
			$t_result=mysql_db_query($database, $t_query);
			if($s=mysql_fetch_array($t_result))
			{
				$full=$s["full"];
			}
		}
		$type[0]=1;
		$title="$full Versions"; $path="<a href=info.php>Music Games</a> :: $full Versions";
	}
}
else
{
	$query = "SELECT v_id, full, about from arcade_games order by full";
	$result = mysql_db_query($database, $query);
	$cur=0;
	while($r = mysql_fetch_array($result))
	{
		$v_ids[$cur]=$r["v_id"];
		$v_full[$cur]=$r["full"];
		$v_about[$cur]=$r["about"];
		$cur++;
	}
	$path="Music Games";
	$title="Music Games";
	$type[9]=1;
}
?>

<?php
	$image="news_hdr.jpg";
	include("header.php");
?>

<?php

	function ver_say($width1, $width2, $left, $right)
	{
		$width1=130; $width2=345;
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>$left:</strong></td>";
		echo "<td vAlign=top width=$width2>".str_replace("\n", "<br>", $right)."</td></tr>";
		echo "<tr bgcolor=\"#E1E7F3\"><td colspan=2>&nbsp</td></tr>";
	}
	function get_aa($steps, $freeze)
	{
		$max_score=($steps*2)+($freeze*6);
		$aa_score=ceil($max_score*.93);
		return($max_score-$aa_score);
	}

	if($connect == 1 )
	{
		echo "<strong><font face=Arial color=#000080 size=2>$path</font></strong><BR><br>";
		//  Main versions
		if($type[0])
		{

			$font="\"Arial\"";
			if($nosongs==1) {
			echo "<a href=info.php?game=$game&allsongs=1>View all songs</a><br><br><table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495 colspan=4><font face=$font size=3><strong>$title</strong></font></td></tr>\n";
			for($k = 0; $k<$row; $k++)
			{
				if(!$flags) { $flags++; $color="#B1BCD3"; }
				else { $flags=0; $color="#E1E7F3"; }
				$last=$k-1;
				if(($sys[$k]!=$sys[$last]) || $k==0) {
					$flagn++;
					echo"<tr bgcolor=$color><td vAlign=top height=4 colspan=4><font face=$font size=2>&nbsp</font></td></tr>\n";
					if($color=="#B1BCD3") { $color = "#E1E7F3"; $flags=0;}
					else { $color = "#B1BCD3"; $flags=1; }
					echo "<tr bgcolor=$color><td vAlign=top height=4 colspan=4><font face=$font size=2>&nbsp&nbsp<b>$sys[$k]:</b></font></td></tr>\n";
					if($color=="#B1BCD3") { $color = "#E1E7F3"; $flags=0;}
					else { $color = "#B1BCD3"; $flags=1; }
				}
				echo "<tr bgcolor=$color><td width=10>&nbsp</td><td vAlign=top width=405 height=4><font face=$font size=2>$names[$k]</font></td>";
				echo "<td width=40><font face=$font size=2>";
				if($songs[$k]) { echo "<a href=info.php?game=$game&songs=$ids[$k]>"; }
				echo "Songs";
				if($songs[$k]) { echo "</a>"; }
				echo " :: </font></td><td width=40><font face=$font size=2><a href=info.php?game=$game&id=$ids[$k]>Info</a></font></td></tr>\n";
			}
			echo "</table>";
			}
			else
			{
				echo "This section is currently in progress.";
			}

		}
		// No Main versions
		else if($type[1])
		{
			echo "<center>The versions Database is currently down.</center>";
		}
		else if($type[9])
		{
			echo "<ul>";
			for($k=0; $k<$cur; $k++)
			{
				echo "<li><a href=info.php?game=$v_ids[$k]>$v_full[$k]</a>";
				if($v_about[$k]) { echo " :: <a href=info.php?game=$v_ids[$k]&about=1>About</a>"; }
				echo "</li>\n";
			}
			echo "</ul>";
		}
		// id, info a particular mix
		else if($type[2])
		{
			$font="\"Arial\"";
			echo "<a href=info.php?game=$game&songs=$id>Songs from $name</a><br><br>";
			echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 colspan=2><font face=$font size=3><strong>$name</strong></font></td></tr>\n";
			echo "<tr brcolor=\"#E1E7F3\"><td colspan=2>$topics</td></tr>";
			if($name){ ver_say($width1, $width2, 'Name', $name); }
			if($sys){ ver_say($width1, $width2, 'System', $sys); }
			if($country){ ver_say($width1, $width2, 'Country Released', $country); }
			if($release){ ver_say($width1, $width2, 'Release Date', $release); }
			if($modes){ ver_say($width1, $width2, 'Modes', $modes); }
			if($diff_code){ ver_say($width1, $width2, 'Difficulty Codes', $diff_code); }
			if($step_code){ ver_say($width1, $width2, 'Step Codes', $step_code); }
			if($grading){ ver_say($width1, $width2, 'Grading', $grading); }
			if($nonstop){ ver_say($width1, $width2, 'Nonstop Songs', $nonstop); }
			if($codes){ ver_say($width1, $width2, 'Gameshark Codes', $codes); }
			if($more){ ver_say($width1, $width2, 'More info', $more); }
			echo "</table>";

		}

		// songs, all songs on one mix
		else if($type[4])
		{
			$font="\"Arial\"";
			$colspan=count($allcols)+2;
			if(($songs==16) || ($songs==74) || ($songs==27))
			$options="$options <br><br><a href=info.php?game=$game&songs=$songs&aa=1>Most points off for an AA</a>";
			echo "<font face=Arial color=#000080 size=2>$options</font><br><br><table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495 colspan=$colspan><font face=$font size=3><strong>$title</strong></font></td></tr>\n";
			if($header[0]) { $rowspan=2; }
			echo "<tr bgcolor=#E1E7F3><td width=200 height=4 rowspan=$rowspan><font face=$font size=2>Song Name</font></td>";
			echo "<td width=200 height=4 rowspan=$rowspan><font face=$font size=2>Artist</font></td>";

			for ($k=0; $k<count($header); $k++)
			{ echo "<td align=center colspan=$length[$k]><font face=$font size=2>$header[$k]</font></td>\n"; }
			echo "</tr>\n<tr bgcolor=#E1E7F3>";

			for ($k=0; $k<count($lower_cols); $k++)
			{ echo "<td>$lower_cols[$k]</td>"; }
			echo "</tr>\n";

			for($k=0; $k<$current; $k++)
			{
				if(!$flags) { $flags++; $bgcolor="#B1BCD3"; }
				else { $flags=0; $bgcolor="#E1E7F3"; }
				$cur_id=$s_id[$k];
				//style=\"text-decoration: none\"
				echo "<tr bgcolor=$bgcolor><td width=200><a href=info.php?game=$game&sid=$s_id[$k]><font face=$font size=2 color=".$colorss[$cur_id].">";
				if($colorss[$cur_id]=='#999966') echo "<b>";
				echo "$s_name[$k]";
				if($colorss[$cur_id]=='#999966') echo "</b>";
				echo "</a></font></td><td vAlign=top width=200 height=4><font face=$font size=2>$s_artist[$k]</font></td>";
				for($m=0; $m<count($lower_cols); $m++)
				{
					$row_to_out="row_$k";
					$data=$col["$row_to_out"][$m];
					echo "<td><font color=$colors[$m]><b>$data</b></font>\n";
				}
			}
			echo "</table>";

			if($allcolors)
				echo "<p><b>Key:</b><br>Songs in <font face=$font size=2 color=#000080><b>blue</b></font> are intially available.<br>Songs in <font face=$font size=2 color=#999966><b>yellow</b></font> are unlockable songs.<br>
				Songs in <font face=$font size=2 color=#006600><b>green</b></font> are new to the mix and initially available.<br>
				Songs in <font face=$font size=2 color=#9900cc><b>purple</b></font> are songs new to the upgrade.<br></p>";
		}

		// sid, individual songs
		else if($type[5])
		{
			$font="\"Arial\"";
			echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=200 colspan=2><font face=$font size=3><strong>$sname</strong></font></td></tr>\n";
			if($name){ ver_say($width1, $width2, 'Name', $name); }
			if($artist){ ver_say($width1, $width2, 'Artist', $artist); }
			if($bpm){ ver_say($width1, $width2, 'Beats per Minute', $bpm); }
			if($o_lyrics){ ver_say($width1, $width2, 'Original Lyrics', $o_lyrics); }
			if($d_lyrics){ ver_say($width1, $width2, 'Game lyrics', $d_lyrics); }

			//output the vote option
			if($votes) { $avg = $total/$votes; echo "<tr bgcolor=\"#E1E7F3\"><td width=$width1><b>Rating:</b></td><td width=$width2><font face=$font size=2>".substr($avg, 0, 3)." in $votes vote(s)</font></td></tr>"; }
			$ttest="g".$game."_id".$id;
			if(Isset($ddrei_info)) { $testa = strpos($ddrei_info, $ttest, 0); }
			if(!$ddrei_info || !$testa )
			{
				echo "              <tr bgcolor=\"#E1E7F3\"><td width=$width1><b>Rate:</b></td>";
				echo "              <td width=$width2>";
				echo "              <form method=POST action=>";
				echo "              <input type=hidden name=id value=$id>";
				echo "				<input type=hidden name=pref value=$abrv>";
				echo "				<input type=hidden name=game value=$game>";
				echo "              <select size=1 name=rate>";
				echo "              <option value=1>1</option>";
				echo "              <option value=2>2</option>";
				echo "              <option value=3>3</option>";
				echo "              <option value=4>4</option>";
				echo "              <option value=5>5</option>";
				echo "              <option value=6>6</option>";
				echo "              <option value=7>7</option>";
				echo "              <option value=8>8</option>";
				echo "              <option value=9>9</option>";
				echo "              <option value=10>10</option>";
				echo "              </select><input type=submit value=submit name=submit></form></td></tr>";
			}
			//echo "ddrei_info=$ddrei_info ttest=$ttest testa= $testa";
			if(count($s_id))
			{
				$width1=130; $width2=345;
				echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>On Games:</strong></td>";
				echo "<td vAlign=top width=$width2><ul type=square>";
				for($k=0; $k<count($s_id); $k++)
				{
					echo "<li><a href=info.php?game=$game&id=$s_id[$k]>$s_name[$k]</a> ($s_sys[$k])</li>";
				}
				echo "</ul></td></tr><tr bgcolor=\"#E1E7F3\"><td colspan=2>&nbsp</td></tr>";
			}
			echo "</table>";
		}
		else if($type[6])
		{
			$font="\"Arial\"";
			echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495 colspan=8><font face=$font size=3><strong>$title</strong></font></td></tr>\n";
			echo "<tr bgcolor=#E1E7F3><td width=275 height=4><font face=$font size=2>Song Name</font></td>";
			echo "<td width=220 height=4><font face=$font size=2>Artist</font></td></tr>";
			for($k = 0; $k<$cur; $k++)
			{
				if(!$flags) { $flags++; $color="#B1BCD3"; }
				else { $flags=0; $color="#E1E7F3"; }
				echo "<tr bgcolor=$color ><td width=275><font face=$font size=2><a href=info.php?game=$game&sid=$a_id[$k]>$a_name[$k]</a></font></td><td vAlign=top width=220 height=4><font face=$font size=2>$a_art[$k]</font></td></tr>";
			}
			echo "</table>";
		}
		else if($type[7])
		{
			$font="\"Arial\"";
			echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495	>";
			echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495 colspan=8><font face=$font size=3><strong>$title</strong></font></td></tr>\n";
			echo "<tr bgcolor=#E1E7F3><td width=275 height=4><font face=$font size=2>Song Name</font></td>";
			echo "<td width=220 height=4><font face=$font size=2>Artist</font></td></tr>";
			for($k = 0; $k<$cur; $k++)
			{
				if($a_short_name[$k])
				{
					if(!$flags) { $flags++; $color="#B1BCD3"; }
					else { $flags=0; $color="#E1E7F3"; }
					echo "<tr bgcolor=$color ><td width=275><font face=$font size=2><a href=info.php?game=$game&sid=$a_id[$k]>$a_name[$k]</a></font></td><td vAlign=top width=220 height=4><font face=$font size=2>$a_art[$k]</font></td></tr>";
				}

			}
			if($cur<1) { echo "<tr bgcolor=#B1BCD3 ><td width=275 colspan=2><font face=$font size=2><b>No Simulator Files avaliable.</b></td></tr>"; }
			echo "</table>";
		}
		else if($type[8])
		{
			echo "$about";
		}
	}
	else
	{
		echo "<p align=center>The versions database is currently down.</p>";
	}

?>

<?php include("footer.php"); ?>