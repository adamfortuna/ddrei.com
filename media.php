<?php
	
	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	$q=0; //query counter;
	
	session_start();
	function get_path($cat_id, $database)
	{
		if($cat_id==0)
		{
			$path[0]="Media";
			$path[1]="Media";
			return $path;
		}
		$k=0;
		while($cat_id!=0)
		{
			$query="SELECT cat_name, cat_parent FROM med_categories WHERE cat_id=$cat_id"; 
			global $q;
			$q++;
			$result = @mysql_db_query($database, $query);
			if($r=@mysql_fetch_array($result))
				{ $cat_name=$r["cat_name"]; $cat_parent=$r["cat_parent"]; }
			$path[$k]="<a href=media.php?cat_id=$cat_id>$cat_name</a>";
			$nopath[$k]="$cat_name";
			$k++;
			$cat_id=$cat_parent;
		}

		$t_path[0]="<a href=media.php>Media</a>";
		$t_path[1]="Media";
		$k--;
		for($j=$k; $j>=0; $j--)
		{
			if($j==0 && !$file) {
				$t_path[0].=" :: $path[$j]";
				$t_path[1].=" :: $nopath[$j]";
			}
			else {
				$t_path[0].=" :: $path[$j]";
				$t_path[1].=" :: $nopath[$j]";
			}
		}

		return $t_path;
	}

	function get_ext($file)
	{
		$lastdot=strrpos($file, ".")+1;
		$extention=substr($file, $lastdot);
		return $extention;
	}

	function get_image($ext)
	{
		if($ext=='asf') return "rar";
		if($ext=='avi') return "avi";
		if($ext=='exe') return "exe";
		if($ext=='jpg') return "jpg";
		if($ext=='mp3') return "mp3";
		if($ext=='rar') return "rar";
		if($ext=='ace') return "rar";
		if($ext=='wmv') return "wmv";
		if($ext=='zip') return "zip";
		if($ext=='mpg') return "wmv";
		if($ext=='mpeg') return "wmv";
		if($ext=='mov') return "wmv";
		if($ext=='asx') return "wmv";
		if($ext=='ram') return "wmv";
		if($ext=='rm') return "wmv";
		else return "no";
	}

	function mysql_timestamp_to_human($dt)
	{

		$yr=strval(substr($dt,0,4));
		$mo=strval(substr($dt,4,2));
		$da=strval(substr($dt,6,2));
		$hr=strval(substr($dt,8,2));
		$mi=strval(substr($dt,10,2));
		//$se=strval(substr($dt,12,2));

		return date("M. d, Y H:i", mktime ($hr,$mi,0,$mo,$da,$yr))." EST";
	}

	function output_tr($for_color, $on_color, $link, $height)
	{
		echo "\n<tr onclick=\"javascript:location.href='$link';\" onmouseout=\"this.style.backgroundColor='$for_color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='$on_color'; this.style.cursor = 'hand'; window.status = 'View more information.'; return true;\" bgcolor=#".$for_color;
			if($height!=0) echo " height=$height";
			echo ">";
	}

	function output_td($for_color, $on_color, $link, $height)
	{
		echo "\n<td onclick=\"javascript:location.href='$link';\" onmouseout=\"this.style.backgroundColor='$for_color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='$on_color'; this.style.cursor = 'hand'; window.status = 'View more information.'; return true;\" bgcolor=#".$for_color;
			if($height!=0) echo " height=$height";
			echo ">";
	}

	//end add comments

	//testing area


/*
	//say the contents of the cookies
	echo "DDrei_login: \"$ddrei_login\" <br>";
	$ddrei_login=explode(",", $ddrei_login);
	for($i=0; $i<3; $i++) echo "$i : $ddrei_login[$i] <br>";
	echo "Count: ".count($ddrei_login);
*/
	//inserting, updating and deleting -- final step

	if($edit)
		include("header.php");

	if($admin_panel)
	{
		include("scripts/database.php");
		$con = @mysql_connect($host, $login, $pass);

		//deleting a category
		if($del_cat)
		{
			$query="delete from med_categories where cat_id='$del_cat'"; $q++;
			$query2="delete from med_files where c_id='$del_cat'"; $q++;
		}
		//deleting a file
		else if($del_file)
		{
			$query="delete from med_files where file_id='$del_file'"; $q++;
		}
		//editing a cat
		else if($edit_cat)
		{
			if($cat_active) $cat_active=1;
			$query="Update med_categories set cat_name='$cat_name', cat_desc='$cat_desc', cat_parent='$cat_parent', cat_active='$cat_active' where cat_id=$edit_cat"; $q++;
		}
		//adding a folder
		else if($cat_name)
		{
			if($cat_active) $cat_active=1;
			$query="Insert into med_categories (cat_name, cat_desc, cat_parent, cat_active) values ('$cat_name', '$cat_desc', '$cat_parent', $cat_active)"; $q++;
		}

		//editing a file
		else if($edit_file)
		{
			if($file_active) $file_active=1;
			$query="Update med_files set file_active='$file_active', name='$file_name', creator='$creator', file='$file', pic='$pic', short_com='$short_com', com='$com', size='$size', c_id='$c_id', a_id='$a_id', t_id='$t_id' where file_id=$edit_file"; $q++;
		}
		// inserting a file
		else if($file_name)
		{
			if($file_active) $file_active=1;
			$query="Insert into med_files (file_active, name, creator, file, pic, short_com, com, size, c_id, a_id, t_id) values ('$file_active', '$file_name', '$creator', '$file', '$pic', '$short_com', '$com', '$size', '$c_id', '$a_id', '$t_id')"; $q++;
		}


		$result=mysql_db_query($database, $query);
		if($query2) $result=mysql_db_query($database, $query2);
		header("Location:http://www.ddrei.com/media.php");
	}
	else if($edit && ($add_cat || $edit_cat || $del_cat || $add_file || $edit_file || $del_file) && $ddrei_level)
	{

		//get the category list
		$query="SELECT cat_id, cat_name, cat_active, count(file_id) as tot_files 
			FROM med_categories 
			left outer join med_files on cat_id=c_id
			group by cat_name"; $q++;
		$result=mysql_db_query($database, $query);
		for($i=0; $i<mysql_num_rows($result); $i++)
		{
			$r=mysql_fetch_array($result);
			extract($r);

			$cat_ids[$i]=$cat_id;
			$cat_names[$i]=$cat_name;
			$cat_actives[$i]=$cat_active;
			$cat_files[$i]=$tot_files;
		}
		$cat_id=""; $cat_name="";

		if($del_cat)
		{
			//get the categories info
			$query="SELECT cat_name, cat_desc FROM med_categories where cat_id=$del_cat"; $q++;
			$result=mysql_db_query($database, $query);
			$r=@mysql_fetch_array($result);
			@extract($r);

			echo "Are you sure you want to delete this category?<br><b>$cat_name</b>: $cat_desc<br><form method=post action=media.php><input type=hidden name=admin_panel value=1><input type=hidden name=del_cat value=$del_cat><input type=submit name=submit value=Yes><br><br></form>";
		}
		else if($del_file)
		{
			//get the files info
			$query="SELECT name, short_com FROM med_files where file_id=$del_file"; $q++;
			$result=mysql_db_query($database, $query);
			$r=@mysql_fetch_array($result);
			@extract($r);
			echo "Are you sure you want to delete this file?<br><br><b>$name</b>: $short_com<br><form method=post action=media.php><input type=hidden name=admin_panel value=1><input type=hidden name=del_file value=$del_file><input type=submit name=submit value=Yes><br><br></form>";
		}

		//adding a file or adding a folder
		else if($add_cat || $edit_cat) //add_cat, edit_cat
		{
			if($add_cat==100) $add_cat=0;
			if($edit_cat==100) $edit_cat=0;
			if($del_cat==100) $del_cat=0;

			if($edit_cat>0) //get the general info
			{
				$query="SELECT * FROM med_categories where cat_id=$edit_cat"; $q++;
				$result=mysql_db_query($database, $query);
				$r=@mysql_fetch_array($result);
				@extract($r);
			}

			//output the category info
			echo "<strong><font face=Arial color=#000080 size=2><a href=media.php>Media</a> :: Add/Edit a Category</font></strong><BR><br>";
			echo "<form method=post action=media.php><input type=hidden name=admin_panel value=1><input type=hidden name=edit_cat value=$cat_id><table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=5 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>Add/Edit a Category</b></td></tr>";
			echo "<tr><td><b>Category Name:</b></td><td><input type=text name=cat_name value=\"$cat_name\" size=40></td></tr>";
			echo "<tr><td><b>Category Description:</b></td><td><textarea name=cat_desc cols=45 rows=2>$cat_desc</textarea></td></tr>";
			echo "<tr><td><b>Category Parent:</b></td><td><select name=cat_parent><option  value=0>Main Page";
			for($i=0; $i<count($cat_ids); $i++)
			{
				echo "<option value=$cat_ids[$i] ";
				if($cat_parent==$cat_ids[$i]) echo "selected";
				echo ">$cat_names[$i] ($cat_files[$i]) ";
				if($cat_actives[$i]==0) echo "(inactive)";
				echo "\n";
			}
			echo "</option></td></tr>";
			
			echo "<tr><td><b>Active?:</b></td><td><input type=checkbox name=cat_active ";
			if($cat_active==1) echo "checked";
			echo "></td></tr>";
			
			echo "<tr><td colspan=2><br><input type=submit></td></tr></table>";

		}
		else //editing a file  file_id (if editing/deleting), add_file, edit_file, delete_file
		{
			//addition list
			$query="SELECT add_id, add_name FROM med_addition order by add_name"; $q++;
			$result=mysql_db_query($database, $query);
			for($i=0; $i<mysql_num_rows($result); $i++)
			{
				$r=mysql_fetch_array($result);
				extract($r);

				$add_ids[$i]=$add_id;
				$add_names[$i]=$add_name;
			}

			//type list
			$query="SELECT type_id, type_name FROM med_type order by type_name"; $q++;
			$result=mysql_db_query($database, $query);
			for($i=0; $i<mysql_num_rows($result); $i++)
			{
				$r=mysql_fetch_array($result);
				extract($r);

				$type_ids[$i]=$type_id;
				$type_names[$i]=$type_name;
			}

			//get the
			if($edit_file)
			{
				$query="SELECT * FROM med_files where file_id=$edit_file"; $q++;
				$result=mysql_db_query($database, $query);
				$r=mysql_fetch_array($result);
				extract($r);
			}
			//output the change form
			echo "<strong><font face=Arial color=#000080 size=2><a href=media.php>Media</a> :: Add/Edit a File</font></strong><BR><br>";
			echo "<form method=post action=media.php><input type=hidden name=admin_panel value=1><input type=hidden name=edit_file value=$edit_file><input type=hidden name=add_file value=$add_file><table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=5 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>Add/Edit a File</b></td></tr>";
			echo "<tr><td><b>File Name:</b></td><td><input type=text name=file_name value=\"$name\" size=50></td></tr>";
			echo "<tr><td><b>Creator:</b></td><td><input type=text name=creator value=\"$creator\" size=50></td></tr>";
			echo "<tr><td><b>File Location:</b></td><td><input type=text name=file value=\"$file\" size=50></td></tr>";
			echo "<tr><td><b>Picture path:</b></td><td><input type=text name=pic value=\"$pic\" size=50></td></tr>";
			echo "<tr><td><b>Short comments:</b></td><td><input type=text name=short_com value=\"$short_com\" size=50></td></tr>";
			echo "<tr><td><b>Full Comments:</b></td><td><textarea name=com cols=40 rows=5>$com</textarea></td></tr>";
			echo "<tr><td><b>Size:</b></td><td><input type=text name=size value=\"$size\" size=50></td></tr>";
			echo "<tr><td><b>Active:</b></td><td><input type=checkbox name=file_active ";
			if($file_active) echo "checked";
			echo "></td></tr>";
			echo "<tr><td><b>Category:</b></td><td><select name=c_id><option  value=0>Main Page";
			
			for($i=0; $i<count($cat_ids); $i++)
			{
				echo "<option value=$cat_ids[$i] ";
				if(($c_id==$cat_ids[$i]) || ($add_file==$cat_ids[$i])) echo "selected";
				echo ">$cat_names[$i] ($cat_files[$i]) ";
				if($cat_actives[$i]==0) echo "(inactive)";
				echo "\n";
			}
			echo "</option></td></tr>";

			echo "<tr><td><b>Type:</b></td><td><select name=t_id>";
			for($i=0; $i<count($type_ids); $i++)
			{
				echo "<option value=$type_ids[$i] ";
				if($t_id==$type_ids[$i]) echo "selected";
				echo ">$type_names[$i]\n";
			}
			echo "</option></td></tr>";

			echo "<tr><td><b>Additional info:</b></td><td><select name=a_id>";
			for($i=0; $i<count($add_ids); $i++)
			{
				echo "<option value=$add_ids[$i] ";
				if($a_id==$add_ids[$i]) echo "selected";
				echo ">$add_names[$i]\n";
			}
			echo "</option></td></tr>";

			echo "<tr><td colspan=2><br><input type=submit></td></tr></table>";

		}
		$footer=1; $noheader=1;
	}


	//main info for a given file
	else if($file_id)
	{
		// get the main data
		$query = "select name, creator, file, pic, com, rating, votes, downloads, date, size, type_name, cat_name, add_name, a_id, c_id, t_id, type_name from med_addition, med_categories, med_files, med_type WHERE med_files.a_id=med_addition.add_id AND med_files.t_id=med_type.type_id AND med_files.c_id=med_categories.cat_id AND med_files.file_id=$file_id"; $q++;
		$result = @mysql_db_query($database, $query);
		if($r=@mysql_fetch_array($result)) 
		{
			extract($r);
			$date2=$date;
			$date=mysql_timestamp_to_human($date);

			// get the path to the current folder
			$path=get_path($c_id, $database);

			//if the file is on DDRei get the file size for it
			//also if the file is on ddrei, it will stream it out
			if((substr_count($file, "www.ddrei.com")) || (substr_count($file, "ddrei.com")))
			{
				//exp:  ftp://dwi:files@64.38.123.225/3rd/sungsuk.ace
				//exp2: ftp://video:files@64.38.123.126/other/newscrap.mpeg
				//path: /usr/local/plesk/apache/vhosts/ddrei.com/web_users/dwi/3rd/sungsuk.ace
				$local=0;
				$first_slash=strpos($file, "/", 7);
				$last_slash=strrpos($file, "/");
				$length=$last_slash-$first_slash-1;
				$last_slash++; $first_slash++;
				$file_path=substr($file, $first_slash, $length);
				$file_name=substr($file, $last_slash);

				//get the ftp login name
				$last_semi=strrpos($file, ":");
				$length=$last_semi-6;
				$file_login=substr($file, 6, $length);

				//put it into paths
				if($file_path)
				{
					$filepath="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/$file_name";
					$file_path="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/";
				}
				else
				{
					$filepath="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_name";
					$file_path="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/";
				}

				//echo "filepath: $filepath <br>path: $file_path<br";
				//file size
				$size = @filesize($filepath);

				//test to see if everything works
			//	echo "filepath: $filepath <br>file_path: $file_path<br>file_login: $file_login<br>filesize:". filesize($filepath);
			}
			else
			{
				$local=0;
				// get the file's path
				$last_slash=strrpos($file, "/")+1;
				$file_path=substr($file, 0, $last_slash);
				$file_name=substr($file, $last_slash);
			}
			// get the file extention
			$extention=get_ext($file);

			$filesize=$size/1024;

			$referer=ereg_replace( "\?.", "",$HTTP_REFERER);
			$thisfile= "http://$HTTP_HOST$PHP_SELF?file_id=$file_id";
			//echo " phpself: $PHP_SELF thisfile: $thisfile referrer: $referer htp_ref: $HTTP_REFERER";

			if(!$dl)
			{
				$type[1]=1;
				if(isset($submit) && isset($rate))
				{
					 $votes++;
					 $rating+=$rate;
					 $query="UPDATE med_files set votes=$votes, rating=$rating, date=$date2 WHERE file_id=$file_id";  $q++;
					 $result = @mysql_db_query($database, $query);
					 setcookie("ddrei_media", $ddrei_media."id_".$file_id, time()+5000000);
					 $ddrei_media.="id_".$file_id;
					 $rated=1;
				}
				if($votes) $avg_rating=@substr(($rating/$votes), 0, 3);
				else { $votes=0; $avg_rating=0; }
				//		 echo "file name: $file_name<br>file path: $file_path<br>total path: $file<br> http_referer: 	$HTTP_REFERER<br>referer: $referer<br>Local: $local";
		 	}
			else
			{
				//if not linking from a ddrei page
				if (!$ddrei_dl) $type[1]=1;
				else
				{
					//increase the total downloads by 1
					$downloads++;
					$query="UPDATE med_files SET downloads='$downloads', date=$date2 WHERE file_id=$file_id"; $q++;
					$result = @mysql_db_query($database, $query);
					header("Location:$file");
				}
			}
		}
	}
	// assume media.php?type_id=1&start=0 (ddr) or add_type
	// of media.php?top=1 or media.php?new=1
	else if($type_id || $add_id || $top || $new)
	{
		if(!$start) $start=0;
		if($type_id) {
			$query = "select file_id, name, file, add_id, type_id, add_name, type_name, cat_name, c_id, short_com, downloads, date from med_files, med_addition, med_categories, med_type WHERE med_files.file_active=1 AND med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id AND med_files.t_id=$type_id order by med_files.pin, med_files.downloads desc LIMIT $start, 20"; $q++; }
		else if($add_id) {
			$query = "select file_id, name, file, add_id, type_id, add_name, type_name, cat_name, c_id, short_com, downloads, date from med_files, med_addition, med_categories, med_type WHERE med_files.file_active=1 AND med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id AND med_files.a_id=$add_id order by med_files.pin, med_files.downloads desc LIMIT $start, 20"; $q++; }
		else if($new) {
			$query = "select file_id, name, file, add_id, type_id, add_name, type_name, cat_name, c_id, short_com, downloads, date from med_files, med_addition, med_categories, med_type WHERE med_files.file_active=1 AND med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id order by med_files.date desc, med_files.downloads desc, med_files.name LIMIT $start, 20"; $q++; }
		else if($top) {
			$query = "select file_id, name, file, add_id, type_id, add_name, type_name, cat_name, c_id, short_com, downloads, date from med_files, med_addition, med_categories, med_type WHERE med_files.file_active=1 AND med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id order by med_files.downloads desc, med_files.name LIMIT $start, 20"; $q++; }
		$result = @mysql_db_query($database, $query);
		$k=0;
		while($s=@mysql_fetch_array($result))
		{
			$f_id[$k]=$s["file_id"];
			$f_name[$k]=$s["name"];
			$f_add_id[$k]=$s["add_id"];
			$f_type_id[$k]=$s["type_id"];
			$f_add_name[$k]=$s["add_name"];
			$f_type_name[$k]=$s["type_name"];
			$f_cat_name[$k]=$s["cat_name"];
			$f_cat_id[$k]=$s["c_id"];
			$f_short_com[$k]=$s["short_com"];
			$f_downloads[$k]=$s["downloads"];
			$f_date[$k]=mysql_timestamp_to_human($s["date"]);
			$file=$s["file"];
			$f_img[$k]=get_image(get_ext($file));
			$k++;
		}
		if($type_id)
		{
			$path[0]="<a href=media.php>Media</a> -> $f_type_name[0]";
			$title="Media ---> $f_type_name[0]";
			//find how many files there are total under this given type
			$query = "select file_id from med_files WHERE t_id=$type_id AND file_active=1"; $q++;
			$result = @mysql_db_query($database, $query);
			$total_types=@mysql_num_rows($result);
		}
		else if($add_id)
		{
			$path[0]="<a href=media.php>Media</a> -> $f_add_name[0]";
			$title="Media ---> $f_add_name[0]";
			//find how many files there are total under this given type
			$query = "select file_id from med_files WHERE a_id=$add_id AND AND file_active=1"; $q++;
			$result = @mysql_db_query($database, $query);
			$total_types=@mysql_num_rows($result);
		}
		else if($top)
		{
			$path[0]="<a href=media.php>Media</a> -> Top Downloaded";
			$title="Media ---> Top Downloaded";
			//find how many files there are total under this given type
			$query = "select file_id med_from files WHERE file_active=1"; $q++;
			$result = @mysql_db_query($database, $query);
			$total_types=@mysql_num_rows($result);
			$display_date=1;
		}
		else if($new)
		{
			$path[0]="<a href=media.php>Media</a> -> Newest Files";
			$title="Media ---> Newest Files";
			//find how many files there are total under this given type
			$query = "select file_id from med_files WHERE file_active=1"; $q++;
			$result = @mysql_db_query($database, $query);
			$total_types=@mysql_num_rows($result);
			$display_date=1;
		}
		$type[2]=1;
	}
	else if($rated)
	{
		if(!$start) $start=0;
		$query = "select file_id, name, file, add_id, type_id, add_name, type_name, cat_name, c_id, short_com, downloads, date, avg(med_files.rating/(med_files.votes)) as avger from med_files, med_addition, med_categories, med_type WHERE med_files.file_active=1 AND med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id AND med_files.votes>0 group by name order by avger desc, med_files.downloads desc, med_files.name LIMIT $start, 20 "; $q++;
		$result = @mysql_db_query($database, $query);
		$k=0;
		while($s=@mysql_fetch_array($result))
		{
			$f_id[$k]=$s["file_id"];
			$f_name[$k]=$s["name"];
			$f_add_id[$k]=$s["add_id"];
			$f_type_id[$k]=$s["type_id"];
			$f_add_name[$k]=$s["add_name"];
			$f_type_name[$k]=$s["type_name"];
			$f_cat_name[$k]=$s["cat_name"];
			$f_cat_id[$k]=$s["c_id"];
			$f_short_com[$k]=$s["short_com"];
			$f_downloads[$k]=$s["downloads"];
			$f_avger[$k]=$s["avger"];
			$f_date[$k]=mysql_timestamp_to_human($s["date"]);
			$file=$s["file"];
			$f_img[$k]=get_image(get_ext($file));
			$k++;
		}
		$path[0]="<a href=media.php>Media</a> -> Top Rated";
		$title="Media ---> Top Rated";
		//find how many files there are total under this given type
		$query = "select file_id from med_files WHERE file_active=1 AND votes>0"; $q++;
		$result = @mysql_db_query($database, $query);
		$total_types=@mysql_num_rows($result);

		$type[4]=1;
	}
	else if($dwi_faq)
	{
		$type[5]=1;
		$path[0]="<a href=media.php>Media</a> -> DWI FAQ";
	}
	else if($media_faq)
	{
		$type[6]=1;
		$path[0]="<a href=media.php>Media</a> -> Media FAQ";
	}
	else // cat_id=
	{
		if(!$cat_id) $cat_id=0;
		//get the folders to display
		$query = "select * from med_categories WHERE cat_parent=$cat_id ORDER BY cat_name"; $q++;
		$result = @mysql_db_query($database, $query);
		$k=0;
		while($s=@mysql_fetch_array($result))
		{
			$c_id[$k]=$s["cat_id"];
			$c_name[$k]=$s["cat_name"];
			$c_desc[$k]=$s["cat_desc"];
			$c_active[$k]=$s["cat_active"];
			$k++;
		}

		// get all the files to display
		$query = "select file_active, file_id, name, file, add_id, type_id, add_name, type_name, cat_name, short_com, downloads, date, size from med_files, med_addition, med_categories, med_type WHERE med_files.c_id=med_categories.cat_id AND med_files.t_id=med_type.type_id AND med_files.a_id=med_addition.add_id AND med_files.c_id=$cat_id order by med_files.pin, med_addition.add_id, med_files.name"; $q++;
		$result = @mysql_db_query($database, $query);
		$k=0; $foldersize=0;
		while($s=@mysql_fetch_array($result))
		{
			$f_id[$k]=$s["file_id"];
			$f_name[$k]=$s["name"];
			$file=$s["file"];
			$f_add_id[$k]=$s["add_id"];
			$f_type_id[$k]=$s["type_id"];
			$f_add_name[$k]=$s["add_name"];
			$f_type_name[$k]=$s["type_name"];
			$f_cat_name[$k]=$s["cat_name"];
			$f_short_com[$k]=$s["short_com"];
			$f_downloads[$k]=$s["downloads"];
			$f_size[$k]=$s["size"];
			$f_active[$k]=$s["file_active"];
			$downloads=$f_downloads[$k];

			$f_date[$k]=mysql_timestamp_to_human($s["date"]);
			$file=$s["file"];
			$f_img[$k]=get_image(get_ext($file));
			$k++;

			/* 
			//get the size of the file and add it to the total
			if((substr_count($file, "www.ddrei.com")) || (substr_count($file, "ddrei.com")))
			{
				//exp:  ftp://dwi:files@64.38.123.225/3rd/sungsuk.ace
				//exp2: ftp://video:files@64.38.123.126/other/newscrap.mpeg
				//path: /usr/local/plesk/apache/vhosts/ddrei.com/web_users/dwi/3rd/sungsuk.ace
				$local=0;
				$first_slash=strpos($file, "/", 7);
				$last_slash=strrpos($file, "/");
				$length=$last_slash-$first_slash-1;
				$last_slash++; $first_slash++;
				$file_path=substr($file, $first_slash, $length);
				$file_name=substr($file, $last_slash);

				//get the ftp login name
				$last_semi=strrpos($file, ":");
				$length=$last_semi-6;
				$file_login=substr($file, 6, $length);

				//put it into paths
				$filepath="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/$file_name";
				$file_path="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/";

				//file size
				$size = @filesize($filepath);
				$total=$size*$downloads;
				$foldersize=$foldersize+$total;
				//test to see if everything works
			//	echo "filepath: $filepath <br>file_path: $file_path<br>file_login: $file_login<br>filesize:". filesize($filepath);
			}
			else
			{
				$total=$f_size*$downloads;
				$foldersize=$foldersize+$total;
			}
			*/
		}
		$foldersize=$foldersize/1024000000;
		$type[0]=1;
		$path=get_path($cat_id, $database);

		//get the total bandwith and size of database
		$query = "select name, file, downloads, size from med_files WHERE 1 order by downloads desc"; $q++;
		$result = @mysql_db_query($database, $query);
		$totalsize=0;
		$filesize=0;
		$totalfiles=@mysql_num_rows($result);
		while($r = @mysql_fetch_array($result))
		{
			$file=$r["file"];
			$downloads=$r["downloads"];
			$size=$r["size"];
			$name=$r["name"];

			//parse the file to get the file path

			if((substr_count($file, "www.ddrei.com")) || (substr_count($file, "ddrei.com")))
			{
				//exp:  ftp://dwi:files@64.38.123.225/3rd/sungsuk.ace
				//exp2: ftp://video:files@64.38.123.126/other/newscrap.mpeg
				//path: /usr/local/plesk/apache/vhosts/ddrei.com/web_users/dwi/3rd/sungsuk.ace
				$local=0;
				$first_slash=strpos($file, "/", 7);
				$last_slash=strrpos($file, "/");
				$length=$last_slash-$first_slash-1;
				$last_slash++; $first_slash++;
				$file_path=substr($file, $first_slash, $length);
				$file_name=substr($file, $last_slash);

				//get the ftp login name
				$last_semi=strrpos($file, ":");
				$length=$last_semi-6;
				$file_login=substr($file, 6, $length);

				//put it into paths
				$filepath="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/$file_name";
				$file_path="/usr/local/plesk/apache/vhosts/www.ddrei.com/web_users/$file_login/$file_path/";

				//file size
				$size = @filesize($filepath);
			}
			$total=$size*$downloads;
			$totalsize=$totalsize+$total;
			$filesize=$size+$filesize;
			//test to see if everything works
			//	echo "filepath: $filepath <br>file_path: $file_path<br>file_login: $file_login<br>filesize:". filesize($filepath);
		}
		$totalsize=($totalsize/1024000000)+5650;
		$filesize=$filesize/1024000;
	}

	$image="news_hdr.jpg";
	if(count($type) && !$noheader) include("header.php");
	if(isset($logged_in) && isset($submit) && !$rated)
	{
		$query = "INSERT INTO med_coms (c_id, c_auth, c_title, c_com) VALUES ('$id', '$ddrei_id', '$ddrei_title', '$ddrei_com')"; $q++;
		$result = mysql_query($query);
	}

?>
<script language="JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<?php

	if(count($type)) echo "<strong><font face=Arial color=#000080 size=2>$path[0]</font></strong><BR><br>";

	//echo "login info: $ddrei_login";

	if($con)
	{
		$bgcolor1="FFFFFF";
		$bgcolor2="EEF1F5";
		$ocolor="E4E9F2";
		//  Main
		if($type[0])
		{
			$cats=0; $files=0;
			//display items before the table
			echo "<p align=right><font face=\"Arial, verdana\" size=1>Total Files in Database: $totalfiles<br>Database Size: ".substr($filesize, 0, 5)." mb<br>Total Served Since 03.08.02: ".substr($totalsize, 0, 7)." gb<br>Total Served in this Folder: ".substr($foldersize, 0, 6)." gb</font></p>";
			//start the table
			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=5 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>$path[1]</b></td></tr>";
			if(count($f_id)) $colspan=3;
			else $colspan=1;
			// output the folders
			for($j=0; $j<count($c_id); $j++)
			{
				if($c_active[$j]==1 || $ddrei_level)
				{
					//get the extra info if it will be showing up
					//gets the total number of folders inside the current one
					$que="select cat_id from med_categories where ";
					if($ddrei_level==0) $que="$que cat_active=1 AND ";
					$que="$que cat_parent=$c_id[$j]"; $q++;
					$res = @mysql_db_query($database, $que);
					$c_folder_count[$j]=mysql_num_rows($res)+1;
				
					//echo $que."<br>";
					$final="c_id=$c_id[$j]";
					while($r=@mysql_fetch_array($res))
						$final="$final or c_id=".$r["cat_id"];
				
					$que="select file_id from med_files where ";
					if($ddrei_level==0) $que="$que file_active=1 AND (";
					$que="$que $final"; $q++;
					if($ddrei_level==0) $que="$que)";
				
					$res = @mysql_db_query($database, $que);
					$c_file_count[$j]=@mysql_num_rows($res);

					if(!$flag) {
						output_tr($bgcolor2, $ocolor, "media.php?cat_id=$c_id[$j]", 40);
						$flag=1;
					}
					else {
						output_tr($bgcolor1, $ocolor, "media.php?cat_id=$c_id[$j]", 40);
						$flag=0;
					}
					echo "<td align=center valign=middle><img src=http://www.ddrei.com/images/media/folder.gif border=0></td><td valign=middle><font face=\"Arial, verdana\" size=2>&nbsp&nbsp&nbsp&nbsp<a href=media.php?cat_id=$c_id[$j]>";
				
					if($c_active[$j]==0 && $ddrei_level>0) echo "<b>";
					echo "$c_name[$j]";
					if($c_active[$j]==0 && $ddrei_level>0) echo "</b>";
				
					echo "</a></font><br><font face=\"Arial, verdana\" size=1><i>&nbsp&nbsp $c_desc[$j]</i></font></td><td align=center width=75 colspan=$colspan><font face=\"Arial, verdana\" size=1>$c_file_count[$j] file(s) in <br>$c_folder_count[$j] folder(s)</font></td></tr>";
					$cats++;
				}
			}
			// output the files
			if(count($f_id))
				echo "<tr><td align=center>&nbsp</td><td align=center><font face=\"Arial, verdana\" size=2><b>File Name</b></font>	</td><td align=center><font face=\"Arial, verdana\" size=2><b>File Type</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>Genre</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>&nbsp Dls &nbsp</b></font></td></tr>";
			for($j=0; $j<count($f_id); $j++)
			{
				if($f_active[$j]==1 || $ddrei_level)
				{
					if(!$flag) {
						output_tr($bgcolor2, $ocolor, "media.php?file_id=$f_id[$j]", 25);
						$flag=1;
					}
					else {
						output_tr($bgcolor1, $ocolor, "media.php?file_id=$f_id[$j]", 25	);
						$flag=0;
					}
					echo "<td align=center valign=middle><a href=media.php?file_type=$f_img[$j]><img src=http://www.ddrei.com/images/media/".$f_img[$j].".gif border=0></a></td><td valign=middle><font face=\"Arial, verdana\" size=2>&nbsp&nbsp&nbsp&nbsp<a href=media.php?file_id=$f_id[$j]>";
					if($f_active[$j]==0 && $ddrei_level>0) echo "<b>";
					echo "$f_name[$j]";
					if($f_active[$j]==0 && $ddrei_level>0) echo "</b>";
					echo "</a></font>";
					if($f_short_com[$j]) echo "<br><font face=\"Arial, verdana\" size=1>&nbsp&nbsp<i> $f_short_com[$j]</i></font>";
					echo "</td><td align=center><font face=\"Arial, verdana\" size=1><a href=media.php?add_id=$f_add_id[$j]>$f_add_name[$j]</a></font></td><td align=center><font face=\"Arial, verdana\" size=1><a href=media.php?type_id=$f_type_id[$j]>$f_type_name[$j]</a></font></td><td align=center><font face=\"Arial, verdana\" size=1>$f_downloads[$j]</font></td></tr>\n";
					$files++;
				}
			}
			if(!$cats && !$files)
			{
				output_tr($bgcolor2, $ocolor, "media.php", 50	);
				echo "<td align=center colspan=5><font face=\"Arial, verdana\" size=2><i>Sorry, there are no files in the current directory</i></font></td></tr>";
			}
			echo "</table>";

			if($cat_id==0) $cat_id=100;
			if($ddrei_level==1)
			{
				echo "<p align=center><b>Admin</b><br><a href=media.php?edit=1&add_cat=$cat_id>Add a category</a>";
				if($cat_id!=100) echo " :: <a href=media.php?edit=1&edit_cat=$cat_id>Edit this category</a> :: <a href=media.php?edit=1&del_cat=$cat_id>Delete this Category</a><br><a href=media.php?edit=1&add_file=$cat_id>Add a file here</a>";
				echo "<br><small><i>Hidden files and folders appear in bold.</small></p>";
			}

		}
		// file_id=324
		else if($type[1])
		{
			//either a mod is logged in or the file is public
			if(($file_active==0 && $ddrei_level) || $file_active=1)
			{
				// If there's a picture about the current item
				if($pic) {
					echo "<p align=center><table border=0><tr><td><img src=$pic border=0></td></tr></table></p>";
				}
				echo "<form method=POST action=media.php?file_id=$file_id><input type=hidden name=file_id value=$file_id><table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=5 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>$name</b></td></tr>";

				echo "<tr height=50><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>File Name:</b><br>&nbsp&nbsp $name</td><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Date Added:</b><br>&nbsp&nbsp $date</td></tr>";
				echo "<tr height=25><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Extention:</b> $extention</td><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Downloads:</b> $downloads</td></tr>";
				echo "<tr height=25><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Creator:</b> ";
				if($creator) echo $creator;
				else echo "(Not yet implemented)";
				echo "</td>";
				output_td($bgcolor1, $ocolor, "media.php?add_id=$a_id", 0);
				echo "<font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Type:</b> <a href=media.php?add_id=$a_id>$add_name</a></td></tr><tr height=25><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>File Size:</b> ";
				$filesize2=substr($filesize, 0, 7);
				if($size) echo "$filesize2 kb";
				else if(!$size) echo "Unknown";
				output_td($bgcolor1, $ocolor, "media.php?type_id=$t_id", 0);
				echo "<font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Genre:</b> <a href=media.php?type_id=$t_id>$type_name</a></td></tr>";
				echo "<tr><td><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Rating:</b> $avg_rating in $votes votes.</td>";
				if(substr_count($ddrei_media, "id_".$file_id)) echo "<td>&nbsp</td>";
				else echo "<td valign=middle>&nbsp&nbsp<font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Vote:</b> <select size=1 name=rate><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option></select>&nbsp&nbsp<input type=submit value=\"Rate It\" name=submit></form></td>";

				echo "</tr>";
	
				if($com) echo "<tr height=25><td colspan=2><font face=\"Arial, verdana\" size=2>&nbsp&nbsp<b>Comments:</b>".stripslashes(str_replace("\n", "<br>", $com))."</td></tr>";
				output_tr($bgcolor1, $ocolor, "media.php?file_id=$file_id&dl=1", 0);
				echo "<td colspan=2 align=center><font face=\"Arial, verdana\" size=2><a href=media.php?file_id=$file_id&dl=1>Download here</a></td></tr><tr><td colspan=2 align=center>";

				echo "<small><i>Please right click and select \"save target as\" on the above link.</i></small></td></tr></table><br><br>";
			}

			if($ddrei_level>0)
			{
				echo "<p align=center><b>Admin</b><br><a href=media.php?edit=1&edit_file=$file_id>Edit this file</a> :: <a href=media.php?edit=1&del_file=$file_id>Delete this file</a></p>";
			}


		}
		// for media.php?type_id=1 or add_id=1
		// new=1 or top=1
		else if($type[2])
		{
			//start the table
			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=6 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>$title</b></td></tr><tr><td></td><td></td><td align=center><font face=\"Arial, verdana\" size=2><b>Filename</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>";
				if($type_id || $new || $top) echo "File Type";
				else echo "Genre";
				echo "</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>Date Added</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>&nbspDls&nbsp</b></font></td></tr>";
			// output the files
			for($j=0; $j<count($f_id); $j++)
			{
				if(!$flag) {
					output_tr($bgcolor1, $ocolor, "media.php?file_id=$f_id[$j]", 0);
					$flag=1;
				}
				else {
					output_tr($bgcolor2, $ocolor, "media.php?file_id=$f_id[$j]", 0);
					$flag=0;
				}

				$place=$j+$start+1;
				echo "<td width=20 align=center><font face=\"Arial, verdana\" size=2>$place</font></td><td align=center width=20><img src=http://www.ddrei.com/images/media/".$f_img[$j].".gif border=0></td><td height=30><font face=\"Arial, verdana\" size=2>&nbsp&nbsp&nbsp&nbsp<a href=media.php?file_id=$f_id[$j]>$f_name[$j]</a></font><br><font face=\"Arial, verdana\" size=1>&nbsp&nbsp <i>$f_short_com[$j]</i></font></td><td align=center><font face=\"Arial, verdana\" size=1><a href=media.php?";
				if($type_id || $new || $top) echo "add_id=$f_add_id[$j]>$f_add_name[$j]";
				else echo "type_id=$f_type_id[$j]>$f_type_name[$j]";
				echo "</a></font></td><td align=center><font face=\"Arial, verdana\" size=1>$f_date[$j]</font></td><td align=center><font face=\"Arial, verdana\" size=1>$f_downloads[$j]</font></td></tr>\n";
			}
			echo "</table><p align=center>";
			$start=0;
			$page=1;
			while($total_types>0)
			{
				echo "<a href=media.php?";
				if($type_id) echo "type_id=$type_id";
				else if($add_id) echo "add_id=$add_id";
				else if($top) echo "top=1";
				else if($new) echo "new=1";
				echo "&start=$start>[-$page-]</a> ";
				$total_types=$total_types-20; $start=$start+20; $page++;
			}
			echo "</p>";
		}
		// Bad referrer
		else if($type[3])
		{
		}
		//top rated files
		else if($type[4])
		{
			//start the table
			echo "<table border=1 cellpadding=0 cellspacing=0 style=\"border-collapse: collapse;  \" bordercolor=#C0C0C0 width=495><tr bgcolor=><td colspan=6 width=495 style=\"border-left:1px solid #C0C0C0; border-right-style:solid; border-right:1px solid #C0C0C0; border-top-style:solid; border-top-width:1; border-bottom-style:solid; border-bottom-width:1\" valign=top bgcolor=#E1E7F3 align=center><b>$title</b></td></tr><tr><td></td><td></td><td align=center><font face=\"Arial, verdana\" size=2><b>Filename</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>File Type</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>&nbspDls&nbsp</b></font></td><td align=center><font face=\"Arial, verdana\" size=2><b>&nbspAvg.&nbsp</b></font></td></tr>\n";
			// output the files
			for($j=0; $j<count($f_id); $j++)
			{
				if(!$flag) {
					output_tr($bgcolor1, $ocolor, "media.php?file_id=$f_id[$j]", 0);
					$flag=1;
				}
				else {
					output_tr($bgcolor2, $ocolor, "media.php?file_id=$f_id[$j]", 0);
					$flag=0;
				}

				$place=$j+$start+1;
				echo "<td width=20 align=center><font face=\"Arial, verdana\" size=2>$place</font></td><td align=center width=20><img src=http://www.ddrei.com/images/media/".$f_img[$j].".gif border=0></td><td height=30><font face=\"Arial, verdana\" size=2>&nbsp&nbsp&nbsp&nbsp<a href=media.php?file_id=$f_id[$j]>$f_name[$j]</a></font><br><font face=\"Arial, verdana\" size=1>&nbsp&nbsp <i>$f_short_com[$j]</i></font></td><td align=center><font face=\"Arial, verdana\" size=1><a href=media.php?add_id=$f_add_id[$j]>$f_add_name[$j]</a></font></td><td align=center><font face=\"Arial, verdana\" size=1>$f_downloads[$j]</font></td><td align=center><font face=\"Arial, verdana\" size=1>".substr($f_avger[$j], 0, 4)."</font></td></tr>\n";
			}
			echo "</table><p align=center>";
			$start=0;
			$page=1;
			while($total_types>0)
			{
				echo "<a href=media.php?rated=1";
				echo "&start=$start>[-$page-]</a> ";
				$total_types=$total_types-20; $start=$start+20; $page++;
			}
			echo "</p>";
		}
		//dwi FAQ
		else if($type[5])
		{
			echo "<p><b>What's an ACE file?</b><br>ACE is a simple file compression and packaging utility.  It enables us to offer mulitple files as one download, just like .zip or .rar.  If you have <a href=http://www.winrar.com>Winrar</a> installed, you will be able to 'un-ace' files.  If not install it or <a href=http://www.winace.com>WinAce</a>.<br><br>
			<b>I can open the file, but how do i install it with DWI?</b><br>After opening the Ace file, you will see a directory called DWI.  This assumed that your DWI directory is also called DWI. If your DWI directory is in c:/dwi, then simply unace the file (right after opening) into c:/.  It will then add the files to the correct places in the dwi directory.<br><br>
			<b>I have a problem getting the DWI files to work...</b><br>All files on the site have been tested to work.  This probably means that something is wrong with your DWI installation.  You can try reinstalling it if you have trouble.";
		}
		//media FAQ
		else if($type[6])
		{
			echo "<p><b>Why can't I download a file?</b><br>There are 2 reasons why you might not be able to download something.. 1) We messed up and gave the wrong path, and the filesize of the file doesn't show (rare) or 2) You are downloading the max number of files.  Due to the type of authentication used in the media section and problems with IE/Netscape, it is possible to not be downloading any files and still be logged into the system the max number of times.  The way around it? Close your browser, wait a little while, and then try again.  If it doesn't work then it may be problem #1.<br><br>If you are trying to download a file and you can't it is something wrong on your side!  The max number of connections to DDRei's server has never before been reached, not by half.  So if there is a problem the best thing you can do is close the broswer and try again.<br><br>
			<b>Can I add a file to the media section?</b><br>Sure! We are always looking for more east coast videos, or anything else that would better the site.  Simply contact Dyogenez, or join the DDRei IRC channel to send it my way.<br><br>
			<b>If no file size is given, the file is not currently avaliable!</b>";
		}
		//navigation bar on every page
		//link to most popular, most recent, highest rated
		/*
		echo "<form name=redirect><select name=Menu onChange=\"MM_jumpMenu('parent',this,0)\"><option value=\"http://www.ddrei.com/media.php\" selected>Category Jump</option><option value=\"http://www.ddrei.com/media.php\">---------</option>";
     	$query="SELECT cat_id, cat_name, cat_parent FROM categories ORDER BY cat_name";
     	echo "query";
		$result=mysql_query($query);
		$number=mysql_numrows($result);
		$i = 0;
		while ($i < $number){
			$catid = mysql_result($result,$i,"cat_id");
			$catname = mysql_result($result,$i,"cat_name");
			$catpar = mysql_result($result,$i,"cat_parent");
			if ($catpar == 0) {
				echo "<option value=\"media.php?cat_id=$catid\">$catname</option>";
     		}
     		$i++;
			$query="SELECT * FROM categories ORDER BY cat_name";
			echo "query";
			$res = mysql_query($query);
			$n2 = mysql_numrows($res);
			$n = 0;
			while ($n < $n2) {
				$subcid = mysql_result($res,$n,"cat_id");
				$subname = mysql_result($res,$n,"cat_name");
				$subpar = mysql_result($res,$n,"cat_parent");
				if ($subpar == $catid) {
					echo "<option value=\"media.php?cat_id=$subcid\">---$subname</option>";
				}
				$n++;
     		}
     	}
		echo "</select></form>";
		*/
		echo "<p align=center><a href=media.php?top=1>Most Popular</a> :: <a href=media.php?new=1>Most Recent</a> :: <a href=media.php?rated=1>Highest Rated</a><br><br><a href=media.php?media_faq=1>Media FAQ</a> :: <a href=media.php?dwi_faq=1>DWI FAQ</a><br><small><i>script executed with $q queries.</p>";
	}
	/*
	else
	{
		echo "<tr><td vAlign=top height=0 width=40%><center>The profiles database is currently down.<br><br>\n";
		echo "<a href=reviews2.php>Want to write a review?</a><br></td></tr></table>\n";
	}
	*/
	if(count($type))
	{
		include("footer.php");
		session_register("ddrei_dl");
		$ddrei_dl=1;
	}
?>