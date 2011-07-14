<?php
	include("scripts/database.php");
	include("header.php");
	
	function start_table($title)
	{
		echo "<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style=\"border-collapse: collapse; border-width: 0\" bordercolorlight=#C0C0C0><tr><td width=480 height=25 style=\"border-style: solid; border-width: 1\" bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>$title</i></b></font></td></tr><tr><td width=480 style=\"border-style: solid; border-width: 1\"><br><p align=center><table border=2 cellspacing=1 cellpadding=2 bordercolor=#C0C0C0 cellpadding=0 style=\"border-collapse: collapse; border-width: 0\" bordercolorlight=#C0C0C0 width=95%>";
	}
	function end_table()
	{
		echo "</table>&nbsp</td></tr></table>";
	}
	function path($title)
	{
		echo "<strong><font face=Arial color=#000080>$title</font></strong><br><br>";
	}	
	function isSame($t1,$t2,$t3,$t4,$t5)
	{
		if($t1==$t2 || $t1==$t3 || $t1==$t4 || $t1==$t5)
			return 1;
		if($t2==$t1 || $t2==$t3 || $t2==$t4 || $t2==$t5)
			return 1;
		if($t3==$t1 || $t3==$t2 || $t3==$t4 || $t3==$t5)
			return 1;
		if($t4==$t1 || $t4==$t2 || $t4==$t3 || $t4==$t5)
			return 1;
		if($t5==$t1 || $t5==$t2 || $t5==$t3 || $t5==$t4)
			return 1;
		return 0;
	}
	
	if($submit)
	{
		
		if($tm_pack) //this is a user voting
		{
			//variables: $tm_1, $tm_2, $tm_3
			//make sure they didn't vote on the same thing
			if(!isSame($tm_1,$tm_2,$tm_3,$tm_4,$tm_5))
			{
				//first be sure they haven't voted before and are trying to scam us
				$query="SELECT tm_voted FROM tournamix_packs WHERE tm_pack=$tm_pack";
				$result=mysql_db_query($database, $query);
				if($r=mysql_fetch_array($result))
					extract($r);
				$votes=explode(",", $tm_voted);
				for($k=1; $k<count($votes); $k++)
				{
					if($votes[$k]==$ddrei_id)
						$flag=1;
				}
				
				if(!$flag)
				{
					//1st place
					$query="SELECT tm_1 as total FROM tournamix WHERE tm_id=$tm_1";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$total++;
						$result=mysql_db_query($database, "UPDATE tournamix SET tm_1=$total WHERE tm_id=$tm_1");
					}
					//2nd place
					$query="SELECT tm_2 as total FROM tournamix WHERE tm_id=$tm_2";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$total++;
						$result=mysql_db_query($database, "UPDATE tournamix SET tm_2=$total WHERE tm_id=$tm_2");
					}
					//3rd place
					$query="SELECT tm_3 as total FROM tournamix WHERE tm_id=$tm_3";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$total++;
						$result=mysql_db_query($database, "UPDATE tournamix SET tm_3=$total WHERE tm_id=$tm_3");
					}
					//4th place
					$query="SELECT tm_4 as total FROM tournamix WHERE tm_id=$tm_4";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$total++;
						$result=mysql_db_query($database, "UPDATE tournamix SET tm_4=$total WHERE tm_id=$tm_4");
					}
					//5th place
					$query="SELECT tm_5 as total FROM tournamix WHERE tm_id=$tm_5";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$total++;
						$result=mysql_db_query($database, "UPDATE tournamix SET tm_5=$total WHERE tm_id=$tm_5");
					}
				
					//register the vote
					$query="SELECT tm_voted, tm_votes FROM tournamix_packs WHERE tm_pack=$tm_pack";
					$result=mysql_db_query($database, $query);
					if($r=mysql_fetch_array($result))
					{
						extract($r);
						$tm_voted=$tm_voted.",$ddrei_idnum";
						$tm_votes++;
						$result=mysql_db_query($database, "UPDATE tournamix_packs SET tm_voted='$tm_voted', tm_votes='$tm_votes' WHERE tm_pack=$tm_pack");
					}
				
					path("Tournamix 5 Vote");
					start_table("TM 5: Vost cast!");
					echo "<tr><td align=center><b>You vote has been cast.<br><br>Results will be available next sunday!</td></tr>";
					end_table();
				}
				else
				{
					path("Tournamix 5 Vote");
					start_table("TM 5: wtf?!!");
					echo "<tr><td align=center><b>You IP and false votes have been logged!  You're not getting away with this #$%#!</td></tr>";
					end_table();
				}
			}
			else
			{
				path("Tournamix 5 Vote");
				start_table("TM 5: Error");
				echo "<tr><td align=center><br><b>An error occured when casting your vote.<br>Please press back and make sure:</b><br><br><ul><li>You did not vote for the same song more than once.</li><li>You left any form blank.</li><li>Your forum name appears in the right column.</li></ul><br><br>Please press back and try again.</td></tr>";
				end_table();
			}
		}
	}
	else
	{
		if($results)
		{
			//make sure that $results pack is up for public viewing
			$query="SELECT tm_done FROM tournamix_packs WHERE tm=5 AND tm_pack=$results";
			$result=mysql_db_query($database, $query);
			if($r=mysql_fetch_array($result))
				extract($r);	
			if($tm_done || (!$tm_done && $ddrei_level))
			{
				if(!$p1) $p1=1;
				if(!$p2) $p2=1;
				if(!$p3) $p3=1;
				if(!$p4) $p4=1;
				if(!$p5) $p5=1;
				$i=0;
				//display previous results
				path("<a href=tm5vote.php>Tournamix 5 Vote</a> :: Pack $results Results");
				start_table("Pack $results results");
				echo "<tr><td><b>#</b></td><td><b>Song Name</b></td>
				<td><b>Creator</b></td>
				<td><b>1st</b></td>
				<td><b>2nd</b></td>
				<td><b>3rd</b></td>
				<td><b>4th</b></td>
				<td><b>5th</b></td>
				<td><b>Total</b></td><tr>";
				$query="SELECT tm_id, tm_song, tm_creator, tm_1, tm_2, tm_3, tm_4, tm_5, tm_dq FROM tournamix WHERE tm=5 AND tm_pack=$results";
				$result=mysql_db_query($database, $query);
				while($r=mysql_fetch_array($result))
				{
					extract($r);
					$t_id[$i]=$tm_id;
					$t_song[$i]=$tm_song;
					$t_creator[$i]=$tm_creator;
					$t_1[$i]=$tm_1;
					$t_2[$i]=$tm_2;
					$t_3[$i]=$tm_3;
					$t_4[$i]=$tm_4;
					$t_5[$i]=$tm_5;
					$t_dq[$i]=$tm_dq;
					$t_sum[$i]=$tm_1*$p1 + $tm_2*$p2 + $tm_3*$p3 + $tm_4*$p4 + $tm_5*$p5;
					$i++;
					
				}
				
				//sigh, not to order them all
				for($k=0; $k<$i; $k++)
				{
					$high=$k;
					for($j=$k; $j<$i; $j++)
					{
						//This one is higher, so swap it out.
						if($t_sum[$j]>$t_sum[$high])
						{
							$high=$j;
						}
						
						//number of votes is the same as the highest total.  check the 1st/2nd/3rd
						else if($t_sum[$j]==$t_sum[$high])
						{
							if($t_1[$j]>$t_1[$high])
							{
								$high=$j;	
							}
							else if($t_1[$j]==$t_1[$high])
							{
								if($t_2[$j]>$t_2[$high])
								{
									$high=$j;
								}
								else if($t_2[$j]==$t_2[$high])
								{
									if($t_3[$j]>$t_3[$high])
									{
										$high=$j;
									}
								}
							}
						}
						
					}
					
					//swap place $k with $high	
					$tmp_id=$t_id[$k];
					$tmp_song=$t_song[$k];
					$tmp_creator=$t_creator[$k];
					$tmp_1=$t_1[$k];
					$tmp_2=$t_2[$k];
					$tmp_3=$t_3[$k];
					$tmp_4=$t_4[$k];
					$tmp_5=$t_5[$k];
					$tmp_dq=$t_dq[$k];
					$tmp_sum=$t_sum[$k];
					
					//move $high into $k
					$t_id[$k]=$t_id[$high];
					$t_song[$k]=$t_song[$high];
					$t_creator[$k]=$t_creator[$high];
					$t_1[$k]=$t_1[$high];
					$t_2[$k]=$t_2[$high];
					$t_3[$k]=$t_3[$high];
					$t_4[$k]=$t_4[$high];
					$t_5[$k]=$t_5[$high];
					$t_dq[$k]=$t_dq[$high];
					$t_sum[$k]=$t_sum[$high];
					
					//move temp into $high
					$t_id[$high]=$tmp_id;
					$t_song[$high]=$tmp_song;
					$t_creator[$high]=$tmp_creator;
					$t_1[$high]=$tmp_1;
					$t_2[$high]=$tmp_2;
					$t_3[$high]=$tmp_3;
					$t_4[$high]=$tmp_4;
					$t_5[$high]=$tmp_5;
					$t_dq[$high]=$tmp_dq;
					$t_sum[$high]=$tmp_sum;
				}
				$num=1;
				for($k=0; $k<$i; $k++)
				{
					if($num<=3)
					{
						//this song already has a place
						if($t_dq[$k])
						{
							echo "<tr><td><b><strike>$num</strike></b></td>
							<td><b><strike>$t_song[$k]<strike></b></td>
							<td><b><strike>$t_creator[$k]<strike></b></td>
							<td><b>$t_1[$k]</b></td>
							<td><b>$t_2[$k]</b></td>
							<td><b>$t_3[$k]</b></td>
							<td><b>$t_4[$k]</b></td>
							<td><b>$t_5[$k]</b></td>
							<td><b>$t_sum[$k]</b></td><tr>";
							$num--;
						}
						else
						{
							echo "<tr><td><b>$num</b></td>
							<td><b>$t_song[$k]</b></td>
							<td><b>$t_creator[$k]</b></td>
							<td><b>$t_1[$k]</b></td>
							<td><b>$t_2[$k]</b></td>
							<td><b>$t_3[$k]</b></td>
							<td><b>$t_4[$k]</b></td>
							<td><b>$t_5[$k]</b></td>
							<td><b>$t_sum[$k]</b></td><tr>";
						}
					}
					else
						echo "<tr><td>$num</td>
							<td>$t_song[$k]</td>
							<td>$t_creator[$k]</td>
							<td>$t_1[$k]</td>
							<td>$t_2[$k]</td>
							<td>$t_3[$k]</td>
							<td>$t_4[$k]</td>
							<td>$t_5[$k]</td>
							<td>$t_sum[$k]</td><tr>";
					$num++;
						
				}
				
				
				//echo "</table>";
				end_table();
			}
			else
			{
				path("<a href=tm5vote.php>Tournamix 5 Vote</a> :: Pack $results Results");
				start_table("PFFFFFFFT");
				echo "<tr><td align=center><b>Try again next sunday!</b></td></tr>";
				end_table();
			}
		}
		else if(!$ddrei_id || !$ddrei_username || $ddrei_username=='ANONYMOUS' || $ddrei_regdate>1089174324)
		{
			path("Tournamix 5 Vote");
			start_table("Not logged in!");
			if(!$ddrei_id || !$ddrei_username || $ddrei_username=='ANONYMOUS')
				echo "<tr><td align=center><br>You must be logged into the <a href=http://forums.ddrei.com>forums</a> to vote!<br><br><br>Please log in and select the 'Log me in automatically' checkbox,<br>then return to this page.</td><tr>";
			if($ddrei_regdate>1089174324)
				echo "<tr><td align=center>We're sorry, but to prevent users from creating accounts only to vote at the last minute, you must have registerred with the DDRei Forums <i>before</i> July 7, 2004.  You are not eligible to vote in this round.</td></tr>";
			
			$query="SELECT tm_pack FROM tournamix_packs WHERE tm_done=1 AND tm=5 Order by tm_pack desc limit 0,1";
			$result=mysql_db_query($database, $query);
			if($r=mysql_fetch_array($result))
				extract($r);
			if($tm_pack>1) 
			{
				echo "<tr><td colspan=2 align=center>";
				for($i=1; $i<=$tm_pack; $i++)
				{	
					echo "<a href=tm5vote.php?results=$i>Pack $i Results</a><br>";
				}
				echo "</td></tr>";
			}
				
			end_table();
		}
		else
		{	
			//find which pack is currently up
			$query="SELECT tm_pack FROM tournamix_packs WHERE tm_active=1 AND tm=5";
			$result=mysql_db_query($database, $query);
			if($r=mysql_fetch_array($result))
				extract($r);
			$flag=0;
			if($tm_pack)
			{
				//find out if they've voted before
				$query="SELECT tm_voted FROM tournamix_packs WHERE tm_pack=$tm_pack";
				$result=mysql_db_query($database, $query);
				if($r=mysql_fetch_array($result))
					extract($r);
				$votes=explode(",", $tm_voted);
				for($k=1; $k<count($votes); $k++)
				{
					if($votes[$k]==$ddrei_id)
						$flag=1;
				}
				
				//list all entries
				path("Tournamix 5 Vote");
				start_table("TM 5: Pack $tm_pack");
				if($flag)
				{
					echo "<tr><td align=center><br>'$ddrei_username' with id  has already cast their vote for TM5, pack $tm_pack.<br><br>Please return in a week for the results and the next vote!</td></tr>";
				}
				else
				{
					echo "<tr><td colspan=2 align=center>Please select your top five DWI's from TM5, pack $tm_pack.</td></tr>
					
					<tr><td align=center><b>1)</b></td><td valign=bottom align=center>";
					
					echo "<form method=POST action=tm5vote.php><select size=1 name=tm_1><option value=\"\">------------</option>";
					$query="SELECT tm_id, tm_song, tm_creator FROM tournamix WHERE tm_pack=$tm_pack AND tm=5";
					$result=@mysql_db_query($database, $query);
					while($r=@mysql_fetch_array($result))
					{
						extract($r);
						echo "<option value=$tm_id>$tm_song";
						if($tm_creator) echo " by $tm_creator</option>";
					}
					echo "</select>";
		
					echo "</td></tr><tr><td align=center><b>2)</b></td><td align=center>";
					
					echo "<select size=1 name=tm_2><option value=\"\">------------</option>";
					$query="SELECT tm_id, tm_song, tm_creator FROM tournamix WHERE tm_pack=$tm_pack AND tm=5";
					$result=@mysql_db_query($database, $query);
					while($r=@mysql_fetch_array($result))
					{
						extract($r);
						echo "<option value=$tm_id>$tm_song";
						if($tm_creator) echo " by $tm_creator</option>";
					}
					echo "</select>";
					
					echo "</td></tr><tr><td align=center><b>3)</b></td><td align=center>";
					
					echo "<select size=1 name=tm_3><option value=\"\">------------</option>";
					$query="SELECT tm_id, tm_song, tm_creator FROM tournamix WHERE tm_pack=$tm_pack AND tm=5";
					$result=@mysql_db_query($database, $query);
					while($r=@mysql_fetch_array($result))
					{
						extract($r);
						echo "<option value=$tm_id>$tm_song";
						if($tm_creator) echo " by $tm_creator</option>";
					}
					echo "</select>";
					
					echo "</td></tr><tr><td align=center><b>4)</b></td><td align=center>";
					
					echo "<select size=1 name=tm_4><option value=\"\">------------</option>";
					$query="SELECT tm_id, tm_song, tm_creator FROM tournamix WHERE tm_pack=$tm_pack AND tm=5";
					$result=@mysql_db_query($database, $query);
					while($r=@mysql_fetch_array($result))
					{
						extract($r);
						echo "<option value=$tm_id>$tm_song";
						if($tm_creator) echo " by $tm_creator</option>";
					}
					echo "</select>";
					
					echo "</td></tr><tr><td align=center><b>5)</b></td><td align=center>";
					
					echo "<select size=1 name=tm_5><option value=\"\">------------</option>";
					$query="SELECT tm_id, tm_song, tm_creator FROM tournamix WHERE tm_pack=$tm_pack AND tm=5";
					$result=@mysql_db_query($database, $query);
					while($r=@mysql_fetch_array($result))
					{
						extract($r);
						echo "<option value=$tm_id>$tm_song";
						if($tm_creator) echo " by $tm_creator</option>";
					}
					echo "</select>";
					
					echo "</td></tr>
					
					<tr><td colspan=2 align=center><input type=hidden name=tm_pack value=$tm_pack><input type=hidden name=ddrei_idnum value=$ddrei_id><input type=submit name=submit value=submit></td></tr>";
					if($tm_pack>1) 
					{
						echo "<tr><td colspan=2 align=center>";
						for($i=1; $i<$tm_pack; $i++)
						{	
							echo "<a href=tm5vote.php?results=$i>Pack $i Results</a><br>";
						}
						echo "</td></tr>";
					}
				}
				end_table();
			}
			else //there is no active vote going on
			{
				path("Tournamix 5 Vote");
				start_table("TM 5");
				echo "<tr><td align=center><b>There is currently no vote available.</b></td><tr>";
				end_table();
			}
		}	
	}
	include("footer.php"); 

	
?>