<?php


	function prof_say($width1, $width2, $left, $right)
	{
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><br><strong>$left:</strong></td>\n";
		echo "<td vAlign=top width=$width2><br>$right</td></tr>\n";
	}

	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }
	$connect = 1;
	include("scripts/time.php");

	if($id)
	{
		if($rate)
		{
			$type[3]=1;
			$query="SELECT name, release, comp_name, country, sys from versions, company where versions.comp=company.comp_id and versions.id=$id";
			$result = @mysql_db_query($database, $query);
			if ($result) { extract(@mysql_fetch_array($result)); }
			$path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$name</a> :: Rate it!";
		}
		else if($songlist)
		{
			$query = "SELECT name, songs, sys, abrv, game_type, a_id, links FROM versions, arcade_games WHERE versions.id=$id and versions.a_id=arcade_games.v_id";
			$result = @mysql_db_query($database, $query);
			if ($result) { extract(@mysql_fetch_array($result)); }

			if($songs)
			{
				$allsongs=explode(",", $songs);
				$total=count($allsongs);

				$terminal=$total-1;
				for($k=0; $k<$total; $k++) {
					if($k!=$terminal) { $songstoget="$songstoget id=$allsongs[$k] or"; }
					else  { $songstoget="$songstoget id=$allsongs[$k]"; }

				}
			$query2="SELECT id, name, artist from songs_".$abrv." WHERE $songstoget order by name"; }
			$result2 = @mysql_db_query($database, $query2);
			$cur=0;
			while ($s=@mysql_fetch_array($result2))
			{
				$s_id[$cur]=$s["id"];
				$s_name[$cur]=$s["name"];
				$s_artist[$cur]=$s["artist"];
				$cur++;
			}
			$type[4]=1;
			$path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$name</a> :: Songs";
		}
		else if($comments)
		{

			$query="SELECT name, sys, links FROM versions WHERE id=$id";
			$result = @mysql_db_query($database, $query);
			if ($s=@mysql_fetch_array($result))
			{
				if(!$flag)
				{
					$name=$s["name"];
					$sys=$s["sys"];
					$links=$s["links"];
					$flag=1;
				}
			}

			$query="SELECT c_auth, c_em, c_title, c_com, c_title FROM reviews2 WHERE c_id=$id";
			$result = @mysql_db_query($database, $query);
			$cur=0;
			while ($s=@mysql_fetch_array($result))
			{
				if(!$flag)
				{
					$c_name=$s["name"];
					$c_sys=$s["sys"];
					$c_links=$s["links"];
					$flag=1;
				}
				$c_auth[$cur]=$s["c_auth"];
				$c_em[$cur]=$s["c_em"];
				$c_title[$cur]=$s["c_title"];
				$c_com[$cur]=$s["c_com"];
				$c_date[$cur]=mysql_timestamp_to_human($s["c_date"]);
				$cur++;
			}
			$path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$name</a> :: Comments";
			$type[5]=1;
		}
		else if($pics)
		{
			$col=4;
			// $id = version number sent
			$query = "SELECT name FROM versions WHERE id=$id";
			$result = mysql_db_query($database, $query);
			if($r=@mysql_fetch_array($result)) { $v_name=$r["name"]; }


			$dir="/home/ddrei/public_html/v_pics/".$id."/small"; // this must be an absolute path
			$picdir="http://ddrei.com/v_pics/".$id."/big";
			$tndir="http://ddrei.com/v_pics/".$id."/small";

			//Load Directory Into Array
			//$folderx[]=0;
			$handle=@opendir($dir);
			while ($file = @readdir($handle)) { $folderx[count($folderx)] = $file; }

			//Clean up and sort
			@closedir($handle);
			if(count($folderx)) { sort($folderx); }

			for($k=2; $k<count($folderx); $k++)
			{
				$p=$k-2;
				$folder[$p]=$folderx[$k];
			}

			if(!count($folder)) { $start=0; $end=0; }
			else
			{
				$addin = count($folder);
				$start=1;
				$end=$addin;
				$t_end=$end+2;
				$row=$end/4;
			}
			if($end==-3) { $end=0; $start=0; }
			$type[2]=1; $path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$v_name</a> :: Pictures :: $start to $end";
		}
		else if($review)
		{
			$query = "SELECT name, sys FROM versions WHERE id=$id";
			$result = mysql_db_query($database, $query);
			if($r=@mysql_fetch_array($result)) { $name=$r["name"]; $sys=$r["sys"]; }
			$type[10]=1; $path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$name</a> :: Write a Review";
		}
		else if($process)
		{
			$type[11]=1; $path="<a href=reviews.php>Reviews</a> :: Thanks!";
		}
		else
		{
			$query = "SELECT name, release, comp_name, country, sys, preview, gameplay, graphics, music, replay, tilt, votes, total, links, abrv, game_type from versions, arcade_games, company where versions.a_id=arcade_games.v_id and versions.comp=company.comp_id and versions.id=$id";
			$result = @mysql_db_query($database, $query);
			if ($result)
			{
				extract(@mysql_fetch_array($result));
				$type[1]=1;
				$path="<a href=reviews.php>Reviews</a> :: <a href=reviews.php?id=$id>$name</a> :: Review";
			}

			$query = "SELECT title, auth, em, com, date from reviews where version_id=$id AND validate=1";
			$result = @mysql_db_query($database, $query);
			$cur=0;
			while($s=mysql_fetch_array($result))
			{
				$r_title[$cur]=$s["title"];
				$r_auth[$cur]=$s["auth"];
				$r_em[$cur]=$s["em"];
				$r_com[$cur]=$s["com"];
				$r_date[$cur]=mysql_timestamp_to_human($s["date"]);
				$cur++;
			}
		}
	}
	else if($reviews)
	{
		$type[7]=1; $path="<a href=reviews.php>Reviews</a> :: All Reviews";
		$query = "SELECT version_id, auth, em, date, name, sys from versions, reviews where versions.id=reviews.version_id and reviews.validate=1 order by versions.name";
		$result = @mysql_db_query($database, $query);
		$k=0;
		while($s=@mysql_fetch_array($result))
		{
			$v_id[$k]=$s["version_id"];
			$v_name[$k]=$s["name"];
			$v_sys[$k]=$s["sys"];
			$v_auth[$k]=$s["auth"];
			$v_em[$k]=$s["em"];
			$v_date[$k]=mysql_timestamp_to_human($s["date"]);
			$k++;
		}
	}
	else if($pictures)
	{
		$type[8]=1; $path="<a href=reviews.php>Reviews</a> :: All Pictures";
	}
	else if($rated)
	{
		$type[9]=1; $path="<a href=reviews.php>Reviews</a> :: All Rated";
		$query = "SELECT id, name, sys, total, votes from versions WHERE votes>0 order by total desc";
		$result = mysql_db_query($database, $query);
		$k=0;
		while($s=mysql_fetch_array($result))
		{
			$r_id[$k]=$s["id"];
			$r_name[$k]=$s["name"];
			$r_sys[$k]=$s["sys"];
			$r_total[$k]=$s["total"];
			$r_votes[$k]=$s["votes"];
			$k++;
		}
	}
	else
	{
		$type[0]=1; $path="Reviews";
		$query = "SELECT id, name, sys from versions order by name";
		$result = mysql_db_query($database, $query);

		// Bring in all the reviews
		$cur=0;
		while($r=mysql_fetch_array($result))
		{
			$t_id[$cur]=$r["id"];
			$t_name[$cur]=$r["name"];
			$t_sys[$cur]=$r["sys"];
			if($t_sys[$cur]=="Playstation") { $t_sys[$cur]="psx"; }
			else if($t_sys[$cur]=="Dreamcast") { $t_sys[$cur]="dc"; }
			else if($t_sys[$cur]=="Gameboy") { $t_sys[$cur]="gb"; }
			else if($t_sys[$cur]=="Playstation 2") { $t_sys[$cur]="ps2"; }
			$cur++;
		}

		// grab the top 10
		$query = "SELECT id, name, sys, total, votes from versions order by total desc";
		$result = mysql_db_query($database, $query);
		$cur=0;
		for($k=0; $k<10; $k++)
		{
			if($s=mysql_fetch_array($result))
			{
				$r_id[$k]=$s["id"];
				$r_name[$k]=$s["name"];
				$r_sys[$k]=$s["sys"];
				$r_total[$k]=$s["total"];
				$r_votes[$k]=$s["votes"];
			}
		}
		// grab the 10  most recent
		$query = "SELECT version_id, auth, em, date, name, sys from versions, reviews where versions.id=reviews.version_id and reviews.validate=1 order by date desc";
		$result = @mysql_db_query($database, $query);
		$cur=0;
		for($k=0; $k<10; $k++)
		{
			if($s=@mysql_fetch_array($result))
			{
				$v_id[$k]=$s["version_id"];
				$v_name[$k]=$s["name"];
				$v_sys[$k]=$s["sys"];
				$v_auth[$k]=$s["auth"];
				$v_em[$k]=$s["em"];
				$v_date[$k]=mysql_timestamp_to_human($s["date"]);
			}
		}
	}


	function art_say($width1, $width2, $left, $right)
	{
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>$left:</strong></td>\n";
		echo "<td vAlign=top width=$width2>".str_replace("\n", "<br>", $right)."</td></tr>\n";
	}
	function say_topmenu($id)
	{
		echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111 width=495>
    <tr>
      <TD bgcolor=#78a9d9 align=center
          onclick=\"javascript:location.href='reviews.php?id=$id';\"
          onmouseout=\"this.style.backgroundColor='78a9d9'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='E1E7F3'; this.style.cursor = 'hand'; window.status = 'Reviews the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b>
			<font face=Arial>Reviews</font></b></td>
      <TD bgcolor=#78a9d9 align=center
          onclick=\"javascript:location.href='reviews.php?id=$id&pics=1';\"
          onmouseout=\"this.style.backgroundColor='78a9d9'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='E1E7F3'; this.style.cursor = 'hand'; window.status = 'Pictures'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b>
			<font face=Arial>Pictures</font></b></td>
      <TD bgcolor=#78a9d9 align=center
          onclick=\"javascript:location.href='reviews.php?id=$id&songlist=1';\"
          onmouseout=\"this.style.backgroundColor='78a9d9'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='E1E7F3'; this.style.cursor = 'hand'; window.status = 'Songslist for the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b>
			<font face=Arial>Songs</font></b></td>
      <TD bgcolor=#78a9d9 align=center
          onclick=\"javascript:location.href='reviews.php?id=$id&comments=1';\"
          onmouseout=\"this.style.backgroundColor='78a9d9'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='E1E7F3'; this.style.cursor = 'hand'; window.status = 'Comments about the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b>
			<font face=Arial>Comments</font></b></td></tr>";
	}

	function say_graph($gameplay, $graphics, $music, $replay, $tilt, $id)
	{
		// start the output of table
			echo "<tr><td width=177 height=20 valign=top style=\"border-left-style: solid; border-left-width: 1; border-right-style: solid; border-right-width: 1; border-bottom-style: solid; border-bottom-width: 1\" bgcolor=#E1E7F3>\n";
			echo "<table border=0 style=\"border-collapse: collapse\" bordercolor=#111111 cellpadding=0 cellspacing=0>\n";
			// Output the graphs
			if($gameplay)
			{
				echo "<tr><td valign=middle width=100 height=11><b><font size=2 face=Arial>Gameplay:</font></b></td><td width=100 height=11><img border=0 src=pics/poll.gif width=".($gameplay*10)." height=10></td><td width=20 height=11 align=center>$gameplay</td></tr>\n";
				echo "<tr><td valign=middle width=100 height=11><b><font size=2 face=Arial>Graphics:</font></b></td><td width=100 height=11><img border=0 src=pics/poll.gif width=".($graphics*10)." height=10></td><td width=20 height=11 align=center>$graphics</td></tr>\n";
				echo "<tr><td valign=middle width=100 height=11><b><font size=2 face=Arial>Music:</font></b></td><td width=100 height=11><img border=0 src=pics/poll.gif width=".($music*10)." height=10></td><td width=20 height=11 align=center>$music</td></tr>\n";
				echo "<tr><td valign=middle width=100 height=11><b><font size=2 face=Arial>Replay:</font></b></td><td width=100 height=11><img border=0 src=pics/poll.gif width=".($replay*10)." height=10></td><td width=20 height=11 align=center>$replay</td></tr>\n";
				echo "<tr><td valign=middle width=100 height=11><b><font size=2 face=Arial>Tilt:</font></b></td><td width=100 height=11><img border=0 src=pics/poll.gif width=".($tilt*10)." height=10></td><td width=20 height=11 align=center>$tilt</td></tr>\n";
			//  The Rate is Button
			}
			else { echo "<tr><td width=100% align=center>There are no ratings so far.<td></tr>"; }
       		echo "</td></tr></table><tr><td align=right><i><b><a href=reviews.php?id=$id&rate=1>Rate it!</a><br><a href=reviews.php?id=$id&review=1>Write a Review!</a></b></i></td></tr>";
       }

	include("header.php");

	echo "<strong><font face=Arial color=#000080 size=2>$path</font></strong><BR><br>";


	if($connect == 1 )
	{
		//  Main
		if($type[0])
		{

			$font="\"Arial\"";
			echo "<br>";

			echo "<form method=POST action=/scripts/redirect.php><select name=redirect size=15>";
			for($k=0; $k<count($t_id); $k++)
			{ echo "<option value=\"reviews.php?id=$t_id[$k]\">$t_name[$k] ($t_sys[$k])\n"; }
			echo "</select><br><input type=submit name=submit value=\"See Review\"></form><br><br>";


			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=2 width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>Top 10 Reviews by Score</b></td></tr>";
			for($j=0; $j<count($r_total); $j++)
			{
				if(!$r_total[$j]) { $r_total[$j]="0"; }
				echo "<tr><td width=90% height=50>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$r_id[$j]>$r_name[$j]</a> :: $r_sys[$j]<br>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$r_id[$j]>Read a review</a> :: <a href=reviews.php?id=$r_id[$j]&comments=1>Comment</a> :: <a href=reviews.php?id=$r_id[$j]&rate=1>Rate it!</a> :: <a href=reviews.php?id=$r_id[$j]&review=1>Write a Review!</a></td><td width=10% align=center><b>$r_total[$j]</b><br><font size=2>$r_votes[$j] vote(s)</font></td></tr>";
			}
			echo "</table><p align=center>Get a list of all rated games <a href=reviews.php?rated=1>here</a>.</p>";

//border-right-width:0;

			echo "<br><br><table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=2 width=100% style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>10 Most Recent Reviews</b></td></tr>";
			for($j=0; $j<count($v_auth); $j++)
			{
				if(!$r_auth[$j]) { $r_auth[$j]="0"; }
				echo "<tr><td width=90% height=50>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$v_id[$j]>$v_name[$j]</a> :: $v_sys[$j]<br>&nbsp&nbsp&nbsp&nbsp by <a href=mailto:$v_em[$j]>$v_auth[$j]</a> on $v_date[$j]</font></td></tr>";
			}
			echo "</table><p align=center>Get a list of all reviews <a href=reviews.php?reviews=1>here</a>.</p>";
		}
		// Reviews
		else if($type[1])
		{
			$img="http://ddrei.com/pics/reviews/$id.jpg";
			$size=@GetImageSize("pics/reviews/$id.jpg");
			if(!$size) { $img="http://ddrei.com/pics/reviews/no.jpg"; }

			say_topmenu($id);

			echo "<tr><td width=495 colspan=4 align=center><br><table border=0 width=478 height=260 style=\"border-collapse: collapse; border-left-width: 0; border-bottom-width: 0\" cellpadding=2 bordercolor=#111111><tr><td width=123 valign=top rowspan=3><p align=left><font face=Arial><img border=0 src=$img width=100 height=100></font><p align=left><font size=2 face=Arial>&nbsp;</font></td>\n";

			echo "<td width=132 valign=top rowspan=3><p align=center><font face=Arial><b>$name</b></font></p><p><font size=2 face=Arial>";
			if($sys) { echo "<i>System:</i> $sys<br>"; }
			if($comp_name) { echo "<i>Company:</i> $comp_name<br>"; }
			if($release) { echo "<i>Release:</i><br>&nbsp&nbsp&nbsp $release<br>"; }
			if($game_type) { echo "<i>Type:</i> $game_type<br>"; }
			if($num_songs) { echo "<i>Songs: </i>$num_songs<br>"; }

			echo "</p></td><td width=177 valign=top bgcolor=#78a9d9 style=\"border-style: solid; border-width: 1\" bordercolor=#000000 height=9><p align=center><font face=Arial><b>DDRei Rating: $total</b></font></td></tr>\n";

			say_graph($gameplay, $graphics, $music, $replay, $tilt, $id);

			echo "<tr><td width=90% valign=top colspan=3><br>";
			if(count($r_title))
			{
				for($k=0; $k<count($r_title); $k++)
				{
					//display all the reviews
					echo "<p><font size=3><b>$r_title[$k]</b></font><br>".str_replace("\t", "&nbsp&nbsp&nbsp", str_replace("\n", "<br>", $r_com[$k]))."<br><font size=2><i>by <a href=\"mailto:$r_em[$k]"."?subject=Review: $name\">$r_auth[$k]</a> on $r_date[$k]</i></font>";
				}
			}
			else { echo "<p>There are currently no reviews written for $name.&nbsp; If you would like to write one, check <a href=reviews.php?id=$id&review=1>here</a>.</p>"; }

			echo "<br><br><b><i><font face=Arial size=2>Related Links:</font></i></b><ul>";
			echo "<li><a href=info.php?game=1&id=$id>$name Information</a> :: Step, song and artist information.</font></li>$links</ul>";
			echo "</table></td></tr></table>";
		}

		// Pictures from the mix reviews.php?id=4&pics=1
		else if($type[2])
		{
			say_topmenu($id);

			echo "<tr><td width=495 colspan=4 align=center><br><table border=0 width=90% style=\"border-collapse: collapse; border-left-width: 0; border-bottom-width: 0\" cellpadding=2 bordercolor=#111111><tr><td width=495 valign=top><p align=center><b>$v_name Pictures</b><p align=center></td></tr><td width=100%>";

			echo "<center><table width=100% border=0 cellspacing=0 cellpadding=0>";

			$current=0;
			// horizontal rows
			for($k=0; $k<$row; $k++)
			{
				echo "<tr>";
				// vertical colums
				for($j=0; $j<4; $j++)
				{
					if($current<$end)
					{
						$tndir="http://ddrei.com/v_pics/".$id."/small/".$folder[$current];
						echo "<td  valign=center align=center><a href=$picdir/$folder[$current]><img src=$tndir BORDER=0><br><font size=2>$folder[$current]</font> </a></td>";
					}
					$current++;
				}
				echo "</tr><tr><td>&nbsp</td></tr>";
			}
			echo "</table>";

			echo "</table></td></tr></table>";
			$main=0; $deep=0;
		}

		//Rate it reviews.php?id=4&rate=1
		else if($type[3])
		{
			$img="http://ddrei.com/pics/reviews/$id.jpg";
			$size=@GetImageSize("pics/reviews/$id.jpg");
			if(!$size) { $img="http://ddrei.com/pics/reviews/no.jpg"; }

			say_topmenu($id);

			if(IsSet($DDRei_reviews)) { $testa = strpos($DDRei_reviews, "id".$id, 1); }
			if(!$DDRei_reviews || !$testa )
			{
			for($k=1; $k<11; $k++)
			{ $options="$options <option value=$k>$k"; }
			echo "<tr><td width=495 colspan=4 align=center><br><table border=0 width=478 height=260 style=\"border-collapse: collapse; border-left-width: 0; border-bottom-width: 0\" cellpadding=2 bordercolor=#111111><tr><td width=250 valign=top rowspan=3><p align=left><font face=Arial><img border=0 src=$img width=100 height=100></font><p align=left><font size=2 face=Arial>\n";


			echo "<i>Name:</i> $name<br>";
			if($comp_name) { echo "<i>Company:</i> $comp_name<br>"; }
			if($release) { echo "<i>Release:</i><br>&nbsp&nbsp&nbsp $release<br>"; }
			if($game_type) { echo "<i>Type:</i> $game_type<br>"; }
			if($num_songs) { echo "<i>Songs: </i>$num_songs<br>"; }
			echo "&nbsp; $DDRei_reviews </font></td>";

			echo "<td width=311 height=85 valign=top><p align=center><font face=Arial><b>Rate: $name</b></font></p><form method=POST action=\"scripts/process_review.php\"><input type=hidden name=r_id value=$id><table border=0>";

			echo "<tr><td width=50%>Gameplay</td><td width=50%><select size=1 name=r_gameplay>$options</select></td></tr>";
			echo "<tr><td width=50%>Graphics</td><td width=50%><select size=1 name=r_graphics>$options</select></td></tr>";
			echo "<tr><td width=50%>Music</td><td width=50%><select size=1 name=r_music>$options</select></td></tr>";
			echo "<tr><td width=50%>Replay</td><td width=50%><select size=1 name=r_replay>$options</select></td></tr>";
			echo "<tr><td width=50%>Tilt</td><td width=50%><select size=1 name=r_tilt>$options</select></td></tr>";

			echo "<tr><td width=50%><input type=submit name=submit value=Rate!></td></tr>";
			echo "</table></td></tr></table></td></tr>";
			} else { echo "<tr><td colspan=4><br>You have already rated $name.  Select a different game<br><br></td></tr>"; }
			echo "</table>";
		}
		// Songlist
		else if($type[4])
		{
			say_topmenu($id);

			echo "<tr><td width=495 colspan=4 align=center><br><table border=0 width=90% style=\"border-collapse: collapse; border-left-width: 0; border-bottom-width: 0\" cellpadding=2 bordercolor=#111111><tr><td width=495 valign=top><p align=center><b>$name Song list</b><p align=center><table border=0 width=100% cellpadding=0 cellspacing=0><tr bgcolor=#93A2BF><td><b>Song Title</b></td><td><b>Artist</b></td></tr>";

			for($k=0; $k<$cur; $k++)
			{
				if(!$flags) { $flags++; $bgcolor="#B1BCD3"; }
				else { $flags=0; $bgcolor="#E1E7F3"; }
				echo "<tr bgcolor=$bgcolor><td><a href=info.php?game=$a_id&sid=$s_id[$k]>$s_name[$k]</a></td><td>$s_artist[$k]</td></tr>";
			}
			if(!$cur) { echo "<tr bgcolor=#b1bcd3><td colspan=2>No songlist avaliable for $name</td></tr>"; }

			echo "</table></td></tr><td width=100%>";

			echo "<br><br><b><i><font face=Arial>Related Links:</font></i></b><ul>";
			echo "<li><a href=info.php?game=1&id=$id>$name Information</a> :: Step, song and artist information.</font></li>$links</ul>";
			echo "</table></td></tr></table>";
		}
		//Comments
		else if($type[5])
		{
			say_topmenu($id);

			echo "<tr><td width=100% colspan=4><br>";

			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr><td colspan=2 width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3><p align=center><b><i><font face=\"Arial, verdana\">$name ($sys) Comments</font></i></b></td></tr><tr><td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top><table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=495>\n";
			for($k=0; $k<$cur; $k++)
			{
				echo "<tr><td><font size=2 face=\"Arial, verdana\"><b>$ctitle[$k]</b> By <a href=mailto:$c_em[$k]>$c_auth[$k]</a> at $c_date[$k]<br>&nbsp&nbsp&nbsp$c_com[$k]</td></tr>\n";
			}
			if(!$cur)
			{ echo "<tr><td><font size=2 face=\"Arial, verdana\">There are currently no comments on $name.</td></tr>"; }
			echo "<tr><td>&nbsp</td></tr></table><tr><td colspan=2 width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3><p align=center><b><i><font face=\"Arial, verdana\">Add a Comment</font></i></b></td></tr><tr>";
			echo "<td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top><table width=100% border=0>
          <form method=\"POST\" action=\"scripts/process_c_review.php\"><input type=hidden name=id value=$id>
		  <tr><td width=150>Name</td>
			  <td><input type=text name=auth size=50 maxlength=50></td></tr>
		  <tr><td width=150>Email</td>
			 <td><input type=text name=em size=50 maxlength=50></td></tr>
		  <tr><td width=150>Title</td>
			  <td><input type=text name=title size=50 maxlength=50 value=\"Re: $name\"></td></tr>
		  <tr><td width=150>Comments</td>
			 <td><textarea rows=6 name=com cols=40></textarea></td></tr>
			 <tr><td>&nbsp</td><td><input type=submit name=submit value=Go>";
		  echo "</td></tr></table></td></tr></table></td></tr></table>";
		}
		//all reviews
		else if($type[7])
		{
			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=2 width=100% style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>All Reviews</b></td></tr>";
			for($j=0; $j<count($v_auth); $j++)
			{
				if(!$r_auth[$j]) { $r_auth[$j]="0"; }
				echo "<tr><td width=90% height=50>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$v_id[$j]>$v_name[$j]</a> :: $v_sys[$j]<br>&nbsp&nbsp&nbsp&nbsp by <a href=mailto:$v_em[$j]>$v_auth[$j]</a> on $v_date[$j]</font></td></tr>";
			}
			echo "</table>";
		}
		//all rated
		else if($type[9])
		{
			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=2 width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>All Rated Games</b></td></tr>";
			for($j=0; $j<count($r_total); $j++)
			{
				if(!$r_total[$j]) { $r_total[$j]="0"; }
				echo "<tr><td width=90% height=50>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$r_id[$j]>$r_name[$j]</a> :: $r_sys[$j]<br>&nbsp&nbsp&nbsp&nbsp<a href=reviews.php?id=$r_id[$j]>Read a review</a> :: <a href=reviews.php?id=$r_id[$j]&comments=1>Comment</a> :: <a href=reviews.php?id=$r_id[$j]&rate=1>Rate it!</a> :: <a href=reviews.php?id=$r_id[$j]&review=1>Write a Review</a></td><td width=10% align=center><b>$r_total[$j]</b><br><font size=2>$r_votes[$j] vote(s)</font></td></tr>";
			}
			echo "</table>";
		}
		// write a review
		else if($type[10])
		{
			say_topmenu($id);

			echo "<tr><td width=100% colspan=4><br>";

			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr><td colspan=2 width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3><p align=center><b><i><font face=\"Arial, verdana\">$name ($sys) Review</font></i></b></td></tr><tr><td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top><table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=495>\n";
			echo "<tr><td><p><font size=2 face=\"Arial, verdana\">Suggestions for topics to write about:";
			echo "<ul><li>What's good about the game?</li><li>What's bad about it?</li><li>How is it different than other similar games</li><li>How is the song selection?</li><li>How is the system for selection songs?</li><li>Gameplay? Graphics? Music? Replay?</li><li>Overall impression?</td></tr>\n";

			echo "<tr><td>&nbsp</td></tr></table><tr><td colspan=2 width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3><p align=center><b><i><font face=\"Arial, verdana\">Add a Review</font></i></b></td></tr><tr>";
			echo "<td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top><table width=100% border=0>
          <form method=\"POST\" action=\"scripts/process_n_review.php\"><input type=hidden name=id value=$id>
		  <tr><td width=100><b>Title</b></td>
			  <td><input type=text name=title size=60 maxlength=60></td></tr>
		  <tr><td width=100><b>Name</b></td>
			  <td><input type=text name=auth size=60 maxlength=60></td></tr>
		  <tr><td width=100><b>Email</b></td>
			 <td><input type=text name=em size=60 maxlength=60></td></tr>
		  <tr><td width=100><b>Review</b></td>
			 <td><textarea rows=25 name=com cols=50></textarea></td></tr>
			 <tr><td>&nbsp</td><td><input type=submit name=submit value=\"Submit a Review\">";
		  echo "</td></tr></table></td></tr></table></td></tr></table>";
		}
		// proccessed review
		else if($type[11])
		{
			say_topmenu($id);

			echo "<tr><td width=100% colspan=4 align=center><br><font face=\"Arial, verdana\">Thank you! Your review will be looked over and then posted.<br><br></td></tr></table>";
		}
	}
	else
	{
		echo "<tr><td vAlign=top height=0 width=40%><center>The profiles database is currently down.<br><br>\n";
		echo "<a href=reviews2.php>Want to write a review?</a><br></td></tr></table>\n";
	}

	include("footer.php");
?>