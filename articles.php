<?php

	function get_date($date)
	{
		$year=strval(substr($date,0,4));
		$month=strval(substr($date,4,2));
		if($month=="01") { $month="Jan"; }
		else if($month=="02") { $month="Feb"; }
		else if($month=="03") { $month="Mar"; }
		else if($month=="04") { $month="Apl"; }
		else if($month=="05") { $month="May"; }
		else if($month=="06") { $month="June"; }
		else if($month=="07") { $month="July"; }
		else if($month=="08") { $month="Aug"; }
		else if($month=="09") { $month="Sep"; }
		else if($month=="10") { $month="Oct"; }
		else if($month=="11") { $month="Nov"; }
		else if($month=="12") { $month="Dec"; }
		$done="$month. $year";
		return $done;
	}
	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }
	$connect = 1;
	include("scripts/time.php");

	if($id)
	{
		$type[1]=1; $path="Articles";
		$query = "SELECT * from articles2 where a_id=$id";
		$result = mysql_db_query($database, $query);

		if($r=@mysql_fetch_array($result))
		{
			$atitle=$r["title"];
			$aauth=$r["auth"];
			$aem=$r["em"];
			$acom=$r["com"];
			$acom=str_replace("\n", "<br>", $acom);
			$acom=str_replace("\t", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", $acom);
			$date=$r["date"];
			$adate=mysql_timestamp_to_human($date);
			$type[1]=1;
			$the_month=get_date($date);
			$month_send=strval(substr($date,0,6));
			$path="<a href=articles.php>Articles</a> :: <a href=articles.php?date=$month_send>$the_month</a> :: $atitle";
			$title = "$atitle";
		}

		//get the comments
		$query = "SELECT * from articles3 where a_id=$id order by date";
		$result = mysql_db_query($database, $query);

		$row=0;
		while($s=@mysql_fetch_array($result))
		{
			$ctitle[$row]=$s["title"];
			$cauth[$row]=$s["auth"];
			$cem[$row]=$s["em"];
			$ccom[$row]=$s["com"];
			$ccom[$row]=str_replace("\n", "<br>", $ccom[$row]);
			$cdate[$row]=mysql_timestamp_to_human($s["date"]);
			$row++;
		}
	}
	else
	{
		if(!$date)
		{
			$today = getdate();
			$month = $today['mon'];
			$mday = $today['mday'];
			$year = $today['year'];
			if($month<10) { $month = "0".$month; }
			if($mday<10) { $mday = "0".$mday; }
			$date="$year"."$month";
		}
		$the_month=get_date($date);
		$lowdate="$date"."00000000";
		$highdate="$date"."31245959";

		$query="SELECT date, pic, preview, em, auth, title, title_short, a_id, com from articles2 where date>$lowdate and date<$highdate order by date desc";
		if($all || $nall) { $query="SELECT date, pic, preview, em, auth, title, title_short, a_id, com from articles2 order by date desc"; }
		$result = mysql_db_query($database, $query);
		$cur=0;
		while($r=@mysql_fetch_array($result))
		{
			if(!$flag)
			{
				$picture=$r["pic"];
				$flag=1;
			}
			$t_date=$r["date"];
			$a_date[$cur]=mysql_timestamp_to_human($t_date);
			$a_preview[$cur]=$r["preview"];
			if(!$a_preview[$cur])
			{
				$a_preview[$cur]=$r["com"];
				$a_preview[$cur]=strval(substr($a_preview[$cur],0,250));
				$a_preview[$cur]="$a_preview[$cur] ...";
			}
			$a_preview[$cur]=str_replace("\n", "<br>", $a_preview[$cur]);
			$a_preview[$cur]=str_replace("\t", "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp", $a_preview[$cur]);
			$a_em[$cur]=$r["em"];
			$a_auth[$cur]=$r["auth"];
			$a_title[$cur]=$r["title"];
			$a_title_short[$cur]=$r["title_short"];
			$a_id[$cur]=$r["a_id"];
			$qu="select count(id) from articles3 where a_id=$a_id[$cur]";
			$res=mysql_db_query($database, $qu);
			if($s=@mysql_fetch_array($res)) {
				if($a_coms[$cur]=$s[0]) {}
				else { $a_coms[$cur]=0; }
			}
			$cur++;
		}
		$type[0]=1;
		if($nall) { $type[0]=0; $type[2]=1; }
		if(!$cur) { $type[0]=0; $type[3]=1; }
		$path="<a href=articles.php>Articles</a> :: $the_month";
		if($all || $nall) { $path="<a href=articles.php>Articles</a> :: All Articles"; }
		//get the next months
		$query="SELECT date from articles2 order by date";
		$result = mysql_db_query($database, $query);
		$row=0;
		while($s=@mysql_fetch_array($result))
		{
			$date=$s["date"];
			$send[$row]=strval(substr($date,0,6));
			$row++;
		}
	}


    include("header.php");

	echo "<strong><font face=Arial color=#000080 size=2>$path</font></strong><BR><br>";

if($type[0])
{
	if($flag)
	{
		if(!$picture)
		{
			$picture="pics/articles/4th_plus_rockys2.jpg";
		}
		$size = GetImageSize("$picture");
		$width=$size[0];
		$height=$size[1];
		$tdwidth=495-$width;
		echo "
<div align=center>
  <center>
  <table border=0 cellpadding=0 style=\"border-collapse: collapse\" bordercolor=#111111 width=495 height=351>
    <tr>
      <td width=$width valign=top>
      <p align=center>

      <table border=1 bordercolor=#c0c0c0 style=\"border-collapse: collapse\" cellpadding=0 cellspacing=0><tr><td><img border=0 src=$picture width=$width height=$height></td></td></table>
      <br></td>
      <td width=$tdwidth height=$height valign=top align=center>
      <table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=95% height=$height>
        <tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3>
          <p align=center><b><i><font face=\"Arial, verdana\">$a_title_short[0]</font></i></b></td>
        </tr>
        <tr>
          <td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\">
          <table border=0 cellpadding=2 stype=\"border-collapse:collapse\" cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111>
		  <tr>
            <td>
			 <p align=justify><font size=2 face=\"Arial, verdana\">$a_preview[0] <a href=articles.php?id=$a_id[0]>Read more...</a><br><i>Comments ($a_coms[0])</i>
		  </font>
		  </td></tr>
            <tr>
            <td>
          <p align=right><font face=Arial><font size=1>$a_date[0] by </font><a href=\"mailto:$a_em[0]\"><font size=1>$a_auth[0]</font></a></font>
		  </td>
            </tr>
          </table>
</td>
        </tr>
      </table>
      </td>
    </tr>
	<tr>
      <td width=495 valign=top colspan=2><br>";

		for($k=1; $k<$cur; $k++)
		{
			echo "
       <table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=100%>
        <tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3>
          <p align=center><b><i><font face=\"Arial, verdana\">$a_title[$k]</font></i></b></td>
        </tr>
        <tr>
          <td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top>
          <table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=100%>
		  <tr>
            <td>
          <p align=justify><font size=2 face=\"Arial, verdana\">$a_preview[$k] <a href=articles.php?id=$a_id[$k]>Read more...</a><br><i>Comments ($a_coms[$k])</i>
		  </font>
		  </td></tr>
            <tr>
            <td>
          <p align=right><font face=Arial><font size=1>$a_date[$k] by
          </font><a href=mailto:$a_em[$k]><font size=1>$a_auth[$k]</font></a></font></td>
            </tr>
          </table>
		</td>
        </tr>
      </table><br>";
		}
  echo "</tr><tr><td width=495 valign=top colspan=2 bgcolor=#E1E7F3 style=\"border-style: solid; border-width: 1\" bordercolor=#C0C0C0><p align=center><font face=Arial><i><b>Previous Articles: </b></i><br><font size=2>";

	   for($j=0; $j<$row; $j++)
		{
		   $last=$j-1;
		   if(($send[$j]!=$send[$last]) || $j==0)
			{
			   $month=get_date($send[$j]);
				echo "<a href=articles.php?date=$send[$j]>$month</a> :: ";
			}
		}
		echo "<a href=articles.php?all=1>All Articles</a> :: <a href=articles.php?nall=1>Titles Only</a></font></font></td></tr></table>";
	 }
}
else if ($type[1])
{
	echo "
		   <table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3>
          <p align=center><b><i><font face=\"Arial, verdana\">$atitle</font></i></b></td></tr><tr>
          <td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top>
          <table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=100%>
		  <tr><td><p align=justify><font size=2 face=\"Arial, verdana\">$acom
		  </font></td></tr><tr><td>
          <p align=right><font face=Arial><font size=1>$adate by
          </font><a href=mailto:$aem><font size=1>$aauth</font></a></font></td>
            </tr></table></td></tr> </table><br>";
		  // display all the comments
		if($row) {
			echo "
			<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3>
          <p align=center><b><i><font face=\"Arial, verdana\">Comments</font></i></b></td></tr><tr>
          <td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top>
          <table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=495>";
			for($k=0; $k<$row; $k++)
			{
				echo "<tr><td><font size=2 face=\"Arial, verdana\"><b>$ctitle[$k]</b> by <a href=mailto:$cem[$k]>$cauth[$k]</a> at $cdate[$k]<br>&nbsp&nbsp&nbsp$ccom[$k]</td></tr>";
			}
		  echo "</table></td></tr></table><br>";
		}
		// output the add comment form
		echo "
			<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=495><tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3>
          <p align=center><b><i><font face=\"Arial, verdana\">Add a Comment</font></i></b></td></tr><tr>
          <td width=100% style=\"border-left:1px solid #C0C0C0; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top>
          <table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\" bordercolor=#111111 width=495><form method=\"POST\" action=\"/scripts/process_article.php\"><input type=hidden name=a_id value=$id>
		  <tr><td width=150>Name</td>
			  <td><input type=text name=auth size=50 maxlength=50></td></tr>
		  <tr><td width=150>Email</td>
			 <td><input type=text name=em size=50 maxlength=50></td></tr>
		  <tr><td width=150>Title</td>
			  <td><input type=text name=title size=50 maxlength=50 value=\"Re: $atitle\"></td></tr>
		  <tr><td width=150>Comments</td>
			 <td><textarea rows=6 name=com cols=43></textarea></td></tr>
			 <tr><td>&nbsp</td><td><input type=submit name=submit value=Go> </table></td></table>";
}
else if($type[2])
{

		echo "<table border=0 cellpadding=0 cellspacing=0 bordercolor=#C0C0C0 width=495><tr><td width=495 valign=top colspan=2 bgcolor=#E1E7F3 style=\"border-style: solid; border-width: 1\" bordercolor=#C0C0C0>
		<table border=0 cellpadding=2 cellspacing=3 style=\"border-collapse: collapse\"><tr><td>";

		for($k=0; $k<count($a_title); $k++)
		{
			if($k) { echo "<br>"; }
			echo "<font face=\"Arial, verdana\" size=2><b><a href=articles.php?id=$a_id[$k]>$a_title[$k]</a></b><font size=1> by <a href=mailto:$a_em[$k]>$a_auth[$k]</a> on $a_date[$k]</font>";
		}
		echo "
		</td></tr></table></td></tr>
		<td>&nbsp</td>
    <tr>
      <td width=495 valign=top colspan=2 bgcolor=#E1E7F3 style=\"border-style: solid; border-width: 1\" bordercolor=#C0C0C0>
       <p align=center><font face=Arial><i><b>Previous Articles: </b></i><br><font size=2>";

	   for($j=0; $j<$row; $j++)
		{
		   $last=$j-1;
		   if(($send[$j]!=$send[$last]) || $j==0)
			{
			   $month=get_date($send[$j]);
				echo "<a href=articles.php?date=$send[$j]>$month</a> :: ";
			}
		}
		echo "<a href=articles.php?all=1>All Articles</a> :: <a href=articles.php?nall=1>Titles Only</a></font></td></tr></table>";
}
else if($type[3])
{
	echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse; border-right-width:0; \" bordercolor=#C0C0C0 width=95% height=$height>
        <tr>
          <td width=100% style=\"border-left-style: solid; border-left-width: 1; border-top-style: solid; border-top-width: 1; border-bottom-style:solid; border-bottom-width:1\" height=25 bgcolor=#E1E7F3><p align=center><font face=Arial><i><b>Previous Articles: </b></i><br><font size=2>";

	   for($j=0; $j<$row; $j++)
		{
		   $last=$j-1;
		   if(($send[$j]!=$send[$last]) || $j==0)
			{
			   $month=get_date($send[$j]);
				echo "<a href=articles.php?date=$send[$j]>$month</a> :: ";
			}
		}
		echo "<a href=articles.php?all=1>All Articles</a> :: <a href=articles.php?nall=1>Titles Only</a></font></font></td></tr></table>";
}
	echo "<p align=center><font face=arial,verdana size=1>Interested in writting an article for DDRei?  Email <a href=mailto:dyo@ddrei.com>Dyogenez</a>.</p>";
?>
<?php include("footer.php"); ?>

