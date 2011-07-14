<?php


	function output_tr($for_color, $on_color, $col1, $col2)
	{
		echo "\n<tr onmouseout=\"this.style.backgroundColor='$for_color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='$on_color';  return true;\" bgcolor=$for_color height=0>";
		echo "<td width=120 height=4><font face=arial size=2><b>$col1:</b></td><td width=320><font face=arial size=2>$col2</font></td></tr>\n";
	}

	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }

	include("header.php");

	if($id)
	{
		$n_id=$id;

		$query="SELECT * FROM characters WHERE id=$n_id";
		$result = @mysql_db_query($database, $query);
		$r=@mysql_fetch_array($result);
		extract($r);

		//say the path to the character
		echo "<b><font face=Arial color=#000080><a href=chara.php>Characters</a> :: $name</font></b><br>";


		//say the characters info
		//start the character table
		$font="\"Arial\"";
		echo "<br><br><table border=1 cellpadding=0 cellspacing=0 style=\"border:1px solid #c0c0c0; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>";
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top align=center height=1 colspan=3><font face=$font size=3><strong>$name</strong></font></td></tr>\n";

		//get the pictures to be used
		$query="SELECT title, file, views FROM characters, characters2 WHERE characters.id=$n_id AND characters2.ch_id=characters.id";
		$result_c = @mysql_db_query($database, $query);
		$t=@mysql_fetch_array($result_c);
		@extract($t);

		//check if there is a picture file for the character
		//check file width
		$width=@getimagesize("pics/chara/$file");
		if($file)
		{
			echo "<tr><td rowspan=13 align=center bgcolor=#EEF1F5><br><img";
			if($width[0]>250) echo " width=250 ";
			echo " src=\"pics/chara/$file\" border=0><br><br></td></tr>";
		}

		output_tr("#EEF1F5", "#E4E9F2", "Name", $name);
		output_tr("#FFFFFF", "#E4E9F2", "Full Name", $full_name);
		if($height) $height="$height cm";
		output_tr("#EEF1F5", "#E4E9F2", "Height", $height);
		output_tr("#FFFFFF", "#E4E9F2", "Home", $home);
		if($age) $age="$age years";
		output_tr("#EEF1F5", "#E4E9F2", "Age", $age);
		output_tr("#FFFFFF", "#E4E9F2", "Hobbies", $hobby);
		output_tr("#EEF1F5", "#E4E9F2", "Specialties", $specialty);
		output_tr("#FFFFFF", "#E4E9F2", "Likes", $like);
		output_tr("#EEF1F5", "#E4E9F2", "Dislikes", $dislike);
		output_tr("#FFFFFF", "#E4E9F2", "Comments", $other);

		//get the different DDR mixes it can be from
		$query2="SELECT id, name FROM versions WHERE a_id=1";
		$result2 = @mysql_db_query($database, $query2);
		while($s=@mysql_fetch_array($result2))
		{
			extract($s);
			$versions[$id]=$name;
		}
		$all_mix=explode(",", $mix);
		$mix="";
		for($k=0; $k<count($all_mix); $k++)
		{
			if($k) $mix="$mix, ";
			$m=$all_mix[$k];
			$mix="$mix<a href=info.php?game=1&id=$all_mix[$k]>$versions[$m]</a>";
		}
		output_tr("#EEF1F5", "#E4E9F2", "On Mixes", $mix);

		echo "</table><p align=center>";

		if($width[0]>250) echo "<img src=\"pics/chara/$file\" border=1><br><br>";

		while($t=@mysql_fetch_array($result_c))
		{
			extract($t);
			$width=getimagesize("pics/chara/$file");
			if($width[0]>495) echo "<img width=495 src=\"pics/chara/$file\" border=1><br><br>";
			else echo "<img src=\"pics/chara/$file\" border=1><br><br>";
		}
	}
	else
	{
		//say the path to the characters
		echo "<b><font face=Arial color=#000080><a href=chara.php>Characters</font></b><br>";

		//start the character table
		$font="\"Arial\"";
		echo "<br><br><table border=1 cellpadding=0 cellspacing=0 style=\"border:1px solid #c0c0c0; border-collapse: collapse; padding-left:4; padding-right:4; padding-top:1; padding-bottom:1\" width=495>";
		echo "<tr bgcolor=\"#E1E7F3\"><td vAlign=top align=center height=1 width=225><font face=$font size=3><strong>Name</strong></font></td><td><font face=$font size=3><b>First appeared in</b></font></td></tr>\n";

		//get the character info
		$query="SELECT id, name, mix FROM characters order by name";
		$result = @mysql_db_query($database, $query);

		//get the different DDR mixes it can be from
		$query2="SELECT id, name FROM versions WHERE a_id=1";
		$result2 = @mysql_db_query($database, $query2);
		while($s=@mysql_fetch_array($result2))
		{
			extract($s);
			$versions[$id]=$name;
		}

		//get the picture info
		$query="SELECT ch_id FROM characters2, characters WHERE characters2.ch_id=characters.id order by characters.name";
		$c_result = @mysql_db_query($database, $query);
		$t=@mysql_fetch_array($c_result);
		@extract($t);

		while($r=@mysql_fetch_array($result))
		{
			extract($r);
			if(!$flags) { $flags++; $color="#EEF1F5"; }
			else { $flags=0; $color="#FFFFFF"; }
			$ilink="#$id";

			echo "\n<tr onclick=\"javascript:location.href='chara.php?id=$id';\" onmouseout=\"this.style.backgroundColor='$color'; window.status = ''; return true;\" onmouseover=\"this.style.backgroundColor='#E4E9F2';  return true;\" this.style.cursor = 'hand'; window.status = 'Check their info!'; return true;\" bgcolor= $color height= 25 >";

			//convert $mix to the mixes the character is in
			$all_mix=explode(",", $mix);
			$m=$all_mix[0];
			$mix="<a href=info.php?game=1&id=$all_mix[0]>$versions[$m]</a>";

	//		echo "chid= $ch_id id= $id";

			//get the number of images of the current character
			while(($ch_id==$id) && !$flag)
			{
				$totalpics++;
				if($t=@mysql_fetch_array($c_result))
				{
					@extract($t);
				}
				else
				{
					$flag=1;
				}
			}

			echo "<td vAlign=center width=120 height=4><font face=$font size=2>";
			echo "<a href=chara.php?id=$id>$name</a>";
			if($totalpics && $totalpics!=101) echo "&nbsp&nbsp&nbsp<font size=1>($totalpics)";
			echo "</font></td>";
			echo "<td width=320><font face=$font size=2>$mix</font></td></tr>\n";
			$totalpics=0;
		}
		echo "</table><br><font size=2 face=arial></a>(# of images in parenthesis)</font>";
	}

	include("footer.php");
?>

