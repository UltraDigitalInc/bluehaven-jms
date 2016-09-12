<?php

function BaseMatrix()
{
	echo "<script type=\"text/javascript\" src=\"js/jquery_accounting_func.js\"></script>\n";
	echo "<input type=\"hidden\" id=\"acct_OID\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><b>Accounting Control Panel</b></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	//echo "<br>";
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='list_Queues')
	{
		list_Queues();
	}
}

function list_Queues()
{
	$qry1   = "SELECT distinct(datepart(yyyy,added)) as years FROM jobs WHERE officeid=".$_SESSION['officeid']." ORDER BY years DESC;";
	$res1   = mssql_query($qry1);
    
    while ($row1   = mssql_fetch_array($res1))
    {
        $yrs_ar[]=$row1['years'];
    }
	
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<div id=\"Accounting_Tab\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"#tab0\"><em>JMS Customer Status</em></a> <img class=\"JMStooltip\" src=\"images/help.png\" title=\"Customer Release Status. This Tab displays Customers that have been added to Quickbooks or have been flagged for Job Release\"></li>\n";
	//echo "					<li><a href=\"#tab1\"><em>QB Processing Queue</em></a> <img class=\"JMStooltip\" src=\"images/help.png\" title=\"Customer Job Data Transfered to Accounting Queue awaiting processing\"></li>\n";
	//echo "					<li><a href=\"#tab2\"><em>QB Processed</em></a> <img class=\"JMStooltip\" src=\"images/help.png\" title=\"Customer Job Data Transfered to Accounting and successfully Processed\"></li>\n";
	//echo "					<li><a href=\"#tab1\"><em>QB Closed</em></a> <img class=\"JMStooltip\" src=\"images/help.png\" title=\"Closed Customer Jobs\"></li>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "					<li><a href=\"#tab1\"><em>QB Raw Log</em></a> <img class=\"JMStooltip\" src=\"images/help.png\" title=\"Transfer Log File\"></li>\n";
	}
	
	echo "				</ul>\n";
	
	echo "				<div id=\"tab0\">\n";
	echo "					<p>\n";

    echo "          <table width=\"100%\">\n";
    echo "          	<tr>\n";
    echo "          		<td align=\"right\">Jobs Added in\n";
    echo "                      <select name=\"select_CustomerYear\" id=\"select_CustomerYear\">\n";
    
    foreach ($yrs_ar as $ny=>$vy)
    {
		/*
        if (isset($yr) and $yr!=0)
        {
            echo "                          <option value=\"".$vy."\" SELECTED>".$vy."</option>\n";
        }
        else
        {
		*/
            echo "                          <option value=\"".$vy."\">".$vy."</option>\n";
        //}
    }
    
    echo "                      </select>\n";
	echo "						<a id=\"update_list_JMS_Released\" href=\"#\"><img src=\"images/arrow_refresh_small.png\"></a>\n";
    echo "                  </td>\n";
    echo "          	</tr>\n";
    echo "          </table>\n";
	
	echo "						<div id=\"panel_JMS_Released\"></div>\n";
	echo "					</p>\n";
	echo "				</div>\n";
	
	/*
	echo "				<div id=\"tab1\">\n";
	echo "					<p>\n";
	
	echo "						<table width=\"915px\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<select id=\"get_Jobs_Queued\">\n";
	echo "										<option value=\"q\">Processing Queued</option>\n";
	echo "										<option value=\"i\">Processing Incomplete</option>\n";
	echo "										<option value=\"e\">Processing Error</option>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "										<option value=\"s\">Processed</option>\n";
	}
	
	echo "										<option value=\"a\">Show All</option>\n";	
	echo "									</select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<div id=\"panel_Accounting_Queues\"></div>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	
	echo "					</p>\n";
	echo "				</div>\n";
	
	echo "				<div id=\"tab2\">\n";
	echo "					<p>\n";
	echo "						<div id=\"panel_Queue_Processed\"></div>\n";
	echo "					</p>\n";
	echo "				</div>\n";
	
	echo "				<div id=\"tab3\">\n";
	echo "					<p>\n";
	
	echo "						<table width=\"915px\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"top\"> Log Count \n";
	echo "									<select id=\"get_Closed_Count\">\n";
	echo "										<option value=\"10\">10</option>\n";
	echo "										<option value=\"50\">50</option>\n";
	echo "										<option value=\"100\">100</option>\n";
	echo "										<option value=\"200\">200</option>\n";
	echo "										<option value=\"500\">500</option>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "										<option value=\"0\">All</option>\n";
	}
	
	echo "									</select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<div id=\"panel_JMS_Closed\"></div>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	
	echo "					</p>\n";
	echo "				</div>\n";
	*/
	
	if ($_SESSION['securityid']==26)
	{
		echo "				<div id=\"tab1\">\n";
		echo "					<p>\n";
	
		echo "						<table width=\"915px\">\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\">Queue Status \n";
		echo "									<select id=\"usr_qact\">\n";
		echo "										<option value=\"A\">All</option>\n";
		echo "										<option value=\"CustomerAdd\">CustomerAdd</option>\n";
		echo "										<option value=\"EmployeeAdd\">EmployeeAdd</option>\n";
		echo "										<option value=\"EmployeeQuery\">EmployeeQuery</option>\n";
		echo "										<option value=\"EstimateAdd\">EstimateAdd</option>\n";
		echo "										<option value=\"InvoiceAdd\">InvoiceAdd</option>\n";
		echo "										<option value=\"ItemInventoryAdd\">ItemInventoryAdd</option>\n";
		echo "										<option value=\"ItemNonInventoryAdd\">ItemNonInventoryAdd</option>\n";
		echo "										<option value=\"ItemServiceAdd\">ItemServiceAdd</option>\n";
		echo "										<option value=\"ReceivePaymentAdd\">ReceivePaymentAdd</option>\n";
		echo "										<option value=\"SalesRepAdd\">SalesRepAdd</option>\n";
		echo "										<option value=\"SalesRepQuery\">SalesRepQuery</option>\n";
		echo "									</select>\n";
		echo "									<select id=\"usr_qstat\">\n";
		echo "										<option value=\"e\">Errors</option>\n";
		//echo "										<option value=\"i\">Incompletes</option>\n";
		echo "										<option value=\"q\">Queued</option>\n";
		echo "										<option value=\"s\">Processed</option>\n";
		echo "									</select>\n";
		echo "									<select id=\"usr_lcnt\">\n";
		echo "										<option value=\"10\">10</option>\n";
		echo "										<option value=\"50\">50</option>\n";
		echo "										<option value=\"100\">100</option>\n";
		echo "										<option value=\"200\">200</option>\n";
		echo "										<option value=\"500\">500</option>\n";
		
		if ($_SESSION['securityid']==26)
		{
			echo "										<option value=\"0\">All</option>\n";
		}
		
		echo "									</select>\n";
		echo "									<a id=\"update_list_Acct_Log\" href=\"#\"><img src=\"images/arrow_refresh_small.png\"></a>\n";
		echo "								</td>\n";
		//echo "								<td align=\"right\" valign=\"top\"><a id=\"get_Log_Count\" href=\"#\"><img src=\"images/arrow_refresh_small.png\"></a></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"left\" valign=\"top\">\n";
		echo "									<div id=\"panel_Log\"></div>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		
		echo "					</p>\n";
		echo "				</div>\n";
	}
	
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

?>