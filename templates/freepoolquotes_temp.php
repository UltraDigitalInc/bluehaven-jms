<?php

function procleademail($head,$body,$src,$process,$test)
{
    //error_reporting(E_ALL);
	//ini_set('display_errors','On');
	
    $lcnt=0;
    $crit_ar=array('fname','lname','zip','hphone');
    $content_ar=array();
	$content_ar['comments']='';
    
	$content_ar['submitted']=date('m/d/y G:i',strtotime(trim($head->MailDate)));

	if (preg_match("/First Name: +[\w+\s\'\-\&\.]+\n/",$body,$matches))
	{
		$u_fname=preg_split("/: +/",$matches[0]);
		$u_fname=array_slice($u_fname,1);

		$fname="";
		foreach($u_fname as $n => $v)
		{
			$fname=$fname." ".$v;
		}
        
		$content_ar['fname']=ucwords(trim(preg_replace('/^ /','',$fname)));
	}
    
    if (preg_match("/Last Name: +[\w+\s\'\-\&\.]+\n/",$body,$matches))
	{
		$u_lname=preg_split("/: +/",$matches[0]);
		$u_lname=array_slice($u_lname,1);

		$lname='';
		foreach($u_lname as $n => $v)
		{
			$lname=$lname." ".$v;
		}
		$content_ar['lname']=ucwords(trim(preg_replace('/^ /','',$lname)));
	}

	if (preg_match("/Address: +[\w+\s\'\.\#\@]+\n/",$body,$matches))
	{
		$u_addr=preg_split("/ +/",$matches[0]);
		$u_addr=array_slice($u_addr,1);

		$addr="";
		foreach($u_addr as $n => $v)
		{
			$addr=$addr." ".$v;
		}
		$content_ar['addr']=trim(preg_replace('/^ /','',$addr));
	}

	if (preg_match("/City:\s.{1,}/",$body,$matches))
	{
		$u_city=preg_split("/ +/",$matches[0]);
		$u_city=array_slice($u_city,1);

		$city='';
		foreach($u_city as $n => $v)
		{
			$city=$city." ".$v;
		}
		$content_ar['city']=ucwords(trim(preg_replace('/^ /','',$city)));
	}

	if (preg_match("/State: +[a-zA-Z0-9]{1,2}/",$body,$matches))
	{
		$u_state=preg_split("/ +/",$matches[0]);
		$u_state=array_slice($u_state,1);
		$content_ar['state']=trim($u_state[0]);
	}

	if (preg_match("/Zip: +[0-9]{1,}/",$body,$matches))
	{
		$u_zip=preg_split("/ +/",$matches[0]);
		$u_zip=array_slice($u_zip,1);
		$content_ar['zip']=trim($u_zip[0]);
	}
    
    if (preg_match("/County:\s{1}[a-zA-Z0-9]{1,}/",$body,$matches))
	{
		$u_county=preg_split("/ +/",$matches[0]);
		$u_county=array_slice($u_county,1);
		$content_ar['county']=trim($u_county[0]);
	}
    
	if (preg_match("/Email: ([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
	{
		$u_email=preg_split("/ +/",$matches[0]);
		$content_ar['email']=trim($u_email[1]);
	}

    if (preg_match("/Phone: +\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}/",$body,$matches))
	{
		$h_phone=preg_split("/: +/",$matches[0]);
		$pat='/\(?\)?\-?\.?\s?/';
        $rep='';
		$content_ar['hphone']=trim(preg_replace($pat,$rep,$h_phone[1]));
	}
    
    if (preg_match("/Cell Phone: +\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}/",$body,$matches))
	{
		$c_phone=preg_split("/: +/",$matches[0]);
        $pat='/\(?\)?\-?\.?\s?/';
        $rep='';
        $content_ar['cphone']=trim(preg_replace($pat,$rep,$c_phone[1]));
	}

	if (preg_match("/Contact Time: +[0-9]{1,}(\-?[0-9]{1,})? +[A-Z]{1,2}/",$body,$matches))
	{
		$u_time=preg_split("/ +/",$matches[0]);
		$content_ar['time']=$u_time[2]." ".$u_time[3];
	}

	if (preg_match("/Opt1: +[0-1]/",$body,$matches))
	{
		$u_opt1=preg_split("/ +/",$matches[0]);
		
		if ($u_opt1[1]==1)
		{
			$content_ar['opt1']=$u_opt1[1];
		}
		else
		{
			$content_ar['opt1']=0;
		}
	}
	else
	{
		$content_ar['opt1']=0;
	}
	
	if (preg_match("/Opt2: +[0-1]/",$body,$matches))
	{
		$u_opt2=preg_split("/ +/",$matches[0]);
		
		if ($u_opt2[1]==1)
		{
			$content_ar['opt2']=$u_opt2[1];
		}
		else
		{
			$content_ar['opt2']=0;
		}
	}
	else
	{
		$content_ar['opt2']=0;
	}
	
	if (preg_match("/Opt3: +[0-1]/",$body,$matches))
	{
		$u_opt3=preg_split("/ +/",$matches[0]);
		
		if ($u_opt3[1]==1)
		{
			$content_ar['opt3']=$u_opt3[1];
		}
		else
		{
			$content_ar['opt3']=0;
		}
	}
	else
	{
		$content_ar['opt3']=0;
	}
	
	if (preg_match("/Opt4: +[0-1]/",$body,$matches))
	{
		$u_opt4=preg_split("/ +/",$matches[0]);
		
		if ($u_opt4[1]==1)
		{
			$content_ar['opt4']=$u_opt4[1];
		}
		else
		{
			$content_ar['opt4']=0;
		}
	}
	else
	{
		$content_ar['opt4']=0;
	}
	
    if (isset($src) && $src!='NA')
    {
        $content_ar['src1']=$src;
    }
    else
    {
        if (preg_match("/Source: +[0-9]{1,4}/",$body,$matches))
        {
            $u_src1=preg_split("/ +/",$matches[0]);
            
            if (isset($u_src1[1]))
            {
                $content_ar['src1']=$u_src1[1];
            }
            else
            {
                $content_ar['src1']=0;
            }
        }
        else
        {
            $content_ar['src1']=0;
        }
    }
	
	if (isset($head->subject))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).$head->subject.chr(13);
	}
	
	if (preg_match("/Google\sMap\nhttp\:\/\/.{1,}\n/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
	
	if (preg_match("/Good\sLuck!\n[0-9A-Z]{1,}\.{1}FreePoolQuotes.com\sQuote\sRequest\s\#{1,}\s[0-9]{1,}\n/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Type\sof\sProject:\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Type\sof\sPool\s(concrete\sor\sfiberglass)\s:\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Homeowner\'s\sbudget:\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Is\sthis\sproject\sa\sremodel\sor\srenovation\sof\san\sexisting pool?\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Time\sframe\sto\sstart\sproject:\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Best\sway\sof\scontact\shomeowner:\s.{1,}/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    if (preg_match("/Project\sdetails or\scomments\s(optional):\r\n------/i",$body,$matches))
	{
		$content_ar['comments']=$content_ar['comments'].chr(13).removequote(trim($matches[0]));
	}
    
    $crit_cnt=0;
    $content_keys=array_keys($content_ar);
    foreach ($crit_ar as $n => $v)
    {
        if (!in_array($v,array_keys($content_ar)))
        {
            $crit_cnt++;
        }
    }

	if ($crit_cnt == 0)
	{
		$qry0	 = "INSERT INTO jest..lead_inc ";
		$qry0 .= "(";
        $qry0 .= "submitted,";
        $qry0 .= "lname,";
        $qry0 .= "addr,";
        $qry0 .= "city,";
        $qry0 .= "state,";
        $qry0 .= "zip,";
        $qry0 .= "phone,";
        $qry0 .= "email,";
        $qry0 .= "comments,";
        $qry0 .= "opt1,";
        $qry0 .= "opt2,";
        $qry0 .= "opt3,";
        $qry0 .= "opt4,";
        $qry0 .= "source";
        $qry0 .= ") ";
		$qry0 .= "VALUES (";
		$qry0 .= "'".$content_ar['submitted']."',";
        $qry0 .= "'".($content_ar['fname'].' '.$content_ar['lname'])."',";
        $qry0 .= "'".$content_ar['addr']."',";
		$qry0 .= "'".$content_ar['city']."',";
        $qry0 .= "'".$content_ar['state']."',";
        $qry0 .= "'".$content_ar['zip']."',";
        $qry0 .= "'".$content_ar['hphone']."',";
		$qry0 .= "'".$content_ar['email']."',";
        $qry0 .= "'".$content_ar['comments']."',";
		$qry0 .= "'".$content_ar['opt1']."',";
        $qry0 .= "'".$content_ar['opt2']."',";
        $qry0 .= "'".$content_ar['opt3']."',";
        $qry0 .= "'".$content_ar['opt4']."',";
        $qry0 .= "".$content_ar['src1']."";
        $qry0 .= ");";
		
		if ($test)
		{
			echo '<pre>';
			print_r($content_ar);
			echo '</pre><br>';
			
			echo '<pre>';
			print_r($crit_ar);
			echo '</pre>';
			
			echo '<pre>';
			print_r(array_keys($content_ar));
			echo '</pre>';
			
            echo $qry0.'<br>';
		}
		
		if ($process)
		{
			//echo $qry0.'<br>';
            $res0	= mssql_query($qry0);
		}
	}
}

?>