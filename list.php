<?php

	$title="DDR East Invasion";
	include("header.php");

   

    
	//Possible values sent to this script
	// - 				Lists all game_types (main page
	// game=x			list of games from game_type=game
	//  &allsongs=1 	all songs and artists from the chosen game
	//	 &sid=z			song information about song_id=z relative to all_songs
	//  &id=y 			information about versions_id=y
	//   &music=1		album information about version_id=y from game_music
	//   &sid=z			song information about song_id=z relative to game y and type x
	// x &reviews=1		all reviews about version_id=y
	//   &songs=y 		songs from version_id=y
	//  &about=1 		info about game_type=x
	//  &genre=y		all songs from game_genre=y
	// artist=x			artist information about game_artist=x
	// sys=x			all versions under sys=x ordered by type
	// country=x		all games released in country x ordered by type
	// comp=x			all games released by company x ordered by type

	//declare some variables to make things easier
	$here="list.php"; // this files name
	$font="\"Arial\""; //default font
	$color2="#93A2BF"; //color of the right border on DDRei
	//functions here
	function output_tr($for_color, $height)
	{
		echo "\n<tr onmouseout=\"this.style.backgroundColor='$for_color';\" onmouseover=\"this.style.backgroundColor='#E4E9F2';\" bgcolor=".$for_color;
			if($height!=0) echo " height=$height";
			echo ">";
	}

	function start_table($first_row)
	{
		$font="arial";
		if($first_row)
			echo "<br>";
		echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border:1px solid #c0c0c0; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>";
		if($first_row)
			echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top align=center height=1 width=495 colspan=10><font face=$font size=3><b>$first_row</b></font></td></tr>\n";
	}

	function get_songs($songs)
	{
		$allsongs=explode(",", $songs);
		$total=count($allsongs);

		//check to see if there are colors
		if($allsongs[0]=='c')
		{
			return --$total/2;

		}
		else
		{
			return --$total;
		}
	}

	function get_aa($steps, $freeze)
	{
		$max_score=($steps*2)+($freeze*6);
		$aa_score=ceil($max_score*.93);
		return($max_score-$aa_score);
	}

	function output_song($song, $version)
	{
		//song= game_songs.song_id.  version=game_versions.version_id
		//get all the information and path information for the song
		$query="SELECT * FROM game_songs, game_types
				LEFT OUTER JOIN game_versions on game_versions.version_id=$version
				WHERE game_songs.game_type=game_types.type_id AND game_songs.song_id=$song";
		$result = mysql_db_query($database, $query);
		if ($s=@mysql_fetch_array($result))
		{
			extract($s);
			if(!$version) $path="<a href=$here?game=$version&allsongs=1>All Songs</a>";
			else $path="<a href=$here?game=$type_id&v_id=$version_id";


			echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?game=$game>$type_full</a> :: All Songs</font></b><br>";
		}
	}
	
	function art_say($width1, $width2, $left, $right)
	{
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>$left:</strong></td>\n";
		echo "<td vAlign=top width=$width2>".str_replace("\n", "<br>", $right)."</td></tr>\n";
	}
	
	function start_vtable($here, $game, $id) //starts the table for any of the given version ID
	{
		$color1="#93A2BF"; //start color
		$color2="#E1E7F3"; //mouseover
		$fcolor="#000000"; //font color
		
		echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse\" bordercolor=#111111 width=495>
    <tr>
      <TD bgcolor=$color1 align=center
          onclick=\"javascript:location.href='$here?game=$game&v_id=$id';\"
          onmouseout=\"this.style.backgroundColor='$color1'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='$color2'; this.style.cursor = 'hand'; window.status = 'Reviews the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b><font face=Arial size=2 color=$fcolor>Main info</font></b></td>
	  <TD bgcolor=$color1 align=center
          onclick=\"javascript:location.href='$here?game=$game&v_id=$id&news=1';\"
          onmouseout=\"this.style.backgroundColor='$color1'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='$color2'; this.style.cursor = 'hand'; window.status = 'Comments about the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b><font face=Arial size=2 color=$fcolor>Updates</font></b></td>
      <TD bgcolor=$color1 align=center
          onclick=\"javascript:location.href='$here?game=$game&v_id=$id&songs=1';\"
          onmouseout=\"this.style.backgroundColor='$color1'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='$color2'; this.style.cursor = 'hand'; window.status = 'Pictures'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b><font face=Arial size=2 color=$fcolor>Songs</font></b></td>
      <TD bgcolor=$color1 align=center
          onclick=\"javascript:location.href='$here?game=$game&v_id=$id&reviews=1';\"
          onmouseout=\"this.style.backgroundColor='$color1'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='$color2'; this.style.cursor = 'hand'; window.status = 'Songslist for the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b><font face=Arial size=2 color=$fcolor>Reviews</font></b></td>
      <TD bgcolor=$color1 align=center
          onclick=\"javascript:location.href='$here?game=$game&v_id=$id&pics=1';\"
          onmouseout=\"this.style.backgroundColor='$color1'; window.status = ''; return true;\"
          onmouseover=\"this.style.backgroundColor='$color2'; this.style.cursor = 'hand'; window.status = 'Comments about the game.'; return true;\" style=\"border-style: solid; border-width: 1\">
			<b><font face=Arial size=2 color=$fcolor>Pictures</font></b></td></tr>";
			
		echo "<tr><td colspan=5 valign=top>";
		start_table(0);
	}
	
	function end_vtable()
	{
		echo "</td></tr></table></td></tr></table>";
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

?>

<script language="JavaScript"><!--

function goThere(form){
	var linkList=form.selectThis.selectedIndex
	if(!linkList==""){window.location.href=form.selectThis.options[linkList].value;}
}
-->
</script>


<style type="text/css"><!--
{
	td {
	   font-weight : normal;
	   font-size : 10px;
	   font-family : Arial;
	   color : #000000;
	}
} -->
</style>

<?php


	// get the jump to boxes up top like in the locations section
	if($game)
	{
		// First Dropdown is a selection of game_type's
		echo "<table border=0 width=495><tr><td align=left>";
		//Output the change game form
		echo "<form name=dropMenu method=post>
		<select name=selectThis size=1 onChange=\"goThere(this.form);\">
		<option selected value=\"\">Select a Game Type";
		$query="SELECT game_types.type_id, game_types.type_full, count(game_versions.version_id) as count FROM game_types, game_versions WHERE game_versions.type_id=game_types.type_id GROUP BY type_full";
		$result = mysql_db_query($database, $query);
		while ($s=@mysql_fetch_array($result))
		{
			extract($s);
			echo "<option value=\"http://www.ddrei.com/$here?game=$type_id\" ";
			if($game==$type_if) echo "selected";
			echo ">$type_full ($count)\n";
		}
		echo "</select></form></td><td align=right>";
	}
	else
	{
		//start the table -- left justified on main page, right justified elsewhere
		echo "<table border=0 width=495><tr><td align=right>";
	}

	//output the system/company/country selection
	echo "<form name=dropMenu2 method=post>
		<select name=selectThis size=1 onChange=\"goThere(this.form);\">
		<option selected value=\"\"> -- Ordered By --
		<option value=\"http://www.ddrei.com/$here\">Game Type
		<option value=\"http://www.ddrei.com/$here?art_main=1\" ";
		if($art_main) echo "selected";
		echo ">Artists<option value=\"http://www.ddrei.com/$here?comp_main=1\" ";
		if($comp_main) echo "selected";
		echo ">Company<option value=\"http://www.ddrei.com/$here?country_main=1\" ";
		if($country_main) echo "selected";
		echo ">Country<option value=\"http://www.ddrei.com/$here?sys_main=1\" ";
		if($sys_main) echo "selected";
		echo ">System</select</form></td></tr></table>";

	if($game||$sys||$company||$country)
	{
		if($allsongs)
		{
			if($sid)
			{
				output_song($sid, "0");
			}
			else //list all songs on game_type=$game
			{
				//main query for all versions
				$query="SELECT type_full FROM game_types WHERE type_id=$game";
				$result = mysql_db_query($database, $query);
				$r=@mysql_fetch_array($result);
				extract($r);

				echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?game=$game>$type_full</a> :: All Songs</font></b><br>";

				start_table("All Songs from $type_full");

				$query="SELECT song_id, song_name, song_artist, art_name FROM game_songs, game_artists WHERE game_type=$game AND song_artist=art_id ORDER BY song_name";
				$result = mysql_db_query($database, $query);
				$s=@mysql_fetch_array($result);

				//output the column titles
				output_tr("#EEF1F5", 0);
				echo "<td><b>Song Name</b></td><td><b>Artist</b></td></tr>";

				while ($s=@mysql_fetch_array($result))
				{
					extract($s);

					$color=="#FFFFFF"?$color="#EEF1F5":$color="#FFFFFF";

					output_tr($color, 0);
					echo "<td><a href=$here?game=$game&allsongs=1&sid=$song_id>$song_name</a></td><td><a herf=$here?artist=$song_artist>$art_name</a></td></tr>";
				}
				echo "</table>";
			}
		}
		else if($v_id)
		{
			//ouput the header for a specific version
			
			if($music) //list the soundtrack for a game
			{
			}
			else if($s_id) //info on a specific song
			{
				output_song($s_id, $v_id);
			}
			else if($songs) //list all songs for the mix
			{

				// mid is the id number inside all_type to usewith
				if(!$mid) { $mid=0; }

				$query= "SELECT version_name, version_format, game_types.type_full, game_types.type_id from game_types, game_versions WHERE game_versions.type_id=game_types.type_id AND game_versions.version_id=$v_id";
				$result=mysql_db_query($database, $query);
				if ($r=@mysql_fetch_array($result))
				{
					extract($r);

					$differents=explode(",", $version_format);

					$total=explode(".", $differents[$mid]);
					for($k=1; $k<count($total); $k++)
					{
						$next=$k+1;
						if(substr_count($total[$k], "#"))
						{
							//sets the color
							$colors[]="$total[$k]";
							//sets the columns to get
							if(!$flag)
							{
								$flag=1;
								$cols_to_get="$total[$next]";
								$allcols[]="$total[$next]";
							}
							else
							{
								$cols_to_get="$cols_to_get, $total[$next]";
								$allcols[]="$total[$next]";
							}
							$next++;
							//sets the lower columns
							$lower_cols[]="$total[$next]";
						}
						//sets the headers that will be displayed
						if(!$total[$k])
						{
							$header[]="$total[$next]";
							$next=$next-2;
							$length[]="$total[$next]";
						}
					}
					$title=$total[0];
				}
				$query = "SELECT version_name, version_songs from game_versions WHERE version_id=$v_id";
				$result = mysql_db_query($database, $query);
				if ($r = @mysql_fetch_array($result)) extract($r);

				$title="$name Songs";

				$allsongs=explode(",", $version_songs);
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
						if($k!=$terminal) $songstoget="$songstoget $allsongs[$k],";
						else  $songstoget="$songstoget $allsongs[$k]";

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
						if($k!=$terminal) $songstoget="$songstoget $allsongs[$k], ";
						else  $songstoget="$songstoget $allsongs[$k]";
					}
				}

				//only for max
				if($aa)
				{
					$query2="SELECT * from game_songs left outer join game_artists on song_artist=art_id WHERE song_id in ($songstoget order by song_name";
					$result2 = mysql_db_query($database, $query2);
					if ($result2)
					{
						$current=0;
						while($s = @mysql_fetch_array($result2))
						{
							$row="row_$current";
							$s_name[$current]=$s["song_name"];
							$s_artist[$current]=$s["art_name"];
							$s_artist_id[$current]=$s["art_id"];
							$s_id[$current]=$s["song_id"];
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
				else
				{
					// Main query
					if($cols_to_get) $query2="SELECT song_id, song_name, art_name, art_id, $cols_to_get from game_songs left outer join game_artists on song_artist=art_id WHERE song_id in ($songstoget) order by song_name";
					else $query2="SELECT song_id, song_name, art_id, art_name from game_songs left outer join game_artists on song_artist=art_id WHERE game_songs.song_artist=game_artists.art_id and song_id in($songstoget) order by song_name";
					
					$result2=mysql_db_query($database, $query2);

					//read in the data to variables
					while($s=@mysql_fetch_array($result2))
					{
						$s_name[$current]=$s["song_name"];
						$s_artist[$current]=$s["art_name"];
						$s_artist_id[$current]=$s["art_id"];
						$s_id[$current]=$s["song_id"];
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

				//get the paths up top
				$flag=0;
				if($aa) $mid=10;
				for($k=0; $k<count($differents); $k++)
				{
					$result=explode(".", $differents[$k]);
					if($mid==$k) { $links=""; }
					else { $links="<a href=$here?game=$game&v_id=$v_id&songs=1&mid=$k>"; }
					if($k==0) { $options="$links $result[0]</a>"; }
					else { $options="$options :: $links $result[0]</a>"; }
				}
				$flag=0;

				//output everything
				echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?game=$game>$type_full</a> :: <a href=$here?game=$game&v_id=$v_id>$version_name</a> :: Song List</font></b><br>";
				$colspan=count($allcols)+2;

				//output the AA link for Max
				if(($songs==17) || ($songs==18) || ($songs==27)) $options="$options <br><br><a href=info.php?game=$game&songs=$songs&aa=1>Most points off for an AA</a>";

				if($options) //Output the change menu
					echo "<br><font face=Arial color=#000080 size=2>$options</font><br><br>";

				if($header[0]) $rowspan=2;
				start_vtable($here, $game, $v_id);
				
				//Default heading for all
				echo "<tr><td><table border=0 width=100% cellpadding=0 cellspacing=0><tr bgcolor=#EEF1F5><td width=200 height=4 rowspan=$rowspan><font face=$font size=2><b>Song Name</b></font></td>";
				echo "<td width=200 height=4 rowspan=$rowspan><font face=$font size=2><b>Artist</b></font></td>";

				//get all the column headers set
				for ($k=0; $k<count($header); $k++)
					echo "<td align=center colspan=$length[$k]><font face=$font size=2><b>$header[$k]</b></font></td>\n";
				echo "</tr>\n<tr bgcolor=#EEF1F5>";
				for ($k=0; $k<count($lower_cols); $k++)
					echo "<td><b>$lower_cols[$k]</b></td>";
				if(!count($lower_cols)) echo "<td colspan=10>&nbsp</td>";
				echo "</tr>\n";

				//output the main data
				for($k=0; $k<count($s_name); $k++)
				{
					if($s_name[$k])
					{
						if(!$flags) { $flags++; $color="#FFFFFF"; }
						else { $flags=0; $color="#EEF1F5"; }
						$cur_id=$s_id[$k];
						output_tr($color, 0);
						echo "<td width=200><a href=$here?game=$game&v_id=$v_id&s_id=$s_id[$k]><font face=$font size=2 color=".$colorss[$cur_id].">";
						if($colorss[$cur_id]=='#999966') echo "<b>";
						echo "$s_name[$k]";
						if($colorss[$cur_id]=='#999966') echo "</b>";
						echo "</a></font></td><td vAlign=top width=200 height=4><font face=$font size=2><a href=$here?artist=$s_artist_id[$k]>$s_artist[$k]</a></font></td>";
						for($m=0; $m<count($lower_cols); $m++)
						{
							$row_to_out="row_$k";
							$data=$col["$row_to_out"][$m];
							echo "<td><font color=$colors[$m]><b>$data</b></font></td>\n";
						}
						echo "</tr>";
					}
				}
				echo "</td></tr></table>";
				end_vtable();

				if($allcolors)
				{
					echo "<p><b>Key:</b><br>Songs in <font face=$font size=2 color=#000080><b>blue</b></font> are intially available.<br>Songs in <font face=$font size=2 color=#999966><b>yellow</b></font> are unlockable songs.<br> Songs in <font face=$font size=2 color=#006600><b>green</b></font> are new to the mix and initially available.<br>Songs in <font face=$font size=2 color=#9900cc><b>purple</b></font> are songs new to the upgrade.<br></p>";
				}

			}
			else if($reviews)
			{
				start_vtable($here, $game, $v_id);
				echo "Reviews";
				end_vtable();
			}
			else if($news)
			{
				start_vtable($here, $game, $v_id);
				echo "news";
				end_vtable();
			}
			else if($pics)
			{
				start_vtable($here, $game, $v_id);
				echo "pics";
				end_vtable();
			}
			else // main info for version_id=$v_id
			{
				//get the path info
				$query="SELECT * 
					FROM game_types, game_versions, game_countries, game_companies, game_systems 
					LEFT OUTER JOIN game_versions_links on game_versions.version_id=game_versions_links.version_id WHERE 
					game_versions.version_country=game_countries.country_id AND
					game_versions.type_id=game_types.type_id AND 
					game_versions.version_company=game_companies.comp_id AND
					game_versions.version_system=game_systems.sys_id AND
					game_versions.version_id=$v_id";
					
				$result = @mysql_db_query($database, $query);
				if($s=@mysql_fetch_array($result))
				{
					extract($s);
					echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?game=$game>$type_full</a> :: $version_name</font></b><br><br>";

					//start the table with all the data
					start_vtable($here, $game, $v_id);

					//output all the data about the game
					$img="http://ddrei.com/images/games/boxart/$v_id.jpg";
					$size=@GetImageSize("images/games/boxart/$v_id.jpg");
					if(!$size) { $img="http://ddrei.com/pics/reviews/no.jpg"; }
			
					echo "<tr><td align=center>
						<table border=0 width=90%>
							<tr><td colspan=2>
								&nbsp
							</td></tr>
						<tr><td align=center width=150><img src=$img border=0></td><td>";
					
					echo "<table bordercolor=#000000 width=100% border=1 cellpadding=1 cellspacing=0><tr><td bgcolor=$color2 align=center><b><font name=arial size=4>$version_name</b></td></tr><tr><td>";
					echo "<b>Total Songs: ";
					
					if($songs) echo $songs;
					else echo "Not listed";
					
					echo "</b><br>";
					if($version_release_date) echo "Release Date: <<b>$version_release_date<b><br>";
					echo "<b>Game Type:</b> <b>$type_game</b><br>";
					echo "<b>Game:</b> <b>$type_full</b><br>";
					echo "<b>Company:</b> <b>$comp_name</b><br>";
					echo "<b>System:</b> <b>$sys_name</b><br>";
					echo "<b>Country:</b> <b>$country_name</b><br>";

					echo "</td></tr></table>";
					
					echo "<br></td></tr></table><p align=center>";
					
					
					//output the latest news for this version
					echo "<table bordercolor=#000000 width=80% border=1 cellpadding=1 cellspacing=0>
					<tr><td bgcolor=$color2 align=center><b><font name=arial size=4>$version_name News</b></td></tr>";
					
					$query="SELECT * FROM game_updates WHERE v_id=$v_id order by v_date desc limit 0,5";
					$result = @mysql_db_query($database, $query);
					while($s=@mysql_fetch_array($result))
					{
						extract($s);
						$total_updates++;
						echo "<tr><td><a href=$here?v_id=$v_id&news=1&nid=$update_id>$v_title</a><br><i>".substr($v_text, 0, 150)." ".mysql_timestamp_to_human($v_date)."</i></td></tr>";
					}
					if(!$total_updates) echo "<tr><td>No news is available for this game.</td></tr>";
					
					echo "</table>";
					
					//end the table
					end_vtable();
				}
			}
		}
		else if($about)
		{
			//get the path
			$query="SELECT type_full, type_about FROM game_types WHERE type_id=$game";
			$result = mysql_db_query($database, $query);
			if($s=@mysql_fetch_array($result))
			{
				extract($s);
				echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?game=$game>$type_full</a> :: About</font></b><br>$type_about";
			}
		}
		else if($genre)
		{
			//implemented later
		}
		else // Main game in for game_type=$game
		{

			//list all the games for a single system, country or company
			if($sys) //find the path, and get the main queries for each
			{
				$path_query="SELECT sys_name as path_name from game_systems WHERE sys_id=$sys";
				$first="<a href=$here?sys_main=1>By System</a>";
				$query="SELECT game_versions.version_id, game_versions.version_name, game_versions.version_songs, sys_id, sys_name_abrv, sys_name, type_full as main, count(distinct review_id) as reviews, game_countries.country_id, game_countries.country_abrv
					FROM game_versions, game_types, game_systems, game_countries LEFT OUTER JOIN game_reviews on game_versions.version_id=game_reviews.version_id
					WHERE game_versions.type_id=game_types.type_id AND game_versions.version_system=game_systems.sys_id AND game_systems.sys_id=$sys
					GROUP BY game_systems.sys_name, game_versions.version_id";
			}
			else if($country)
			{
				$path_query="SELECT country_name as path_name from game_countries WHERE country_id=$country";
				$first="<a href=$here?country_main=1>By Country</a>";
				$query="SELECT game_versions.version_id, game_versions.version_name, game_versions.version_songs, sys_id, sys_name_abrv, sys_name, type_full as main, count(distinct review_id) as reviews, game_countries.country_id, game_countries.country_abrv
					FROM game_versions, game_types, game_systems, game_countries
					LEFT OUTER JOIN game_reviews on game_versions.version_id=game_reviews.version_id
					WHERE game_versions.type_id=game_types.type_id AND game_versions.version_system=game_systems.sys_id AND game_versions.version_country=game_countries.country_id AND game_countries.country_id=$country
					GROUP BY game_types.type_full, game_systems.sys_name, game_versions.version_id";
			}
			else if($company)
			{
				$path_query="SELECT comp_name as path_name from game_companies WHERE comp_id=$company";
				$first="<a href=$here?comp_main=1>By Company</a>";
				$query="SELECT game_versions.type_id as game, game_versions.version_id, game_versions.version_name, game_versions.version_songs, sys_id, sys_name_abrv, sys_name, type_full as main, count(distinct review_id) as reviews, game_countries.country_id, game_countries.country_abrv
					FROM game_versions, game_types, game_systems, game_companies, game_countries
					LEFT OUTER JOIN game_reviews on game_versions.version_id=game_reviews.version_id
					WHERE game_versions.type_id=game_types.type_id AND game_versions.version_system=game_systems.sys_id AND game_versions.version_company=game_companies.comp_id AND game_companies.comp_id=$company
					GROUP BY game_types.type_full, game_systems.sys_name, game_versions.version_id";
			}
			else
			{
				//main query for all versions
				$path_query="SELECT type_full FROM game_types WHERE type_id=$game";
				$query="SELECT game_versions.version_id, game_versions.version_name, game_versions.version_songs, sys_id, sys_name_abrv, sys_name as main, type_full, count(distinct review_id) as reviews, game_countries.country_id, game_countries.country_abrv
					FROM game_versions, game_types, game_systems, game_countries LEFT OUTER JOIN game_reviews on game_versions.version_id=game_reviews.version_id
					WHERE game_versions.type_id=game_types.type_id AND game_versions.version_system=game_systems.sys_id AND game_types.type_id=$game
					GROUP BY game_systems.sys_name, game_versions.version_id";
				$sys=1;
			}

		//	echo "query='$query'<br><br>";
			//all queries have: $version_id, $version_name, $version_songs, $sys_name, $type_full, $reviews
			$result = mysql_db_query($database, $path_query);
			$s=@mysql_fetch_array($result);
			extract($s);
			if($first) echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: $first :: $path_name</font></b><br>";
			else echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: $type_full Versions</font></b><br>";
			
			//get the main data
			$result = mysql_db_query($database, $query);
			$s=@mysql_fetch_array($result);
			//start the table
			if($first) start_table("$path_name Games");
			else start_table("$type_full Versions");

			output_tr("#EEF1F5", 0);
			echo "<td>&nbsp</td>";
			if(!$sys) echo "<td align=center vAlign=center><b>System</b></td>";
			echo "<td align=center vAlign=center><b>Songs</b></td>";
			if(!$country) echo "<td align=center vAlign=center><b>Country</b></td>";
			echo "<td align=center vAlign=center><b>Reviews<b></td></tr>";
			
			
			while ($s=@mysql_fetch_array($result))
			{
				extract($s);
				if(!$flags) { $flags++; $color="#FFFFFF"; }
				else { $flags=0; $color="#EEF1F5"; }
				output_tr($color, 0);

				//get the number of songs on the version
				if($version_songs) $songs=get_songs($version_songs);
				else $songs="";
				if(!$reviews) $reviews="";

				//start a new tr to order by system
				if($last!=$main)
				{
					echo "<td colspan=5>&nbsp</td></tr>";
					if(!$flags) { $flags++; $color="#FFFFFF"; }
					else { $flags=0; $color="#EEF1F5"; }
					output_tr($color, 0);
					echo "<td vAlign=center width=495 class=versions colspan=5>&nbsp&nbsp<b>$main</b></td></tr>";
					if(!$flags) { $flags++; $color="#FFFFFF"; }
					else { $flags=0; $color="#EEF1F5"; }
					output_tr($color, 0);
					$last=$main;
				}
				echo "<td vAlign=center width=350><font face=arial size=2>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href=$here?game=$game&v_id=$version_id>$version_name</a>";
				//if(!$sys) echo " <i>($sys_name)</i>";
				echo "</td>";
				if(!$sys) echo "<td vAlign=center align=center><font face=arial size=1><a href=$here?sys=$sys_id>$sys_name</a></td>";
				echo "<td vAlign=center align=center><font face=arial size=2><a href=$here?game=$game&v_id=$version_id&songs=1>$songs</a></td>";
				if(!$country) echo "<td align=center vAlign=center><font face=$font size=1><a href=$here?country=$country_id>$country_abrv</a></td>";
				echo "<td align=center vAlign=center><font face=$font><a href=$here?game=$game&v_id=$version_id&reviews=1>$reviews</a></td></tr>";
			}
			echo "</table>";
		}
	}
	else if($artist) // artist info about $artist id
	{
		//get all the info about this artist
		$query="SELECT g1.art_name, g1.art_bio, g1.art_link, count(distinct song_id) as songs, g2.art_id as alias_id, g2.art_name as alias_name
				FROM game_artists AS g1, game_songs
				LEFT OUTER JOIN game_artists AS g2 ON g1.art_alias=g2.art_id
				WHERE game_songs.song_artist=g1.art_id AND g1.art_id=$artist
				GROUP BY g1.art_name";

		//start the first table - artist info
		$result = mysql_db_query($database, $query);
		$s=@mysql_fetch_array($result);
		extract($s);

		echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: <a href=$here?art_main=1>Artists</a> :: $art_name</font></b><br>";
		start_table($art_name);

		echo "<tr><td><font face=arial size=2><b>Artist Name:</b> $art_name<br><b>Alias Name:</b> ";
		if(!$alias_name) echo "<i>(none)</i>";
		else echo "<a href=$here?artist=$alias_id>$alias_name</a>";
		echo "<br><b>Music Game Songs:</b> $songs<br><b>Web Page:</b> ";
		if(!$art_link) echo "<i>(none)</i>";
		else echo "<a href=$art_link>Click here.</a>";
		echo "</td></tr></table><br><br>";

		//output all the songs done by this artist
		if($alias_name) $title="Songs by alias $alias_name";
		else $title="Songs by $art_name";
		start_table($title);

		$query="SELECT song_name, song_id, game_type, type_id, type_full
				FROM game_songs, game_artists, game_types
				WHERE song_artist=art_id and art_id=$artist and game_type=type_id
				ORDER BY song_name, type_full";
		$result = mysql_db_query($database, $query);
		while ($s=@mysql_fetch_array($result))
		{
			extract($s);
			if(!$flags) { $flags++; $color="#FFFFFF"; }
			else { $flags=0; $color="#EEF1F5"; }
			output_tr($color, 0);
			echo "<td><font face=arial size=2><a href=$here?game=$game_type&allsongs=1&sid=$song_id>$song_name</a></td><td><font face=arial size=2>$art_name</td><td><font face=arial size=2><a href=$here?game=$type_id>$type_full</a></td></tr>";
		}
		echo "</table>";

		//Output all the songs by the alias
		$query="SELECT song_name, song_id, art_alias, art_name, game_type, type_id, type_full
				FROM game_songs, game_artists, game_types
				WHERE song_artist=art_id and art_alias=$artist and game_type=type_id
				ORDER BY song_name, type_full";
		$result = mysql_db_query($database, $query);
		while ($s=@mysql_fetch_array($result))
		{
			extract($s);
			if(!$flag) { start_table("All songs under $art_name's Aliases"); $flag++; }
			if(!$flags) { $flags++; $color="#FFFFFF"; }
			else { $flags=0; $color="#EEF1F5"; }
			output_tr($color, 0);
			echo "<td><font face=arial size=2><a href=$here?game=$game_type&allsongs=1&sid=$song_id>$song_name</a></td><td><font face=arial size=2><a href=$here?artists=$art_id>$art_name</td><td><font face=arial size=2><a href=$here?game=$type_id>$type_full</a></td></tr>";
		}
		if($flag) echo "</table>";

		if($alias_name) echo "This is only an alias for <a href=$here?artist=$alias_id>$alias_name</a>.  Check <a href=$here?artist=$alias_id>here</a> for a complete song list.<br>";

	}
	else if($art_main) // List all the artists
	{
		//things to output about each artist:
		// #of songs...uh
		echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: Artists</font></b><br>";
		start_table("Artist List");

		//output the header column
		output_tr("#EEF1F5", 0);
		echo "<td vAlign=center width=495>&nbsp&nbsp<b>Artist Name</b></td><td><b>Alias</b></td><td><b>Songs</b></td><td><b>Aliases</b></td></tr>";

		$query="SELECT g1.art_id, g1.art_name, g3.art_name as alias_name, g3.art_id as alias_id, count(distinct song_id) as songs, count(distinct g2.art_id) as aliases
				FROM game_artists AS g1, game_songs
				LEFT OUTER JOIN game_artists AS g2 ON g1.art_id=g2.art_alias
				LEFT OUTER JOIN game_artists AS g3 ON g1.art_alias=g3.art_id
				WHERE game_songs.song_artist=g1.art_id
				GROUP BY g1.art_name";
		$result = mysql_db_query($database, $query);
		while ($s=@mysql_fetch_array($result))
		{
			extract($s);
			if(!$flags) { $flags++; $color="#FFFFFF"; }
			else { $flags=0; $color="#EEF1F5"; }
			output_tr($color, 0);

			echo "<td><font face=arial size=2><a href=$here?artist=$art_id>$art_name</a>";
			if($alias_id) echo "*";
			echo "</td><td>";
			if($alias_id) echo "<font face=arial size=2><a href=$here?artist=$alias_id align=center>$alias_name</a>";
			echo "</td><td align=center>$songs</td><td align=center>$aliases</td></tr>";
		}
		echo "</table><br><br><b>*</b> - Denotes that this is an alias name of the artist appearing in the 'Alias' column.<br>
			<b>Songs column</b> - Denotes how many songs appearing in all music games the artist performed.<br>
			<b>Aliases</b> - Denotes how many aliases the artist goes by.  For instance, if Naoki one released songs as Naoki, 8bit and NMR, then he would have 2 aliases.<br>";
	}
	else // main page
	{
		//sys_main, comp_main, country_main
		if($sys_main||$comp_main||$country_main)
		{
			if($sys_main)
			{

				$query="SELECT game_systems.sys_id as id, game_systems.sys_name as name, count(game_versions.version_id) as count FROM game_systems, game_versions WHERE game_versions.version_system=game_systems.sys_id GROUP BY sys_name";
				$title="Systems";
				$var="sys";
			}
			else if($comp_main)
			{

				$query="SELECT game_companies.comp_id as id, game_companies.comp_name as name, count(game_versions.version_id) as count FROM game_companies, game_versions WHERE game_versions.version_company=game_companies.comp_id GROUP BY comp_name";
				$title="Companies";
				$var="company";

			}
			else if($country_main)
			{
				//output the title box
				$query="SELECT game_countries.country_id as id, game_countries.country_name as name, count(game_versions.version_id) as count FROM game_countries, game_versions WHERE game_versions.version_country=game_countries.country_id GROUP BY country_name";
				$title="Countries";
				$var="country";
			}
			//echo "query='$query'<br><br>";
			echo "<b><font face=Arial color=#000080 size=2><a href=$here>Music Games</a> :: $title</font></b><br><ul>";
			$result = @mysql_db_query($database, $query);
			while ($s=@mysql_fetch_array($result))
			{
				extract($s);
				echo "<li><b><a href=$here?$var=$id>$name</a></b> ($count)";
				echo "</li>\n";
			}
			echo "</ul>";
		}
		else //  MAIN PAGE
		{
			//output the title box
			echo "<b><font face=Arial color=#000080 size=2>Music Games</font></b><br><ul>";

			$query="SELECT game_types.type_id, game_types.type_about, game_types.type_full, count(game_versions.version_id) as count FROM game_types, game_versions WHERE game_versions.type_id=game_types.type_id GROUP BY type_full";
			$result = @mysql_db_query($database, $query);
		//	echo "query='$query'<br><br>";
			while ($s=@mysql_fetch_array($result))
			{
				extract($s);
				echo "<li><b><a href=$here?game=$type_id>$type_full</a></b> ($count)";
				if($type_about) { echo " :: <a href=$here?game=$type_id&about=1>About</a>"; }
				echo "</li>\n";
			}
			echo "</ul>";
		}
	}

	// bottom selection box
	if($game)
	{
		echo "<br><br><table><tr><td align=center>";

		// Second Dropdown is a selection of games of game_type if a game is selected
		echo "<form name=dropMenu3 method=post>
		<select name=selectThis size=1 onChange=\"goThere(this.form);\">
		<option selected value=\"\">Select a Game";

		//get all the versions from game_type=$game
		$query="SELECT version_id, version_name, sys_name FROM game_versions, game_types, game_systems WHERE game_versions.type_id=game_types.type_id AND game_versions.version_system=game_systems.sys_id  AND game_types.type_id=$game ORDER BY game_systems.sys_name, game_versions.version_id";
		$result = mysql_db_query($database, $query);
		while ($r=@mysql_fetch_array($result))
		{
			extract($r);
			echo "<option value=\"http://www.ddrei.com/$here?game=$game&id=$version_id\" ";
			if($version_id==$id) echo "selected";
			echo ">$version_name ($sys_name)\n";
		}
		echo "</select></form></td></tr></table>";
	}

	include("footer.php");
?>