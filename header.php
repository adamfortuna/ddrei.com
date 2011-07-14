<?php
	$htp_path="http://www.ddrei.com";

	$pic_path="http://www.ddrei.com";

	$home_path="/home/dyogenez/ddrei.com/";
                                                                                                                                                                     
	if(!$no_login)

	{

		$sessiondata = isset( $HTTP_COOKIE_VARS['phpbb2mysql_data'] ) ? unserialize(stripslashes($HTTP_COOKIE_VARS['phpbb2mysql_data'])) : "";

		$ddrei_id = ( isset($sessiondata['userid']) ) ? $sessiondata['userid'] : ANONYMOUS;

		$ddrei_password = ( isset($sessiondata['autologinid']) ) ? $sessiondata['autologinid'] : ANONYMOUS;



		if($ddrei_id && $ddrei_password)

		{

			include($home_path."scripts/database.php");

			$con = @mysql_connect($forum_host, $forum_login, $forum_pass);

			//check to see if the password matches and get the username

			$query = "SELECT user_password, username, user_level, user_regdate FROM phpbb_users WHERE user_id=$ddrei_id";

			$result = @mysql_db_query($forum_database, $query);

			if ($r=@mysql_fetch_array($result))

			{

				$phpbb_pass=$r["user_password"];

				$ddrei_username=$r["username"];

				$ddrei_level=$r["user_level"];
				
				$ddrei_regdate=$r["user_regdate"];

				// check to see if the password matches

				if($phpbb_pass==$ddrei_password) { $logged_in=1; }

				else { $logged_in=0; }

			}

			else { $logged_in=0; }

		}

		else

		{

			$logged_in=0;

		}



		$con=@mysql_close($con);

		//connect to the main database

		$con = @mysql_connect($host, $login, $pass);

	}



	//variables

	// $logged_in=1

	// $ddrei_id

	// $ddrei_password

	// $ddrei_username



	include($home_path."scripts/banners.php");

?>


<HTML>

<HEAD>

<TITLE>DDR East Invasion</TITLE>



<META content="text/html; charset=iso-8859-1" http-equiv=Content-Type>

<META NAME="keywords" CONTENT="ddr, dance dance revolution, pump it up, para para paradise, IIDX, bemani, konami, ddr videos, dancemania, pop 'n music, guitar freaks, beatmania, arcade locations, game information, ddr tournaments,  ddr max, ddr extreme">

<META NAME="description" CONTENT="Dance Dance Revolution on the East Coast! We have arcade locations, the latest news, articles, tips and information about almost any music game out there.">

<META NAME="expires" CONTENT="never">

<META NAME="language" CONTENT="english">

<META NAME="charset" CONTENT="ISO-8859-1">

<META NAME="distribution" CONTENT="Global">

<META NAME="robots" CONTENT="INDEX,FOLLOW">

<META NAME="email" CONTENT="admin@ddrei.com">

<META NAME="publisher" CONTENT="DDRei.com">

<META NAME="copyright" CONTENT="Copyright ©2002 - DDRei.com">



<SCRIPT language=JavaScript src="http://www.ddrei.com/scripts/mouse.js" type=text/javascript></SCRIPT>



<style>

{

	a:link {  }

	a:visited {  }

	a:active  { text-decoration: none; }

	a:hover { font-style : normal; color : #78a9d9; 	text-decoration: none;}



	SELECT {

	   font-weight : normal;

	   font-size : 10px;

	   font-family : Verdana, Arial, Helvetica, sans-serif;

	   color : #000000;

	   background-color : #E1E7F3;

	   border-width : 2px;

	   border-color : #ffffff;

	}

}

</style>



<SCRIPT language=JavaScript>

<!--

function PicWindow()

{ window.open("","pollwindow","toolbar=no,scrollbars=no,directories=no,status=no,menubar=no,resizable=yes,autofit=yes,width=270,height=250" ) }

// -->

</SCRIPT>


<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-90326-1";
urchinTracker();
</script>

<meta name="microid" content="a04b139a70f0590bfb8d73a9fa64dc7db6d08d44" />
</HEAD>





<body bgcolor="#E1E7F3" marginheight="0" marginwidth="0" topmargin="10" bottommargin="0" leftmargin="0" link="#000080" vlink="#000080" alink="#000080">

<p>

<div align="center"><center>



<table border="0" width="700" cellpadding="0" cellspacing="0" vspace="0" hspace="0">

  <tr>

    <td align="LEFT" valign="TOP" bgcolor="#FFFFFF">

    <div align="left">

    <table border="0" cellspacing="0" width="700" cellpadding="0" bgcolor="#FFFFFF">

    <tr>

    <td rowspan="2"><a href="<?php echo "$htp_path"; ?>">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/logo.jpg" width="85" height="46" alt="DDRei.com"></a></td>

    <td colspan="9">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/top.jpg" width="588" height="21"></td>

    <td rowspan="2"><a href="<?php echo "$htp_path"; ?>/help.php">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/q.jpg" width="45" height="46"></a></td>

    </tr>

    <tr>



    <td><a href="<?php echo "$htp_path"; ?>" onMouseOver="hiLite('navi1', 'b')" onMouseOut="hiLite('navi1', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_news.gif" width="50" height="25" name="navi1" alt="News"></a></td>



    <td><a href="<?php echo "$htp_path"; ?>/events.php" onMouseOver="hiLite('navi2', 'b')" onMouseOut="hiLite('navi2', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_events.gif" width="58" height="25" name="navi2" alt="Events"></a></td>



    <td><a href="<?php echo "$htp_path"; ?>/articles.php" onMouseOver="hiLite('navi8', 'b')" onMouseOut="hiLite('navi8', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_articles.gif" width="75" height="25" name="navi8" alt="Articles"></a></td>



	<td><a href="<?php echo "$htp_path"; ?>/loc.php" onMouseOver="hiLite('navi3', 'b')" onMouseOut="hiLite('navi3', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_locations.gif" width="84" height="25" name="navi3" alt="Locations"></a></td>



    <td><a href="http://forums.ddrei.com/memberlist.php" onMouseOver="hiLite('navi4', 'b')" onMouseOut="hiLite('navi4', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_profiles.gif" width="75" height="25" name="navi4" alt="Profiles"></a></td>



    <td><a href="<?php echo "$htp_path"; ?>/info.php" onMouseOver="hiLite('navi5', 'b')" onMouseOut="hiLite('navi5', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_info.gif" width="55" height="25" name="navi5" alt="Information"></a></td>



    <td><a href="http://www.ddrei.com/media.php" onMouseOver="hiLite('navi6', 'b')" onMouseOut="hiLite('navi6', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_media.gif" width="60" height="25" name="navi6" alt="Media"></a></td>



    <td><a href="http://forums.ddrei.com" onMouseOver="hiLite('navi7', 'b')" onMouseOut="hiLite('navi7', 'a')">

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_forum.gif" width="58" height="25" name="navi7" alt="Forum"></a></td>



    <td>

    <img border="0" src="<?php echo "$pic_path"; ?>/images/template/t_back.gif" width="73" height="25"></td>

    </tr>



    <tr>

    <td colspan="11"><img border="0" src="<?php echo "$pic_path"; ?>/images/template/bg4.gif" width="700" height="1"></td>

    </tr>



    </table>



    </div>

      <TABLE border=0 cellPadding=0 cellSpacing=0 width=700>

        <TBODY>

        <TR>

          <TD align=left background="<?php echo "$pic_path"; ?>/images/template/left.gif" bgColor=#ffffff rowSpan=2 vAlign=top width=80>

            <DIV align=left>



            <TABLE border=0 cellPadding=0 cellSpacing=0 width=92 height="16">

              <TBODY>

              <TR>

                <TD align=left vAlign=top width="90" colspan="2" height="1"><IMG border=0 height=16 src="<?php echo "$pic_path"; ?>/images/template/top_left.gif" width=85><BR>

                </TD></TR>

              <tr>

                <TD align=left vAlign=top width="7" height="19" >&nbsp;</TD>

                <TD align=left vAlign=top width="82" height="19" style="color: #FFFFFF; font-family: Arial; font-size: 10px; text-decoration: none; font-weight: bold">

                <b>

                &nbsp;<font face="Arial" size="2" color="#ffffff"><a href="<?php echo "$htp_path"; ?>" style="text-decoration: none"><font color="#ffffff">News</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/events.php" style="text-decoration: none"><font color="#ffffff">Event</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/loc.php" style="text-decoration: none"><font color="#ffffff">Locations</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/info.php?game=1&about=1" style="text-decoration: none"><font color="#ffffff">About DDR</a><br>

		&nbsp;<a href="http://www.ddrei.com/media.php" style="text-decoration: none"><font color="#ffffff">Media</a><br>

                <br>

                <img border="0" src="<?php echo "$pic_path"; ?>/images/template/l_people.gif" width="78" height="16"><br>

                &nbsp;<a href="http://forums.ddrei.com/memberlist.php" style="text-decoration: none"><font color="#ffffff">Profiles</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/teams.php" style="text-decoration: none"><font color="#ffffff">Teams</a><br>

                &nbsp;<a href="http://forums.ddrei.com" style="text-decoration: none"><font color="#ffffff">Forums</a><br>

		<br>

                <img border="0" src="<?php echo "$pic_path"; ?>/images/template/l_info.gif" width="78" height="16"><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/info.php" style="text-decoration: none"><font color="#ffffff">Song Lists</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/articles.php" style="text-decoration: none"><font color="#ffffff">Articles</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/reviews.php" style="text-decoration: none"><font color="#ffffff">Reviews</a>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/dictionary.php" style="text-decoration: none"><font color="#ffffff">Dictionary</a>

                <br>

                <img border="0" src="<?php echo "$pic_path"; ?>/images/template/l_ddrei.gif" width="78" height="16"><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/contact.php" style="text-decoration: none"><font color="#ffffff">Contact</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/banners.php" style="text-decoration: none"><font color="#ffffff">Banners</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/polls.php" style="text-decoration: none"><font color="#ffffff">Past Polls</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/tm.php?tm=5" style="text-decoration: none"><font color="#ffffff">Tm 5</a><br>

                &nbsp;<a href="<?php echo "$htp_path"; ?>/faq.php" style="text-decoration: none"><font color="#ffffff">FAQ</a><br>

		</font>

		&nbsp;</TD>

              </tr>

              </TBODY>

             </TABLE></DIV>

            </TD>



          <TD align=left background="<?php echo "$pic_path"; ?>/images/template/bg2.gif" width=626 height=6 vAlign=top>

          	<TABLE border=0 cellPadding=0 cellSpacing=0 width=626>

              <TBODY>

              <TR>

                <TD align=left vAlign=top width=626>

                	<IMG border=0 height=6 src="<?php echo "$pic_path"; ?>/images/template/bg3.gif" width=626></TD>

              </TR>

              </TBODY>

             </TABLE>

<br>&nbsp&nbsp&nbsp

<?php echo $top_banner; ?>

	</TD>



        </TR>

        <TR>

          <TD align=left vAlign=top>

            <TABLE border=0 cellPadding=4 cellSpacing=0 width=626 hspace="0" vspace="0" height="1" style="border-collapse: collapse" bordercolor="#111111">

              <TBODY>

              <TR>

                <TD align=left background="<?php echo "$pic_path"; ?>/images/template/bg2.gif" height=1 vAlign=top width=501 rowspan="3"><br>









