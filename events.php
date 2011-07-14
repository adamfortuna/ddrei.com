<?php

	include("header.php");
	function title($title)
	{
		echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>
		<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=495 colspan=3><font face=arial size=3><strong>$title</strong></font></td></tr>";
	}

	$today = getdate();
	$month = $today['mon'];
	$mday = $today['mday'];
	$year = $today['year'];
	if($month<10) { $month = "0".$month; }
	if($mday<10) { $mday = "0".$mday; }
	$curdate="$year"."$month"."$mday";
	$currentdate="$month".".$mday".".$year";

	$font="<font face=arial size=2>";

	//if submitting a new event
	if(isset($submit))
	{
		if(!$e_name || !$e_day || !$e_st) echo "<p align=center>$font You need to at least put the name of the event, the date and the state.<br>Please press back and correct the information.</p>";
		else //the information is correct
		{
			if($e_prereg=='preyes') $e_prereg=1;
			//insert it into the database
			$query="insert into events1 (e_name, e_page, e_em, e_author, e_day, e_arcade, e_arcade_link, e_city, e_st, e_prereg, e_topic, e_com, e_type) values ('$e_name', '$e_page', '$e_em', '$e_author', '$e_day', '$e_arcade', '$e_arcade_link', '$e_city', '$e_st', '$e_prereg', '$e_topic', '$e_com', '$e_type')";
		   	$result = @mysql_db_query($database, $query);
		   	echo "<strong><font face=\"Arial\" color=#000080 size=2><a href=events.php>Events</a> :: Add an Event<br><br></font></strong>";
		   	echo "<p align=center>Thank you!  Your input will be proccessed and added shortly.</p>";
		}
	}
	//if adding an event
	else if($new)
	{
		echo "<strong><font face=\"Arial\" color=#000080 size=2><a href=events.php>Events</a> :: Add an Event<br><br></font></strong>";
		title("Add an event");
  		$width=400;
  	?>
  		<tr bgcolor=#E1E7F3>
    <td colspan=2><p align="left">

    The new events system works a little differently than before.  There is now an option to enable people to sign up.  This will stop when the tournamnts date has passed, but until then will allow anyone to put their name on the sign up list to get a general idea of the turnout.

    </p>
     <form method="POST" action="events.php"> </td></tr>
       <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Event Name:</strong></td>
          <td><input type="text" name="e_name" size="40" maxlength="100" ></td>
        </tr><tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Event Web Page:</strong></td>
          <td><input type="text" name="e_page" size="40" maxlength="200" ></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Arcade/Location:</strong></td>
          <td><input type="text" name="e_arcade" size="30" maxlength="100"></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>City:</strong></td>
          <td><input type="text" name="e_city" size="30" maxlength="100"></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>State:</strong></td>
          <td><select size="1" name="e_st">
	  <?php echo "<option value=$st>$st</option>"; ?>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="Canada">Canada</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Deleware</option>
          <option value="DC">Washington DC</option>
          <option calue="Europe">Europe</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hamshire</option>
          <option value="NJ">New Jersy</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklaholma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="PR">Puerto Rico</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconson</option>
          <option value="WY">Wyoming</option>
          </select></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Date:</strong><br><font size=1><i>ex: 06.15.2002</i></font></td>
          <td><input type="text" name="e_day" size="20" maxlength="50"></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Arcade Link:</strong></td>
          <td><input type="text" name="e_arcade_link" size="40" maxlength="150" ><br><small><i>Give a link to the location in DDRei's location section.  If it is not held at an arcade leave this blank.</td>
        </tr>
    	<tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Tournament Type:</strong></td>
          <td><input type="text" name="e_type" size="40" maxlength="100"><br><small><i>Specify what type of tournament. ex. Freestyle, Technical.</td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Allow Pre-registration?:</strong><br><br></td>
          <td><input type="radio" value="preyes" name="e_prereg" checked>Yes<input type="radio" value="preno" name="e_prereg">No</td>
        </tr>

        <!--
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Make Registration list public?:</strong><br><font size=1><i>If allowed</i><br><br></font></td>
          <td><input type="radio" value="makeyes" name="makereg" checked>Yes<input type="radio" value="makeno" name="makereg">No</td>
        </tr>
        -->


        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Comments:</strong><br><font size=1><i>FS/Tech rules</i></font></td>
          <td><textarea rows="10" name="e_com" cols="35"></textarea><br><small><i>Comment suggestions: Mix the tournament will be on, time of pre-registration and the tournament, entrance fee, freestyle and technical tournament rules and anything else.</i></small></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Contact Name:</strong></td>
          <td><input type="text" name="e_author" size="30" maxlength="50"></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td width=<?php echo "$width"; ?>><strong>Contact Email:</strong></td>
          <td><input type="text" name="e_em" size="30" maxlength="50"></td>
        </tr>
        <tr bgcolor=#E1E7F3>
          <td colspan=2><br><br><input type="submit" name=submit value="SUBMIT"> <input type="RESET"></td>
        </tr>
      </form>
    </table>

   <?php
   	}
   	//looking at a specific event
   	else if($id)
   	{
	   	if(isset($add_player) && $ev_name) //adds the name to the pre-reg section
	   	{
		   	$query="insert into events2 (e_id, ev_name, ev_enter, ev_com) values ('$id', '$ev_name', '$ev_enter', '$ev_com')";
		   	$result = mysql_db_query($database, $query);
   		}
   		else if(isset($add_results) && $eve_results)
   		{
	   		$query="insert into events3 (e_id, eve_name, eve_email, eve_results) values ('$id', '$eve_name', '$eve_email', '$eve_results')";
		   	$result = mysql_db_query($database, $query);
		   	echo "<p align=center>$font Thank you! Your input will be proccessed and added shortly.</p>";
	   	}

	   	$query = "SELECT * FROM events1 WHERE e_id=$id";
		$result = mysql_db_query($database, $query);
		if ($r = @mysql_fetch_array($result))
		{
			extract($r);
			echo "<strong><font face=\"Arial\" color=#000080 size=2><a href=events.php>Events</a> :: $e_name<br><br></font></strong>";
			title($e_name);
			echo "<tr bgcolor=#E1E7F3><td>$font<p align=center><table border=0 width=470><tr><td>$font";
			// output all the info about the current event

			if($e_name)
			{
				echo "<tr><td vAlign=top width=150>$font<strong>Name of Event:</strong></td><td>$font <b>";
				if($e_page) echo "<a href=$e_page target=_blank>";
				echo $e_name;
				if($e_page) echo "</a>";
				echo "</b></font></td></tr>\n";
			}
			if($e_day) echo "<tr><td vAlign=top>$font<strong>Date:</strong></td><td>$font $e_day</font></td></tr>\n";
			if($e_arcade_link)
			{
				//go get the address for the location
				$query = "SELECT l_city, l_address, l_zip, l_phone, l_name FROM loc3 WHERE l_id=$e_arcade_link";
				$result = mysql_db_query($database, $query);
				if ($r = @mysql_fetch_array($result))
				{
					extract($r);
					echo "<tr><td vAlign=top>$font<strong>Location:</strong></td><td>$font<b><a href=http://www.ddrei.com/loc.php?arc_id=$e_arcade_link>".stripslashes($l_name)."</a></b><br>".stripslashes(str_replace("\n", "<br>", $l_address))."<br>".stripslashes($l_city).", $e_st $l_zip<br>$l_phone<br></td></tr>\n";
				}
			}
			else if($e_arcade)
			{
				echo "<tr><td vAlign=top>$font<strong>Location:</strong></td><td>$font $e_arcade<br>$e_city, $e_st</font></td></tr>\n";
			}

			if($e_type) echo "<tr><td vAlign=top>$font<strong>Tournament Type:</strong></td><td>$font ".stripslashes(str_replace("\n", "<br>", $e_type))."<br></font></td></tr>\n";
			echo "</table><table border=0 width=470>";
			if($e_com) echo "<tr><td vAlign=top>$font<strong>Comments:</strong></td><td>&nbsp</td></tr><tr><td colspan=2>$font".stripslashes(str_replace("\n", "<br>", $e_com))."</font><br><br></td></tr>\n";
			echo "</table><table border=0 width=470>";
			if($e_author)
			{
				echo "<tr><td vAlign=top>$font<strong>Contact:</strong></td><td>$font\n";
				if($e_em) echo "<a href=\"mailto: $e_em \">";
				if($e_author) echo "$e_author";
				if($e_em) echo "</a>";
				echo "</font><br><br></td></tr>\n";
			}
			if($e_topic) echo "<tr><td colspan=2 align=center><a href=$e_topic>Talk about this tournament in the Forum</a></td></tr>";

			echo "</td></tr></table></p></tr></td></table><br><br>";
			//determine if it's in the future or the past
			if($e_date>$curdate) //future
			{
				//see if it allows preregistration
				if($e_prereg) //allowed
				{
					echo "<form method=post action=events.php><input type=hidden name=id value=$id>";
					title("Pre-registration List");
					echo "<tr bgcolor=#E1E7F3><td><p align=center><table border=0 width=470><tr><td>$font <b>Player Name</b></td><td>$font <b>Entering</b></td><td>$font <b>Hometown, state</b></td></tr>";
					//see if anyone has pre-registered, and get all the names
					$query = "SELECT * FROM events2 WHERE e_id=$id order by ev_name";
					$result = mysql_db_query($database, $query);
					if(mysql_num_rows($result))
					{
						while ($s = @mysql_fetch_array($result))
						{
							extract($s);
							echo "<tr><td>$font ";
							if($ev_email) echo "<a href=mailto:$ev_email>";
							echo "$ev_name";
							if($ev_email) echo "</a>";
							echo "</td><td>$font $ev_enter</td><td>$font $ev_com</td></tr>";
						}
					}
					echo "<tr><td><input type=text name=ev_name size=15 maxlength=50></td><td><input type=text name=ev_enter size=15 maxlength=50></td><td><input type=text name=ev_com size=25 maxlength=50>&nbsp&nbsp<input type=submit name=add_player value=\"Add it!\"></td></tr>";
					echo "<tr><td colspan=3><font face=arial size=1><i>DDRei's Pre-registration is completely unofficial and is in no way connected to the actual event.</i></td></tr></table></td></tr></table>";
				}
			}
			else //past event
			{
				if($e_results)
				{
					title("Results");
					echo "<tr bgcolor=#E1E7F3><td><p align=center><table border=0 width=470>";
				echo "<tr><td>$font".stripslashes(str_replace("\n", "<br>", $e_results))."<br><br><font face=arial size=1></td></tr></table></p></td></tr></table>";
				}

				//add results form
				echo "<form method=post action=events.php><input type=hidden name=id value=$id>";
				title("Add Results");
				echo "<tr bgcolor=#E1E7F3><td><p align=center><table border=0 width=470>";
				echo "<tr><td>$font<b>Name:</b></td><td><input type=text name=eve_name size=30 maxlength=50></tr>";
				echo "<tr><td>$font<b>Email:</b></td><td><input type=text name=eve_email size=30 maxlength=50></tr>";
				echo "<tr><td colspan=2 align=center><textarea rows=5 name=eve_results cols=35></textarea><br><input type=submit name=add_results value=\"Add it!\"></td><tr>";
				echo "</table></p></td></tr></table>";
			}

		}
		else
		{
			echo "No Such Event listed";
		}
   	}
   	//regular events section
   	else
   	{
	   	echo "<strong><font face=\"Arial\" color=#000080 size=2>Events<br><br></font></strong>";
	   	//Go get all the events
	   	$query="SELECT * from events1 where e_valid=1 order by e_date desc";
	   	$result = @mysql_db_query($database, $query);
		while($s=@mysql_fetch_array($result))
		{
			if(!$e_id) // first time through loop
			{
				echo "<P>$font Current date: $currentdate<br>If you would like an event listed, please fill out the events <a href=events.php?new=1>addition form</a>.  Thank you ^_^<br><i>* - Results are avaliable for this event.</i><br><br>\n";
				echo "<table border=0 cellpadding=0 cellspacing=0 style=\"border:1px solid #000000; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>";
				echo "<tr bgcolor=\"#93A2BF\"><td vAlign=top align=center height=1 width=60>$font<strong>Date</strong></font></td>\n";
				echo "<td valign=top align=left height=1 width=265>$font<b>Event Name</b></font></td>\n";
				echo "<td valign=top align=left height=1 width=170>$font<b>Location</b></td></font></font></tr>\n";
				echo "<tr bgcolor=#B1BCD3><td colspan=3>$font<b>Upcoming Events:</b></font></td></tr>\n";
			}
			extract($s);

			if(!$flags) { $flags++; $color="#E1E7F3"; }
			else { $flags=0; $color="#B1BCD3"; }

			if(($e_date<$curdate)&&(!$flag)) // Separates into past events
			{
				echo "<tr bgcolor=$color><td colspan=3>&nbsp</td></tr>\n";
				if(!$flags) { $flags++; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				echo "<tr bgcolor=$color><td colspan=3>$font<b>Past Events</b></font></td></tr>\n";
				if(!$flags) { $flags++; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				$flag=1;
			}
			if($e_date<20020000 && !$flag2)  // puts the 2001 Events
			{
				echo "<tr bgcolor=$color><td colspan=3>&nbsp</td></tr>\n";
				if(!$flags) { $flags++; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				echo "<tr bgcolor=$color><td colspan=3>$font<b>2001 Events</b></font></td></tr>\n";
				if(!$flags) { $flags++; $color="#E1E7F3"; }
				else { $flags=0; $color="#B1BCD3"; }
				$flag2=1;
			}
			echo "<tr bgcolor=$color><td vAlign=top height=23>$font$e_day</td>\n";
			echo "<td height=23>$font<a href=events.php?id=$e_id>$e_name</a>";
			if($e_results) echo "*";
			echo "</td>\n";
			echo "<td vAlign=top height=23>$font $e_city, $e_st</td></tr>\n";
		}
		echo "</table>\n";
  	}

  	include("footer.php");
?>