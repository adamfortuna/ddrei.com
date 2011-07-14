<? 
//Author: Woody Stanford(woody@northlink.com)
//we use UNIX's time specification as the base specification 

        function mysql_datetime_to_human($dt) 
        { 
                $yr=strval(substr($dt,0,4)); 
                $mo=strval(substr($dt,5,2)); 
                $da=strval(substr($dt,8,2)); 
                $hr=strval(substr($dt,11,2)); 
                $mi=strval(substr($dt,14,2)); 
//              $se=strval(substr($dt,17,2)); 

                return date("M. d, Y H:i", mktime ($hr,$mi,0,$mo,$da,$yr))." EST"; 
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

        function mysql_timestamp_to_timestamp($dt) 
        { 
                $yr=strval(substr($dt,0,4)); 
                $mo=strval(substr($dt,4,2)); 
                $da=strval(substr($dt,6,2)); 
                $hr=strval(substr($dt,8,2)); 
                $mi=strval(substr($dt,10,2)); 
                $se=strval(substr($dt,10,2)); 

                return mktime($hr,$mi,$se,$mo,$da,$yr); 
        } 

        function mysql_datetime_to_timestamp($dt) 
        { 
                $yr=strval(substr($dt,0,4)); 
                $mo=strval(substr($dt,5,2)); 
                $da=strval(substr($dt,8,2)); 
                $hr=strval(substr($dt,11,2)); 
                $mi=strval(substr($dt,14,2)); 
                $se=strval(substr($dt,17,2)); 

                return mktime($hr,$mi,$se,$mo,$da,$yr); 
        } 

        function timestamp_to_mysql($ts) 
        { 
                $d=getdate($ts); 

                $yr=$d["year"]; 
                $mo=$d["mon"]; 
                $da=$d["mday"]; 
                $hr=$d["hours"]; 
                $mi=$d["minutes"]; 
                $se=$d["seconds"]; 

                return sprintf("%04d%02d%02d%02d%02d%02d",$yr,$mo,$da,$hr,$mi,$se); 
        } 


function timeleft($begin,$end) 
{ 
        //for two timestamp format dates, returns the plain english difference between them. 
        //note these dates are UNIX timestamps 


        $dif=$end-$begin; 

        $years=intval($dif/(60*60*24*365)); 
        $dif=$dif-($years*(60*60*24*365)); 

        $months=intval($dif/(60*60*24*30)); 
        $dif=$dif-($months*(60*60*24*30)); 

        $weeks=intval($dif/(60*60*24*7)); 
        $dif=$dif-($weeks*(60*60*24*7)); 

        $days=intval($dif/(60*60*24)); 
        $dif=$dif-($days*(60*60*24)); 

        $hours=intval($dif/(60*60)); 
        $dif=$dif-($hours*(60*60)); 

        $minutes=intval($dif/(60)); 
        $seconds=$dif-($minutes*60); 

        $s=""; 

        //if ($years<>0) $s.= $years." years "; 
        //if ($months<>0) $s.= $months." months "; 
        if ($weeks<>0) $s.= $weeks." weeks "; 
        if ($days<>0) $s.= $days." days "; 
        if ($hours<>0) $s.= $hours." hours "; 
        if ($minutes<>0) $s.= $minutes." minutes "; 
        //if ($seconds<>0) $s.= $seconds." seconds "; 

        return $s; 

} 

?> 