<?php
	$title="DDR East Invasion";
	$image="news_hdr.jpg";
	include("scripts/time.php");
    include("header.php");
    include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
?>
	<script language="JavaScript"><!--

// ***********************************************
// AUTHOR: WWW.CGISCRIPT.NET, LLC
// URL: http://www.cgiscript.net
// Use the script, just leave this message intact.
// Download your FREE CGI/Perl Scripts today!
// ( http://www.cgiscript.net/scripts.htm )
// ***********************************************

function goThere(form){
	var linkList=form.selectThis.selectedIndex
	if(!linkList==""){window.location.href=form.selectThis.options[linkList].value;}
}
//-->
</script>

<?php
/* assume:

	co_id = country_id
	st_id  = state_id
	arc_id   = arcade_id
	game_id   = Type of game
	mix_id = Exactly what game
	m_id   = any given machine (never called directly)
*/

	$font="\"Arial\"";
	if(!$co_id) $co_id=1;
	if(!$game_id) $game_id=1;

	function title($title)
	{
		echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>
		<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495><font face=arial size=3><strong>$title</strong></font></td></tr>";
	}
	function title_st($title)
	{
			echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495><tr bgcolor=\"#93A2BF\"><td vAlign=top align=left height=1 width=200><font face=$font size=3><strong>$title</strong></font></td>\n";
			echo "<td valign=top align=left height=1 width=100><font face=$font size=3><strong>City</strong></font></td>\n";
			echo "<td valign=top align=left height=1 width=195><font face=$font size=3><strong>Machine Type</strong></td></font></font></tr>\n";
	}

	//for path need: version name, arcade_game full, arcade_game id, country, country id, state, state id, arcade name
	function get_path($name, $arc_full, $arc_id, $country_name, $country_id, $st_name, $st_id, $arcade_name, $mix, $all)
	{
		if($name)
		{
			//tier1: arcade game, arcade id, country
			if(!$st_name) return "<b>$arc_full Locations :: $name</b><br>$country_name";
			//tier2: arcade game, arcade id, country, country id, state
			else if(!$st_id) return "<b><a href=loc.php?co_id=$country_id&game_id=$arc_id>$arc_full Locations</a> :: $name</b><br><a href=loc.php?co_id=$country_id&game_id=$arc_id&mix_id=$mix>$country_name</a> :: $st_name";
			//tier3: arcade game, arcade id, country, country id, state, state id, arcade name
			else return "<b><a href=loc.php?co_id=$country_id&game_id=$arc_id>$arc_full Locations</a> :: $name</b><br><a href=loc.php?co_id=$country_id&game_id=$arc_id&mix_id=$mix>$country_name</a> :: <a href=loc.php?st_id=$st_id&game_id=$arc_id&mix_id=$mix>$st_name</a> :: $arcade_name";
		}
		else if($all)
		{
			//tier1: arcade game, arcade id, country
			if(!$st_name) return "<b>$arc_full Locations</b><br>$country_name";
			//tier2: arcade game, arcade id, country, country id, state
			else if(!$st_id) return "<b><a href=loc.php?co_id=$country_id&all_games=1>$arc_full Locations</a></b><br><a href=loc.php?co_id=$country_id&all_games=1>$country_name</a> :: $st_name";
			//tier3: arcade game, arcade id, country, country id, state, state id, arcade name
			else return "<b><a href=loc.php?co_id=$country_id&all_games=1>$arc_full Locations</a></b><br><a href=loc.php?co_id=$country_id&all_games=1>$country_name</a> :: <a href=loc.php?st_id=$st_id&all_games=1>$st_name</a> :: $arcade_name";
		}
		else
		{
			//tier1: arcade game, arcade id, country
			if(!$st_name) return "<b>$arc_full Locations</b><br>$country_name";
			//tier2: arcade game, arcade id, country, country id, state
			else if(!$st_id) return "<b><a href=loc.php?co_id=$country_id&game_id=$arc_id>$arc_full Locations</a></b><br><a href=loc.php?co_id=$country_id&game_id=$arc_id>$country_name</a> :: $st_name";
			//tier3: arcade game, arcade id, country, country id, state, state id, arcade name
			else return "<b><a href=loc.php?co_id=$country_id&game_id=$arc_id>$arc_full Locations</a></b><br><a href=loc.php?co_id=$country_id&game_id=$arc_id>$country_name</a> :: <a href=loc.php?st_id=$st_id&game_id=$arc_id>$st_name</a> :: $arcade_name";
		}
	}
	function loc_say($width1, $width2, $left, $right)
	{
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top width=$width1 align=left><strong>$left:</strong></td>\n";
		echo "<td vAlign=top width=$width2>$right</td></tr>\n";
	}
	//array with v-names, array w v-ids, selected name, selected id, price, songs, memory card, autofail, location, coms, suffix
	function machine_out($all_machines, $all_ids, $mach_name, $mach_id, $price, $songs, $mem, $fail, $location, $comments, $end)
	{
		echo "<table border=0 width=450 bgcolor=#B1BCD3 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\"><td>Machine: <select size=1 name=m_mach_id[$end]><option value=$mach_id>$mach_name</option>";

		for($k=0; $k<count($all_machines); $k++) echo "<option value=$all_ids[$k]>$all_machines[$k]</option>";

				//price, songs, delete
		echo "</select></td><td>Price <input type=text name=m_price[$end] size=6 maxlength=25 value=$price></td><td>Songs <input type=text name=m_songs[$end] size=3 maxlength=3 value=$songs></td><td valign=center>Delete<input type=checkbox name=m_delete[$end] value=ON></td></tr>";


		echo "<tr><td rowspan=2 valign=top>Comments<br><textarea rows=3 name=m_com[$end] cols=30>$comments</textarea></td><td colspan=2>Memory Card Slot?<input type=checkbox name=m_memory[$end] value=ON ";
		if($mem) echo "checked";
		echo "></td><td>AutoFail?<input type=checkbox name=m_autofail[$end] value=ON ";
		if($fail) echo "checked";
		echo "></td></tr><tr><td colspan=3>Location: <input type=text name=m_location[$end] size=25 maxlength=100 value=\"$location\"></td></tr></table>";
	}



	//Title output here
	echo "<table border=0 width=495><tr><td align=left>";
	//Output the change game form
	echo "<form name=dropMenu method=post>
	<select name=selectThis size=1 onChange=\"goThere(this.form);\">
	<option selected value=\"\">Select a Game";
	$query="SELECT arcade_games.full, arcade_games.v_id, count(distinct loc3.l_id) as locs FROM arcade_games, versions, loc3, loc4 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id GROUP by arcade_games.full";
	$result = mysql_db_query($database, $query);
	echo "<option value=\"http://www.ddrei.com/loc.php?co_id=$co_id&all_games=1\">--- All Games ----";
	while ($s=@mysql_fetch_array($result))
	{
		extract($s);
		//echo "<option value=\"http://www.ddrei.com/loc.php?co_id=$co_id&game_id=$v_id\"><b>$full ($locs)</b>\n";
		echo "<option value=\"http://www.ddrei.com/loc.php?co_id=1&game_id=$v_id\"><b>$full ($locs)</b>\n";
		if($v_id==$game_id)
		{
			$query="SELECT versions.name, versions.id, count(distinct loc4.m_id) as locs
			FROM arcade_games, versions, loc4
			WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND versions.a_id=$game_id GROUP BY versions.name";
			$result2 = mysql_db_query($database, $query);
			while ($t=@mysql_fetch_array($result2))
			{
				extract($t);
				echo "<option value=\"http://www.ddrei.com/loc.php?co_id=1&mix_id=$id&game_id=$game_id\">&nbsp- $name ($locs)\n";
			}
		}
	}
	echo "</select></form></td><td align=right>";



	//output the option to change countries
	echo "<form name=dropMenu method=post>
	<select name=selectThis size=1 onChange=\"goThere(this.form);\">
	<option selected value=\"\">Then select a Country";

	//get the countries for the current game and the number of locations at each
	if(!$allsongs)	$query="SELECT loc1.loc_id, loc1.loc_country, count(distinct loc3.l_id) as locs FROM loc2, loc3, arcade_games, versions, loc4, loc1

	WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND arcade_games.v_id=$game_id

	GROUP BY loc1.loc_country";
	else $query="SELECT loc1.loc_id, loc1.loc_country, count(distinct loc3.l_id) as locs FROM loc2, loc3, arcade_games, versions, loc4, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id GROUP BY loc1.loc_country";
	$result = mysql_db_query($database, $query);
	while ($r=@mysql_fetch_array($result))
	{
		extract($r);
		if($all_games) echo "<option value=\"http://www.ddrei.com/loc.php?co_id=$loc_id&all_games=1\">$loc_country ($locs)\n";
		else echo "<option value=\"http://www.ddrei.com/loc.php?co_id=$loc_id&game_id=$game_id\">$loc_country ($locs)\n";
	}
	echo "</select></form></td></tr></table>";


	// process adding a location, or rating an arcade
	if(isset($submit))
	{
		//check to see if they sent the bare minimum of data
		if(!$l_name || !$lo_id)
		{
			echo "In order to submit a location you must provide at LEAST the name of the arcade as well as the state/province";
		}
		else
		{
			if($l_smoke) $l_smoke=1;
			if($l_food) $l_food=1;
			if($l_drink) $l_drink=1;
			if($l_atm) $l_atm=1;
			//insert a new row into loc3 with the arcade info
			if(!$mod)
			{
				$query="INSERT INTO loc3 (lo_id, l_new, l_name, l_city, l_address, l_zip, l_phone, l_hours, l_smoke, l_food, l_drink, l_atm, l_web, l_com) VALUES ('$lo_id', 1, '$l_name', '$l_city', '$l_address', '$l_zip', '$l_phone', '$l_hours', '$l_smoke', '$l_food', '$l_drink', '$l_atm', '$l_web', '$l_com')";
			}
			//if modifying a location
			else
			{
				$query="INSERT INTO loc3 (lo_id, l_parent, l_name, l_city, l_address, l_zip, l_phone, l_hours, l_smoke, l_food, l_drink, l_atm, l_web, l_com) VALUES ('$lo_id', '$mod', '$l_name', '$l_city', '$l_address', '$l_zip', '$l_phone', '$l_hours', '$l_smoke', '$l_food', '$l_drink', '$l_atm', '$l_web', '$l_com')";
			}

//			echo "q1: $query <br>";
			$result=mysql_db_query($database, $query);

			//get the ID for the value just inserted
			$query="SELECT l_id FROM loc3 WHERE lo_id='$lo_id' AND l_name='$l_name' AND l_com='$l_com' AND l_address='$l_address' AND l_city='$l_city' AND l_phone='$l_phone' AND l_hours='$l_hours' AND l_web='$l_web' order by l_id desc";

			$result=mysql_db_query($database, $query);
			if($r=@mysql_fetch_array($result))
				$l_id=$r["l_id"];
	//		echo "q2: $query <br>";
			//cycle through the machines inserting them
			$total_machines=count($m_mach_id);
			for($i=0; $i<100; $i++)
			{
				if($m_mach_id[$i] && !$m_delete[$i]) //there is a machine at this array location
				{
					if($m_autofail[$i]) $m_autofail[$i]=1;
					if($m_memory[$i]) $m_memory[$i]=1;
					//insert the machine info into the db
					$query="INSERT INTO loc4 (l_id, v_id, m_price, m_songs, m_location, m_autofail, m_memory, m_com) VALUES ('$l_id', $m_mach_id[$i], '$m_price[$i]', '$m_songs[$i]', '$m_location[$i]', '$m_autofail[$i]', '$m_memory[$i]', '$m_com[$i]')";
	//				echo "q $i: $query <br><br>";
					$result=mysql_db_query($database, $query);
				}
			}
			//redirect the user to their state with allgames
			$st_id=$lo_id; $all_games=1; $working=0; $mod=0; $new=0;
		}
	}



	//if moving a locations from new to verified
	if($new_yes)
	{
		$q="update loc3 set l_new=0 where l_id=$l_id";
		$result=mysql_db_query($database, $q);

		//get the state to redirect to
		$q="select lo_id from loc3 where l_id=$l_id";
		$result=mysql_db_query($database, $q);
		if($r=mysql_fetch_array($result)) $st_id=$r["lo_id"];
	}
	//if deleting a location
	else if($new_no)
	{
		//before checking to be sure they want to delete
		if(!$new_no2)
		{
			echo "<b>Are you sure you want to delete this location?</b>
				<form action=loc.php method=post><input type=hidden name=l_id value=$l_id><input type=hidden name=new_no value=1><input type=submit name=new_no2 value=\"Yes, Delete it\"></form>";
			$arc_id=$l_id;
		}
		//after checking -- actual deleting
		else
		{
			$q="select lo_id from loc3 where l_id=$l_id";
			$result=mysql_db_query($database, $q);
			if($r=mysql_fetch_array($result)) $st_id=$r["lo_id"];

			$q1="delete from loc3 where l_id=$l_id";
			$q2="delete from loc4 where l_id=$l_id";
			$result=mysql_db_query($database, $q1);
			$result=mysql_db_query($database, $q2);
		}
	}

	if($mod_yes)
	{
		$q="select lo_id from loc3 where l_id=$l_id";
		$result=mysql_db_query($database, $q);
		$r=mysql_fetch_array($result);
		$st_id=$r["lo_id"];
		$all_games=1;

		$q1="delete from loc4 where l_id=$old_id";
		$q2="update loc4 set l_id=$old_id where l_id=$l_id";
		$q3="select * from loc3 where l_id=$l_id";
		$q4="delete from loc3 where l_id=$l_id";

		$result=mysql_db_query($database, $q1);
		$result=mysql_db_query($database, $q2);
		$result=mysql_db_query($database, $q3);
		$result=mysql_db_query($database, $q4);
		if($r=@mysql_fetch_array($result))
		{
			extract($r);
			$q5="update loc3 set l_name='$l_name' , l_city='$l_city', l_address='$l_address', l_zip='$l_zip', l_phone='$l_phone', l_hours='$l_hours', l_smoke='$l_smoke', l_food='$l_food', l_drink='$l_drink', l_atm='$l_atm', l_web='$l_web', l_com='$l_com' where l_id=$old_id";
//			$result=mysql_db_query($database, $q5);
		}
//		echo "1: $q1 <br>2: $q2<br>3: $q3<br>4: $q4<br>5: $q5";

	}
	else if($mod_no)
	{
		echo "<b>Are you sure you want to delete this location?</b>
				<form action=loc.php method=post><input type=hidden name=l_id value=$l_id><input type=hidden name=new_no value=1><input type=submit name=new_no2 value=\"Yes, Delete it\"></form>";
			$arc_id=$l_id;
	}


	// Add a new location
	if($new || $mod || $working)
	{
		echo "<FORM action=loc.php method=post><input type=hidden name=working value=1>";
		//1st page modify a location
		if($mod && !$working)
		{
			//get all the data that can be changed except machine info
			$query="SELECT loc1.loc_id, loc1.loc_country, loc2.lo_p, loc2.lo_id, loc3.* FROM loc2, loc3, arcade_games, versions, loc4, loc1 WHERE arcade_games.v_id=versions.a_id AND loc2.lo_id=loc3.lo_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.l_id=$mod AND loc2.loc_id=loc1.loc_id GROUP BY loc2.lo_p";
			$result = mysql_db_query($database, $query);
			if ($r=@mysql_fetch_array($result))
			{
				extract($r);
				echo "<b>Modify a Location :: $l_name</b><br><br>";
				title("Modify a Location");
				echo "<input type=hidden name=mod value=$mod>";
			}
		}
		//1st page add a location
		else if($new || $working)
		{
			echo "<b>Add a Location</b><br><br>";
			title("Add a Location");
		}

		if($lo_id) { $old_lo_id=$lo_id; }
		if($loc_id && !$co_id) { $co_id=$loc_id; }

		//2nd+ page add/modify.  all data already grabbed
		else if($working)
		$width1=150; $width2=320;
		//output add a location for
		echo "<tr bgcolor=#E1E7F3><td><table border=0 width=470><tr><td colspan=2 align=center><font face=arial size=2><b>First, select the country for the location if not selected.</b><br>Next provide any information you can about the arcade and it's machines.<br>If you are deleting a location, add <b>[REMOVED]</b> to the title of the arcade.</td></tr>";

		// get all the countries
		$query="select loc_id, loc_country from loc1 order by loc_country";
		$co_res=mysql_db_query($database, $query);

		//get all the states.  if no country, assume assume country=1
		$query="select loc2.lo_id, loc2.lo_p from loc1, loc2 where loc1.loc_id=$co_id and loc2.loc_id=loc1.loc_id order by loc2.lo_p";
		$st_res=mysql_db_query($database, $query);
		while($r=mysql_fetch_array($st_res))
		{
			extract($r);
			//output the country
			if(!$flag)
			{
				echo "<tr><td width=$width1><font face=arial size=2><b>Country:</td><td width=$width2>
				<select name=selectThis size=1 onChange=\"goThere(this.form);\">
				<option value=\"\"> -- Select a Country --\n";
				while($s=mysql_fetch_array($co_res))
				{
					extract($s);
					echo "<option value=\"http://www.ddrei.com/loc.php?co_id=$loc_id&new=1\" ";
					if($co_id==$loc_id) echo "selected";
					echo ">$loc_country \n";
				}
				echo"</select></td></tr>";

				//start the state listing
				echo "<tr><td width=$width1><font face=arial size=2><b>State:</td><td width=$width2>";
				echo "<select name=lo_id size=1>";
				$flag=1;
			}
			echo "<option value=\"$lo_id\" ";
			if($old_lo_id==$lo_id) echo "selected";
			echo ">$lo_p\n";
		}


		echo "</select></td></tr>";

		echo "<tr><td width=$width1><font face=arial size=2><b>Arcade Name:</td><td width=$width2><input type=text name=\"l_name\" size=50 maxlength=100 value=\"$l_name\"></td></tr>";

		echo "<tr><td width=$width1><font face=arial size=2><b>City:</td><td width=$width2><input type=text name=\"l_city\" size=50 maxlength=100 value=\"$l_city\"></td></tr>";
		//echo the country and state from modify/new w/auto update
		echo "<tr><td width=$width1><font face=arial size=2><b>Address:</td><td width=$width2><textarea rows=2 name=l_address cols=40>$l_address</textarea></td></tr>";
		echo "<tr><td width=$width1><font face=arial size=2><b>Zip Code:</td><td width=$width2><input type=text name=l_zip size=10 maxlength=12 value=\"$l_zip\"></td></tr>";
		echo "<tr><td width=$width1><font face=arial size=2><b>Phone Number:</td><td width=$width2><input type=text name=l_phone size=25 maxlength=50 value=\"$l_phone\"></td></tr>";
		echo "<tr><td width=$width1><font face=arial size=2><b>Hours:</td><td width=$width2><textarea rows=2 name=l_hours cols=40>$l_hours</textarea></td></tr>";
		echo "<tr><td width=$width1><font face=arial size=2><b>Website:</td><td width=$width2><input type=text name=l_web size=50 maxlength=50 value=\"$l_web\"></td></tr>";

		echo "<tr><td>&nbsp</td><td><font face=arial size=2><b><input type=checkbox name=l_smoke value=ON ";
		if($l_smoke) echo "checked";
		echo ">Smoking Allowed?&nbsp&nbsp&nbsp&nbsp<input type=checkbox name=l_food value=ON ";
		if($l_food) echo "checked";
		echo ">Food Avaliable?<br><input type=checkbox name=l_drink value=ON ";
		if($l_drink) echo "checked";
		echo ">Drinks Avaliable?&nbsp&nbsp&nbsp&nbsp&nbsp<input type=checkbox name=l_atm value=ON ";
		if($l_atm) echo "checked";
		echo ">ATM Avaliable?</td></tr>";

		echo "<tr><td width=$width1><font face=arial size=2><b>Comments:</td><td width=$width2><textarea rows=4 name=l_com cols=40>$l_com</textarea></td></tr><tr><td><td><br><br></td><tr>";

		//generate a list of all games avaliable
		$query="SELECT id, name FROM versions WHERE sys='Arcade'";
		$result= mysql_db_query($database, $query);
		$i=0;
		while($r=@mysql_fetch_array($result))
		{
			$all_machs[$i]=$r["name"];
			$all_ids[$i]=$r["id"];
			$i++;
		}
		//get the machines and output them
		if($working)
		{
			echo "<tr><td colspan=2><p align=center><br>";
			for($m=1; $m<=$machines; $m++)
			{
				if(!$m_delete[$m] && $m_mach_id[$m])
				{
					$query="SELECT name FROM versions WHERE id=".$m_mach_id[$m];
					$result=mysql_db_query($database, $query);
					if($r=@mysql_fetch_array($result)) $mach_name=$r["name"];

					machine_out($all_machs, $all_ids, $mach_name, $m_mach_id[$m], $m_price[$m], $m_songs[$m], $m_memory[$m], $m_autofail[$m], $m_location[$m], $m_com[$m], $m);
					echo "<br>";
				}
			}
			$machines++;
			machine_out($all_machs, $all_ids, "Select a Game", "", "", "", "", "", "", "", $machines);
			echo "<br><input type=hidden name=machines value=$machines><input type=hidden name=mod value=$mod><br><input type=submit name=working value=\"Add another Machine or Update\"></p>";
		}
		else if($new && !$mod)
		{
			echo "<tr><td colspan=2><p align=center><br>";
			machine_out($all_machs, $all_ids, "Select a Game", "", "", "", "", "", "", "", 1);
			echo "<input type=hidden name=machines value=1><br><input type=submit name=working value=\"Add another Machine\"><br>";
		}
			//array with v-names, array w v-ids, selected name, selected id, price, songs, memory card, autofail, location, coms, suffix
		else if($mod)
		{
			$query="SELECT loc4.*, versions.id, versions.name, arcade_games.v_id FROM loc4, versions, arcade_games, loc3 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=$mod AND loc4.l_id=loc3.l_id order by versions.name";
			$result = mysql_db_query($database, $query);
			echo "<tr><td colspan=2><p align=center>";
			$mach=0;
			while($r=@mysql_fetch_array($result))
			{
				$mach++;
				extract($r);
				//echo the choices of location
				machine_out($all_machs, $all_ids, $name, $id, $m_price, $m_songs, $m_memory, $m_autofail, $m_location, $m_com, $mach);
				echo "<br>";
			}
			$mach++;
			machine_out($all_machs, $all_ids, "Select a Game", "", "", "", "", "", "", "", $mach);
			echo "<br><input type=hidden name=machines value=$mach><br><input type=submit name=working value=\"Add another Machine\"><br><input type=submit name=working value=\"Update\"></p>";
		}

		echo "<br><p align=center>If all the information above is correct then submit it here.<br><input type=submit name=submit value=Submit></p></td></tr></table></td></tr></table>";

	}
	// FAQ about the locations section (static page)
	else if($help)
	{
		title("Locations FAQ");
		echo "<tr bgcolor=#E1E7F3><td><table border=0 width=470><tr><td>";

		echo "<b>How do I find a location near me?</b><br>
				First, select what type of game you are looking for, or simply \"All Games\" to get a full list of the arcades.  Next, select a country for which you are looking for locations from.  Only countries with matching locations are displayed.<br><br>
				<b>How do I add a location not listed?</b><br>
				At the bottom of the main page as well as the listings by state/province there is a link at the bottom for submiting new locations<br><br>
				<b>Something is wrong with a location, how can i change it?</b><br>
				We are currently working on a script to allow editing, but for the next week or so only new locaitons are accepted.  If a location already exists, do not submit it again.<br><br>
				<b>I've found something wrong with the script...</b><br>
				ARGH!  Please email me: dyo@ddrei.com with as much information as you can about the error you can across including what you were doing, and what the result was.<br><br>
				<b>The country/state for my location isn't listed, where do i put it?</b><br>
				Please select 'Elsewhere' as the country and the state of the location.  Then in the comments about the location note what country and what state/province it should go under.";

		echo "</td></tr></table></td></tr></table>";
	}
	//Arcade information
	else if($arc_id)
	{

		//grab the general info on the site including country(id)/state(id)/game searching for (for path purposes)
		if($mix_id)
		{
			$query="SELECT name from versions where id=$mix_id";
			$result = mysql_db_query($database, $query);
			$s=@mysql_fetch_array($result);
			$final_name=$s["name"];
		}
		if($all_games) $query="SELECT loc1.loc_id, loc1.loc_country, loc2.lo_p, loc2.lo_id, arcade_games.full, loc3.* FROM loc2, loc3, arcade_games, versions, loc4, loc1 WHERE arcade_games.v_id=versions.a_id AND loc2.lo_id=loc3.lo_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.l_id=$arc_id AND loc2.loc_id=loc1.loc_id GROUP BY loc2.lo_p";
		else if($game_id) $query="SELECT loc1.loc_id, loc1.loc_country, loc2.lo_p, loc2.lo_id, arcade_games.full, loc3.* FROM loc2, loc3, arcade_games, versions, loc4, loc1 WHERE arcade_games.v_id=versions.a_id AND loc2.lo_id=loc3.lo_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND arcade_games.v_id=$game_id AND loc3.l_id=$arc_id AND loc2.loc_id=loc1.loc_id GROUP BY loc2.lo_p";
		$result = mysql_db_query($database, $query);
		if ($r=@mysql_fetch_array($result))
		{
			extract($r);
			$l_name=stripslashes($l_name);
			$old_l_parent=$l_parent;
			if($all_games) $path=get_path(0, "All Game", $game_id, $loc_country, $loc_id, $lo_p, $lo_id, $l_name, $mix_id, 1);
			else $path=get_path($final_name, $full, $game_id, $loc_country, $loc_id, $lo_p, $lo_id, $l_name, $mix_id, 0);
			echo "$path<br><br>";
			title($l_name);

			echo "<tr bgcolor=#E1E7F3><td vAlign=top width=100% align=left >";

			if($l_votes>0) $avg=$l_total/$l_votes;
			else $avg=0;

			echo "<p align=center><table border=0 width=95%><tr>";
			if($l_parent) echo "<td valign=top width=50%><b>New information</b><br><br>";
			else echo "<td>";
			echo "<b>".stripslashes($l_name)."</b><br>".stripslashes(str_replace("\n", "<br>", $l_address))."<br>".stripslashes($l_city).", $lo_p $l_zip<br>$l_phone<br><br>";
			if($l_hours) echo "<b>Hours:</b> ".stripslashes(str_replace("\n", "<br>", $l_hours))."<br><br>";

			echo "<b>Rating:</b> $avg in $l_votes votes<br><br><b>Comments:</b>".stripslashes(str_replace("\n", "<br>", $l_com));

			//show the old information if there is any for this location
			if($l_parent)
			{
				$q="SELECT * FROM loc3 where l_id=$l_parent";
				$result = mysql_db_query($database, $query);
				if ($r=@mysql_fetch_array($result))
				{
					extract($r);
					echo "</td><td vAlign=top width=50% align=left><b>Old information</b><br><br><b>".stripslashes($l_name)."</b><br>".stripslashes(str_replace("\n", "<br>", $l_address))."<br>".stripslashes($l_city).", $lo_p $l_zip<br>$l_phone<br><br>";
					if($l_hours) echo "<b>Hours:</b> ".stripslashes(str_replace("\n", "<br>", $l_hours))."<br><br>";

					echo "<b>Rating:</b> $avg in $l_votes votes<br><br><b>Comments:</b>".stripslashes(str_replace("\n", "<br>", $l_com));


					echo "</td></tr><tr><td colspan=2><br><br><font face=$font size=2><b>New Machines Information</b>";
				}
			}

			//get the games at this location and output them
			$query="SELECT loc4.*, versions.id, versions.name, arcade_games.v_id

FROM loc4, versions, arcade_games, loc3

WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=$arc_id AND loc4.l_id=loc3.l_id

order by versions.name";
			$result = mysql_db_query($database, $query);
			echo "<p align=center><table border=0 width=470 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" cellpadding=2 cellspacing=0><tr bgcolor=#93A2BF font=arial>
			<td><font face=$font size=2><b>Arcade Name</td><td><font face=$font size=2><b>Price</td><td><font face=$font size=2><b>Songs</td><td><font face=$font size=2><b>Memory</td><td><font face=$font size=2><b>Autofail</td></tr>";
			//
			while ($s=@mysql_fetch_array($result))
			{
				if(!$flags) { $flags=1; $color="#D1DBEE"; }
				else { $flags=0; $color="#B1BCD3"; }
				extract($s);
				if($m_autofail) $auto="On";
				else $auto="Off";
				if($m_memory) $mem="Yes";
				else $mem="No";
				echo "<tr bgcolor=$color><td width=200><font face=$font size=2><a href=info.php?game=$v_id&id=$id>$name</a></td><td><font face=$font size=2>$m_price</td><td><font face=$font size=2>$m_songs</td><td width=10><font face=$font size=2>$mem</td><td width=10><font face=$font size=2>$auto</td></tr>";
				if($m_location || $m_coms)
				{
					echo "<tr bgcolor=$color font=arial><td colspan=5><font face=$font size=2>";
					if($m_location) echo "<b>Location:</b> $m_location<br>";
					if($m_coms) echo "<b>Comments:</b> $m_coms<br>";
					echo "</td></tr>";
				}
				//<td colspan=3><font face=$font size=2><b>Comments:</b> $m_coms</td>
			}

			//get the parent locations and display them
			if($old_l_parent)
			{
				echo "</table><p align=left><font face=$font size=2><b>Old Machine Information</b>";
				$query="SELECT loc4.*, versions.id, versions.name, arcade_games.v_id FROM loc4, versions, arcade_games, loc3 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=$old_l_parent AND loc4.l_id=loc3.l_id order by versions.name";
				$result = mysql_db_query($database, $query);
				echo "<p align=center><table border=0 width=470 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" cellpadding=2 cellspacing=0><tr bgcolor=#93A2BF font=arial>
			<td><font face=$font size=2><b>Arcade Name</td><td><font face=$font size=2><b>Price</td><td><font face=$font size=2><b>Songs</td><td><font face=$font size=2><b>Memory</td><td><font face=$font size=2><b>Autofail</td></tr>";
			//
				while ($s=@mysql_fetch_array($result))
				{
					if(!$flags) { $flags=1; $color="#D1DBEE"; }
					else { $flags=0; $color="#B1BCD3"; }
					extract($s);
					if($m_autofail) $auto="On";
					else $auto="Off";
					if($m_memory) $mem="Yes";
					else $mem="No";
					echo "<tr bgcolor=$color><td width=200><font face=$font size=2><a href=info.php?game=$v_id&id=$id>$name</a></td><td><font face=$font size=2>$m_price</td><td><font face=$font size=2>$m_songs</td><td width=10><font face=$font size=2>$mem</td><td width=10><font face=$font size=2>$auto</td></tr>";
					if($m_location || $m_coms)
					{
						echo "<tr bgcolor=$color font=arial><td colspan=5><font face=$font size=2>";
						if($m_location) echo "<b>Location:</b> $m_location<br>";
						if($m_coms) echo "<b>Comments:</b> $m_coms<br>";
						echo "</td></tr>";
					}
				//<td colspan=3><font face=$font size=2><b>Comments:</b> $m_coms</td>
				}
			}
			echo "</table><br></td></tr>";
			//logged in as an admin
			if($ddrei_level>0)
			{
				//to move a new location to vverified
				if($l_new)
				{
					echo "<tr><td><font face=$font size=2><b>Mods: Is this location correct?</b><FORM action=loc.php method=post><input type=hidden name=l_id value=$arc_id><input type=submit name=new_yes value=\"Yes, move to verified\"> <input type=submit name=new_no value=\"No Delete it\"></form></td></tr>";
				}
				else if($old_l_parent)
				{
					echo "<tr><td colspan=2><br><font face=$font size=2><b>Mods: Is the new information correct?</b><FORM action=loc.php method=post><input type=hidden name=old_id value=$old_l_parent><input type=hidden name=l_id value=$arc_id><input type=submit name=mod_yes value=\"Yes, replace the old information\"> <input type=submit name=mod_no value=\"No Delete it\"></form></td></tr>";
				}
			}

			echo "</table></td></tr></table><p align=center>";
			if(!$l_parent && !$l_new) echo "<a href=loc.php?mod=$arc_id&new=1>Edit this location</a><br>";
			echo "Last modified on ".mysql_timestamp_to_human($l_date).".</p>";
		}
		else echo "No such location";
	}
	//State information
	else if($st_id)
	{
		if($mix_id)
		{
			$query="SELECT count(distinct loc3.l_id) as locn FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND arcade_games.v_id=$game_id AND loc2.lo_id=$st_id AND versions.id=$mix_id GROUP BY loc2.lo_p";
		}
		else if($all_games)
		{
			$query="SELECT count(distinct loc3.l_id) as locn FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id GROUP BY loc2.lo_p";
		}
		else
		{
			//get the total number of locations for this state
			$query="SELECT count(distinct loc3.l_id) as locn FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND arcade_games.v_id=$game_id AND loc2.lo_id=$st_id GROUP BY loc2.lo_p";
		}
		$result = mysql_db_query($database, $query);
		if($r=@mysql_fetch_array($result))
				$total_st=$r["locn"];

		// Get the arcades
		if($mix_id)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND arcade_games.v_id=$game_id AND versions.id=$mix_id AND loc3.l_new=0 AND loc3.l_parent=0 ORDER BY loc3.l_city, loc3.l_name";
		}
		else if($all_games)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_new=0  AND loc3.l_parent=0 ORDER BY loc3.l_city, loc3.l_name";
		}
		else
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_new=0 AND arcade_games.v_id=$game_id AND loc3.l_parent=0 ORDER BY loc3.l_city, loc3.l_name";
		}
		$result = mysql_db_query($database, $query);
		$flag=0;
		$r=@mysql_fetch_array($result);
		$total_rows=mysql_num_rows($result);
		$flags=1; $color="#E1E7F3";
		$height=20;
		//output the verified locations
		while (!$done && $total_rows)
		{
			if(!$flag)
			{
				extract($r);
				$l_name=stripslashes($l_name);
				if($mix_id) $path=get_path($name, $full, $game_id, $loc_country, $loc_id, $lo_p, 0, 0, $mix_id, 0);
				else if($all_games) $path=get_path(0, "All Game", $game_id, $loc_country, $loc_id, $lo_p, 0, 0, $mix_id, 1);
				else $path=get_path(0, $full, $game_id, $loc_country, $loc_id, $lo_p, 0, 0, 0, 0);
				echo "$path<br><br>";
				$lo_p=$lo_p." ($total_st)";
				title_st($lo_p);
				echo "<tr bgcolor=#B1BCD3 height=$height><td colspan=3><font face=$font size=2><b>Verified Locations</b></td></tr>";
			}
			$l_name=stripslashes($l_name);
			//output the states of the current country
			if($last_id==$l_id) echo ", $name";
			else
			{
				echo "<tr bgcolor=$color height=$height><td><font face=$font size=2><a href=loc.php?co_id=$co_id&arc_id=$l_id&game_id=$game_id&mix_id=$mix_id&all_games=$all_games>$l_name</a></td><td><font face=$font size=2>$l_city</td><td><font face=$font size=2>$name";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
			}
			$flag=1;
			$last_id=$l_id;
			if($r=@mysql_fetch_array($result)) { extract($r); }
			else $done=1;
			if($l_id!=$last_id) echo "</td></tr>";
		}


		//now the new locations
		if($mix_id)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND arcade_games.v_id=$game_id AND versions.id=$mix_id AND loc3.l_new=1 ORDER BY loc3.l_city, loc3.l_name";
		}
		else if($all_games)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_new=1 ORDER BY loc3.l_city, loc3.l_name";
		}
		else
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_new=1 AND arcade_games.v_id=$game_id ORDER BY loc3.l_city, loc3.l_name";
		}
		$result = mysql_db_query($database, $query);
		$flag=0; $done=0;
		if($s=@mysql_fetch_array($result)) extract($s);
		$old_rows=$total_rows;
		if(!$total_rows) { $total_rows=mysql_num_rows($result);  }
		$total_rowss=mysql_num_rows($result);

		//output the new locations
		$last_id=0;
		while (!$done && $total_rowss)
		{
			//first new location
			if(!$last_id)
			{
				if(!$old_rows) title_st($lo_p);
				echo "<tr bgcolor=$color><td colspan=3>&nbsp</td></tr>";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				echo "<tr bgcolor=$color height=$height><td colspan=3><font face=$font size=2><b>New Locations</b></td></tr>";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
			}

			//output the states of the current country
			if($last_id==$l_id) echo ", $name";
			else
			{
				echo "<tr bgcolor=$color height=$height><td><font face=$font size=2><a href=loc.php?co_id=$co_id&arc_id=$l_id&game_id=$game_id&mix_id=$mix_id&all_games=$all_games>$l_name</a></td><td><font face=$font size=2>$l_city</td><td><font face=$font size=2>$name";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
			}
			$flag=1;
			$last_id=$l_id;
			if($s=@mysql_fetch_array($result)) { extract($s); }
			else $done=1;
			if($l_id!=$last_id) echo "</td></tr>";
		}
//		if(!$total_rowss) echo "<tr bgcolor=$color><td colspan=3><font face=$font size=2>No New Locations</td></tr>";


		//echo the modified locations
		//now the new locations
		if($mix_id)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND arcade_games.v_id=$game_id AND versions.id=$mix_id AND loc3.l_parent>0 ORDER BY loc3.l_city, loc3.l_name";
		}
		else if($all_games)
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_parent>0 ORDER BY loc3.l_city, loc3.l_name";
		}
		else
		{
			$query="SELECT versions.name, loc3.l_name, loc3.l_city, loc3.l_id, loc2.lo_p, loc2.lo_id, loc1.loc_id, loc1.loc_country, arcade_games.full, arcade_games.v_id FROM arcade_games, versions, loc4, loc3, loc2, loc1 WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc2.lo_id=$st_id AND loc3.l_parent>0 AND arcade_games.v_id=$game_id ORDER BY loc3.l_city, loc3.l_name";
		}
		$result = mysql_db_query($database, $query);
		if($s=@mysql_fetch_array($result)) extract($s);
		$mod_rows=mysql_num_rows($result);
		if(!$old_rows && !$total_rowss && $mod_rows) title_st($lo_p);
		$flag=0; $done=0;
		$old_rows=$total_rows;
		if(!$total_rows) { $total_rows=mysql_num_rows($result);  }

		$last_id=0;
		//output the new locations
		while (!$done && $mod_rows)
		{
			if(!$last_id)
			{
				echo "<tr bgcolor=$color><td colspan=3>&nbsp</td></tr>";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				echo "<tr bgcolor=$color height=$height><td colspan=3><font face=$font size=2><b>Modified Locations</b></td></tr>";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
			}

			//output the states of the current country
			if($last_id==$l_id) echo ", $name";
			else
			{
				echo "<tr bgcolor=$color height=$height><td><font face=$font size=2><a href=loc.php?co_id=$co_id&arc_id=$l_id&game_id=$game_id&mix_id=$mix_id&all_games=$all_games>$l_name</a></td><td><font face=$font size=2>$l_city</td><td><font face=$font size=2>$name";
				if(!$flags) { $flags=1; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
			}
			$flag=1;
			$last_id=$l_id;
			if($s=@mysql_fetch_array($result)) { extract($s); }
			else $done=1;
			if($l_id!=$last_id) echo "</td></tr>";
		}
	//	if(!$mod_rows) echo "<tr bgcolor=$color><td colspan=3><font face=$font size=2>No Modified Locations</td></tr>";



		if($total_rows) echo "</table><br><p align=center>Found a location not on this page? Submit it <a href=loc.php?new=1&co_id=$co_id&lo_id=$st_id>here</a>.</p>";
		else echo "No Such Location";
	}
	//Country information
	else if($co_id)
	{
		//if sorting by a particular mix
		if($mix_id)
		{
			$query="SELECT loc1.loc_id, arcade_games.full, loc1.loc_country, loc2.lo_p, loc2.lo_id, count(distinct loc3.l_id) as locn, versions.name FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND arcade_games.v_id=$game_id AND loc1.loc_id=$co_id AND versions.id=$mix_id GROUP BY loc2.lo_p";
		}
		else if($all_games)
		{
			$query="SELECT loc1.loc_id, arcade_games.full, loc1.loc_country, loc2.lo_p, loc2.lo_id, count(distinct loc3.l_id) as locn, versions.name FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND loc1.loc_id=$co_id GROUP BY loc2.lo_p";
		}
		//if sorting by a particular type of game
		else
		{
			$query="SELECT loc1.loc_id, arcade_games.full, loc1.loc_country, loc2.lo_p, loc2.lo_id, count(distinct loc3.l_id) as locn FROM loc1, loc2, loc3, loc4, arcade_games, versions WHERE arcade_games.v_id=versions.a_id AND versions.id=loc4.v_id AND loc4.l_id=loc3.l_id AND loc3.lo_id=loc2.lo_id AND loc2.loc_id=loc1.loc_id AND arcade_games.v_id=$game_id AND loc1.loc_id=$co_id GROUP BY loc2.lo_p";
		}
		$result = mysql_db_query($database, $query);
		$result2 = $result;
		$rows=mysql_num_rows($result)/2;
		$total_co=mysql_num_rows($result);
		for($i=0; $i<$total_co; $i++)
		{
			$n=@mysql_fetch_array($result2);
			$total_arc+=$n["locn"];
		}
		$flag=0;
		$result = mysql_db_query($database, $query);
		while ($r=@mysql_fetch_array($result))
		{
			extract($r);
			if(!$flag)
			{
				if($mix_id) $path=get_path($name, $full, $game_id, $loc_country, $loc_id, 0, 0, 0, $mix_id, 0);
				else if($all_games) $path=get_path(0, "All Game", $game_id, $loc_country, $loc_id, 0, 0, 0, $mix_id, 1);
				else $path=get_path(0, $full, $game_id, $loc_country, 0, 0, 0, 0, 0, 0);
				echo "$path<br><br>";
				$loc_country=$loc_country." ($total_arc)";
				title($loc_country);
				echo "<tr bgcolor=#E1E7F3><td vAlign=top align=center><table border=0 width=470><tr><td><font face=$font size=2><ul>";
			}

			if($flag>=$rows) { echo "</ul></td><td><font face=$font size=2><ul>"; $rows=99999; }
			//output the states of the current country
			echo "<li><a href=loc.php?co_id=$co_id&st_id=$lo_id&game_id=$game_id&mix_id=$mix_id&all_games=$all_games>$lo_p</a> ($locn)</li>";
			$flag++;
		}
		if($total_co) echo "</ul></td></tr></table></td></tr></table><br><p align=center>Found a location not on this page? Submit it <a href=loc.php?new=1&co_id=$co_id>here</a>.</p>";
		else echo "No Matching Locations";
	}
	echo "<p align=center>Do you need more help with how the locations section works? <a href=loc.php?help=1>Click here</a></p>";

	include("footer.php");

?>