<?php
	$main=1;
	include("header.php");
?>

<strong><font face="Arial" color="#000080">DDR East Invasion</font></strong><BR><BR>

<?php 
//MySQL-Connection
include("scripts/database.php");
if(!$db=@mysql_connect($forum_host,$forum_login,$forum_pass))
{
  die("Database error");
}

mysql_select_db($forum_database,$db);
 $sql = "SELECT topic_title, post_text, phpbb_topics.topic_id, topic_time, topic_replies, user_id, username 
	  	FROM phpbb_topics, phpbb_posts_text, phpbb_users, phpbb_posts 
	  	WHERE phpbb_topics.forum_id = 107 AND 
	  	phpbb_topics.topic_time=phpbb_posts.post_time and 
	  	phpbb_posts.post_id=phpbb_posts_text.post_id AND 
	  	phpbb_topics.topic_poster = phpbb_users.user_id 
	  	ORDER BY phpbb_topics.topic_id DESC LIMIT 5";

      $ergebnis = @mysql_query($sql,$db);
      while($r=@mysql_fetch_array($ergebnis))
      {
	      //format $r['post_text'] to include cut/readmore
	      $post_text=$r['post_text'];
	      if($cut=strpos($post_text, "[list"))
	      	$post_text=substr($post_text, 0, $cut)."<br><br><a href=http://forums.ddrei.com/viewtopic.php?t=".$r['topic_id'].">Read more...</a>";
	      $post_text=str_replace("\n", "<br>", $post_text);
	      
	     echo "<table cellspacing='1' cellpadding='0' bgcolor='#000000' width='98%'>
<tr>
<td>
<table cellspacing='4' cellpadding='0' bgcolor='#E1E7F3' width='100%' align='center'>
<tr>
<td><font face='verdana' size='3'><b>".$r['topic_title']."</b></font></td>
</tr>
<tr>
<td><font class='newshead'>Posted on ";
echo date("D, M d, Y", $r['topic_time'])." at ".date("g:i:s a", $r['topic_time'])." by <A HREF=http://forums.ddrei.com/profile.php?mode=viewprofile&u=".$r['user_id'].">".$r['username']."</A></font></td>
</tr>
<tr>
<td><hr noshade size='1' color='#000000'><font face='verdana' size='2'>".$post_text."</font></td>
</tr>
<tr>
<td ALIGN=CENTER><hr noshade size='1' color='#000000'>
<font class='body'>[ <A HREF=http://forums.ddrei.com/viewtopic.php?t=".$r['topic_id'].">Comments(".$r['topic_replies'].")</a> | <A HREF=http://forums.ddrei.com/viewforum.php?f=11>Read more news...</A> ]</FONT>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>";
      }
?>

<p align=center><font face=arial size=2><a href=http://forums.ddrei.com/viewforum.php?f=107>More News</a></p>

<?php include("footer.php"); ?>