<?php
/**
 * Created by PhpStorm.
 * User: Allen Do
 * Date: 9/1/2015
 * Time: 10:22 PM
 */

function insert_lead($hubspot)
{
    $out=0;

    if (is_array($hubspot))
    {

        $name = $hubspot['name']." ".$hubspot['lastname'];
        $source = 0;
        $qry0  = "INSERT INTO lead_inc ";
        $qry0 .= "(submitted,lname,addr,city,state,zip,phone,email,comments,opt1,opt2,opt3,opt4,source) ";
        $qry0 .= "VALUES (";
        $qry0 .= "getdate(),'".replacequote($name)."','".replacequote($hubspot['address'])."',";
        $qry0 .= "'".replacequote($hubspot['city'])."','".$hubspot['state']."','".$hubspot['zip']."','".$hubspot['phone']."',";
        $qry0 .= "'".$hubspot['email']."','".replacequote($hubspot['comments'])."',";
        $qry0 .= "'".$hubspot['opt1']."','".$hubspot['opt2']."','".$hubspot['opt3']."','".$hubspot['opt4']."','".$source."'); SELECT @@IDENTITY;";
        $res0  = mssql_query($qry0);
        $row0  = mssql_fetch_row($res0);

        $out=$row0[0];
    }

    return $out;
}

function sort_and_notify()
{
    $cid_ZIP=autosort_ZIP();
    $cid_DIR=autosort_DIRECT();

    $email_ecnt=0;
    if (count($cid_ZIP) > 0)
    {
        foreach ($cid_ZIP as $nz=>$vz)
        {
            JMS_email_notify($vz,true,false,true,false,'JMS Notification: New Territory Lead from BHNM!');
            $email_ecnt++;
        }
    }

    if (count($cid_DIR) > 0)
    {
        foreach ($cid_DIR as $nd=>$vd)
        {
            JMS_email_notify($vd,true,false,true,false,'JMS Notification: New Direct Lead from BHNM!');
            $email_ecnt++;
        }
    }

    //Process Intro Emails
    process_email_intro(0);

}

function process_hubspot()
{
    $results = file_get_contents("php://input");
    $json_data = json_decode($results, true);

    $hubspot = array('errors'=>array());
    $hubspot['lastname'] = $json_data['properties']['lastname']['value'];
    $hubspot['name'] = $json_data['properties']['name']['value'];
    $hubspot['address'] = $json_data['properties']['address']['value'];
    $hubspot['city'] = $json_data['properties']['city']['value'];
    $hubspot['state'] = $json_data['properties']['state']['value'];
    $hubspot['zip'] = $json_data['properties']['zip']['value'];
    $hubspot['email'] = $json_data['properties']['email']['value'];
    $hubspot['phone'] = $json_data['properties']['phone']['value'];
    $hubspot['choose'] = $json_data['properties']['choose']['value'];

    // Parse and fill in the full requests for now
    $choose = "";
    if (strpos($hubspot['choose'], 'brochure') !== FALSE) {
        $choose = "a free 44-page color brochure with photos, pool buyer's checklist, & construction overview.";
    }
    if (strpos($hubspot['choose'], 'estimate') !== FALSE) {
        if (strlen($choose) > 1) {
            $choose .= "; ";
        }
        $choose .= "a free, no-obligation in-home estimate & pool 3D design presentation custom designed for my backyard.";
    }

    $comments = "Source: Hubspot\n";
    $comments .= "Name: ".$hubspot['name']." ".$hubspot['lastname']."\n";
    $comments .= "Address: ".$hubspot['address']."\n";
    $comments .= "City: ".$hubspot['city']."\n";
    $comments .= "State: ".$hubspot['state']."\n";
    $comments .= "Zip: ".$hubspot['zip']."\n";
    $comments .= "E-Mail: ".$hubspot['email']."\n";
    $comments .= "Phone: ".$hubspot['phone']."\n";
    $comments .= "Requests:\n---------------".$choose."---------------\n";

    $hubspot['comments'] = $comments;

    insert_lead($hubspot);

    // sort_and_notify();
}

include(".\connect_db.php");
include(".\common_func.php");
include(".\email_notify.php");
include(".\process_leads.php");
process_hubspot();

?>