<?php

$all_banners[0]='<script type="text/javascript"><!--
google_ad_client = "pub-5098013088670023";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
//2006-10-13: DDRei.com
google_ad_channel = "3553255367";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000080";
google_color_text = "000000";
google_color_url = "93a2bf";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';


$all_banners[1]='<script type="text/javascript"><!--
google_ad_client = "pub-5098013088670023";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text";
//2006-10-13: DDRei.com
google_ad_channel = "3553255367";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000080";
google_color_text = "000000";
google_color_url = "93a2bf";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';


	mt_srand((double)microtime() * 1000000);		$rand_number1=mt_rand(0,(count($all_banners)-1));		do		{			mt_srand((double)microtime() * 1000000);			$rand_number2=mt_rand(0,(count($all_banners)-1));		} while($rand_number1==$rand_number2);		$top_banner=$all_banners[$rand_number1];		$bottom_banner=$all_banners[$rand_number2];?>