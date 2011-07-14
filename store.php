<?php

	include("scripts/database.php");
	$con = @mysql_connect($host, $login, $pass);
	if (!$con) { $connect = 0; $title='Database Error'; exit(); }
	$connect = 1;
	$output = "";
	
	// redirect page
	if($go) {
		$query = "SELECT link, s_clicked from store_items where item_id=$go";
		$result = mysql_db_query($database, $query);

		if($r=@mysql_fetch_array($result))
		{
			@extract($r);
			$query = "UPDATE store_items SET s_clicked = ".($s_clicked+1)." where item_id=$go";
			$result = mysql_db_query($database, $query);
			header("Location:$link");
		}
	}
	
	
	
	// single item page
	if($id) {
		$query = "SELECT * from store_items si, store_sections ss 
			WHERE ss.section_id = si.section_id AND si.item_id = $id";
		$result = mysql_db_query($database, $query);

		if($r=@mysql_fetch_array($result)) {
			@extract($r);
			$query = "UPDATE store_items SET s_viewed = ".($s_viewed+1)." where item_id=$id";
			$result = mysql_db_query($database, $query);
			
			$output .= "<li><a href='store.php?go=$item_id' target=_blank>$name</a> - $com</li>";
		}
		
		$path="<a href=store.php>Store</a> :: <a href=store.php?section=$section_id>$title</a> :: $name";
	}
	// Section page
	elseif($section) {
		$query = "SELECT * from store_items si, store_sections ss 
			WHERE ss.section_id = si.section_id AND si.section_id = $section";
		$result = mysql_db_query($database, $query);

		while($r=@mysql_fetch_array($result)) {
			@extract($r);
			
			if(!$i)
				$output .= "<table border=2 cellspacing=0 bordercolor=#C0C0C0 width=495 cellpadding=0 style=\"border-collapse: collapse; border-width: 0\" bordercolorlight=#C0C0C0><tr><td width=489 height=24 style=\"border-style: solid; border-width: 1\" colspan=2 bgcolor=#E1E7F3><p align=center><font face=Verdana>&nbsp;&nbsp; <b> <i>$title</i></b></font></td></tr>";
			$output .= "<tr><td width=141 height=157 valign=middle style=\"border-left: medium none #C0C0C0; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom: 1px solid #C0C0C0\" align=center><br><a href=store.php?id=$item_id target=_blank><img border=0 src=$pic></a></td><td width=348 height=157 valign=top style=\"border-left-style: none; border-left-width: medium; border-right: medium none #C0C0C0; border-top: medium none #C0C0C0; border-bottom: 1px solid #C0C0C0\"><font face=\"arial, helvetica\" size=-1><ul><br><li><font face=\"arial, helvetica\" size=2><b>$name</b></font></li>$com<li><a href=store.php?go=$item_id target=_blank>Order Now! </a>&nbsp;- $".$price.".</li></ul></font></td></tr>";
			$i=1;
		}
		$output .= "</td></tr></table>";
		$path="<a href=store.php>Store</a> :: $title";
		
	}
	// Listing of sections
	else {
		$query = "SELECT * from store_sections ORDER BY TITLE";
		$result = mysql_db_query($database, $query);

		while($r=@mysql_fetch_array($result)) {
			@extract($r);
			
			$output .= "<li><a href=store.php?section=$section_id><b>$title</b></a><br><small>&nbsp&nbsp&nbsp&nbsp<i>$write</i></small></li>";
		}
		
		$path="Store";
	}
	
	include("header.php");
	$output =  "<strong><font face=Arial color=#000080 size=2>$path</font></strong><br><br>".$output;
	echo $output;
	include("footer.php");
?>