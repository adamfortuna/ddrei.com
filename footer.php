
<br><br><p align=center><?php echo $bottom_banner; ?></p>



<TD align=left background="<?php echo "$pic_path"; ?>/images/template/bg.gif" width=109 vAlign=top cellpadding=1>

<font face="Arial" size="2">

<!--

	<p align=center><font face="Arial" size=2>

	<b><a href=http://store.yahoo.com/cgi-bin/clink?adux+srptHa+ignitionpad.html target="_blank">Redoctane Ignition Pad</a> Giveaway!<b>

	<FORM method=GET action=/scripts/email.php>

<TABLE cellspacing=0 border=0>

<tr valign=top>

<td align=center>

<font face="Arial" size=2><b>Name:

	<INPUT TYPE=text name=name size=13 maxlength=100 value="">

	<font face="Arial" size=2><b>Email:

	<INPUT TYPE=text name=email size=13 maxlength=100 value="">

	<INPUT type=submit name=submit VALUE="Signup"></td></tr></table>

	</form>

	<br><br></p>

-->



<?php if($main) include("scripts/poll.php"); ?>





	<p align=center><font face="Arial" size=2><br><br>

	<b>Link to DDRei.com!</b>

	<img src=<?php echo "$pic_path"; ?>/images/banners/ei2.gif><br><br>

	<img src=<?php echo "$pic_path"; ?>/images/banners/ei.gif>

	</p>



	<p align=center><font face="Arial" size=1>



	



<?php  if($main) { ?>

<p align=center>

 <font size=2><b>Links</b><br>

 <a href="http://store.yahoo.com/cgi-bin/clink?adux+srptHa+ddr.html"><img src="<?php echo $pic_path; ?>/images/store/banners/rologo88x31.gif"></a>

 </font>

</p>

<?php } ?>

<br>

<?php include($home_path."scripts/counter.php");

echo "<br><font size=1 face=Arial>Visitors since<br>October 8, 2001</font></p>";

 ?>

<!-- check to see if logged in.. if not put login form -->

<p align=center><font size=2 face=Arial>

<?php



	if($logged_in) echo "Logged in as <a href=http://forums.ddrei.com/profile.php?mode=viewprofile&u=$ddrei_id>$ddrei_username</a></font><br><font size=1 face=Arial><a href=http://forums.ddrei.com/login.php?logout=true>Logout?</a></font>";

	else echo "Not Logged in.<br><a href=http://forums.ddrei.com/login.php>Login?</a>";

?>

</p>

<font color=#93A2BF size=1><?php /* if(!$notacounter) { $display="Yes"; include("counter/counter.php"); } */ ?>





</TBODY></TABLE></TD></TR>

</TBODY></TABLE></TD></TR>





  <TR>

    <TD align=left vAlign=top width="727">

      <TABLE border=0 cellPadding=0 cellSpacing=0 width=700 hspace="0" vspace="0">

        <TBODY>

        <TR>

          <TD align=left vAlign=top width=92>

          <IMG alt="Lower left" border=0 height=32 src="<?php echo "$pic_path"; ?>/images/template/low_left.gif" width=91></TD>

          <TD align=left vAlign=top width=608><FONT face=Arial size=1>

          <img border="0" src="<?php echo "$pic_path"; ?>/images/template/bottom.jpg" width="627" height="32"></FONT><p></TD>

          </TR></TBODY></TABLE></TD></TR>

  <TR>

    <TD align=left vAlign=top width="700">

      <DIV align=left>

      <TABLE border=0 cellPadding=0 cellSpacing=0 width=700>

        <TBODY>

        <TR>

          <TD width=90></TD>

          <TD align=middle vAlign=center width=450><FONT face=Arial size=1>

          <a href="<?php echo "$htp_path"; ?>"><BR><font color="#000080">Home</font></a><font color="#000080"> |

          </font>



          <a href="<?php echo "$htp_path"; ?>/media.php"><font color="#000080">Media

          </font> </a><font color="#000080">| </font><a href="<?php echo "$htp_path"; ?>/about.php">

          <font color="#000080">About us</font></a><font color="#000080">

            | </font>

<a href="<?php echo "$htp_path"; ?>/copy.php"><font color="#000080">Copyright</font></a><font color="#000080"> |

          </font>

	<a href="<?php echo "$htp_path"; ?>/pp.php">

          <font color="#000080">Privacy Policy</font></a></TD>

          </TR></TBODY></TABLE></DIV></TD></TR></TBODY></TABLE></CENTER></DIV></FORM><br>



</BODY></HTML>

