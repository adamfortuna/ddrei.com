<?php

	include("header.php");



	function start_table($title)

	{

		echo "<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style=\"border-collapse: collapse; border-width: 0\" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style=\"border-style: solid; border-width: 1\" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>$title</i></b></font></td></tr>";

	}

	function start_tr($colspan)

	{

		echo "<tr><td align=left style=\"border-style: solid; border-width: 1\" colspan=$colspan><br>";

	}

	function inner_table()

	{

		echo "<p align=center><table border=0 width=95%><tr><td>";

	}

	function path($title)

	{

		echo "<strong><font face=Arial color=#000080>$title</font></strong><br><br>";

	}

	$q=0; //query counter

	

	if($tm==4)

	{

		path("<a href=tm.php>Tournamix</a> :: Tournamix 4");

		echo "<img src=tm/tm4/TM4-01-A-Intro_files/image002.jpg width=495 height=155>";

		start_table("Tournamix 4");

		start_tr(2);

		inner_table();

		echo "

		

		The forth installment of Tournamix is finally upon us! The Tournamix team has thought long and hard to bring the most updated and fair rules this time around.  Please be sure to read throught them all BEFORE starting your entry.  There are many songs that won't be allowed this time, so make sure your entry is valid beforehand.  The earlist you may submit your file is May 1st, With the final deadline being June 12.  Read on for all the details!<br><br>

		1) <a href=tm/tm4/TM4-01-A-Intro.php>Introduction</a><br>

		2) <a href=tm/tm4/TM4-01-B-FAQ.php>Tournamix FAQ</a><br>

		3) <a href=tm/tm4/TM4-02-A-Format.php>The New Format</a><br>

		4) <a href=tm/tm4/TM4-03-A-EntrySubmission.php>Entry Submissions</a><br>

		5) <a href=tm/tm4/TM4-04-A-SongRestrictions.php>Song Restrictions</a><br>

		6) <a href=tm/tm4/TM4-04-B-SongAdvice.php>Song Advice</a><br>

		7) <a href=tm/tm4/TM4-05-A-DWIFORMAT.php>DWI Formatting</a><br>

		8) <a href=tm/tm4/TM4-05-B-ExampleDWI.php>Example DWIs</a><br>

		9) <a href=tm/tm4/TM4-06-A-ReadMe.php>How to make a ReadMe File</a><br>

		10) <a href=tm/tm4/TM4-06-B-ExampleReadMe.php>Example ReadMe Files</a><br>

		</td></tr></table></td></tr></table>";

		

	}

	else if($tm==5)

	{

		path("<a href=tm.php>Tournamix</a> :: Tournamix 5");

		echo "<p align=center><img src=tm/tm5/DDRei_TournaMix5.jpg></p>";

		start_table("Tournamix 5");

		start_tr(2);

		inner_table();

		echo "

		

		The fifth installment of Tournamix is finally upon us! The Tournamix team has thought long and hard to bring the most updated and fair rules this time around.  Please be sure to read throught them all BEFORE starting your entry.  There are many songs that won't be allowed this time, so make sure your entry is valid beforehand.  <b>The deadline for registering is May 8th!</b>  You must email your entry to <a href=mailto:tm5@ddrei.com>Sivus</a> by the deadline to enter!  Read on for all the details!<br><br>

		1) <a href=tm/tm5/TM5-01-Registration.php>Registration</a><br>

		2) <a href=tm/tm5/TM5-02-Format.php>Format</a><br>

		3) <a href=tm/tm5/TM5-03-Score.php>Score</a><br>

		4) <a href=tm/tm5/TM5-04-Restrictions.php>Restrictions</a><br>

		5) <a href=tm/tm5/TM5-05-Extras.php>Entry Extras</a><br>

		6) <a href=tm/tm5/TM5-06A-Examples.php>Example</a><br>

		</td></tr></table></td></tr></table>";

	}

	else if($tm==6)

	{

		path("<a href=tm.php>Tournamix</a> :: Tournamix Final");

		//echo "<p align=center><img src=tm/tm5/DDRei_TournaMix5.jpg></p>";

		start_table("Tournamix Final");

		start_tr(2);

		inner_table();

		echo "

		

		The <b>Final</b> installment of Tournamix is finally upon us! The Tournamix team has thought long and hard to bring the most updated and fair rules this time around.  Please be sure to read throught them all BEFORE starting your entry.  There are many songs that won't be allowed this time, so make sure your entry is valid beforehand.  <b>The deadline for registration is July 25th!</b>. The deadline for file submission is July 29th.  Contest begins August 1st.  You must email your entry to <a href=mailto:TournaMix@gmail.com>Sivus</a> by the deadline to enter!  Read on for all the details!<br><br>

		1) <a href=tm/tmFinal/00-Basics.php>Basic Entry Submission Information</a><br>

		2) <a href=tm/tmFinal/01-Registration.php>Registration Information</a><br>

		3) <a href=tm/tmFinal/02-Entry.php>Entry Format Information</a><br>

		4) <a href=tm/tmFinal/03-Score.php>Score Break Down Information</a><br>

		5) <a href=tm/tmFinal/04-Song.php>Song Restrictions and Time Length Restrictions</a><br>

		6) <a href=tm/tmFinal/05-EntryExtras.php>Entry Extras Information</a><br>
		
		7) <a href=tm/tmFinal/06-Breakdown.php>How the Contest Breaks Down Per Round</a><br>

		</td></tr></table></td></tr></table>";

	}
	else if($results)

	{

	}

	else if($writeup)

	{

		//get the information about this writeup

		$query="SELECT ta_name, wu_com, tm_id FROM tournamix_authors, tournamix_writeups WHERE ta_id=a_id AND wu_id=$writeup";

		$result=mysql_db_query($database, $query); $q++;

		if($r=mysql_fetch_array($result))

		{

			extract($r);

			path("<a href=tm.php>Tournamix</a> :: <a href=tm.php?tm=$tm_id>TM $tm_id</a> :: $ta_name's write up");

			start_table("$ta_name's write up");

			start_tr(2);

			inner_table();

			echo $wu_com."<br></td></tr></table></td></tr></table>";

		}

		else

		{

			$error=1;

		}

	}

	else if($author)

	{

	}

	else //main page

	{

		//make the header

		path("Tournamix");

		?>

		<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style="border-collapse: collapse; border-width: 0" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style="border-style: solid; border-width: 1" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>DDRei Tournamix 1 Winners</i></b></font></td></tr>

<tr><td align=center style="border-style: solid; border-width: 1" colspan=2>

<br><b>1st: </b> Healing Vision ~Half Mix~ By cmcm<br>

<b>2nd: </b> Naturally By BeMaNiRuler <br>

<b>3rd: </b> Evolution By Mercury Storm <br><br>

<b>Results</b><br>

<a href=http://www.ddrei.com/tm/tm1/popular.php>Complete Results</a><br><br>

</td></tr></table>

<br><br>

<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style="border-collapse: collapse; border-width: 0" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style="border-style: solid; border-width: 1" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>DDRei Tournamix 2 Winners</i></b></font></td></tr>

<tr><td align=center style="border-style: solid; border-width: 1" colspan=2>



<br><b>Standard Winners (judges vote)</b><br>

<b>1st: </b> Klungkung 1655 By KieferSkunk and Ryouga <br>

<b>2nd: </b> Flying To your Soul (Game Cut) By Pre10der <br>

<b>3rd: </b> The Great Giana Sisters By Stephen<br><br>



<b>Standard Winners (popular vote)</b><br>

<b>1st: </b> The Great Giana Sisters By Stephen<br>

<b>2nd: </b> M By Reivaj <br>

<b>3rd: </b> Klungkung 1655 By KieferSkunk and Ryouga <br><br><br>



<b>Heavy Winners (judges vote)</b><br>

<b>1st: </b> Heaven By NerdJNerdBird <br>

<b>2nd: </b> Metal Gear Solid 2: Main Theme By Dj Slash<br>

<b>3rd: </b> So Deep - Wire Remix (Long Version) By AgentChang<br><br>



<b>Heavy Winners (popular vote)</b><br>

<b>1st: </b> So Deep - Wire Remix (Long Version) By AgentChang<br>

<b>2nd: </b> Heaven By NerdJNerdBird <br>

<b>3rd: </b> QQQ By MoogleHunter<br><br>

<b>Results</b><br>

<a href=http://www.ddrei.com/tm/tm2/popular.php>Popular Results</a><br>

<a href=http://www.ddrei.com/tm/tm2/judges.php>Judges Results</a><br><br>

<b>Judges Write Ups</b><br>

<a href=http://www.ddrei.com/tm/tm2/cmcm_Comments.php>Cmcm's write up</a><br>

<a href=http://www.ddrei.com/tm/tm2/nmr_Comments.php>NMR's write up</a><br><br>

</td></tr></table>

		

<br><br>

<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style="border-collapse: collapse; border-width: 0" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style="border-style: solid; border-width: 1" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>DDRei Tournamix 3 Winners</i></b></font></td></tr>

<tr><td align=center style="border-style: solid; border-width: 1" colspan=2>



<br><b>Standard Winners (judges vote)</b><br>

<b>1st: </b> Doesn't Really Matter By Shortysnmn2010<br>

<b>2nd: </b> Hey Hey Hey Hey' By IcePagoda<br>

<b>3rd: </b> Airen By KingKirby87<br><br>



<b>Standard Winners (popular vote)</b><br>

<b>1st: </b> Eat 'Em Up By Plaguefox<br>

<b>2nd: </b> Blue Fever By cmcm<br>

<b>3rd: </b> Airen By KingKirby87<br><br><br>





<br><b>Heavy Winners (judges vote)</b><br>

<b>1st (tie): </b> Spin By Unrealitized<br>

<b>1st (tie): </b> Simple and Clean 1655 By Justin Landry<br>

<b>2nd: </b> Invoke By Reivaj<br>

<b>3rd: </b> Boom Boom Dollar By Dave Phaneuf<br><br>



<b>Heavy Winners (popular vote)</b><br>

<b>1st: </b> Invoke By Reivaj<br>

<b>2nd: </b> Stoic By Moogle Hunter<br>

<b>3rd: </b> Tsu.4 By PyneapeL<br><br>



<b>Results</b><br>

<a href=http://www.ddrei.com/tm/tm3/popular.php>Popular Results</a><br>

<a href=http://www.ddrei.com/tm/tm3/judges.php>Judges Results</a><br><br>

<b>Judges Write Ups</b><br>

<a href=http://www.ddrei.com/tm/tm3/Chugi_Comments.php>Chugi's write up</a><br>

<a href=http://www.ddrei.com/tm/tm3/Ollec2004_Comments.php>Ollec2004's write up</a><br>

<a href=http://www.ddrei.com/tm/tm3/Radien_A-K_Comments.php>Radien's write up (A-K)</a><br>

<a href=http://www.ddrei.com/tm/tm3/Radien_L-Z_Comments.php>Radien's write up (L-Z)</a><br>

<a href=http://www.ddrei.com/tm/tm3/Sivus_Comments.php>Sivus's write up</a><br>

<a href=http://www.ddrei.com/tm/tm3/STVs_Comments.txt>STV's comments</a><br><br>

</td></tr></table>



<br><br>

<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style="border-collapse: collapse; border-width: 0" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style="border-style: solid; border-width: 1" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>DDRei Tournamix 4</i></b></font></td></tr>

<tr><td align=center style="border-style: solid; border-width: 1" colspan=2>

<br><a href=tm.php?tm=4>Tournamix 4 Rules</a><br><br></td></tr></table>

<?

	}

	

	//echo $q;

	include("footer.php");

?>