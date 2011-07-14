<?php
		$all_banners[0]="<a href=http://store.yahoo.com/cgi-bin/clink?adux+srptHa+ddr.html target=_blank><img src=$pic_path/images/store/banners/affiliate-banner-ps2-dc-psx.gif border=0></a>";

		$all_banners[1]="<a href=http://us.yesasia.com/assocred.asp?0LJKOAPT+http://us.yesasia.com target=_blank><img src=$pic_path/images/store/banners/banner2.gif border=0></a>";

		$all_banners[2]="<a href=http://store.yahoo.com/cgi-bin/clink?adux+srptHa+ddr.html target=_blank><img src=$pic_path/images/store/banners/affilite-3easysteps.gif border=0></a>";

		$all_banners[3]="<a href=http://us.yesasia.com/assocred.asp?0LJKOAPT+http://us.yesasia.com target=_blank><img src=$pic_path/images/store/banners/banner.gif border=0></a>";

		$all_banners[4]="<a href=http://store.yahoo.com/cgi-bin/clink?adux+srptHa+ddr.html target=_blank><img src=$pic_path/images/store/banners/affilite-redoctaneignitionpad.jpg border=0></a>";

				mt_srand((double)microtime() * 1000000);
		$rand_number1=mt_rand(0,(count($all_banners)-1));

		do
		{
			mt_srand((double)microtime() * 1000000);
			$rand_number2=mt_rand(0,(count($all_banners)-1));
		} while($rand_number1==$rand_number2);

		$top_banner=$all_banners[$rand_number1];
		$bottom_banner=$all_banners[$rand_number2];

?>