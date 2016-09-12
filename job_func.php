<?php

if ($_SESSION['securityid']==26)
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
}

function basematrix()
{
	include ('job_sub_func.php');
	
	if ($_SESSION['call']=="list")
	{
		list_jobs();
	}
	elseif ($_SESSION['call']=="create_job")
	{
		create_job();
	}
	elseif ($_SESSION['call']=="create_job_chk")
	{
		create_job_chk();
	}
	elseif ($_SESSION['call']=="post_create_job")
	{
		post_create_job();
	}
	elseif ($_SESSION['call']=="delete_job1")
	{
		//echo "XX";
		delete_job();
	}
	elseif ($_SESSION['call']=="delete_job2")
	{
		delete_job();
	}
	elseif ($_SESSION['call']=="delete_addendum1")
	{
		delete_addendum();
	}
	elseif ($_SESSION['call']=="delete_addendum2")
	{
		delete_addendum();
	}
	elseif ($_SESSION['call']=="view_retail")
	{
		view_job_retail();
	}
	elseif ($_SESSION['call']=="view_job_addendum_retail")
	{
		view_job_addendum_retail();
	}
	elseif ($_SESSION['call']=="view_cost")
	{
		view_job_cost();
	}
	elseif ($_SESSION['call']=="view_cost_int")
	{
		view_job_cost_int();
	}
	elseif ($_SESSION['call']=="view_job_addendum_cost")
	{
		view_job_addendum_cost();
	}
	elseif ($_SESSION['call']=="view_bid_jobmode")
	{
		//echo "Contract VBJM<br>";
		view_bid_job_mode();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode")
	{
		//echo "Contract VBJM<br>";
		edit_bid_job_mode();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_add")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_add")
	{
		edit_mpa_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="create_add")
	{
		//create_addendum();
		build_addendum_start();
	}
	elseif ($_SESSION['call']=="create_add_post_mas")
	{
		//build_post_mas_addendum_start();
		build_addendum_start();
	}
	elseif ($_SESSION['call']=="build_add")
	{
		build_addendum();
	}
	elseif ($_SESSION['call']=="save_add")
	{
		
		if ($_SESSION['securityid']==2699999999999999999999999999)
		{
			display_array($_REQUEST);
		}
		else
		{
			build_addendum_save();
		}
	}
	elseif ($_SESSION['call']=="save_add_post_mas")
	{
		build_addendum_save_post_mas();
	}
	elseif ($_SESSION['call']=="view_add_post_mas")
	{
		build_post_mas_addendum_view();
	}
	elseif ($_SESSION['call']=="post_save_add")
	{
		insert_add();
	}
	elseif ($_SESSION['call']=="edit_add_price")
	{
		edit_add_price();
	}
	elseif ($_SESSION['call']=="set_mas")
	{
		set_mas();
	}
	elseif ($_SESSION['call']=="search")
	{
		job_search();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		list_jobs();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="set_postmas_proc")
	{
		set_postmas_proc();
	}
	elseif ($_SESSION['call']=="updtsalesrep")
	{
		updtsalesrep();
	}
	elseif ($_SESSION['call']=="view_wo")
	{
		view_workorder();
	}
	elseif ($_SESSION['call']=="sp_set_renov")
	{
		updtsetrenov();
	}
	elseif ($_SESSION['call']=="biddel")
	{
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="mpadel")
	{
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="constructiondates_process")
	{
		constructiondates_process();
	}
	elseif ($_SESSION['call']=="jobprogress")
	{
		job_progress();
	}
}

function job_progress()
{
	//echo 'Job Progress Report';
	//ini_set('display_errors','On');
    //error_reporting(E_ALL);
	
	$qry = "SELECT securityid,constructdateaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	if (isset($row['constructdateaccess']) and $row['constructdateaccess'] < 5) {
		echo "You do not appropriate access to view this resource<br>";
	}
	else {
		$qry0 = "select officeid,name from offices where officeid=".$_SESSION['officeid'].";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		$qry1 ="
			select
				 J1.jid
				,J1.officeid
				,J1.custid
				,J1.jobid
				,(select substring(fname,1,2) from security where securityid=J1.securityid) as fsrep
				,(select substring(lname,1,1) from security where securityid=J1.securityid) as lsrep
				,J1.njobid
				,C1.clname as customer
				,C1.scity as city
				,(select contractdate from jest..jdetail where jobid=J1.jobid and jadd=0) as contractdate
				,(select
					(select cast(contractamt as money) from jest..jdetail where jobid=J1.jobid and jadd = 0)
					+ (select isnull(sum(cast(psched_adj as money)),0) from jdetail where jobid=J1.jobid and jadd >= 1)
				 ) as contractamt
				--,(select contractamt from jest..jdetail where jobid=J1.jobid and jadd=0) as contractamt
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=1 and dtype=1) as permitout
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=1 and dtype=2) as permitin
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=9 and dtype=2) as digdate
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=12 and dtype=2) as plumb
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=15 and dtype=2) as steel
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=17 and dtype=2) as gun
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=20 and dtype=2) as elec
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=22 and dtype=2) as tile
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=24 and dtype=2) as deck
				,(select cdate from jest..constructiondates where cid=J1.custid and phsid=32 and dtype=2) as plaster
				,(select isnull(sum(ramt),0) from jest..constructiondates where cid=J1.custid) as rcvd
				,(select
					(
						(select cast(contractamt as money) from jest..jdetail where jobid=J1.jobid and jadd = 0)
						+ (select isnull(sum(cast(psched_adj as money)),0) from jdetail where jobid=J1.jobid and jadd >= 1)
					)
					- (select isnull(sum(ramt),0) from jest..constructiondates where cid=J1.custid)
				) as amtdue
				,(
					select 
						top 1
						p.shortname
					from 
						constructiondates as C
					inner join
						jest..phasebase as p
					on 
						c.phsid=p.phsid
					where 
						c.cid=J1.custid
						and c.dtype=2
						and p.rptseq > 0
					order by
						p.rptseq desc
				) as lstatus
			from 
				jest..jobs AS J1
			inner join
				jest..cinfo as C1
			on
				J1.jobid=C1.jobid
			where 
				J1.officeid=".$_SESSION['officeid']."
				and C1.officeid=".$_SESSION['officeid']."
				and J1.njobid!='0'
				and C1.cid not in (select cid from jest..constructiondates where phsid=46)
			order by 
				J1.njobid asc;
			";
			
		if ($_SESSION['securityid']==26999999999999999999999999999) {
			echo $qry1.'<br>';
		}
		
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);
		
		echo "	<table align=\"center\" width=\"1300px\">\n";
		echo "		<tr>\n";
		echo "			<td align=\"right\">\n";
		echo "				<div class=\"noPrint\">\n";
		echo "				<table>\n";
		echo "					<tr>\n";
		echo "						<td align=\"right\">Feedback</td>\n";
		echo "						<td align=\"left\">\n";
		echo "         					<form method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"message\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"new_feedback\">\n";
		echo "								<input class=\"transnb\" type=\"image\" src=\"images/pencil.png\" alt=\"Feedback\">\n";
		echo "      			   		</form>\n";
		echo "						</td>\n";
		echo "						<td align=\"center\" width=\"20px\">\n";
		
		//HelpNode('JobProgessReport',9);
		
		echo "                      </td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "				</div>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td align=\"right\">\n";
		echo "				<table class=\"outer\" width=\"100%\">\n";
		echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"left\"><table><tr><td><b>Job Progress Report</b></td><td>".$row0['name']."</td></tr></table></td>\n";
		echo "						<td class=\"gray\" align=\"center\"><table><tr><td align=\"right\"><b>".$nrow1."</b></td><td align=\"left\"> Open Job(s)</td></tr></table></td>\n";
		echo "						<td class=\"gray\" align=\"right\"><table><tr><td><b>\n";
		
		?>
			
			<script type="text/javascript">
				setLocalTime();
			</script>
			
		<?php
		
		echo "						</b></td></tr></table></td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td>\n";
	
			if ($nrow1 > 0)
			{
				echo "				<table id=\"jobProgress\" class=\"tablesorter\" cellpadding=\"1\">\n";
				echo "					<thead>\n";
				echo "						<tr>\n";
				echo "							<th align=\"center\"><b>Rep</b></th>\n"; //0
				echo "							<th align=\"center\"><b>Job#</b></th>\n"; //1
				echo "							<th align=\"center\"><img src=\"images/pixel.gif\"></th>\n"; //2
				echo "							<th align=\"center\"><b>Customer</b></th>\n"; //3
				echo "							<th align=\"center\"><b>City</b></th>\n"; //4
				echo "							<th align=\"center\"><b>Sold</b></th>\n"; //5
				echo "							<th align=\"center\"><b>Contract</b></th>\n"; //6
				echo "							<th align=\"center\"><b>PermOut</b></th>\n"; //7
				echo "							<th align=\"center\"><b>PermIn</b></th>\n"; //8
				echo "							<th align=\"center\"><b>DigDate</b></th>\n"; //9
				echo "							<th align=\"center\"><b>Plumb</b></th>\n"; //10
				echo "							<th align=\"center\"><b>Steel</b></th>\n"; //11
				echo "							<th align=\"center\"><b>Gun</b></th>\n"; //12
				echo "							<th align=\"center\"><b>Elec</b></th>\n"; //13
				echo "							<th align=\"center\"><b>Tile</b></th>\n"; //14
				echo "							<th align=\"center\"><b>Deck</b></th>\n"; //15
				echo "							<th align=\"center\"><b>Plast</b></th>\n"; //16
				echo "							<th align=\"center\"><b>Status</b></th>\n"; //17
				echo "							<th align=\"center\"><b>Due</b></th>\n"; //18
				echo "							<th align=\"center\"><b>Notes</b></th>\n"; //19
				echo "						</tr>\n";
				echo "					</thead>\n";
				echo "					<tbody>\n";
				
				$tamt=0;
				$jcnt=0;
				while ($row1 = mssql_fetch_array($res1))
				{
					$jcnt++;
					$uid  =md5(session_id().time().$row1['custid']).".".$_SESSION['securityid'];
					$tamt=$tamt + $row1['amtdue'];
					echo "						<tr>\n";
					echo "							<td align=\"center\" width=\"35px\" title=\"".$jcnt."\">\n"; //0
					
					echo $row1['fsrep'].$row1['lsrep'];
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //1
	
					echo $row1['njobid'];
					
					echo "							</td>\n";
					echo "							<td align=\"left\" width=\"15px\">\n"; //2
					
					if ($row['constructdateaccess'] >= 5) {
						echo "				<div class=\"noPrint\">\n";
						echo "				<form method=\"POST\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						echo "					<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
						echo "					<input type=\"hidden\" name=\"cid\" value=\"".$row1['custid']."\">\n";
						echo "					<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "					<input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"8\" width=\"8\" alt=\"View Information\">\n";
						echo "				</form>\n";
						echo "				</div>\n";
					}
	
					echo "							</td>\n";
					echo "							<td align=\"left\" width=\"100px\">\n"; //3
					
					echo $row1['customer'];
					
					echo "							</td>\n";
					echo "							<td align=\"left\" width=\"75px\">\n"; //4
					
					echo $row1['city'];
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"60px\">\n"; //5
	
					if (valid_date(date('m/d/y',strtotime($row1['contractdate']))) && strtotime($row1['contractdate']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['contractdate']));
					}
	
					echo "							</td>\n";
					echo "							<td align=\"right\" width=\"70px\">\n"; //6
					
					echo number_format($row1['contractamt'],2,'.','');
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //7
					
					if (valid_date(date('m/d/y',strtotime($row1['permitout']))) && strtotime($row1['permitout']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['permitout']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //8
					
					if (valid_date(date('m/d/y',strtotime($row1['permitin']))) && strtotime($row1['permitin']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['permitin']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //9
					
					if (valid_date(date('m/d/y',strtotime($row1['digdate']))) && strtotime($row1['digdate']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['digdate']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //10
					
					if (valid_date(date('m/d/y',strtotime($row1['plumb']))) && strtotime($row1['plumb']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['plumb']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //11
					
					if (valid_date(date('m/d/y',strtotime($row1['steel']))) && strtotime($row1['steel']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['steel']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //12
					
					if (valid_date(date('m/d/y',strtotime($row1['gun']))) && strtotime($row1['gun']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['gun']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //13
					
					if (valid_date(date('m/d/y',strtotime($row1['elec']))) && strtotime($row1['elec']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['elec']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //14
					
					if (valid_date(date('m/d/y',strtotime($row1['tile']))) && strtotime($row1['tile']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['tile']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //15
					
					if (valid_date(date('m/d/y',strtotime($row1['deck']))) && strtotime($row1['deck']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['deck']));
					}
					
					echo "							</td>\n";
					echo "							<td align=\"center\" width=\"50px\">\n"; //16
					
					if (valid_date(date('m/d/y',strtotime($row1['plaster']))) && strtotime($row1['plaster']) >= strtotime('1/1/2000'))
					{
						echo date('m/d/y',strtotime($row1['plaster']));
					}
					
					echo "							</td>\n";
					
					echo "							<td align=\"left\" width=\"50px\">\n"; //17
					
					if (isset($row1['lstatus']) && strlen($row1['lstatus']) > 1)
					{
						echo $row1['lstatus'];
					}
					
					echo "							</td>\n";
					
					echo "							<td align=\"right\" width=\"50px\">\n"; //18
					
					echo number_format($row1['amtdue'],2,'.','');
					
					echo "							</td>\n";
					echo "							<td align=\"left\">\n"; //19
					
					$qry1A ="select top 1 mtext as cnotes from jest..construction_comments where cid=".$row1['custid']." order by mdate desc;";
					$res1A = mssql_query($qry1A);
					$nrow1A= mssql_num_rows($res1A);
					//echo $qry1A.'<br>';
					
					if ($nrow1A > 0)
					{
						//echo $qry1A.'<br>';
						$row1A= mssql_fetch_array($res1A);
						echo $row1A['cnotes'];
					}
					
					echo "							</td>\n";
					echo "						</tr>\n";
				}
	
				echo "					</tbody>\n";
				echo "				</table>\n";
			}
		
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
	}
	
}

function constructiondates_process()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//display_array($_REQUEST);
	
	$qry0 = "SELECT
				cid
				,jobid
				,(select psched from jdetail where jobid=C.jobid and jadd=0) as psched
				,(select contractdate from jdetail where jobid=C.jobid and jadd=0) as contrdate
				,(select digdate from jobs where jobid=C.jobid) as digdate
				,(select closed from jobs where jobid=C.jobid) as closed
				,(select closesec from jobs where jobid=C.jobid) as closesec
				,(select renov from jobs where jobid=C.jobid) as renov
			FROM
				jest..cinfo as C
			WHERE
				C.cid=".$_REQUEST['cid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	//echo $qry0.'<br>';
	
	if (isset($_REQUEST['condates']) && is_array($_REQUEST['condates']))
	{
		$c_phs=45;
		$e_phs=9;
		$a_phs=46;
		
		foreach ($_REQUEST['condates'] as $cn=>$cv)
		{			
			if (isset($cv['clear']) && $cv['clear']==1)
			{
				if ($cn!=$e_phs) //Clear non 508L Dates
				{
					$qryE = "DELETE FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."';";
					$resE = mssql_query($qryE);
						
					if ($cn==$a_phs) // Clear Job Table Close Date
					{
						$qryEcu  = "UPDATE jest..jobs SET closed=NULL, closesec=".$_SESSION['securityid']." ";
						$qryEcu .= "WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."';";
						$resEcu  = mssql_query($qryEcu);
							
						//echo $qryEcu.'<br>';
					}
				}
				else
				{
					if ($cn==$e_phs) // Clear 508L Date
					{
						$qryEc = "DELETE FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=3;";
						$resEc = mssql_query($qryEc);
						
						if ($row0['renov']==1)
						{
							$qryE0 = "DELETE FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=2;";
							$resE0 = mssql_query($qryE0);
							
							$qryEa = "select id,cdate FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=1;";
							$resEa = mssql_query($qryEa);
							$rowEa = mssql_fetch_array($resEa);
							$nrowEa = mssql_num_rows($resEa);
							
							if ($nrowEa == 1)
							{
								$prd_mo	= date("m", strtotime($rowEa['cdate']));
								$prd_yr	= date("Y", strtotime($rowEa['cdate']));
								$qryDD	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
								$resDD	= mssql_query($qryDD);
								$nrowDD	= mssql_num_rows($resDD);
							
								//$nrowDD=0; // Test Deleles if Dig Report exists
								if ($nrowDD==0)
								{
									$qryEc = "DELETE FROM jest..constructiondates WHERE id='".$rowEa['id']."';";
									$resEc = mssql_query($qryEc);
									DeleteAllCommissions($_SESSION['officeid'],$row0['jobid']);
									
									$qryEcu  = "UPDATE jest..jobs SET digdate=NULL, digsec=".$_SESSION['securityid']." ";
									$qryEcu .= "WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."';";
									$resEcu  = mssql_query($qryEcu);
								}
							}
						}
						elseif ($row0['renov']==0)
						{
							//echo 'Removing Non Renovation Entries<br>';
							$qryE0 = "DELETE FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=1;";
							$resE0 = mssql_query($qryE0);
							
							$qryEa = "select id,cdate FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=2;";
							$resEa = mssql_query($qryEa);
							$rowEa = mssql_fetch_array($resEa);
							$nrowEa = mssql_num_rows($resEa);
							
							//echo $qryEa.'<br>';
							if ($nrowEa==1)
							{
								///echo $qryEa.'<br>';
								$prd_mo	= date("m", strtotime($rowEa['cdate']));
								$prd_yr	= date("Y", strtotime($rowEa['cdate']));
								$qryDD	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
								$resDD	= mssql_query($qryDD);
								$nrowDD	= mssql_num_rows($resDD);
							
								//$nrowDD=0; // Test Deletes if Dig Report exists
								if ($nrowDD==0)
								{
									$qryEc = "DELETE FROM jest..constructiondates WHERE id='".$rowEa['id']."';";
									$resEc = mssql_query($qryEc);
									
									DeleteAllCommissions($_SESSION['officeid'],$row0['jobid']);
									
									$qryEcu  = "UPDATE jest..jobs SET digdate=NULL, digsec=".$_SESSION['securityid']." ";
									$qryEcu .= "WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."';";
									$resEcu  = mssql_query($qryEcu);
								}
							}
						}
					}
				}
			}
			else
			{
				if (isset($cv['sdate']) && valid_date($cv['sdate']) && strtotime($cv['sdate']) >= strtotime('1/1/2005'))
				{
					if ($cn==$e_phs and $row0['renov']==0 and
						valid_date(date('m/d/Y',strtotime($row0['contrdate']))) and
						(strtotime($row0['contrdate']) > strtotime($cv['sdate'])))
					{
						echo "<b>Error!</b><br>Contract Date cannot be greater than Renovation Dig Date<br>508L Date change did not process\n";
						
						//if ($_SESSION['securityid']==26)
						//{
						//	//echo $nrowE.'<br>';
						//	echo '<br>';
						//	echo strtotime($cv['edate']).'<br>';
						//	echo strtotime($row0['contrdate']).'<br>';
						//}
					}
					else
					{
						$qryS = "SELECT id,phsid,cid,cdate FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=1;";
						$resS = mssql_query($qryS);
						$rowS = mssql_fetch_array($resS);
						$nrowS= mssql_num_rows($resS);
						
						if ($nrowS==0)
						{
							//Insert
							$qrySi  = "INSERT INTO jest..constructiondates (cid,phsid,jobid,cdate,dtype,auid) VALUES (";
							$qrySi .= "".$_REQUEST['cid'].",".$cn.",'".$_REQUEST['jobid']."','".$cv['sdate']."',1,".$_SESSION['securityid'].");";
							$resSi = mssql_query($qrySi);
							
							if ($cn==$e_phs and (isset($row0['renov']) and $row0['renov']==1))
							{
								set_digdate_new($_SESSION['officeid'],$row0['contrdate'],$cv['sdate'],$row0['jobid'],1);
							}
						}
						elseif ($nrowS==1 && strtotime($cv['sdate'])!=strtotime($rowS['cdate']))
						{
							//Update
							$qrySu  = "UPDATE jest..constructiondates SET cdate='".$cv['sdate']."',udate=getdate(),uuid=".$_SESSION['securityid']." ";
							$qrySu .= "WHERE id=".$rowS['id'].";";
							$resSu = mssql_query($qrySu);
							
							//echo $qrySu.'<br>';
							
							if ($cn==$e_phs and (isset($row0['renov']) and $row0['renov']==1))
							{
								set_digdate_new($_SESSION['officeid'],$row0['contrdate'],$cv['sdate'],$row0['jobid'],0);
							}
						}
						elseif ($nrowS > 1)
						{
							echo "Error occured, Start Date not processed contact Support (".$cn.") <br>";
						}
						
						//echo 'B    : '.$cn.'<br>';
						//echo 'CNT  : '.$nrowS.'<br>';
						//echo 'Start: '.$cv['sdate'].'<br>---<br>';
					}
				}

				if (isset($cv['edate']) && valid_date($cv['edate']) && strtotime($cv['edate']) >= strtotime('1/1/2005'))
				{
					if ($cn==$e_phs and $row0['renov']==0 and
						valid_date(date('m/d/Y',strtotime($row0['contrdate']))) and
						(strtotime($row0['contrdate']) > strtotime($cv['edate'])))
					{
						echo "<b>Error!</b><br>Contract Date cannot be greater than Dig Date<br>508L Date change did not process\n";
						
						//if ($_SESSION['securityid']==26)
						//{
						//	//echo $nrowE.'<br>';
						//	echo '<br>';
						//	echo strtotime($cv['edate']).'<br>';
						//	echo strtotime($row0['contrdate']).'<br>';
						//}
					}
					else
					{
						$qryE = "SELECT id,phsid,cid,cdate FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=2;";
						$resE = mssql_query($qryE);
						$rowE = mssql_fetch_array($resE);
						$nrowE= mssql_num_rows($resE);
						
						if ($_SESSION['securityid']==269999999999)
						{
							//echo <br>';
							echo 'Enter1<br>';
						}
						
						if ($nrowE==0)
						{
							//Insert
							$qryEi  = "INSERT INTO jest..constructiondates (cid,phsid,jobid,cdate,dtype,auid) VALUES (";
							$qryEi .= "".$_REQUEST['cid'].",".$cn.",'".$_REQUEST['jobid']."','".$cv['edate']."',2,".$_SESSION['securityid'].");";
							$resEi = mssql_query($qryEi);
							
							if ($cn==$e_phs and (isset($row0['renov']) and $row0['renov']==0))
							{
								//echo 'Inserting...<br>';
								set_digdate_new($_SESSION['officeid'],$row0['contrdate'],$cv['edate'],$row0['jobid'],1);
							}
						}
						elseif ($nrowE==1 and strtotime($cv['edate'])!=strtotime($rowE['cdate']))
						//elseif ($nrowE==1)
						{
							$qryEu  = "UPDATE jest..constructiondates SET cdate='".$cv['edate']."',udate=getdate(),uuid=".$_SESSION['securityid']." ";
							$qryEu .= "WHERE id=".$rowE['id'].";";
							$resEu = mssql_query($qryEu);
							
							if ($_SESSION['securityid']==269999999999999999)
							{
							//	echo $qryEu.'<br>';
								echo 'Enter2<br>';
							}
							
							if ($cn==$e_phs and (isset($row0['renov']) and $row0['renov']==0))
							{
								if ($_SESSION['securityid']==26999999999999999)
								{
								//	echo $qryEu.'<br>';
									echo 'Enter3<br>';
								}
								set_digdate_new($_SESSION['officeid'],$row0['contrdate'],$cv['edate'],$row0['jobid'],0);
							}
						}
						elseif ($nrowE > 1)
						{
							echo "Error occured, End Date not processed contact Support (".$cn.") <br>";
						}
						
						if ($cn==$a_phs && strtotime($cv['edate'])!=strtotime($row0['closed'])) // Close
						{
							$qryEc2  = "UPDATE jest..jobs SET closed='".$cv['edate']."', closesec=".$_SESSION['securityid']." ";
							$qryEc2 .= "WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."';";
							$resEc2 = mssql_query($qryEc2);
						}
					}
				}
				
				if (isset($cv['rdate']) && valid_date($cv['rdate']) && strtotime($cv['rdate']) >= strtotime('1/1/2005'))
				{
					$qryR = "SELECT id,phsid,cid,cdate,ramt FROM jest..constructiondates WHERE jobid='".$_REQUEST['jobid']."' AND phsid='".$cn."' and dtype=3;";
					$resR = mssql_query($qryR);
					$rowR = mssql_fetch_array($resR);
					$nrowR= mssql_num_rows($resR);
					
					if ($nrowR==0)
					{
						//Insert
						$qryRi  = "INSERT INTO jest..constructiondates (cid,phsid,jobid,cdate,dtype,ramt,auid) VALUES (";
						$qryRi .= "".$_REQUEST['cid'].",".$cn.",'".$_REQUEST['jobid']."','".$cv['rdate']."',3,convert(money,'".number_format($cv['ramt'],2,'.','')."'),".$_SESSION['securityid'].");";
						$resRi = mssql_query($qryRi);
						
						//echo $qryRi.'<br>';
					}
					elseif ($nrowR==1 && (strtotime($cv['rdate'])!=strtotime($rowR['cdate']) || number_format($cv['ramt'],2,'.','')!=number_format($rowR['ramt'],2,'.','') ))
					{
						//Update
						$qryRu  = "UPDATE jest..constructiondates SET cdate='".$cv['rdate']."',ramt=convert(money,'".number_format($cv['ramt'],2,'.','')."'),udate=getdate(),uuid=".$_SESSION['securityid']." ";
						$qryRu .= "WHERE id=".$rowR['id'].";";
						$resRu = mssql_query($qryRu);
						
						//echo $qryRu.'<br>';
					}
					elseif ($nrowR > 1)
					{
						echo "Error occured, End Date not processed contact Support (".$cn.") <br>";
					}
					//echo 'R  : '.$cn.'<br>';
					//echo 'CNT: '.$nrowR.'<br>';
					//echo 'End: '.$cv['rdate'].'<br>';
				}
			}
		}
	}
	
	ini_set('display_errors','Off');
	chistory_list();
}

function set_digdate_new($oid,$cdate,$ddate,$jobid,$setupd)
{
	$dd	=$ddate . " 00:01";
	$ct	=strtotime($cdate);
	$dt	=strtotime($ddate);
	
	if ($_SESSION['securityid']==2699999999999999999999)
	{
		echo 'UpdateSUB<br>';
		echo $dt.'<br>';
		echo $ct.'<br>';
	}
	
	if ((isset($dt) and isset($ct)) and $dt >= $ct)
	{
		$qrypre0 = "SELECT estid,jobid,added FROM est WHERE officeid=".$oid." AND jobid='".$jobid."';";
		$respre0 = mssql_query($qrypre0);
		$rowpre0 = mssql_fetch_array($respre0);

		$qrypreA = "SELECT jobid,added FROM jdetail WHERE officeid=".$oid." AND jobid='".$jobid."' AND jadd='0';";
		$respreA = mssql_query($qrypreA);
		$rowpreA = mssql_fetch_array($respreA);

		$qrypreB = "SELECT jobid,securityid,digdate FROM jobs WHERE officeid=".$oid." AND jobid='".$jobid."';";
		$respreB = mssql_query($qrypreB);
		$rowpreB = mssql_fetch_array($respreB);

		$qrypreC = "SELECT securityid,newcommdate FROM security WHERE securityid=".$rowpreB['securityid'].";";
		$respreC = mssql_query($qrypreC);
		$rowpreC = mssql_fetch_array($respreC);
		
		$prd_mo	= date("m", $dt);
		$prd_yr	= date("Y", $dt);
		
		$qryDD	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
		$resDD	= mssql_query($qryDD);
		$nrowDD	= mssql_num_rows($resDD);

		if (isset($setupd) && $setupd==1)
		{
			$qry = "UPDATE jobs SET digdate='".$dd."',digsec='".$_SESSION['securityid']."' WHERE officeid=".$oid." AND jobid='".$jobid."';";
			$res = mssql_query($qry);
			
			$qrypreD = "SELECT count(hid) as chid FROM jest..CommissionHistory WHERE oid=".$oid." AND jobid='".$jobid."';";
			$respreD = mssql_query($qrypreD);
			$rowpreD = mssql_fetch_array($respreD);
			
			if ($rowpreD['chid'] == 0)
			{
				if (strtotime($rowpre0['added']) >= strtotime($rowpreC['newcommdate']))
				{
					PullStoreCommissions($oid,$jobid);
				}
				else
				{
					PullandStoreSingleCommission($oid,$jobid);
				}
			}
		}
		else
		{
			if ($nrowDD==0 and $ddate!=strtotime($rowpreB['digdate']))
			{
				if (strtotime($rowpre0['added']) >= strtotime($rowpreC['newcommdate']))
				{
					echo 'Calling New Commission Storage...';
					$qry = "UPDATE jobs SET digdate='".$dd."',digsec='".$_SESSION['securityid']."' WHERE officeid=".$oid." AND jobid='".$jobid."';";
					$res = mssql_query($qry);
		
					DeleteAllCommissions($oid,$jobid);
					PullStoreCommissions($oid,$jobid);
				}
				else
				{
					echo 'Calling Old Commission Storage...';
					$qry = "UPDATE jobs SET digdate='".$dd."',digsec='".$_SESSION['securityid']."' WHERE officeid=".$oid." AND jobid='".$jobid."';";
					$res = mssql_query($qry);
		
					DeleteAllCommissions($oid,$jobid);
					PullandStoreSingleCommission($oid,$jobid);
				}
			}
		}
	}
	else
	{
		echo "<b>Error!</b><br>Date Error or Contract Date is greater than Dig Date<br>Update did not process\n";
	}
}

function mas_import_prep_sql()
{
	// Builds Dataset for MAS Import
	$va	  =$_SESSION['viewarray'];
	$oid  =$_SESSION['officeid'];
	$jobid=$_REQUEST['njobid'];
	
	$qry0 = "SELECT * FROM jobs WHERE officeid='".$oid."' AND njobid='".$jobid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		
	}
}

function set_mas() {
	$va=$_SESSION['viewarray'];
	//show_post_vars();
	if (isset($_REQUEST['setmas']) && $_REQUEST['setmas']==0 || $_REQUEST['setmas']==1)
	{
		//echo "YY<br>";
		//show_post_vars();
		$qry1 = "UPDATE cinfo SET mas_prep='".$_REQUEST['setmas']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
		$res1 = mssql_query($qry1);
		
		$qry0 = "
				select 
					o.processor as prc,
					o.name as name,
					s.email as email
				from 
					offices as o
				inner join
					security as s
				on
					o.processor=s.securityid
				where 
					o.officeid='".$_SESSION['officeid']."';
				";
		
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		/*
		if (
				isset($row0['prc'])
				&& $row0['prc']!=0
				&& valid_email_addr(trim($row0['email']))
			)
		{
			//echo $qry0."<br>";
			$qry2 = "SELECT officeid,securityid,clname,cfname FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			
			$to		= array();
			$to[]	= trim($row0['email']);
			$to[]	= 'sschirmer@corp.bluehaven.com';
			//$to[]	= 'thelton@corp.bluehaven.com';
			
			if (isset($_REQUEST['setmas']) && $_REQUEST['setmas']==1)
			{
				$sub	 = "".$va['masjobid']." - ".$row2['clname']." - Job MAS Ready";
				$mess	 = "Status : MAS Ready\r\n";
			}
			else
			{
				$sub	 = "".$va['masjobid']." - ".$row2['clname']." - Job NOT MAS Ready";
				$mess	 = "Status : NOT MAS Ready\r\n";
			}
			
			$mess	.= "Cust   : ".$row2['cfname']." ".$row2['clname']."\r\n";
			$mess	.= "----------------------\r\n";
			$mess	.= "Rel By : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
			$mess	.= "LHost  : ".$_SERVER['SERVER_NAME']."\r\n";
			$mess	.= "RHost  : ".getenv('REMOTE_ADDR')."\r\n";
			
			SendSystemEmail('jmsadmin@bhnmi.com',$to,$sub,$mess);
		}
		*/
	}
	view_job_retail();
}

function updtsalesrep()
{
	error_reporting(E_ALL);
		
	$qry0 = "SELECT sidm FROM security WHERE securityid='".$_REQUEST['secid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	if (isset($_REQUEST['sidm']) && $_REQUEST['sidm']!=$row0['sidm'])
	{
		$sidm=$_REQUEST['sidm'];
	}
	else
	{
		$sidm=$row0['sidm'];
	}
	
	$qry  = "exec sp_set_job_securityid @secid=".$_REQUEST['secid'].",@manid=".$sidm.",@lupdt='".$_SESSION['securityid']."',@officeid='".$_SESSION['officeid']."',@njobid='".$_REQUEST['njobid']."';";
	$res = mssql_query($qry);

	view_job_retail();
}

function updtsetrenov()
{
	error_reporting(E_ALL);
	
	$qry  = "exec sp_set_renov @officeid='".$_SESSION['officeid']."',@njobid='".$_REQUEST['njobid']."',@setrenov='".$_REQUEST['setrenov']."';";
	$res = mssql_query($qry);

	//echo $qry."<br>";
	view_job_retail();
}

function set_postmas_proc()
{
	$qry = "UPDATE jdetail SET pmasreq=1,lupdate='".$_SESSION['securityid']."',lupdtime=getdate() WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$res = mssql_query($qry);
	//$row = mssql_fetch_array($res);

	//build_post_mas_addendum_view();
	view_job_addendum_retail();
}

function build_post_mas_addendum_view()
{
	global $viewarray,$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$estidret,$taxrate;
	$MAS=$_SESSION['pb_code'];

	//echo $_REQUEST['estid']." EST<br>";
	//echo $_REQUEST['jobid']." JOB<br>";
	//echo $_REQUEST['jadd']." JOB<br>";

	if (!isset($_REQUEST['njobid'])||$_REQUEST['njobid']=='')
	{
		echo "Fatal Error: Contract ID Error!";
		exit;
	}

	if (!isset($_REQUEST['jadd'])||$_REQUEST['jadd']=='')
	{
		echo "Fatal Error: Addn ID Error!";
		exit;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreC = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	//echo $qrypreA."<br>";

	$addcnt=$_REQUEST['jadd'];

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>		$rowpreA['spa_type'],
	'spa2'=>		$rowpreA['spa_pft'],
	'spa3'=>		$rowpreA['spa_sqft'],
	'tzone'=>		$rowpreA['tzone'],
	'camt'=>		$rowpreA['contractamt'],
	'status'=>		$rowpreC['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'custid'=>		$rowpreC['custid'],
	'estsecid'=>	$rowpreC['securityid'],
	'jobsecid'=>	$rowpreC['securityid'],
	'deck'=>		$rowpreA['deck'],
	'erun'=>		$rowpreA['erun'],
	'prun'=>		$rowpreA['prun'],
	'njobid'=>		$rowpreA['njobid'],
	'jadd'=>		$rowpreA['jadd'],
	'refto'=>		$rowpreA['refto'],
	'ps1a'=>		$rowpreA['apft'],
	'added'=>		$rowpreA['added']
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryB1 = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resB1 = mssql_query($qryB1);
	$rowB1 = mssql_fetch_array($resB1);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}

	$adate		=date("m/d/Y");
	$estidret   =$rowpreC['estid'];
	$vdiscnt    =0;
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	if ($rowpreA['pmasreq']==1)
	{
		$tbg	= "lightgreen";
	}
	elseif ($rowpreA['post_add']==1)
	{
		$tbg	= "yellow";
	}
	else
	{
		$tbg	= "gray";
	}

	//print_r($rowpreA);

	echo "			<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"2\" class=\"".$tbg."\" align=\"left\"><b>Post MAS 60".$addcnt."L Addendum Notes</b></td>\n";
	echo "			               <td class=\"".$tbg."\" align=\"right\"><b>Date:</b> ".$adate."</td>\n";
	echo "								<td colspan=\"2\" class=\"".$tbg."\" align=\"right\"><b>Addendum # <font color=\"red\">".$addcnt."</font> for Job # <font color=\"red\">".$viewarray['njobid']."</font></b></td>\n";
	echo "								</td>\n";
	echo "            			</tr>\n";
	echo "            			<tr>\n";
	echo "			               <td class=\"".$tbg."\" align=\"right\"><b>Customer:</b></td>\n";
	echo "								<td class=\"".$tbg."\" align=\"left\">".$rowI['clname'].", ".$rowI['cfname']."</td>\n";
	echo "			               <td class=\"".$tbg."\" align=\"right\"></td>\n";
	echo "								<td class=\"".$tbg."\" align=\"right\"><b>Salesman:</b></td>\n";
	echo "								<td class=\"".$tbg."\" align=\"left\"> ".$rowD['lname'].", ".$rowD['fname']."</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	//echo "            			<tr>\n";
	//echo "               			<td colspan=\"5\" class=\"gray\" align=\"left\"><b> 60".$addcnt."L Addendum Notes:</b></td>\n";
	//echo "							</tr>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "									<textarea name=\"comments\" rows=\"5\" cols=\"95\">".$rowpreA['comments']."</textarea>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	/*
	echo "            			<tr>\n";
	echo "               			<td colspan=\"5\" class=\"gray\" align=\"right\">\n";
	echo "         						<table align=\"right\" border=0>\n";
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>Retail Pay Schedule Adjust:</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"pschedadj\" value=\"".$rowpreA['psched_adj']."\"></td>\n";
	echo "										</tr>\n";
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>Commission Adjust:</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"comadj\" value=\"".$rowpreA['raddncm_man']."\"></td>\n";
	echo "										</tr>\n";
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>60".$addcnt."L Cost Adjust:</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"cstadj\" value=\"".$rowpreA['raddncs_man']."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	*/
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";

	if ($_SESSION['jlev'] >= 999)
	{
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"set_postmas_proc\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"right\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Process\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "               </td>\n";
		echo "</form>\n";
		echo "            </tr>\n";
	}

	echo "			</table>\n";
}

function parse_filter_diffs($old,$new,$pmas)
{
	// This function detects Cost ADDs, DELs, CHNGs for Addendums
	global $viewarray;

	$c_cnt		=0;
	$cchg_ar		='';
	$tt_chg_ar	='';
	$old=preg_replace("/,\Z/","",$old);
	$new=preg_replace("/,\Z/","",$new);
	$ex_old=explode(",",$old);
	$ex_new=explode(",",$new);
	$ar_diff=filter_diffs_job($ex_new,$ex_old,$pmas);

	if ($pmas!=1)
	{
		foreach ($ar_diff as $vA2)
		{
			foreach ($vA2 as $vA3)
			{
				$in_nA2=explode(":",$vA3);
				if ($in_nA2[0])
				{
					$cchg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].":".$in_nA2[8].",";
					//echo "FILTERS: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].":".$in_nA2[8]."<br>";
					$tt_chg_ar=$tt_chg_ar.$cchg_ar;
					$c_cnt++;
				}
			}
		}
	}
	
	$tt_chg_ar=preg_replace("/,\Z/","",$tt_chg_ar);
	$diff_out=array(0=>0,1=>0,2=>0,3=>$c_cnt,4=>$tt_chg_ar);
	return $diff_out;
}

function parse_filter_cost_diffs($filters,$pmas)
{
	$MAS			=$_SESSION['pb_code'];
	$filters		=preg_replace("/,\Z/","",$filters);
	$c_cnt		=0;
	$l_chg		='';
	$m_chg		='';
	$tl_chg		='';
	$tm_chg		='';

	//echo "C: ".$costs."<br>";
	//echo "F: ".$filters."<br>";

	$ifilters	=explode(",",$filters);
	foreach ($ifilters as $n1=>$v1)
	{
		$in_v1	=explode(":",$v1);
		
		$qry0a		="SELECT disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$in_v1[1]."';";
		$res0a		=mssql_query($qry0a);
		$row0a		=mssql_fetch_array($res0a);
		
		//echo $qry0a."<br>";
		
		if ($row0a['disabled']!=1)
		{
			$qry1		="SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v1[1]."';";
			$res1		=mssql_query($qry1);
			$row1		=mssql_fetch_array($res1);
			$nrow1	=mssql_num_rows($res1);
	
			if ($nrow1 > 0)
			{
				//echo $qry1."<br>";
				$qry1a	= "SELECT bprice FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1['cid']."';";
				$res1a	= mssql_query($qry1a);
				$row1a	= mssql_fetch_array($res1a);
				$nrow1a	= mssql_num_rows($res1a);
	
				if ($nrow1a > 0)
				{
					//echo $qry1a."<br>";
					$lbp	=number_format($row1a['bprice'], 2, '.', '');
				}
				else
				{
					$lbp	="0.00";
				}
	
				$l_chg	=$in_v1[0].":".$in_v1[1].":".$in_v1[6].":".$in_v1[7].":".$in_v1[8].":".$row1['cid'].":".$lbp.",";
				$tl_chg	=$tl_chg.$l_chg;
			}
		}
	}

	//echo "<hr>";

	foreach ($ifilters as $n2=>$v2)
	{
		$in_v2	=explode(":",$v2);
		
		$qry0b		="SELECT disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$in_v2[1]."';";
		$res0b		=mssql_query($qry0b);
		$row0b		=mssql_fetch_array($res0b);
		
		//echo $qry0b."<br>";
		
		if ($row0a['disabled']!=1)
		{
			$qry2		="SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v2[1]."';";
			$res2		=mssql_query($qry2);
			$row2		=mssql_fetch_array($res2);
			$nrow2	=mssql_num_rows($res2);
	
			if ($nrow2 > 0)
			{
				//echo $qry2."<br>";
				$qry2a	="SELECT bprice,matid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row2['cid']."';";
				$res2a	=mssql_query($qry2a);
				$row2a	=mssql_fetch_array($res2a);
				$nrow2a	=mssql_num_rows($res2a);
	
				if ($nrow2a > 0)
				{
					//echo $qry2a."<br>";
					if ($row2a['matid']!=0)
					{
						$qry2aa	="SELECT bp,id FROM [material_master] WHERE id='".$row2a['matid']."';";
						$res2aa	=mssql_query($qry2aa);
						$row2aa	=mssql_fetch_array($res2aa);
						$nrow2aa	=mssql_num_rows($res2aa);
	
						//echo $qry2aa."<br>";
						$mbp		=number_format($row2aa['bp'], 2, '.', '');
					}
					else
					{
						$mbp	=number_format($row2a['bprice'], 2, '.', '');
					}
				}
				else
				{
					$mbp	="0.00";
				}
				//echo "MRID: ".$row2['rid']."<br>";
				//echo "MCID: ".$row2['cid']."<br>----<br>";
				//1411:2343:4:0.00:7:982:0.00
				//echo "MAT: ".$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$in_v2[2]."<br>";
				$m_chg	=$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$mbp.",";
				$tm_chg	=$tm_chg.$m_chg;
			}
		}
	}

	$tl_chg		=preg_replace("/,\Z/","",$tl_chg);
	$tm_chg		=preg_replace("/,\Z/","",$tm_chg);

	//echo $tl_chg."<br>";
	//echo $tm_chg."<br>";
	$diff_out=array(0=>0,1=>0,2=>0,3=>$c_cnt,4=>$tl_chg,5=>$tm_chg);
	return $diff_out;
}

function filter_diffs_job(&$a1,&$a2,$pmas)
{
	$r_add	=array();
	$r_del	=array();
	$r_cng	=array();
	$rr_del	=array();

	foreach ($a1 as $pl) // Add Construct
	{
		if (! in_array($pl, $a2, true) )
		{
			$r_add[] = $pl;
		}
	}

	foreach ($a2 as $pl) // Del Construct
	{
		if (! in_array($pl, $a1, true) && ! in_array($pl, $r_add, true) )
		{
			$r_del[] = $pl;
		}
	}

	$adjtypeflag	=0;
	$adjpriceflag	=0;
	$adjquanflag	=0;

	if ($pmas!=1)
	{
		foreach ($r_add as $n1a=>$pla) // Diff Construct
		{
			$ri_add=explode(":",$pla);
			foreach ($r_del as $n1d=>$pld)
			{
				$ri_del=explode(":",$pld);
				if ($ri_del[0]==$ri_add[0])
				{
					if ($ri_del[1]==$ri_add[1])
					{
						if ($ri_add[6]!=$ri_del[6])
						{
							$n_adjtype	=$ri_add[6];
							$adjtypeflag++;
						}
						else
						{
							$n_adjtype	=$ri_del[6];
						}
	
						if ($ri_add[7]!=$ri_del[7])
						{
							$n_adjprice	=$ri_add[7]-$ri_del[7];
							$adjpriceflag++;
						}
						else
						{
							$n_adjprice	=$ri_add[7];
						}
	
						if ($ri_add[8]!=$ri_del[8])
						{
							$n_adjquan	=$ri_add[8]-$ri_del[8];
							$adjquanflag++;
						}
						else
						{
							$n_adjquan	=$ri_add[8];
						}
	
						if ($adjtypeflag > 0 ||	$adjpriceflag > 0 ||$adjquanflag > 0)
						{
							$r_cng[]=$ri_add[0].":".$ri_add[1].":".$ri_add[2].":".$ri_add[3].":".$ri_add[4].":".$ri_add[5].":".$n_adjtype.":".$n_adjprice.":".$n_adjquan;
	
							unset($r_add[$n1a]);
							unset($r_del[$n1d]);
						}
					}
				}
				$adjtypeflag	=0;
				$adjpriceflag	=0;
				$adjquanflag	=0;
			}
		}
	}

	foreach ($r_del as $n2d=>$p2d)
	{
		$r2_del=explode(":",$p2d);
		$rr_del[]=$r2_del[0].":".$r2_del[1].":".$r2_del[2].":".$r2_del[3].":".$r2_del[4].":".$r2_del[5].":".$r2_del[6].":".$r2_del[7].":".$r2_del[8]*-1;
	}

	/*
	echo "<pre>";
	echo "ADDS: <br>";
	array2table($r_add);
	echo "DELS: <br>";
	array2table($r_del);
	echo "RDELS: <br>";
	array2table($rr_del);
	echo "CNGH: <br>";
	array2table($r_cng);
	echo "</pre>";
	*/

	$r_out=array(0=>$r_add,1=>$rr_del,2=>$r_cng);
	return $r_out;
}

function parse_filter_cost_diffsold($filters,$pmas)
{
	$MAS			=$_SESSION['pb_code'];
	$filters		=preg_replace("/,\Z/","",$filters);
	$c_cnt		=0;
	$l_chg		='';
	$m_chg		='';
	$tl_chg		='';
	$tm_chg		='';

	//echo "C: ".$costs."<br>";
	//echo "F: ".$filters."<br>";

	$ifilters	=explode(",",$filters);
	foreach ($ifilters as $n1=>$v1)
	{
		$in_v1	=explode(":",$v1);
		$qry1		="SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v1[1]."';";
		$res1		=mssql_query($qry1);
		$row1		=mssql_fetch_array($res1);
		$nrow1	=mssql_num_rows($res1);

		if ($nrow1 > 0)
		{
			//echo $qry1."<br>";
			$qry1a	= "SELECT bprice FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1['cid']."';";
			$res1a	= mssql_query($qry1a);
			$row1a	= mssql_fetch_array($res1a);
			$nrow1a	= mssql_num_rows($res1a);

			if ($nrow1a > 0)
			{
				//echo $qry1a."<br>";
				$lbp	=number_format($row1a['bprice'], 2, '.', '');
			}
			else
			{
				$lbp	="0.00";
			}

			$l_chg	=$in_v1[0].":".$in_v1[1].":".$in_v1[6].":".$in_v1[7].":".$in_v1[8].":".$row1['cid'].":".$lbp.",";
			$tl_chg	=$tl_chg.$l_chg;
		}
	}

	//echo "<hr>";

	foreach ($ifilters as $n2=>$v2)
	{
		$in_v2	=explode(":",$v2);
		$qry2		="SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v2[1]."';";
		$res2		=mssql_query($qry2);
		$row2		=mssql_fetch_array($res2);
		$nrow2	=mssql_num_rows($res2);

		if ($nrow2 > 0)
		{
			//echo $qry2."<br>";
			$qry2a	="SELECT bprice,matid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row2['cid']."';";
			$res2a	=mssql_query($qry2a);
			$row2a	=mssql_fetch_array($res2a);
			$nrow2a	=mssql_num_rows($res2a);

			if ($nrow2a > 0)
			{
				//echo $qry2a."<br>";
				if ($row2a['matid']!=0)
				{
					$qry2aa	="SELECT bp,id FROM [material_master] WHERE id='".$row2a['matid']."';";
					$res2aa	=mssql_query($qry2aa);
					$row2aa	=mssql_fetch_array($res2aa);
					$nrow2aa	=mssql_num_rows($res2aa);

					//echo $qry2aa."<br>";
					$mbp		=number_format($row2aa['bp'], 2, '.', '');
				}
				else
				{
					$mbp	=number_format($row2a['bprice'], 2, '.', '');
				}
			}
			else
			{
				$mbp	="0.00";
			}
			//echo "MRID: ".$row2['rid']."<br>";
			//echo "MCID: ".$row2['cid']."<br>----<br>";
			//1411:2343:4:0.00:7:982:0.00
			//echo "MAT: ".$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$in_v2[2]."<br>";
			$m_chg	=$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$mbp.",";
			$tm_chg	=$tm_chg.$m_chg;
		}
	}

	$tl_chg		=preg_replace("/,\Z/","",$tl_chg);
	$tm_chg		=preg_replace("/,\Z/","",$tm_chg);

	//echo $tl_chg."<br>";
	//echo $tm_chg."<br>";
	$diff_out=array(0=>0,1=>0,2=>0,3=>$c_cnt,4=>$tl_chg,5=>$tm_chg);
	return $diff_out;
}

function create_addendum()
{
	build_addendum_start();
	//build_post_mas_addendum_start();
}

function edit_add_price()
{
	//display_array($_POST);
	
	if (empty($_REQUEST['royadj']))
	{
		$royadj=0;
	}
	else
	{
		$royadj=$_REQUEST['royadj'];
	}

	$prmanadj	=$_REQUEST['prmanadj'];
	$cmmanadj	=$_REQUEST['cmmanadj'];
	$pschadj	=$_REQUEST['pschadj'];
	
	if (isset($_REQUEST['add_type']) and $_REQUEST['add_type']==1)
	{
		$qry  = "UPDATE jdetail SET ";
		$qry .= "raddnpr_man='".$prmanadj."',";
		$qry .= "raddncm_man='".$cmmanadj."',";
		$qry .= "raddnroy_man='".$royadj."',";
		$qry .= "psched_adj='".$pschadj."' ";
		$qry .= "WHERE officeid='".$_SESSION['officeid']."' ";
		$qry .= "AND njobid='".$_REQUEST['njobid']."' ";
		$qry .= "AND jadd='".$_REQUEST['jadd']."';";
		$res  = mssql_query($qry);
	}
	else
	{
		$qry  = "UPDATE jdetail SET ";
		$qry .= "raddnpr_man='".$pschadj."',";
		$qry .= "raddncm_man='".$cmmanadj."',";
		$qry .= "raddnroy_man='".$royadj."',";
		$qry .= "psched_adj='".$pschadj."' ";
		$qry .= "WHERE officeid='".$_SESSION['officeid']."' ";
		$qry .= "AND njobid='".$_REQUEST['njobid']."' ";
		$qry .= "AND jadd='".$_REQUEST['jadd']."';";
		$res  = mssql_query($qry);
	}
	
	if ($_SESSION['securityid']==26999999999999999999)
	{
		echo $qry.'<br>';
	}
	
	if (isset($_REQUEST['addsecid']) and $_REQUEST['addsecid']!=0)
	{
		$qry0 = "SELECT cmid FROM CommissionBuilder WHERE oid='".$_SESSION['officeid']."' AND active=1;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "SELECT hid FROM CommissionHistory WHERE jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);
			
			if ($nrow1!=0)
			{
				$qry2 = "UPDATE CommissionHistory SET udate=getdate(),rate='0.0',ratetype=1,amt=convert(money,'".$cmmanadj."'),secid=".$_REQUEST['addsecid']." WHERE hid='".$row1['hid']."';";
				$res2 = mssql_query($qry2);
			}
			else
			{
				if (isset($cmmanadj) and $cmmanadj!=0)
				{
					$qry2  = "INSERT INTO CommissionHistory ";
					$qry2 .= "(oid,secid,jobid,jadd,njobid,cid,descrip,htype,cbtype,ratetype,amt,rate) VALUES (";
					$qry2 .= ") VALUES (";
					$qry2 .= "".$_SESSION['officeid'].",";
					$qry2 .= "".$_REQUEST['addsecid'].",";
					$qry2 .= "".$_REQUEST['jobid'].",";
					$qry2 .= "".$_REQUEST['jadd'].",";
					$qry2 .= "".$_REQUEST['njobid'].",";
					$qry2 .= "".$_REQUEST['cid'].",";
					$qry2 .= "'SRA ".$_REQUEST['jadd']."',";
					$qry2 .= "'N',";
					$qry2 .= "3,";
					$qry2 .= "1,";
					$qry2 .= "'".$cmmanadj."',";
					$qry2 .= "'0.0');";
					$res2 = mssql_query($qry2);
				}
			}
		}
	}

	view_job_addendum_retail();
}

function parse_diffs($old,$new,$pmas,$a,$d)
{
	// This function detects Cost ADDs, DELs, CHNGs for Addendums
	$diff_out	=1;
	$ar_price	=0;
	$dr_price	=0;
	$cr_price	=0;
	$ac_price	=0;
	$dc_price	=0;
	$cc_price	=0;
	$c_cnt		=0;
	$t_achg_ar	="";
	$t_dchg_ar	="";
	$t_cchg_ar	="";
	$tt_chg_ar	="";
	$old_ar		=array();
	$new_ar		=array();

	$old=preg_replace("/,\Z/","",$old);
	$new=preg_replace("/,\Z/","",$new);
	//echo "OLD: ".$old."<br>";
	//echo "NEW: ".$new."<br>";

	//Start Variance Detection
	$ex_old=explode(",",$old);
	foreach ($ex_old as $n1 => $v1)
	{
		$in_old=explode(":",$v1);
		if (!in_array($in_old[0],$old_ar))
		{
			$old_ar[]=$in_old[0];
		}
	}

	$ex_new=explode(",",$new);
	foreach ($ex_new as $n2 => $v2)
	{
		$in_new=explode(":",$v2);
		if (!in_array($in_new[0],$new_ar))
		{
			$new_ar[]=$in_new[0];
		}
	}
	
	/*
	echo "<pre>";
	echo "OLD: <br>";
	print_r($old_ar);
	echo "NEW: <br>";
	print_r($new_ar);
	echo "</pre>";
	*/

	$add_ar_diff=array_diff($new_ar,$old_ar);
	$del_ar_diff=array_diff($old_ar,$new_ar);
	$inter_ar=array_intersect($old_ar,$new_ar);

	//unset($new_ar);
	//unset($old_ar);

	/*
	echo "<pre>";
	echo "ADD: <br>";
	print_r($add_ar_diff);
	echo "DEL: <br>";
	print_r($del_ar_diff);
	echo "INT: <br>";
	print_r($inter_ar);
	echo "</pre>";
	*/
	
	/*
	if (in_array("",$add_ar_diff)||in_array(0,$add_ar_diff))
	{
	echo "Found Empty in ADD<br>";
	}

	if (in_array("",$del_ar_diff)||in_array(0,$del_ar_diff))
	{
	echo "Found Empty in DEL<br>";
	}

	if (in_array("",$inter_ar))
	{
	echo "Found Empty in INT<br>";
	}
	*/

	foreach ($add_ar_diff as $nA1 => $vA1)
	{
		foreach ($ex_new as $nA2 => $vA2)
		{
			$in_nA2=explode(":",$vA2);
			if ($vA1==$in_nA2[0])
			{
				if ($pmas!=1)
				{
					$achg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[2].":".$in_nA2[10].":0,";
					//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[2].":".$in_nA2[10]."<br>";
					$t_achg_ar=$t_achg_ar.$achg_ar;
					$c_cnt++;
				}
				else
				{
					if (in_array($in_nA2[0],$a))
					{
						$achg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[2].":".$in_nA2[10].":0,";
						//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[2].":".$in_nA2[10]."<br>";
						$t_achg_ar=$t_achg_ar.$achg_ar;
					}
					$c_cnt++;
				}
			}
		}
	}

	//DEL Diffs;
	foreach ($del_ar_diff as $nD1 => $vD1)
	{
		foreach ($ex_old as $nD2 => $vD2)
		{
			$in_nD2=explode(":",$vD2);
			if ($vD1==$in_nD2[0])
			{
				if ($pmas!=1)
				{
					$Dquan=$in_nD2[2]*-1;
					$dchg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[2].":".$in_nD2[10].":0,";
					//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[2].":".$in_nD2[10]."<br>";
					$t_dchg_ar=$t_dchg_ar.$dchg_ar;
					$c_cnt++;
				}
				else
				{
					if (in_array($in_nD2[0],$d))
					{
						$Dquan=$in_nD2[2]*-1;
						$dchg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[2].":".$in_nD2[10].":0,";
						//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[2].":".$in_nD2[10]."<br>";
						$t_dchg_ar=$t_dchg_ar.$dchg_ar;
					}
					$c_cnt++;	
				}
			}
		}
	}

	//CHG Diffs;
	$pmas=0;
	if ($pmas!=1)
	{
		$ch_ar1=array();
		foreach ($inter_ar as $n3 => $v3)
		{
			foreach ($ex_old as $on3i => $ov3i)
			{
				$c_old=explode(":",$ov3i);
				if ($c_old[0]==$v3)
				{
					foreach ($ex_new as $nn3i => $nv3i)
					{
						$c_new=explode(":",$nv3i);
						if ($c_old[0]==$c_new[0])
						{
							if ($c_old[2]!=$c_new[2])
							{
								if ($c_old[0]==$c_new[0] && !in_array($c_new[1],$ch_ar1))
								{
									//echo "<pre> DINT: ";
									//print_r($c_new);
									//echo "</pre>";
									$nquan=$c_new[2]-$c_old[2];
									$cchg_ar=$c_new[0].":".$c_new[1].":".$nquan.":".$c_new[3].":".$c_new[4].":".$c_new[5].":".$c_old[6].":".$c_new[2].":".$c_new[10].":1,";
									//echo "CHG: ".$c_new[0].":".$c_new[1].":".$nquan.":".$c_old[3].":".$c_new[4].":".$c_new[5].":".$c_old[6].":".$c_new[2].":".$c_new[10]."<br>";
									$ch_ar1[]=$c_new[1];
									$t_cchg_ar=$t_cchg_ar.$cchg_ar;
									$c_cnt++;
								}
							}
						}
					}
				}
			}
		}
		
		/*
		echo "<pre>";
		echo "C1: <br>";
		print_r($ch_ar1);
		echo "</pre>";
		*/	
	}
	
	$tt_chg_ar=$t_achg_ar.$t_dchg_ar.$t_cchg_ar;
	$t_achg_ar=preg_replace("/,\Z/","",$t_achg_ar);
	$t_dchg_ar=preg_replace("/,\Z/","",$t_dchg_ar);
	$t_cchg_ar=preg_replace("/,\Z/","",$t_cchg_ar);
	$tt_chg_ar=preg_replace("/,\Z/","",$tt_chg_ar);
	$diff_out=array(0=>$t_achg_ar,1=>$t_dchg_ar,2=>$t_cchg_ar,3=>$c_cnt,4=>$tt_chg_ar);

	//print_r($diff_out);
	//echo "<br>";
	return $diff_out;
}

function countcostitems($data,$type)
{
	$MAS=$_SESSION['pb_code'];
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry = "SELECT id FROM [".$MAS."rclinks_".$type."] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			$ecnt=$ecnt+$nrow;
		}
	}
	return $ecnt;
}

function countpackageitems($data)
{
	$MAS=$_SESSION['pb_code'];
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry0 = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
			$res0 = mssql_query($qry0);
			$row0	= mssql_fetch_array($res0);

			if ($row0['qtype']==55||$row0['qtype']==72)
			{
				$qry = "SELECT id FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['id']."';";
				$res = mssql_query($qry);
				$nrow= mssql_num_rows($res);
				$ecnt=$ecnt+$nrow;
			}
		}
	}
	return $ecnt;
}

function countpackagecostitems($data,$type)
{
	$MAS=$_SESSION['pb_code'];
	//echo "DATA: ".$data;
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry = "SELECT id FROM [".$MAS."rclinks_".$type."] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[1]."';";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			$ecnt=$ecnt+$nrow;
		}
	}

	return $ecnt;
}

function store_com_items($estid,$jobid,$jadd,$estsecid,$sidm,$trandate,$cid,$njobid,$nsecid)
{
	//$err=0;
	//error_reporting(E_ALL);
	//ini_set('display_errors','On');
	
	if (isset($_REQUEST['csched']))
	{		
		if ($jadd==0)
		{
			$qry	= "SELECT csid,oid,estid,jobid,jadd,uid FROM CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
		}
		else
		{
			$qry	= "SELECT csid,oid,estid,jobid,jadd,uid FROM CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$jadd.";";
		}
		
		$res	= mssql_query($qry);
		$nrow	= mssql_num_rows($res);
		
		//echo 'QRY: '.$qry.'<br>';
		
		if ($nrow > 0)
		{
			if ($jadd==0)
			{
				$qry1	= "DELETE FROM CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
			}
			else
			{
				$qry1	= "DELETE FROM CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$jadd.";";
			}
			
			$res1	= mssql_query($qry1);
			
			//echo $nrow.' Commissions Found & Removed<br>';
		}

		foreach ($_REQUEST['csched'] as $n => $v)
		{
			/*echo '<pre>';
		
			print_r($v);
		
			echo '</pre>';*/
			
			$qry2	= "INSERT INTO CommissionSchedule ";
			$qry2  .= "(oid,estid,jobid,jadd,type,rate,amt,secid,uid,cbtype";
			
			if (isset($v['label']) && strlen($v['label']) >= 3)
			{
				$qry2  .= ",label";
			}
			
			if (isset($v['notes']) && strlen($v['notes']) >= 3)
			{
				$qry2  .= ",notes";
			}
			
			$qry2  .= ") ";
			$qry2  .= "VALUES ";
			$qry2  .= "('".$_SESSION['officeid']."',";
			$qry2  .= "'".$estid."',";
			$qry2  .= "'".$jobid."',";
			$qry2  .= "'".$jadd."',";
			$qry2  .= "".$v['ctype'].",";
			$qry2  .= "convert(float,'".$v['rwdrate']."'),";
			$qry2  .= "convert(money,'".$v['rwdamt']."'),";
			
			if ($v['catid']==4)
			{
				$qry2  .= "".$sidm.",";
			}
			else
			{
				$qry2  .= "".$estsecid.",";
			}
			
			$qry2  .= "'".$v['uid']."',";
			$qry2  .= "".$v['catid']."";
			
			if (isset($v['label']) && strlen($v['label']) >= 3)
			{
				$qry2  .= ",'".replacequote($v['label'])."'";
			}
			
			if (isset($v['notes']) && strlen($v['notes']) >= 3)
			{
				$qry2  .= ",'".replacequote($v['notes'])."'";
			}
			
			$qry2  .= ");";
			$res2	= mssql_query($qry2);
			
			if ($jadd!=0 && $v['catid']==3) // CommissionHistory Insert Addn Commission
			{
				$qry2a  = "INSERT INTO CommissionHistory (";
				$qry2a .= "drid,";
				$qry2a .= "oid,";
				$qry2a .= "njobid,";
				$qry2a .= "jobid,";
				$qry2a .= "jadd,";
				$qry2a .= "secid,";
				$qry2a .= "amt,";
				$qry2a .= "trandate,";
				$qry2a .= "descrip,";
				$qry2a .= "cid,";
				$qry2a .= "cbtype,";
				$qry2a .= "rate,";
				$qry2a .= "ratetype,";
				$qry2a .= "htype,";
				$qry2a .= "uid) VALUES ";
				$qry2a .= "(0,".$_SESSION['officeid'].",'".$njobid."','".$jobid."',".$jadd.",".$nsecid.",convert(money,'".$v['rwdamt']."'),";
				$qry2a .= "'".$trandate."','".replacequote($v['label'])."',".$cid.",".$v['catid'].",".$v['rwdrate'].",";
				$qry2a .= "".$v['ctype'].",'N',".$_SESSION['securityid'].");";
				$res2a  = mssql_query($qry2a);
				
				//echo $qry2a.'<br>';
				
				$qry2b	= "update jdetail set raddncm_man='".$v['rwdamt']."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$jadd.";";
				$res2b  = mssql_query($qry2b);
				
				//echo $qry2b.'<br>';
			}
			
			if ($jadd==0)
			{
				$qry3	= "UPDATE jobs SET applyov=1 WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."'";
				$res3	= mssql_query($qry3);
			}
		}
	}
	
	//return $err;
}

function store_labor_baseitems($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$p_out='';
	$data_out=array();

	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 	="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 	=mssql_query($qrypre0);
	$rowpre0 	=mssql_fetch_array($respre0);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum,phsid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND baseitem=1 ORDER BY seqnum;";
	$res0    =mssql_query($qry0);
	$nrow0   =mssql_num_rows($res0);

	$ecnt		=0;
	if ($nrow0 > 0)
	{
		$bc=0;
		$rc=0;

		while($row0=mssql_fetch_row($res0))
		{
			if ($row0[1]==1) // Fixed
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==2) // Quantity
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$row1['quantity'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==3) // per PFT
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*($ps1*$row1['quantity']);
				$quan  =$ps1;
			}
			elseif ($row0[1]==4) // per SQFT
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$ps2;
				$quan  =$ps2;
			}
			elseif ($row0[1]==5) // Base+ PFT (Fixed Base + amt per pft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =($row1['bprice']*1)+(($ps1-$row1['hrange'])*$row1['quantity']);
				$quan  =$ps1;
			}
			elseif ($row0[1]==6) // Base+ SQFT (Fixed Base + amt per sqft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				if ($ps2<=$row1['lrange'])
				{
					$bcsub =$row1['bprice'];
				}
				elseif ($ps2 > $row1['lrange'])
				{
					$bcsub =($row1['bprice']*1)+(($ps2-$row1['hrange'])*$row1['quantity']);
				}
				$quan  =$ps2;
			}
			elseif ($row0[1]==7) // Base+ IA
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']+(($row1['lrange'])*$row1['quantity']);
				$quan  =$iarea;
			}
			elseif ($row0[1]==9) // Bracket PFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$quan  =$ps1;
					}
					elseif ($ps1 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
						$quan  =$ps1;
					}
				}
			}
			elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				//echo "TEST<BR>";
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps2 >= $row1['lrange'] && $ps2 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$quan  =$ps2;
					}
					elseif ($ps2 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
						$quan  =$ps2;
					}
				}
				//echo "PS2: ".$ps2."<br>";
			}
			elseif ($row0[1]==11) // Bracket IA
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']*$row1['lrange'];
					$quan  =$row1['lrange'];
				}
				elseif ($iarea > $rowpre1[3])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =($row1['bprice']*$rowpre1[3])+(($iarea-$rowpre1[3])*$row1['quantity']);
					$quan  =$iarea;
				}
				else
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);

					while ($row1  =mssql_fetch_array($res1))
					{
						if ($iarea >= $row1['lrange'] && $iarea <= $row1['hrange'])
						{
							$bcsub =$row1['bprice']*$iarea;
							$quan  =$iarea;
						}
					}
				}
			}
			elseif ($row0[1]==30) // Fixed per PFT
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($ps1 < $rowpre1[0])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$quan  =$ps1;
				}
				elseif ($ps1 > $rowpre1[1])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
					$quan  =$ps1;
				}
				else
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$quan  =$ps1;
				}
			}
			elseif ($row0[1]==53) // Permit
			{
				if ($rowpre0[5]==1)
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
					$res1a =mssql_query($qry1a);
					$row1a =mssql_fetch_array($res1a);

					$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
					$res1b =mssql_query($qry1b);
					$row1b =mssql_fetch_array($res1b);

					$qry2 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
					$res2 =mssql_query($qry2);
					$row2 =mssql_fetch_array($res2);

					$bcsub =$row2['permit'];
					$item  ="Permit (".$row2['city'].")";
					$a1    ="";
					$quan  =1;
				}
			}


			if ($row0[1]==53)
			{
				$id   	=$row1['id'];
				$accid  	=$row1['accid'];
				$phsid	=$row1['phsid'];
				$matid	=$row1['matid'];
				$qtype	=$row1['qtype'];
				$mtype	=$row1['mtype'];
				$bprice	=$row1['bprice'];
				$lrange	=$row1['lrange'];
				$hrange	=$row1['hrange'];
				$quan		=$quan;
				$supplier=$row1['supplier'];
				$super	=$row1['supercedes'];
				$code		=$row1['code'];
			}
			else
			{
				$id   	=$row1['id'];
				$accid  	=$row1['accid'];
				$phsid	=$row1['phsid'];
				$matid	=$row1['matid'];
				$qtype	=$row1['qtype'];
				$mtype	=$row1['mtype'];
				$bprice	=$bcsub;
				$lrange	=$row1['lrange'];
				$hrange	=$row1['hrange'];
				$quan		=$quan;
				$supplier=$row1['supplier'];
				$super	=$row1['supercedes'];
				$code		=$row1['code'];

				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				//echo "ITEM: ".$item."<br>";
				//echo "QTYP: ".$row1['qtype']."<br>";
			}

			$fbprice    =number_format($bprice, 2, '.', '');

			if ($ecnt!=1)
			{
				$p=$id.":".$accid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$fbprice.":".$item.":".$a1.":".$lrange.":".$hrange.":".$quan.":".$supplier.":".$super.":".$code.",";
			}
			else
			{
				$p=$id.":".$accid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$fbprice.":".$item.":".$a1.":".$lrange.":".$hrange.":".$quan.":".$supplier.":".$super.":".$code;
			}
			$p_out=$p_out.$p;
			//echo $p_out."<BR>";
			$ecnt--;
		}
	}

	$p_out=preg_replace("/,\Z/","",$p_out);
	$data_out=array(0=>$p_out);
	return $data_out;
}

function store_material_baseitems($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$p_out='';
	$data_out=array();
	$officeid=$_SESSION['officeid'];

	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$qry   ="SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND baseitem=1 ORDER by seqnum;";
	$res   =mssql_query($qry);
	$nrows =mssql_num_rows($res);

	if ($nrows > 0)
	{
		$ecnt=$nrows;

		while($row=mssql_fetch_array($res))
		{
			$id   	=$row['invid'];
			$accid  	=$row['accid'];
			$raccid  =$row['raccid'];
			$phsid	=$row['qtype'];
			$matid	=$row['qtype'];
			$qtype	=$row['qtype'];
			$mtype	=$row['mtype'];
			$bprice	=$row['bprice'];
			$quan		=$row['quan_calc'];
			$vpno		=$row['vpno'];
			$item		=$row['item'];
			$a1		=$row['atrib1'];


			if ($ecnt!=1)
			{
				$p=$id.":".$accid.":".$raccid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$bprice.":".$item.":".$a1.":0:0:".$quan.":0:0:".$vpno.",";
			}
			else
			{
				$p=$id.":".$accid.":".$raccid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$bprice.":".$item.":".$a1.":0:0:".$quan.":0:0:".$vpno;
			}
			$p_out=$p_out.$p;
			$ecnt--;
		}
	}

	$p_out=preg_replace("/,\Z/","",$p_out);
	$data_out=array(0=>$p_out);
	return $data_out;
}

function store_packages($jobid,$jadd,$estdata)
{
	//echo "(Internal)<br>";
	// Takes an Estimate Data input, extrapolates Main Package Objects and related package items and filters and writes them to
	// a mulidimensional Text array in jdetail (filters)
	$MAS=$_SESSION['pb_code'];
	$p_arout='';
	$pcl_arout='';
	$pcm_arout='';
	$data_out=array();
	$excnt   =countpackageitems($estdata);
	$edata	=explode(",",$estdata);
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,qtype,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		//if ($row0['disabled']!=1)
		//{
			if ($row0['qtype']==55||$row0['qtype']==72)
			{
				$qry1 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['id']."';";
				$res1 = mssql_query($qry1);
				$nrow1= mssql_num_rows($res1);
	
				if ($nrow1 > 0)
				{
					while ($row1 = mssql_fetch_array($res1))
					{
						$qry2 = "SELECT id,rp,qtype,commtype,crate FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1['iid']."';";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);
	
						$frpfil    =number_format($row2['rp'], 2, '.', '');
	
						if ($excnt!=1)
						{
							$p_ar=$idata[0].":".$row2['id'].":".$frpfil.":".$row2['qtype'].":".$row2['commtype'].":".$row2['crate'].":".$row1['adjtype'].":".$row1['adjamt'].":".$row1['adjquan'].",";
						}
						else
						{
							$p_ar=$idata[0].":".$row2['id'].":".$frpfil.":".$row2['qtype'].":".$row2['commtype'].":".$row2['crate'].":".$row1['adjtype'].":".$row1['adjamt'].":".$row1['adjquan'];
						}
						$p_arout=$p_arout.$p_ar;
						$excnt--;
					}
				}
			}
		//}
		//else
		//{
		//	$excnt--;
		//}
	}

	// Stores Package Related Labor Cost items
	$fxcnt		=countpackagecostitems($p_arout,'l');
	if (!empty($fxcnt)||$fxcnt > 0)
	{
		//echo "FXCNT: ".$fxcnt."<br>";
		$pcl_arout	='';
		$fdata		=explode(",",$p_arout);
		foreach ($fdata as $fn1 => $fv1)
		{
			$jdata=explode(":",$fv1);
			$qry4 = "SELECT id,qtype,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$jdata[1]."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);

			//if ($row4['disabled']!=1)
			//{
				$qry5 = "SELECT * FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row4['id']."';";
				$res5 = mssql_query($qry5);
				$nrow5= mssql_num_rows($res5);
	
				if ($nrow5 > 0)
				{
					while ($row5 = mssql_fetch_array($res5))
					{
						$qry6 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row5['cid']."';";
						$res6 = mssql_query($qry6);
						$row6 = mssql_fetch_array($res6);
	
						$flabbp    =number_format($row6['bprice'], 2, '.', '');
	
						if ($fxcnt!=1)
						{
							//breakout (package retail ID:retail item ID:Adjust Type:Adjust Amt:Adjust Quan:Cost Item ID:Retail Price:Cost Item qtype:Cost Price:0:0)
							$pcl_ar=$jdata[0].":".$jdata[1].":".$jdata[6].":".$jdata[7].":".$jdata[8].":".$row6['id'].":".$jdata[2].":".$row6['qtype'].":".$flabbp.":0:0".",";
						}
						else
						{
							$pcl_ar=$jdata[0].":".$jdata[1].":".$jdata[6].":".$jdata[7].":".$jdata[8].":".$row6['id'].":".$jdata[2].":".$row6['qtype'].":".$flabbp.":0:0";
						}
						//echo "PCL ".$pcl_ar."<br>";
						$pcl_arout=$pcl_arout.$pcl_ar;
						$fxcnt--;
					}
				}
			//}
			//else
			//{
			//	$fxcnt--;
			//}
		}
	}

	// Stores Package Related Material Cost items
	$gxcnt		=countpackagecostitems($p_arout,'m');
	if (!empty($gxcnt)||$gxcnt > 0)
	{
		//echo "FXCNT: ".$gxcnt."<br>";
		$pcm_arout	='';
		$gdata		=explode(",",$p_arout);
		foreach ($gdata as $gn1 => $gv1)
		{
			$kdata=explode(":",$gv1);

			$qry8 = "SELECT id,qtype,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$kdata[1]."';";
			$res8 = mssql_query($qry8);
			$row8 = mssql_fetch_array($res8);

			//if ($row8['disabled']!=1)
			//{
				$qry9 = "SELECT * FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row8['id']."';";
				$res9 = mssql_query($qry9);
				$nrow9= mssql_num_rows($res9);
	
				if ($nrow9 > 0)
				{
					while ($row9 = mssql_fetch_array($res9))
					{
						$qry10 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row9['cid']."';";
						$res10 = mssql_query($qry10);
						$row10 = mssql_fetch_array($res10);
	
						if ($row10['matid']!=0)
						{
							$qry10a = "SELECT bp FROM material_master WHERE id='".$row10['matid']."';";
							$res10a = mssql_query($qry10a);
							$row10a = mssql_fetch_array($res10a);
	
							$fbp	=$row10a['bp'];
						}
						else
						{
							$fbp=$row10['bprice'];
						}
	
						$ffbp	=number_format($fbp, 2, '.', '');
	
						if ($gxcnt!=1)
						{
							//breakout (package retail ID:retail item ID:Adjust Type:Adjust Amt:Adjust Quan:Cost Item ID:Retail Price:Cost Item qtype: Cost Price:0:0)
							$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0".",";
						}
						else
						{
							$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0";
						}
						$pcm_arout=$pcm_arout.$pcm_ar;
						$gxcnt--;
					}
				}
			//}
			//else
			//{
			//	$gxcnt--;
			//}
		}
	}

	$data_out=array(0=>$p_arout,1=>$pcl_arout,2=>$pcm_arout);
	return $data_out;
}

function store_labor_cost_items($jobid,$jadd,$estdata)
{
	$MAS=$_SESSION['pb_code'];
	//$qry = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	//$res = mssql_query($qry);
	//$row = mssql_fetch_array($res);

	$p_arout='';
	$data_out=array();
	//$edata=explode(",",$row['estdata']);
	//$ecnt=countcostitems($row['estdata'],"l");
	$edata=explode(",",$estdata);
	$ecnt=countcostitems($estdata,"l");
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);
		
		$qryA = "SELECT id,qtype,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		//if ($rowA['disabled']!=1)
		//{
			$qry0 = "SELECT id,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);
	
			if ($nrow0 > 0)
			{
				while ($row0 = mssql_fetch_array($res0))
				{
					$qry1 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['cid']."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);
	
					$fbprice=number_format($row1['bprice'], 2, '.', '');
	
					if ($ecnt!=1)
					{
						$p_ar=$idata[0].":".$row1['id'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['lrange'].":".$row1['hrange'].":".$row1['phsid'].":".$row1['rinvid'].":".$row1['quantity'].",";
					}
					else
					{
						$p_ar=$idata[0].":".$row1['id'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['lrange'].":".$row1['hrange'].":".$row1['phsid'].":".$row1['rinvid'].":".$row1['quantity'];
					}
					//echo $p_ar."<br>";
					$p_arout=$p_arout.$p_ar;
					$ecnt--;
				}
			}
		//}
		//else
		//{
		//	$ecnt--;
		//}
	}

	$data_out=array(0=>$p_arout);
	return $data_out;
}

function store_material_cost_items($jobid,$jadd,$estdata)
{
	$MAS=$_SESSION['pb_code'];
	$qry = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$p_arout='';
	$data_out=array();
	//$edata=explode(",",$row['estdata']);
	//$ecnt=countcostitems($row['estdata'],"m");
	$edata=explode(",",$estdata);
	$ecnt=countcostitems($estdata,"m");
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qryA = "SELECT id,qtype,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		//if ($rowA['disabled']!=1)
		//{
			$qry0 = "SELECT id,rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);
	
			if ($nrow0 > 0)
			{
				while ($row0 = mssql_fetch_array($res0))
				{
					$qry1 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row0['cid']."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);
	
					if ($row1['matid']!=0)
					{
						$qry1a = "SELECT bp FROM material_master WHERE id='".$row1['matid']."';";
						$res1a = mssql_query($qry1a);
						$row1a = mssql_fetch_array($res1a);
	
						$bp=$row1a['bp'];
					}
					else
					{
						$bp=$row1['bprice'];
					}
	
					$fbprice=number_format($bp, 2, '.', '');
	
					if ($ecnt!=1)
					{
						$p_ar=$idata[0].":".$row1['invid'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['phsid'].":".$row1['rinvid'].",";
					}
					else
					{
						$p_ar=$idata[0].":".$row1['invid'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['phsid'].":".$row1['rinvid'];
					}
					$p_arout=$p_arout.$p_ar;
					$ecnt--;
				}
			}
		//}
		//else
		//{
		//	$ecnt--;
		//}
	}

	//$qry3 = "UPDATE jdetail SET costdata_m='".$p_arout."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	//$res3 = mssql_query($qry3);
	$data_out=array(0=>$p_arout);
	return $data_out;
}

function build_post_mas_addendum_start()
{
	global $viewarray,$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$estidret,$taxrate;
	$MAS=$_SESSION['pb_code'];

	//echo $_REQUEST['estid']." EST<br>";
	//echo $_REQUEST['jobid']." JOB<br>";
	//echo $_REQUEST['jadd']." JOB<br>";

	if (!isset($_REQUEST['estid'])||$_REQUEST['estid']==''||$_REQUEST['estid']==0)
	{
		echo "Fatal Error: Estimate ID Error!";
		exit;
	}

	if (!isset($_REQUEST['njobid'])||$_REQUEST['njobid']=='')
	{
		echo "Fatal Error: Contract ID Error!";
		exit;
	}

	if (!isset($_REQUEST['jadd'])||$_REQUEST['jadd']=='')
	{
		echo "Fatal Error: Addn ID Error!";
		exit;
	}

	if (!isset($_REQUEST['add_type'])||$_REQUEST['add_type']=='0')
	{
		$add_type="Job";
		$add_typeint=0;
	}
	else
	{
		$add_type="GM";
		$add_typeint=1;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreC = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypreE  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypreE .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypreE .= "ON a.catid=b.catid ";
	$qrypreE .= "AND a.officeid='".$_SESSION['officeid']."' ";
	$qrypreE .= "AND a.active=1 ";
	$qrypreE .= "ORDER BY a.seqn ASC;";
	$respreE = mssql_query($qrypreE);

	$type=1; // Est=0 Job=1

	//echo $numrowpreB." Adds<br>";
	$addcnt=$_REQUEST['jadd']+1;

	while ($rowpreE = mssql_fetch_row($respreE))
	{
		$catarray[]=$rowpreE[0];
	}

	$viewarray=array(
	'ps1'=>$rowpreA['pft'],
	'ps2'=>$rowpreA['sqft'],
	'spa1'=>$rowpreA['spa_type'],
	'spa2'=>$rowpreA['spa_pft'],
	'spa3'=>$rowpreA['spa_sqft'],
	'tzone'=>$rowpreA['tzone'],
	'camt'=>$rowpreA['contractamt'],
	'status'=>$rowpreC['status'],
	'ps5'=>$rowpreA['shal'],
	'ps6'=>$rowpreA['mid'],
	'ps7'=>$rowpreA['deep'],
	'custid'=>$rowpreC['custid'],
	'estsecid'=>$rowpreC['securityid'],
	'jobsecid'=>$rowpreC['securityid'],
	'deck'=>$rowpreA['deck'],
	'erun'=>$rowpreA['erun'],
	'prun'=>$rowpreA['prun'],
	'njobid'=>$rowpreA['njobid'],
	//'comadj'=>$rowpreA['comadj'],
	//'sidm'=>$rowpreA['sidm'],
	//'buladj'=>$rowpreA['buladj'],
	//'applyou'=>$rowpreA['applyov'],
	//'applybu'=>$rowpreA['applybu'],
	'refto'=>$rowpreA['refto'],
	'ps1a'=>$rowpreA['apft']
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryB1 = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resB1 = mssql_query($qryB1);
	$rowB1 = mssql_fetch_array($resB1);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}

	$adate		=date("m/d/Y");
	$estidret   =$rowpreC['estid'];
	$vdiscnt    =0;
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"save_add_post_mas\">\n";
	echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"".$addcnt."\">\n";
	echo "<input type=\"hidden\" name=\"add_type\" value=\"".$add_typeint."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"secid\" value=\"".$viewarray['jobsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table class=\"outer\" align=\"center\" width=\"950px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "   						<tr>\n";
	echo "      						<td align=\"right\" NOWRAP>\n";
	echo "         						<input class=\"buttondkgry\" type=\"submit\" value=\"Save Addendum\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"2\" class=\"gray\" align=\"left\"><b>Post MAS ".$add_type." Addendum Worksheet</b></td>\n";
	echo "			               <td align=\"right\"><b>Date:</b> ".$adate."</td>\n";
	echo "								<td colspan=\"2\" class=\"gray\" align=\"right\"><b>Addendum # <font color=\"red\">".$addcnt."</font> for Job # <font color=\"red\">".$viewarray['njobid']."</font></b></td>\n";
	echo "								</td>\n";
	echo "            			</tr>\n";
	echo "            			<tr>\n";
	echo "			               <td align=\"right\"><b>Customer:</b></td>\n";
	echo "								<td align=\"left\">".$rowI['clname'].", ".$rowI['cfname']."</td>\n";
	echo "			               <td align=\"right\"></td>\n";
	echo "								<td align=\"right\"><b>Salesman:</b></td>\n";
	echo "								<td align=\"left\"> ".$rowD['lname'].", ".$rowD['fname']."</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"5\" class=\"gray\" align=\"left\"><b>Addendum Notes:</b></td>\n";
	echo "							</tr>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"5\" class=\"gray\" align=\"left\">\n";
	echo "									<textarea name=\"comments\" rows=\"5\" cols=\"95\"></textarea>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"5\" class=\"gray\" align=\"right\">\n";
	echo "         						<table align=\"right\" border=0>\n";
	/*
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><b>Job</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><b>Pay Schedule</b></td>\n";
	echo "										</tr>\n";
	*/
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>Retail Pay Schedule Adjust:</b></td>\n";
	//echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"retadj\" value=\"0.00\"></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"pschedadj\" value=\"0.00\"></td>\n";
	echo "										</tr>\n";
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>Commission Adjust:</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"comadj\" value=\"0.00\"></td>\n";
	//echo "               						<td class=\"gray\" align=\"right\"></td>\n";
	echo "										</tr>\n";
	echo "            						<tr>\n";
	echo "               						<td class=\"gray\" align=\"right\"><b>Cost Adjust:</b></td>\n";
	echo "               						<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"cstadj\" value=\"0.00\"></td>\n";
	//echo "               						<td class=\"gray\" align=\"right\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</form>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function build_addendum_start()
{
	error_reporting(E_ALL);
	global $viewarray,$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$estidret,$taxrate;
	$MAS=$_SESSION['pb_code'];
	
	//show_post_vars();

	if (!isset($_REQUEST['estid'])||$_REQUEST['estid']==''||$_REQUEST['estid']==0)
	{
		echo "Fatal Error: Estimate ID Error!";
		exit;
	}

	if (!isset($_REQUEST['njobid'])||$_REQUEST['njobid']=='')
	{
		echo "Fatal Error: Contract ID Error!";
		exit;
	}

	if (!isset($_REQUEST['jadd'])||$_REQUEST['jadd']=='')
	{
		echo "Fatal Error: Addn ID Error!";
		exit;
	}

	if (!isset($_REQUEST['add_type'])||$_REQUEST['add_type']=='0')
	{
		$add_type="Job";
		$add_typeint=0;
	}
	else
	{
		$add_type="GM";
		$add_typeint=1;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreC = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypreE  = "SELECT DISTINCT a.catid,a.seqn,a.name ";
	$qrypreE .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypreE .= "ON a.catid=b.catid ";
	$qrypreE .= "AND a.officeid='".$_SESSION['officeid']."' ";
	$qrypreE .= "AND a.active=1 ";
	$qrypreE .= "ORDER BY a.seqn ASC;";
	$respreE = mssql_query($qrypreE);
	
	while ($rowpreE = mssql_fetch_row($respreE))
	{
		$catarray[$rowpreE[0]]=$rowpreE[2];
	}

	$type=1; // Est=0 Job=1

	//echo $numrowpreB." Adds<br>";
	$addcnt=$_REQUEST['jadd']+1;
	
	$viewarray=array(
	'ps1'=>$rowpreA['pft'],
	'ps2'=>$rowpreA['sqft'],
	'spa1'=>$rowpreA['spa_type'],
	'spa2'=>$rowpreA['spa_pft'],
	'spa3'=>$rowpreA['spa_sqft'],
	'tzone'=>$rowpreA['tzone'],
	'camt'=>$rowpreA['contractamt'],
	'status'=>$rowpreC['status'],
	'ps5'=>$rowpreA['shal'],
	'ps6'=>$rowpreA['mid'],
	'ps7'=>$rowpreA['deep'],
	'estsecid'=>$rowpreC['securityid'],
	'jobsecid'=>$rowpreC['securityid'],
	'deck'=>$rowpreA['deck'],
	'erun'=>$rowpreA['erun'],
	'prun'=>$rowpreA['prun'],
	'njobid'=>$rowpreA['njobid'],
	'refto'=>$rowpreA['refto'],
	'ps1a'=>$rowpreA['apft'],
	'renov'=>$rowpreC['renov']
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryB1 = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resB1 = mssql_query($qryB1);
	$rowB1 = mssql_fetch_array($resB1);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,mas_div,rmas_div FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}
	
	$qryK = "SELECT cmid FROM CommissionBuilder WHERE oid=".$_SESSION['officeid'].";";
	$resK = mssql_query($qryK);
	$nrowK= mssql_num_rows($resK);

	$masjinfo=getmasjobinfo($viewarray['njobid']);
	
	if ($masjinfo[1] >=5)
	{
		$post_add	=1;
		$dis			="DISABLED";
	}
	else
	{
		$post_add	=0;
		$dis			="";
	}
	
	$adate=date("m/d/Y");
	$estidret   =$rowpreC['estid'];
	$vdiscnt    =0;
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	
	if ($viewarray['renov']==1 && $rowD['rmas_div']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['rmas_div'],$_REQUEST['njobid']);
	}
	else
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$_REQUEST['njobid']);
	}

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"save_add\">\n";
	echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"".$addcnt."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"secid\" value=\"".$viewarray['jobsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"add_type\" value=\"".$add_typeint."\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<table align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<div class=\"noPrint\">\n";
	echo "						<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "                  				<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Update\" title=\"Save Addendum\" onClick=\"return AddnAlert('comadj','payadj','secid','nsecid')\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "						</div>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            				<tr>\n";
	echo "								<td class=\"gray\" colspan=\"2\" class=\"gray\" align=\"left\"><b>".$add_type." Addendum Worksheet</b></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Date:</b> ".$adate."</td>\n";
	echo "								<td class=\"gray\" colspan=\"2\" class=\"gray\" align=\"right\"><b>Addendum # <font color=\"red\">".$addcnt."</font> for Job # <font color=\"red\">".$destidret[0]."</font></b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Customer:</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">".$rowI['clname'].", ".$rowI['cfname']."</td>\n";
	echo "								<td class=\"gray\" align=\"right\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Salesman:</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\"> ".$rowD['lname'].", ".$rowD['fname']."</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td class=\"gray\" lign=\"left\"><b>Addendum Description</b></td>\n";
	echo "               			<td class=\"gray\" align=\"left\"><b>Commission & Pay Schedule Adjust</b></td>\n";
	echo "						</tr>\n";
	echo "            			<tr>\n";
	echo "               			<td class=\"gray\" align=\"center\">\n";
	echo "								<textarea name=\"comments\" rows=\"5\" cols=\"70\"></textarea>\n";
	echo "							</td>\n";
	echo "               			<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	
	if ($nrowK > 0)
	{
		$cadjperc=.05;
		echo "								<table border=0>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Pay Schedule</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght formatCurrency JMStooltip\" type=\"text\" name=\"paysadj\" id=\"payadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\" onChange=\"return updPercAddn('payadj','cadjperc','comadjdiv','comadj');\" title=\"Enter the amount of the Retail Contract Adjustment here\">\n";
		echo "										</td>\n";
		echo "               						<td align=\"left\"></td>\n";
		echo "									</tr>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Commission</b> ".($cadjperc * 100)."%</td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "											<div id=\"comadjdiv\">0.00</div>\n";
		echo "              							<input type=\"hidden\" name=\"csched[3][rwdamt]\" id=\"comadj\" value=\"\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][label]\" value=\"SRA".$addcnt."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][catid]\" value=\"3\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][ctype]\" value=\"2\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][rwdrate]\" id=\"cadjperc\" value=\"".($cadjperc * 100)."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][uid]\" value=\"".md5(session_id().time().$viewarray['jobsecid']).".".$_SESSION['securityid']."\">\n";
	}
	else
	{
		echo "								<table border=0>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Pay Schedule</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght JMStooltip\" type=\"text\" name=\"paysadj\" id=\"payadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\" title=\"Enter the amount of the Retail Contract Adjustment here\">\n";
		echo "										</td>\n";
		echo "               						<td align=\"left\"></td>\n";
		echo "									</tr>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Commission</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght\" type=\"text\" name=\"csched[3][rwdamt]\" id=\"comadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][label]\" value=\"SRA".$addcnt."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][catid]\" value=\"3\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][ctype]\" value=\"1\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][rwdrate]\" value=\"0\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][uid]\" value=\"".md5(session_id().time().$viewarray['jobsecid']).".".$_SESSION['securityid']."\">\n";
	}
	
	echo "										</td>\n";
	echo "               						<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"nsecid\" title=\"Commission will be allocated to selected\">\n";
	
	$qryDz = "SELECT securityid,fname,lname,substring(slevel,13,1) as slev FROM security WHERE officeid=".$_SESSION['officeid']." and substring(slevel,13,1) >=1 order by lname asc;";
	$resDz = mssql_query($qryDz);
	$nrowDz= mssql_num_rows($resDz);

	while ($rowDz = mssql_fetch_array($resDz))
	{
		if ($rowDz['securityid']==$viewarray['jobsecid'])
		{
			echo "<option value=\"".$rowDz['securityid']."\" SELECTED>".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
		}
		else
		{
			echo "<option value=\"".$rowDz['securityid']."\">".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
		}
	}
	
	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                  	<tr>\n";
	echo "                     		<td class=\"gray\" colspan=\"12\" align=\"left\" valign=\"bottom\"><b>POOL DIMENSIONS</b></td>\n";
	echo "                     	</tr>\n";
	echo "                    	<tr>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		echo "<b>Peri</b>\n";
	}
	else
	{
		echo "<b>Surf Area</b>\n";
	}

	echo "							</td>\n";
	echo "                     		<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($rowB1['quan1t'] > 0)
	{
		if ($rowpreB['pft_sqft']=="p")
		{
			if ($masjinfo[1] >=5)
			{
				echo "                        	<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\" DISABLED></td>\n";
				echo "                        	<input type=\"hidden\" name=\"ps1\" value=\"".$viewarray['ps1']."\"></td>\n";
			}
			else
			{
				echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\"></td>\n";
			}
		}
		else
		{
			if ($masjinfo[1] >=5)
			{
				echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\" DISABLED></td>\n";
				echo "                        	<input type=\"hidden\" name=\"ps2\" value=\"".$viewarray['ps2']."\"></td>\n";
			}
			else
			{
				echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\"></td>\n";
			}
		}
	}
	else
	{
		if ($masjinfo[1] >=5)
		{
			if ($rowpreB['pft_sqft']=="p")
			{
				echo "                        	<select name=\"ps1\" DISABLED>\n";
			}
			else
			{
				echo "                        	<select name=\"ps2\" DISABLED>\n";
			}
			
			while($rowA = mssql_fetch_array($resA))
			{
				if ($rowA['quan']==$rowB['quan'])
				{
					echo "                        		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
				}
				else
				{
					echo "                        		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
				}
			}
		
			echo "									</select>\n";
			
			if ($rowpreB['pft_sqft']=="p")
			{
				echo "                        	<input type=\"hidden\" name=\"ps1\" value=\"".$viewarray['ps1']."\"></td>\n";
			}
			else
			{
				echo "                        	<input type=\"hidden\" name=\"ps2\" value=\"".$viewarray['ps2']."\"></td>\n";
			}
		}
		else
		{
			if ($rowpreB['pft_sqft']=="p")
			{
				echo "                        	<select name=\"ps1\">\n";
			}
			else
			{
				echo "                        	<select name=\"ps2\">\n";
			}
			
			while($rowA = mssql_fetch_array($resA))
			{
				if ($rowA['quan']==$rowB['quan'])
				{
					echo "                        		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
				}
				else
				{
					echo "                        		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
				}
			}
		
			echo "									</select>\n";
		}
	}
	
	echo "                     		</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		echo "<b>Surf Area</b>\n";
	}
	else
	{
		echo "<b>Peri</b>\n";
	}

	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		if ($masjinfo[1] >=5)
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\" DISABLED></td>\n";
			echo "                        	<input type=\"hidden\" name=\"ps2\" value=\"".$viewarray['ps2']."\">\n";
		}
		else
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\"></td>\n";
		}
	}
	else
	{
		if ($masjinfo[1] >=5)
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\" DISABLED></td>\n";
			echo "                        	<input type=\"hidden\" name=\"ps1\" value=\"".$viewarray['ps1']."\">\n";
		}
		else
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\"></td>\n";
		}
	}

	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Depth</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "									<input class=\"bboxl\" type=\"text\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\" DISABLED>\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\" DISABLED>\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\" DISABLED>\n";
		echo "									<input type=\"hidden\" name=\"ps5\" value=\"".$viewarray['ps5']."\">\n";
		echo "									<input type=\"hidden\" name=\"ps6\" value=\"".$viewarray['ps6']."\">\n";
		echo "									<input type=\"hidden\" name=\"ps7\" value=\"".$viewarray['ps7']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\">\n";
	}
	
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Electrical Run</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "									<input class=\"bboxl\" type=\"text\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['erun']."\" DISABLED>\n";
		echo "									<input type=\"hidden\" name=\"erun\" value=\"".$viewarray['erun']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['erun']."\">\n";
	}
	
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Plumbing Run</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "									<input class=\"bboxl\" type=\"text\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['prun']."\" DISABLED>\n";
		echo "									<input type=\"hidden\" name=\"prun\" value=\"".$viewarray['prun']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['prun']."\">\n";
	}
		
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Total Deck</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "									<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['deck']."\" DISABLED>\n";
		echo "									<input type=\"hidden\" name=\"deck\" value=\"".$viewarray['deck']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['deck']."\">\n";
	}
	
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "         			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" colspan=\"5\" align=\"left\" valign=\"bottom\"><b>SPA DIMENSIONS</b></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "								<select name=\"spa1\">\n";

	while($rowE = mssql_fetch_row($resE))
	{
		echo "							<option value=\"".$rowE[0]."\">".$rowE[1]."</option>\n\n";
	}

	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Spa Perimeter</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "						<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa2']."\" DISABLED>\n";
		echo "						<input type=\"hidden\" name=\"spa2\" value=\"".$viewarray['spa2']."\">\n";
	}
	else
	{
		echo "						<input class=\"bboxl\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa2']."\">\n";
	}
	
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Spa Surface Area</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if ($masjinfo[1] >=5)
	{
		echo "                  <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa3']."\" DISABLED>\n";
		echo "                  <input type=\"hidden\" name=\"spa3\" value=\"".$viewarray['spa3']."\">\n";
	}
	else
	{
		echo "                  <input class=\"bboxl\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa3']."\">\n";
	}
	
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "      <td>\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><b>REFERRAL</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\">To:</td>\n";
	echo "								<td align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"refto\" value=\"".$viewarray['refto']."\" size=\"15\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "					<td class=\"gray\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><b>TRAVEL</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\">\n";
	echo "      			            	<input class=\"bboxbc\" type=\"text\" name=\"tzone\" value=\"".$viewarray['tzone']."\" size=\"15\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	/*
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"wh\" valign=\"top\">\n";

	$ecnt=1;
	foreach ($catarray as $n=>$v)
	{
		if ($n!=0)
		{
			if ($ecnt==count($catarray))
			{
				echo "<a href=\"#".$n."\">".$v."</a>";
			}
			else
			{
				echo "<a href=\"#".$n."\">".$v."</a> - ";
			}
			$ecnt++;
		}
	}

	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	*/
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";

	if ($_SESSION['securityid']!=269999999999999999999999999999)
	{

	foreach ($catarray as $n=>$v)
	{
		if ($n!=0)
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh\" colspan=\"4\" align=\"left\" valign=\"top\">\n";
			echo "					<input type=\"hidden\" name=\"#".$n."\"><b>".$v."</b></td>\n";
			echo "				<td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\">Up</a></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "            <td colspan=\"5\" class=\"gray\" valign=\"top\">\n";

			$qryM  = "SELECT
							--id,qtype
							id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled
						FROM
							[".$MAS."acc]
						WHERE
							officeid='".$_SESSION['officeid']."'
							AND catid='".$n."'
						ORDER BY
							seqn;";
			$resM  = mssql_query($qryM);
			$nrowM = mssql_num_rows($resM);

			$qcnt=0;

			while ($rowM=mssql_fetch_row($resM))
			{
				$qcnt++;
				
				$itbg='inner_borders';
				
				if ($qcnt==1)
				{
					form_element_ACC_NEW($rowM[0],1,$rowpreA['estdata'],$type,$rowM,$itbg);
				}
				elseif ($qcnt==$nrowM)
				{
					form_element_ACC_NEW($rowM[0],2,$rowpreA['estdata'],$type,$rowM,$itbg);
				}
				else
				{
					form_element_ACC_NEW($rowM[0],0,$rowpreA['estdata'],$type,$rowM,$itbg);
				}
			}

			echo "                 </td>\n";
			echo "         </tr>\n";
		}
	}
	}
	else
	{
		echo 'Items turned off for Testing.';
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "                  	<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Update\" title=\"Save Addendum\" onClick=\"return AddnAlert('comadj','payadj','secid','nsecid')\">\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</div>\n";
}

function build_addendum_save()
{
	//error_reporting(E_ALL);
    //ini_set('display_errors','On');
	
	$estAdata_init =estAdata_init();
	global $t_chg_ar,$addproc;

	$va=$_SESSION['viewarray'];
	//echo "UID: ".$_REQUEST['uid'];
	if (empty($_REQUEST['uid']))
	{
		echo "<b>Transition Error Occured!</b>";
		exit;
	}
	
	$qry  = "SELECT jadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$_REQUEST['uid']."'; ";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrow = mssql_num_rows($res);

	$qry1	= "SELECT officeid,pft_sqft,processor FROM offices WHERE officeid='".$_SESSION['officeid']."'; ";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);

	$qry2	= "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."'; ";
	$res2 = mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$masjinfo	=getmasjobinfo($_REQUEST['njobid']);
		
	if ($masjinfo[1] >=5)
	{
		$post_add	=1;
	}
	else
	{
		$post_add	=0;
	}

	$qry3	= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	$res3	= mssql_query($qry3);
	$row3	= mssql_fetch_array($res3);
	
	$qry4	= "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$row3['securityid']."';";
	$res4	= mssql_query($qry4);
	$row4	= mssql_fetch_array($res4);

	$viewarray	=array(
	'ps1'=>	$_REQUEST['ps1'],
	'ps2'=>	$_REQUEST['ps2'],
	'ps5'=>	$_REQUEST['ps5'],
	'ps6'=>	$_REQUEST['ps6'],
	'ps7'=>	$_REQUEST['ps7'],
	'spa1'=>	$_REQUEST['spa1'],
	'spa2'=>	$_REQUEST['spa2'],
	'spa3'=>	$_REQUEST['spa3'],
	'deck'=>	$_REQUEST['deck'],
	'tzone'=>	$_REQUEST['tzone']
	);
	
	$addproc=1;

	if ($row1['pft_sqft']=="p")
	{
		$defmeas=$_REQUEST['ps1'];
	}
	else
	{
		$defmeas=$_REQUEST['ps2'];
	}

	if (empty($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}

	if ($nrow > 0)
	{
		echo "<b>This Addendum has already been Submitted!</b>";
		exit;
	}
	elseif ($nrow==0)
	{
		if ($_REQUEST['jadd'] > 1)
		{
			$jaddn=$_REQUEST['jadd']-1;
		}
		else
		{
			$jaddn=0;
		}

		$qryXa  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$jaddn."' ;";
		$resXa  = mssql_query($qryXa);
		$rowXa  = mssql_fetch_array($resXa);

		if ($row1['pft_sqft']=="p")
		{
			$defmeasa=$rowXa['pft'];
		}
		else
		{
			$defmeasa=$rowXa['sqft'];
		}

		$tr_price	=0;
		$cm_price	=0;
		$c_cnt		=0;
		$t_chg_ar	="";

		if ($rowXa['estdata']!=$estAdata_init||$_REQUEST['ps1']!=$rowXa['pft']||$_REQUEST['ps2']!=$rowXa['sqft']||strlen($_REQUEST['comments']) > 1)
		{
			if ($_REQUEST['ps1']!=$rowXa['pft']||$_REQUEST['ps2']!=$rowXa['sqft'])
			{
				if ($row2['quan1t'] > 0)
				{
					$b_price_c	=0;
					$b_comm_c	=0;
					$b_price_d	=0;
					$b_comm_d	=0;
					//echo "SUM<br>";
					//echo $"<br>";
					$qryXc  = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan;";
					$resXc  = mssql_query($qryXc);

					//echo $qryXc."<br>";

					while ($rowXc  = mssql_fetch_array($resXc))
					{
						if ($defmeas >= $rowXc['quan'] && $defmeas <= $rowXc['quan1'])
						{
							//echo "SUM1<br>";
							$b_price_c	=$rowXc['price'];
							$b_comm_c	=$rowXc['comm'];
						}
					}

					$b_price_d	=$rowXa['bpprice'];
					$b_comm_d	=$rowXa['bpcomm'];
				}
				else
				{
					//echo "LIST<br>";
					$qryXc  = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
					$resXc  = mssql_query($qryXc);
					$rowXc  = mssql_fetch_array($resXc);

					$b_price_c	=$rowXc['price'];
					$b_comm_c	=$rowXc['comm'];

					//$qryXd  = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeasa."';";
					//$resXd  = mssql_query($qryXd);
					//$rowXd  = mssql_fetch_array($resXd);

					$b_price_d	=$rowXa['bpprice'];
					$b_comm_d	=$rowXa['bpcomm'];
				}

				$tr_price=$tr_price+($b_price_c-$b_price_d);
				$cm_price=$cm_price+($b_comm_c-$b_comm_d);
			}
			else
			{
				$tr_price=$rowXa['bpprice'];
				$cm_price=$rowXa['bpcomm'];

				$b_price_c=$rowXa['bpprice'];
				$b_comm_c=$rowXa['bpcomm'];
			}

			if ($row3['renov']==1 && $row4['rmas_div']!=0 && strtotime($row3['added']) >= strtotime('9/28/07'))
			{
				$dnjobid=disp_mas_div_jobid($row4['rmas_div'],$_REQUEST['njobid']);
			}
			else
			{
				$dnjobid=disp_mas_div_jobid($row4['mas_div'],$_REQUEST['njobid']);
			}

			//$verifiedpr	=align_pricing($estAdata_init);
			$pkgdata	=store_packages($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$lbrcost	=store_labor_cost_items($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$matcost	=store_material_cost_items($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$lbrbcost	=store_labor_baseitems($_REQUEST['njobid'],$_REQUEST['jadd']);
			$matbcost	=store_material_baseitems($_REQUEST['njobid'],$_REQUEST['jadd']);

			// Store Addn as Est.
			$qryA   = "INSERT INTO jdetail ";
			$qryA  .= "(officeid,njobid,jobid,jadd,pft,sqft,";
			$qryA  .= "shal,mid,deep,spa_pft,spa_sqft,";
			$qryA  .= "spa_type,deck,erun,prun,tzone,bpprice,";
			$qryA  .= "bpcomm,add_type,refto,contractamt,estdata,comments,";
			$qryA  .= "filters,pcostdata_l,pcostdata_m,costdata_l,costdata_m,";
			$qryA  .= "bcostdata_l,";
			$qryA  .= "bcostdata_m,";
			$qryA  .= "post_add,psched_adj,raddncm_man,";
			$qryA  .= "unique_id)";
			$qryA  .= " VALUES ";
			$qryA  .= "(";
			$qryA  .= "'".$_SESSION['officeid']."',";
			$qryA  .= "'".$_REQUEST['njobid']."',";
			$qryA  .= "'".$row3['jobid']."',";
			$qryA  .= "'".$_REQUEST['jadd']."',";
			$qryA  .= "'".$_REQUEST['ps1']."', ";
			$qryA  .= "'".$_REQUEST['ps2']."', ";
			$qryA  .= "'".$_REQUEST['ps5']."', ";
			$qryA  .= "'".$_REQUEST['ps6']."', ";
			$qryA  .= "'".$_REQUEST['ps7']."', ";
			$qryA  .= "'".$_REQUEST['spa2']."', ";
			$qryA  .= "'".$_REQUEST['spa3']."', ";
			$qryA  .= "'".$_REQUEST['spa1']."', ";
			$qryA  .= "'".$_REQUEST['deck']."', ";
			$qryA  .= "'".$_REQUEST['erun']."', ";
			$qryA  .= "'".$_REQUEST['prun']."', ";
			$qryA  .= "'".$_REQUEST['tzone']."', ";
			$qryA  .= "'".$b_price_c."', ";
			$qryA  .= "'".$b_comm_c."', ";
			$qryA  .= "'".$_REQUEST['add_type']."', ";
			$qryA  .= "'".$refto."', ";
			$qryA  .= "'".$rowXa['contractamt']."', ";
			$qryA  .= "'".$estAdata_init."',";
			$qryA  .= "'".replacequote($_REQUEST['comments'])."', ";
			$qryA	 .= "'".$pkgdata[0]."',";
			$qryA	 .= "'".$pkgdata[1]."',";
			$qryA	 .= "'".$pkgdata[2]."',";
			$qryA	 .= "'".$lbrcost[0]."',";
			$qryA	 .= "'".$matcost[0]."',";
			$qryA	 .= "'".$lbrbcost[0]."',";
			$qryA	 .= "'".$matbcost[0]."',";
			$qryA	 .= "'".$post_add."',";
			
			if (isset($_REQUEST['paysadj']) && $_REQUEST['paysadj']!=0)
			{
				$qryA  .= "'".$_REQUEST['paysadj']."',";
			}
			else
			{
				$qryA  .= "'0.00',";
			}
			
			if (isset($_REQUEST['csched'][3]['rwdamt']) && $_REQUEST['csched'][3]['rwdamt']!=0)
			{
				$qryA  .= "'".$_REQUEST['csched'][3]['rwdamt']."',";
			}
			else
			{
				$qryA  .= "'0.00',";
			}
			
			$qryA  .= "'".$_REQUEST['uid']."'";
			$qryA  .= ");";
			$resA   = mssql_query($qryA);
			
			/*$qryXy  = "SELECT securityid,estid,sidm,custid,jobid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$resXy  = mssql_query($qryXy);
			$rowXy  = mssql_fetch_array($resXy);*/
			
			$qryXya  = "SELECT estid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$row3['estid']."';";
			$resXya  = mssql_query($qryXya);
			$rowXya  = mssql_fetch_array($resXya);
			
			$qryXyb  = "SELECT securityid,sidm,newcommdate FROM security WHERE securityid='".$row3['securityid']."';";
			$resXyb  = mssql_query($qryXyb);
			$rowXyb  = mssql_fetch_array($resXyb);
			
			if (strtotime($rowXya['added']) >= strtotime($rowXyb['newcommdate']))
			{
				if (isset($_REQUEST['nsecid']) && $_REQUEST['nsecid']!=0)
				{
					$nsecid=$_REQUEST['nsecid'];
				}
				else
				{
					$nsecid=$row3['securityid'];
				}
				
				store_com_items($row3['estid'],$row3['jobid'],$_REQUEST['jadd'],$row3['securityid'],$row3['sidm'],date('m/d/y',time()),$row3['custid'],$row3['njobid'],$nsecid);
			}

			// Writing New Bid Items
			foreach ($_POST as $n=>$v)
			{
				if (substr($n,0,4)=="bbba")
				{
					$asid=substr($n,4);
					if ($_REQUEST['bbba'.$asid] > 0)
					{
						if (array_key_exists("eeea".$asid,$_POST))
						{
							$qryB  = "INSERT INTO jbids (officeid,njobid,jadd,bidinfo,bidamt,dbid) VALUES ('".$_SESSION['officeid']."','".$_REQUEST['njobid']."','".$_REQUEST['jadd']."','".replacequote($_REQUEST['eeea'.$asid])."','".replacequote($_REQUEST['ddda'.$asid])."','$asid');";
							$resB  = mssql_query($qryB);
							//echo $qryB;
						}
					}
				}
			}

			$qryXb  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXb  = mssql_query($qryXb);
			$rowXb  = mssql_fetch_array($resXb);

			$odata=explode(",",$rowXa['estdata']);
			$ndata=explode(",",$rowXb['estdata']);

			//Start Variance Detection
			foreach ($odata as $n1 => $v1)
			{
				$in_o=explode(":",$v1);
				$o_ar[]=$in_o[0];
			}

			foreach ($ndata as $n2 => $v2)
			{
				$in_n=explode(":",$v2);
				$n_ar[]=$in_n[0];
			}
			
			//print_r($n_ar);
			$add_ar_diff=array_diff($n_ar,$o_ar);
			$del_ar_diff=array_diff($o_ar,$n_ar);
			$inter_ar=array_intersect($o_ar,$n_ar);

			//print_r($o_ar);
			//print_r($n_ar);
			//echo "<br>ADD: ";
			//print_r($add_ar_diff);
			//echo "<br>DEL: ";
			//print_r($del_ar_diff);
			//print_r($inter_ar);
			
			$adds=$add_ar_diff;
			$dels=$del_ar_diff;

			$ar_price=0;
			$dr_price=0;
			$cr_price=0;
			$ac_price=0;
			$dc_price=0;
			$cc_price=0;

			//ADD Diffs;
			foreach ($add_ar_diff as $nA1 => $vA1)
			{
				foreach ($ndata as $nA2 => $vA2)
				{
					$in_nA2=explode(":",$vA2);
					if ($vA1==$in_nA2[0])
					{
						$chg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].",";
						//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7]."<br>";
						$t_chg_ar=$t_chg_ar.$chg_ar;
						$ar_price=$ar_price+($in_nA2[2]*$in_nA2[3]);

						if ($in_nA2[5]==1)
						{
							$ac_price=$ac_price+(($in_nA2[2]*$in_nA2[3])*$in_nA2[6]);
						}
						else
						{
							$ac_price=$ac_price+($in_nA2[2]*$in_nA2[6]);
						}

						//echo $ac_price."<br>";
						$c_cnt++;
					}
				}
			}

			//DEL Diffs;
			foreach ($del_ar_diff as $nD1 => $vD1)
			{
				foreach ($odata as $nD2 => $vD2)
				{
					$in_nD2=explode(":",$vD2);
					if ($vD1==$in_nD2[0])
					{
						$Dquan=$in_nD2[2]*-1;
						$chg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[7].",";
						//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6]."<br>";
						$t_chg_ar=$t_chg_ar.$chg_ar;
						$dr_price=$dr_price+($Dquan*$in_nD2[3]);

						if ($in_nD2[5]==1)
						{
							$dc_price=$dc_price+(($Dquan*$in_nD2[3])*$in_nD2[6]);
						}
						else
						{
							$dc_price=$dc_price+($Dquan*$in_nD2[6]);
						}

						$c_cnt++;
					}
				}
			}

			//CHG Diffs;
			array_walk($odata,'tst_vals',$ndata);
			$tr_price=$ar_price+$dr_price+$cr_price;
			$cm_price=$ac_price+$dc_price+$cc_price;
			$t_chg_ar=preg_replace("/,\Z/","",$t_chg_ar);
			
			//echo "<br>CHG: ".$t_chg_ar."<br>";

			$fdiff=0;

			if ($rowXa['costdata_l']!=$rowXb['costdata_l'])
			{
				//echo "<b>Cost Lab DIFF:</b><br>";
				if (strlen($rowXa['costdata_l']) < 3||strlen($rowXb['costdata_l']) < 3)
				{
					$cldiff=0;
				}
				else
				{
					//$ocldata=explode(",",$rowXa['costdata_l']);
					//$ncldata=explode(",",$rowXb['costdata_l']);
					$cldiff=parse_diffs($rowXa['costdata_l'],$rowXb['costdata_l'],$post_add,$adds,$dels);
				}
			}
			else
			{
				$cldiff=0;
			}

			if ($rowXa['costdata_m']!=$rowXb['costdata_m'])
			{
				//echo "<b>Cost Mat DIFF:</b><br>";
				if (strlen($rowXa['costdata_m']) < 3||strlen($rowXb['costdata_m']) < 3)
				{
					$cmdiff=0;
				}
				else
				{
					//$ocldata=explode(",",$rowXa['costdata_m']);
					//$ncldata=explode(",",$rowXb['costdata_m']);
					$cmdiff=parse_diffs($rowXa['costdata_m'],$rowXb['costdata_m'],$post_add,$adds,$dels);
				}
			}
			else
			{
				$cmdiff=0;
			}

			//echo "BCOSTo: ".$rowXa['bcostdata_l']."<br>";
			//echo "BCOSTn: ".$rowXb['bcostdata_l']."<br>";
			if ($rowXa['bcostdata_l']!=$rowXb['bcostdata_l'])
			{
				//echo "<b>Base Cost Lab DIFF:</b><br>";
				if (strlen($rowXa['bcostdata_l']) < 3||strlen($rowXb['bcostdata_l']) < 3)
				{
					$bcldiff=0;
				}
				else
				{
					//$obcldata=explode(",",$rowXa['bcostdata_l']);
					//$nbcldata=explode(",",$rowXb['bcostdata_l']);
					$bcldiff=parse_diffs($rowXa['bcostdata_l'],$rowXb['bcostdata_l'],$post_add,$adds,$dels);
				}
			}
			else
			{
				$bcldiff=0;
			}

			if ($rowXa['bcostdata_m']!=$rowXb['bcostdata_m'])
			{
				//echo "<b>Base Cost Mat DIFF:</b><br>";
				//echo "<b>A |".$rowXa['bcostdata_m']."| A</b><br>";
				//echo "<b>B |".$rowXb['bcostdata_m']."| B</b><br>";
				if (strlen($rowXa['bcostdata_m']) < 3||strlen($rowXb['bcostdata_m']) < 3)
				{
					$bcmdiff=0;
				}
				else
				{
					//$obcmdata=explode(",",$rowXa['bcostdata_m']);
					//$nbcmdata=explode(",",$rowXb['bcostdata_m']);
					$bcmdiff=parse_diffs($rowXa['bcostdata_m'],$rowXb['bcostdata_m'],$post_add,$adds,$dels);
				}
			}
			else
			{
				$bcmdiff=0;
			}

			//Detection for Package Filter Cost Item Diffs
			if ($rowXa['filters']!=$rowXb['filters'])
			{
				//echo "<b>Package Cost Mat DIFF:</b><br>";
				if (strlen($rowXa['filters']) < 3||strlen($rowXb['filters']) < 3)
				{
					$fdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0);
				}
				else
				{
					$prefdiff	=parse_filter_diffs($rowXa['filters'],$rowXb['filters'],$post_add,$adds,$dels);
					$fdiff		=parse_filter_cost_diffs($prefdiff[4],$post_add);
				}
			}
			else
			{
				$fdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0);
			}

			$qryXz  = "UPDATE jdetail SET ";
			$qryXz .= "raddnacc='".$t_chg_ar."',raddnpr='".$tr_price."',raddncm='".$cm_price."',";
			$qryXz .= "costlabdiff='".$cldiff[4]."',costmatdiff='".$cmdiff[4]."',";
			$qryXz .= "bcostlabdiff='".$bcldiff[4]."',bcostmatdiff='".$bcmdiff[4]."',";
			$qryXz .= "pcostlabdiff='".$fdiff[4]."',pcostmatdiff='".$fdiff[4]."'";
			$qryXz .= " WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXz  = mssql_query($qryXz);
			
			// Send Post Addn Email
			//echo $post_add."<br>"; 
			if ($post_add==1)
			{
				$qry0 = "
				select 
					o.processor as prc,
					o.name as name,
					s.email as email
				from 
					offices as o
				inner join
					security as s
				on
					o.processor=s.securityid
				where 
					o.officeid='".$_SESSION['officeid']."';
				";
		
				$res0 = mssql_query($qry0);
				$row0 = mssql_fetch_array($res0);
				
				/*$qry0a = "SELECT securityid,email as smail FROM security WHERE securityid='".PROC_SPVSR."';";
				$res0a = mssql_query($qry0a);
				$row0a = mssql_fetch_array($res0a);*/
				
				if (isset($row0['prc']) && $row0['prc']!=0 && valid_email_addr($row0['email']))
				{
					//echo $qry0."<br>";
					$qry2 = "SELECT officeid,securityid,clname,cfname FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);
					
					$to	 	 = $row0['email'].",thelton@corp.bluehaven.com";
					$sub	 = "".$dnjobid[0]." - ".$row2['clname']." - JMS: Post MAS Addn Created";
					$mess	 = "Status : Post MAS Addn Created\r\n";
					$mess	.= "Cust   : ".$row2['cfname']." ".$row2['clname']."\r\n";
					$mess	.= "Job    : ".$dnjobid[0]."\r\n";
					$mess	.= "-------:----------------------\r\n";
					$mess	.= "Rel By : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
					$mess	.= "LHost  : ".$_SERVER['SERVER_NAME']."\r\n";
					$mess	.= "RHost  : ".getenv('REMOTE_ADDR')."\r\n";
					
					//mail_out($to,$sub,$mess);
					//SendSystemEmail($from,$to,$sub,$mess)
					
					$emc_ar=array(
						'to'=>		$to,
						'from'=>	'jmsadmin@bhnmi.com',
						'FromName'=>'JMS System Admin',
						'esubject'=>trim($sub),
						'ebody'=>	trim($mess),
						'oid'=> 	89,
						'lid'=> 	0,
						'tid'=> 	0,
						'cid'=> 	0,
						'uid'=> 	1797,
						'ename'=>	'',
						'chistory'=>false,
						'SMTPdbg'=>	1
					);
	
					ExtEmailSendPlain($emc_ar);
				}
			}
			
			view_job_addendum_retail();
		}
		else
		{
			echo "<b>No changes detected. <font color=\"red\">Addendum not saved!</font></b>";
		}

		echo "</td></tr>\n";
		echo "</table>\n";
	}
}

function build_addendum_save_post_mas()
{
	$estAdata_init =estAdata_init();

	//echo "UID: ".$_REQUEST['uid'];
	if (empty($_REQUEST['uid']))
	{
		echo "<b>Transition Error Occured!</b>";
		exit;
	}

	if (!isset($_REQUEST['pschedadj']) || !is_numeric($_REQUEST['pschedadj']))
	{
		echo "<b>Pay Schedule Error</b> <br> You have entered invalid Pay Schedule. <br> Click BACK and correct.";
		exit;
	}
	
	if (!isset($_REQUEST['comadj']) || !is_numeric($_REQUEST['comadj']))
	{
		echo "<b>Commission Error</b> <br> You have entered invalid Commission. <br> Click BACK and correct.";
		exit;
	}
	
	if (!isset($_REQUEST['cstadj']) || !is_numeric($_REQUEST['cstadj']))
	{
		echo "<b>Cost Error</b> <br> You have entered invalid Cost. <br> Click BACK and correct.";
		exit;
	}
	
	$qry  = "SELECT jadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$_REQUEST['uid']."'; ";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrow = mssql_num_rows($res);

	$qry1	= "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."'; ";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);

	$qry2	= "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."'; ";
	$res2 = mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	/*
	if ($row1['pft_sqft']=="p")
	{
	$defmeas=$_REQUEST['ps1'];
	}
	else
	{
	$defmeas=$_REQUEST['ps2'];
	}
	*/
	if (empty($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}

	if ($nrow > 0)
	{
		echo "<b>This Addendum has already been Submitted!</b>";
		exit;
	}
	elseif ($nrow==0)
	{
		if ($_REQUEST['jadd'] > 1)
		{
			$jaddn=$_REQUEST['jadd']-1;
		}
		else
		{
			$jaddn=0;
		}

		$qryXa  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$jaddn."' ;";
		$resXa  = mssql_query($qryXa);
		$rowXa  = mssql_fetch_array($resXa);
		
		$qryXb  = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd=0;";
		$resXb  = mssql_query($qryXb);
		$rowXb  = mssql_fetch_array($resXb);

		if ($row1['pft_sqft']=="p")
		{
			$defmeas=$rowXa['pft'];
			$defmeasa=$rowXa['pft'];
		}
		else
		{
			$defmeas=$rowXa['sqft'];
			$defmeasa=$rowXa['sqft'];
		}

		$tr_price	=0;
		$cm_price	=0;
		$c_cnt		=0;
		$t_chg_ar	="";

		if (strlen($_REQUEST['comments']) > 1)
		{
			$tr_price=$rowXa['bpprice'];
			$cm_price=$rowXa['bpcomm'];

			$b_price_c=$rowXa['bpprice'];
			$b_comm_c=$rowXa['bpcomm'];

			//$verifiedpr	=align_pricing($estAdata_init);
			$pkgdata		=store_packages($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$lbrcost		=store_labor_cost_items($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$matcost		=store_material_cost_items($_REQUEST['njobid'],$_REQUEST['jadd'],$estAdata_init);
			$lbrbcost	=store_labor_baseitems($_REQUEST['njobid'],$_REQUEST['jadd']);
			$matbcost	=store_material_baseitems($_REQUEST['njobid'],$_REQUEST['jadd']);

			echo "<table width=\"50%\" align=\"center\">\n";
			echo "<tr><td align=\"left\"><b>Addendum Storage Progress:</b></td>\n";
			echo "<tr><td align=\"left\" NOWRAP>\n";
			echo "Storing Addendum DATA <br>";

			// Store Addn as Est.
			$qryA   = "INSERT INTO jdetail ";
			$qryA  .= "(officeid,jobid,njobid,jadd,pft,sqft,";
			$qryA  .= "shal,mid,deep,spa_pft,spa_sqft,";
			$qryA  .= "spa_type,deck,erun,prun,tzone,bpprice,";
			$qryA  .= "bpcomm,add_type,refto,contractamt,estdata,comments,";
			$qryA  .= "raddnpr_man,";
			$qryA  .= "raddncm_man,";
			$qryA  .= "raddncs_man,";
			$qryA  .= "raddnpr,";
			$qryA  .= "raddncm,";
			$qryA  .= "psched_adj,";
			$qryA  .= "filters,pcostdata_l,pcostdata_m,costdata_l,costdata_m,bcostdata_l,bcostdata_m,";
			$qryA  .= "post_add,";
			$qryA  .= "unique_id)";
			$qryA  .= " VALUES ";
			$qryA  .= "(";
			$qryA  .= "'".$_SESSION['officeid']."',";
			$qryA  .= "'".$rowXa['jobid']."',";
			$qryA  .= "'".$rowXa['njobid']."',";
			$qryA  .= "'".$_REQUEST['jadd']."',";
			$qryA  .= "'".$rowXa['pft']."', ";
			$qryA  .= "'".$rowXa['sqft']."', ";
			$qryA  .= "'".$rowXa['shal']."', ";
			$qryA  .= "'".$rowXa['mid']."', ";
			$qryA  .= "'".$rowXa['deep']."', ";
			$qryA  .= "'".$rowXa['spa_pft']."', ";
			$qryA  .= "'".$rowXa['spa_sqft']."', ";
			$qryA  .= "'".$rowXa['spa_type']."', ";
			$qryA  .= "'".$rowXa['deck']."', ";
			$qryA  .= "'".$rowXa['erun']."', ";
			$qryA  .= "'".$rowXa['prun']."', ";
			$qryA  .= "'".$rowXa['tzone']."', ";
			$qryA  .= "'".$b_price_c."', ";
			$qryA  .= "'".$b_comm_c."', ";
			$qryA  .= "'".$_REQUEST['add_type']."', ";
			$qryA  .= "'".$rowXa['refto']."', ";
			$qryA  .= "'".$rowXa['contractamt']."', ";
			$qryA  .= "'".$rowXa['estdata']."',";
			$qryA  .= "'".replacequote($_REQUEST['comments'])."', ";
			$qryA  .= "'".$_REQUEST['pschedadj']."',";
			$qryA  .= "'".$_REQUEST['comadj']."',";
			$qryA  .= "'".$_REQUEST['cstadj']."',";
			$qryA  .= "'".$_REQUEST['pschedadj']."',";
			$qryA  .= "'".$_REQUEST['comadj']."',";
			$qryA  .= "'".$_REQUEST['pschedadj']."',";
			$qryA  .= "'".$rowXa['filters']."',";
			$qryA  .= "'".$rowXa['pcostdata_l']."',";
			$qryA  .= "'".$rowXa['pcostdata_m']."',";
			$qryA  .= "'".$rowXa['costdata_l']."',";
			$qryA  .= "'".$rowXa['costdata_m']."',";
			$qryA  .= "'".$rowXa['bcostdata_l']."',";
			$qryA  .= "'".$rowXa['bcostdata_m']."',";
			$qryA  .= "'1',";
			$qryA  .= "'".$_REQUEST['uid']."'";
			$qryA  .= ");";
			$resA   = mssql_query($qryA);

			//echo $qryA;
			/*
			// Writing New Bid Items
			foreach ($_POST as $n=>$v)
			{
			if (substr($n,0,4)=="bbba")
			{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
			if (array_key_exists("eeea".$asid,$_POST))
			{
			$qryB  = "INSERT INTO jbids (officeid,njobid,jadd,bidinfo,bidamt,dbid) VALUES ('".$_SESSION['officeid']."','".$_REQUEST['njobid']."','".$_REQUEST['jadd']."','".replacequote($_REQUEST['eeea'.$asid])."','".replacequote($_REQUEST['ddda'.$asid])."','$asid');";
			$resB  = mssql_query($qryB);
			//echo $qryB;
			}
			}
			}
			}

			$qryXb  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXb  = mssql_query($qryXb);
			$rowXb  = mssql_fetch_array($resXb);

			$odata=explode(",",$rowXa['estdata']);
			$ndata=explode(",",$rowXb['estdata']);

			//Start Variance Detection
			foreach ($odata as $n1 => $v1)
			{
			$in_o=explode(":",$v1);
			$o_ar[]=$in_o[0];
			}

			foreach ($ndata as $n2 => $v2)
			{
			$in_n=explode(":",$v2);
			$n_ar[]=$in_n[0];
			}

			$add_ar_diff=array_diff($n_ar,$o_ar);
			$del_ar_diff=array_diff($o_ar,$n_ar);
			$inter_ar=array_intersect($o_ar,$n_ar);

			//print_r($o_ar);
			//print_r($n_ar);
			//print_r($add_ar_diff);
			//print_r($del_ar_diff);
			//print_r($inter_ar);

			$ar_price=0;
			$dr_price=0;
			$cr_price=0;
			$ac_price=0;
			$dc_price=0;
			$cc_price=0;

			//ADD Diffs;
			foreach ($add_ar_diff as $nA1 => $vA1)
			{
			foreach ($ndata as $nA2 => $vA2)
			{
			$in_nA2=explode(":",$vA2);
			if ($vA1==$in_nA2[0])
			{
			$chg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].",";
			//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6]."<br>";
			$t_chg_ar=$t_chg_ar.$chg_ar;
			$ar_price=$ar_price+($in_nA2[2]*$in_nA2[3]);

			if ($in_nA2[5]==1)
			{
			$ac_price=$ac_price+(($in_nA2[2]*$in_nA2[3])*$in_nA2[6]);
			}
			else
			{
			$ac_price=$ac_price+($in_nA2[2]*$in_nA2[6]);
			}

			//echo $ac_price."<br>";
			$c_cnt++;
			}
			}
			}

			//DEL Diffs;
			foreach ($del_ar_diff as $nD1 => $vD1)
			{
			foreach ($odata as $nD2 => $vD2)
			{
			$in_nD2=explode(":",$vD2);
			if ($vD1==$in_nD2[0])
			{
			$Dquan=$in_nD2[2]*-1;
			$chg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].",";
			//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6]."<br>";
			$t_chg_ar=$t_chg_ar.$chg_ar;
			$dr_price=$dr_price+($Dquan*$in_nD2[3]);

			if ($in_nD2[5]==1)
			{
			$dc_price=$dc_price+(($Dquan*$in_nD2[3])*$in_nD2[6]);
			}
			else
			{
			$dc_price=$dc_price+($Dquan*$in_nD2[6]);
			}

			$c_cnt++;
			}
			}
			}

			//CHG Diffs;
			foreach ($inter_ar as $n3 => $v3)
			{
			foreach ($odata as $on3i => $ov3i)
			{
			$c_old=explode(":",$ov3i);
			if ($c_old[0]==$v3)
			{
			foreach ($ndata as $nn3i => $nv3i)
			{
			$c_new=explode(":",$nv3i);
			if ($c_old[0]==$c_new[0])
			{
			if ($c_old[2]!=$c_new[2])
			{
			$nquan=$c_new[2]-$c_old[2];
			$chg_ar=$c_new[0].":".$c_new[1].":".$nquan.":".$c_old[3].":".$c_new[4].":".$c_new[5].":".$c_old[6].",";
			//echo "CHG: ".$c_new[0].":".$c_new[1].":".$nquan.":".$c_old[3].":".$c_new[4].":".$c_new[5].":".$c_old[6]."<br>";
			$t_chg_ar=$t_chg_ar.$chg_ar;
			$cr_price=$cr_price+($nquan*$c_old[3]);

			if ($c_new[5]==1)
			{
			$cc_price=$cc_price+(($nquan*$c_old[3])*$c_old[6]);
			}
			else
			{
			$cc_price=$cc_price+($nquan*$c_old[6]);
			}

			$c_cnt++;
			}
			}
			}
			}
			}
			}

			//$tr_price=$tr_price+$ar_price+$dr_price+$cr_price;
			//$cm_price=$cm_price+$ac_price+$dc_price+$cc_price;
			$tr_price=$ar_price+$dr_price+$cr_price;
			$cm_price=$ac_price+$dc_price+$cc_price;
			$t_chg_ar=preg_replace("/,\Z/","",$t_chg_ar);

			$fdiff=0;

			if ($rowXa['costdata_l']!=$rowXb['costdata_l'])
			{
			//echo "<b>Cost Lab DIFF:</b><br>";
			if (strlen($rowXa['costdata_l']) < 3||strlen($rowXb['costdata_l']) < 3)
			{
			$cldiff=0;
			}
			else
			{
			//$ocldata=explode(",",$rowXa['costdata_l']);
			//$ncldata=explode(",",$rowXb['costdata_l']);
			$cldiff=parse_diffs($rowXa['costdata_l'],$rowXb['costdata_l']);
			}
			}
			else
			{
			$cldiff=0;
			}

			if ($rowXa['costdata_m']!=$rowXb['costdata_m'])
			{
			//echo "<b>Cost Mat DIFF:</b><br>";
			if (strlen($rowXa['costdata_m']) < 3||strlen($rowXb['costdata_m']) < 3)
			{
			$cmdiff=0;
			}
			else
			{
			//$ocldata=explode(",",$rowXa['costdata_m']);
			//$ncldata=explode(",",$rowXb['costdata_m']);
			$cmdiff=parse_diffs($rowXa['costdata_m'],$rowXb['costdata_m']);
			}
			}
			else
			{
			$cmdiff=0;
			}

			//echo "BCOSTo: ".$rowXa['bcostdata_l']."<br>";
			//echo "BCOSTn: ".$rowXb['bcostdata_l']."<br>";
			if ($rowXa['bcostdata_l']!=$rowXb['bcostdata_l'])
			{
			//echo "<b>Base Cost Lab DIFF:</b><br>";
			if (strlen($rowXa['bcostdata_l']) < 3||strlen($rowXb['bcostdata_l']) < 3)
			{
			$bcldiff=0;
			}
			else
			{
			//$obcldata=explode(",",$rowXa['bcostdata_l']);
			//$nbcldata=explode(",",$rowXb['bcostdata_l']);
			$bcldiff=parse_diffs($rowXa['bcostdata_l'],$rowXb['bcostdata_l']);
			}
			}
			else
			{
			$bcldiff=0;
			}

			if ($rowXa['bcostdata_m']!=$rowXb['bcostdata_m'])
			{
			//echo "<b>Base Cost Mat DIFF:</b><br>";
			//echo "<b>A |".$rowXa['bcostdata_m']."| A</b><br>";
			//echo "<b>B |".$rowXb['bcostdata_m']."| B</b><br>";
			if (strlen($rowXa['bcostdata_m']) < 3||strlen($rowXb['bcostdata_m']) < 3)
			{
			$bcmdiff=0;
			}
			else
			{
			//$obcmdata=explode(",",$rowXa['bcostdata_m']);
			//$nbcmdata=explode(",",$rowXb['bcostdata_m']);
			$bcmdiff=parse_diffs($rowXa['bcostdata_m'],$rowXb['bcostdata_m']);
			}
			}
			else
			{
			$bcmdiff=0;
			}

			if ($rowXa['pcostdata_l']!=$rowXb['pcostdata_l'])
			{
			//echo "<b>Package Cost Lab DIFF:</b><br>";
			if (strlen($rowXa['pcostdata_l']) < 3||strlen($rowXb['pcostdata_l']) < 3)
			{
			$pcldiff=0;
			}
			else
			{
			//$opcldata=explode(",",$rowXa['pcostdata_l']);
			//$npcldata=explode(",",$rowXb['pcostdata_l']);
			$pcldiff=parse_diffs($rowXa['pcostdata_l'],$rowXb['pcostdata_l']);
			}
			}
			else
			{
			$pcldiff=0;
			}

			if ($rowXa['pcostdata_m']!=$rowXb['pcostdata_m'])
			{
			//echo "<b>Package Cost Mat DIFF:</b><br>";
			if (strlen($rowXa['pcostdata_m']) < 3||strlen($rowXb['pcostdata_m']) < 3)
			{
			$pcmdiff=0;
			}
			else
			{
			//$opcmdata=explode(",",$rowXa['pcostdata_m']);
			//$npcmdata=explode(",",$rowXb['pcostdata_m']);
			$pcmdiff=parse_diffs($rowXa['pcostdata_m'],$rowXb['pcostdata_m']);
			}
			}
			else
			{
			$pcmdiff=0;
			}

			$qryXz  = "UPDATE jdetail SET ";
			$qryXz .= "raddnacc='".$t_chg_ar."',raddnpr='".$tr_price."',raddncm='".$cm_price."',";
			$qryXz .= "filtersdiff='".$fdiff."',";
			$qryXz .= "costlabdiff='".$cldiff[4]."',costmatdiff='".$cmdiff[4]."',";
			$qryXz .= "bcostlabdiff='".$bcldiff."',bcostmatdiff='".$bcmdiff."',";
			$qryXz .= "pcostlabdiff='".$pcldiff."',pcostmatdiff='".$pcmdiff."'";
			$qryXz .= " WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXz  = mssql_query($qryXz);

			echo "with ".$c_cnt." Accessory changes: <b>Complete!</b><br>";
			*/
			echo "Storage for Job # <font color=\"red\"><b>".$_REQUEST['njobid']."</font></b> Addendum # <b><font color=\"red\">".$_REQUEST['jadd']."</font>: Complete!</b><br>";
			echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
			echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
			echo "<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
			echo "<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "</form>\n";
		}
		else
		{
			echo "<b>No changes detected. <font color=\"red\">Addendum not saved!</font></b>";
		}

		echo "</td></tr>\n";
		echo "</table>\n";
	}
}

function get_MAS_Job_Status()
{
	//ini_set('display_errors','On');
	//error_reporting(E_ALL);
	
	$mjar=array();
	
	//$odbc_ser	=	"192.168.1.22"; #the name of the SQL Server
	//$odbc_add	=	"192.168.1.22";
	/*
	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestuser"; #a valid username
	$odbc_pass	=	"bhestuser"; #a password for the username
	*/
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username

	$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$odbc_qry0  = "SELECT id,jobid,sstatus FROM BHESTJobData_Stats WHERE ";
	$odbc_qry0 .= "officeid='".$_SESSION['officeid']."' AND sstatus <= 9;"; // Remove after tests

	$odbc_res0	=	odbc_exec($odbc_conn0, $odbc_qry0);

	if (odbc_num_rows($odbc_res0) > 0)
	{
		while (odbc_fetch_row($odbc_res0))
		{
			$odbc_ret1 	= odbc_result($odbc_res0, 1);
			$odbc_ret2 	= odbc_result($odbc_res0, 2);
			$odbc_ret3 	= odbc_result($odbc_res0, 3);
	
			$mjar2[]		=$odbc_ret2;
			$mjar3[]		=$odbc_ret3;
		}
	
		if(count($mjar2) == count($mjar3))
		{
			for($x=0; $x<count($mjar2); $x++)
			{
				$mjar[$mjar2[$x]] = $mjar3[$x];
			}
		}
	}
	
	return $mjar;
}

function create_job()
{
	$errinp=0;
	$qrypre1	= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);
	
	//echo $qrypre1."<br>";

	$qrypre1a	= "SELECT contractdate FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respre1a	= mssql_query($qrypre1a);
	$rowpre1a	= mssql_fetch_array($respre1a);

	$qrypre2	= "SELECT psched,psched_perc,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$respre2	= mssql_query($qrypre2);
	$rowpre2	= mssql_fetch_array($respre2);

	$qrypre3	= "SELECT fname,lname,mas_div,rmas_div FROM security WHERE securityid='".$rowpre1['securityid']."';";
	$respre3	= mssql_query($qrypre3);
	$rowpre3	= mssql_fetch_array($respre3);

	$tdate		=date("m-d-Y", time());
	$sdate		=date("m-d-Y", strtotime($rowpre1['added']));
	$cdate		=date("m-d-Y", strtotime($rowpre1a['contractdate']));


	//echo "RENOV:  ".$rowpre1['renov']."<br>";
	//echo "RMASID: ".$row['renov']."<br>";

	if ($rowpre1['applyov']!=1)
	{
		echo "<font color=\"red\"><b>Error!</b></font><br>Commision Adjustment not Applied.<br> Click the BACK button and Apply a Commission Adjustment";
		exit;
	}
	else
	{
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"create_job_chk\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" id=\"sys_oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$rowpre1['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";

		echo "<script type=\"text/javascript\" src=\"js/jquery_job_func.js\"></script>\n";
		echo "<table class=\"outer\" align=\"center\" width=\"350px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"3\" align=\"left\"><b>Create New Job</b></th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>System Insert Date:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"sdate\" value=\"".$sdate."\" DISABLED>\n";
		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Contract Date:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"cdate\" value=\"".$cdate."\" DISABLED>\n";
		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Contract Number:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"jobid\" value=\"".$rowpre1['jobid']."\" DISABLED>\n";
		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Job Number:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
		echo "      	<table cellspacing=0 cellpadding=0>\n";
		echo "      		<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		
		
		if ($rowpre2['accountingsystem']!=2)
		{
			if ($rowpre1['renov']==1 && $rowpre3['rmas_div']!=0)
			{
				echo "         ".str_pad($rowpre3['rmas_div'], 2, "0", STR_PAD_LEFT)." <input type=\"text\" name=\"njobid\" id=\"usr_njobid\" size=17 maxlength=\"5\">\n";
			}
			else
			{
				echo "         ".str_pad($rowpre3['mas_div'], 2, "0", STR_PAD_LEFT)." <input type=\"text\" name=\"njobid\" id=\"usr_njobid\" size=17 maxlength=\"5\">\n";
			}
		}
		else
		{
			echo "         <input type=\"text\" name=\"njobid\" id=\"usr_njobid\" maxlength=\"5\">\n";
		}
		
		echo "      			</td>\n";
		echo "      			<td align=\"left\" width=\"20px\">\n";
		echo "						<div id=\"status_getjobnumber\"></div>\n";
		echo "      			</td>\n";
		echo "      			<td align=\"left\">\n";
		echo "						<img class=\"JMStooltip\" id=\"usr_findnextjobnumber\" src=\"images/find.png\" title=\"Click to retrieve the next available Job Number\">\n";
		echo "      			</td>\n";
		echo "      		</tr>\n";
		echo "      	</table>\n";
		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		
		echo "		</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Salesman:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"jobid\" value=\"".$rowpre3['lname'].",".$rowpre3['fname']."\" DISABLED>\n";
		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Errors:</b></td>\n";
		echo "      <td class=\"gray\" align=\"right\"></td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

		if ($rowpre2['accountingsystem']!=2)
		{
			if ($rowpre1['renov']==1 && $rowpre3['rmas_div']!=0)
			{
				if (!isset($rowpre3['rmas_div']) || $rowpre3['rmas_div']==0)
				{
					$errinp++;
				}
		
				if (!isset($rowpre3['rmas_div']) || $rowpre3['rmas_div']==0)
				{
					echo "<font color=\"red\">SalesRep not Configured.<br>Contact your Packet Processor.</font>";
				}
			}
			else
			{
				if (!isset($rowpre3['mas_div']) || $rowpre3['mas_div']==0)
				{
					$errinp++;
				}
		
				if (!isset($rowpre3['mas_div']) || $rowpre3['mas_div']==0)
				{
					echo "<font color=\"red\">SalesRep not Configured.<br>Contact your Packet Processor.</font>";
				}
			}
		}

		echo "      </td>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "   </tr>\n";

		if ($rowpre2['accountingsystem'] >= 2)
		{
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\"> Accounting Release\n";
			echo "         <input class=\"transnb\" type=\"checkbox\" name=\"accountingrelease\" value=\"1\">\n";
			echo "      </td>\n";
			echo "      <td class=\"gray\" align=\"left\"></td>\n";
			echo "   </tr>\n";
		}

		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"left\"></td>\n";
		echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";

		if ($errinp > 0)
		{
			echo "         <button type=\"submit\" DISABLED>Create Job</button>\n";
		}
		else
		{
			echo "         <button type=\"submit\">Create Job</button>\n";
		}

		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
}

function create_job_chk()
{
	$qrypre1		= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$respre1		= mssql_query($qrypre1);
	$rowpre1		= mssql_fetch_array($respre1);

	$qrypre1a		= "SELECT contractdate FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respre1a		= mssql_query($qrypre1a);
	$rowpre1a		= mssql_fetch_array($respre1a);

	$qrypre1b		= "SELECT njobid,jobid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	$respre1b		= mssql_query($qrypre1b);
	$nrowpre1b		= mssql_num_rows($respre1b);

	$qrypre2		= "SELECT psched,psched_perc,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2		= mssql_query($qrypre2);
	$rowpre2		= mssql_fetch_array($respre2);

	$qrypre3		= "SELECT fname,lname,mas_div,rmas_div FROM security WHERE securityid='".$rowpre1['securityid']."';";
	$respre3		= mssql_query($qrypre3);
	$rowpre3		= mssql_fetch_array($respre3);

	$sdate	=date("m-d-Y", strtotime($rowpre1['added']));
	$cdate	=date("m-d-Y", strtotime($rowpre1a['contractdate']));

	if ($rowpre1['renov']==1 && $rowpre3['rmas_div']!=0)
	{
		$jnum	=$rowpre3['rmas_div'].str_pad($_REQUEST['njobid'], 5, "0", STR_PAD_LEFT);
	}
	else
	{
		$jnum	=$rowpre3['mas_div'].str_pad($_REQUEST['njobid'], 5, "0", STR_PAD_LEFT);
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"post_create_job\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['custid']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$rowpre1['jobid']."\">\n";
	echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";

	echo "<script type=\"text/javascript\" src=\"js/jquery_job_func.js\"></script>\n";
	echo "<table class=\"outer\" align=\"center\" width=\"350px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <th class=\"gray\" colspan=\"2\" align=\"left\">Create New Job (Validate)</th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>System Insert Date:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"sdate\" value=\"".$sdate."\" DISABLED>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Contract Date:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"cdate\" value=\"".$cdate."\" DISABLED>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Contract Number:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"jobid\" value=\"".$rowpre1['jobid']."\" DISABLED>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Job Number:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\"><b>".$jnum."</b></td>\n";
	echo "   </tr>\n";

	$errinp=0;

	if ($nrowpre1b > 0)
	{
		$errinp++;
	}

	if (empty($_REQUEST['njobid']) || strlen($_REQUEST['njobid']) < 4 || strlen($_REQUEST['njobid']) > 7 || !is_numeric($_REQUEST['njobid']))
	{
		$errinp++;
	}

	if ($errinp > 0)
	{
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\" valign=\"top\"><b>Errors:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "			<font color=\"red\">\n";

		// Errors

		if ($nrowpre1b > 0)
		{
			echo "Job Number Already Exists.<br>";
		}

		if (empty($_REQUEST['njobid']))
		{
			echo "Job Number is blank.<br>";
		}

		if (strlen($_REQUEST['njobid']) < 4 || strlen($_REQUEST['njobid']) > 7 )
		{
			echo "Job Number must be between 4 and 7 digits.<br>";
		}

		if (!is_numeric($_REQUEST['njobid']))
		{
			echo "Job Number must be Numeric.<br>";
		}

		echo "			</font>\n";
		echo "		</td>\n";
		echo "   </tr>\n";
	}

	if (isset($rowpre2['accountingsystem']) and $rowpre2['accountingsystem'] >= 2)
	{
		if (isset($_REQUEST['accountingrelease']) and $_REQUEST['accountingrelease']==1)
		{
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\"> Accounting Release\n";
			echo "         <input class=\"transnb\" type=\"checkbox\" name=\"accountingrelease\" id=\"usr_accountingrelease\" value=\"1\" CHECKED>\n";
			echo "      </td>\n";
			echo "      <td class=\"gray\" align=\"left\"></td>\n";
			echo "   </tr>\n";
		}
		else
		{
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\"> Accounting Release\n";
			echo "         <input class=\"transnb\" type=\"checkbox\" name=\"accountingrelease\" id=\"usr_accountingrelease\" value=\"1\">\n";
			echo "      </td>\n";
			echo "      <td class=\"gray\" align=\"left\"></td>\n";
			echo "   </tr>\n";
		}
	}

	echo "   <tr>\n";
	echo "      <td class=\"gray\" colspan=\"2\" align=\"right\">\n";

	if ($errinp > 0)
	{
		echo "         <button type=\"submit\" DISABLED>Create Job</button>\n";
	}
	else
	{
		echo "         <button type=\"submit\" id=\"usr_createjob\">Create Job</button>\n";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";

}

function post_create_job()
{
	if (empty($_REQUEST['njobid'])||strlen($_REQUEST['njobid']) < 3)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: Invalid Job #! Click Back and correct.";
		exit;
	}
	elseif (empty($_REQUEST['jobid'])||strlen($_REQUEST['jobid']) < 3)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: Invalid Contract #!";
		exit;
	}
	else
	{
		$qry1	= "SELECT njobid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."'";
		$res1	= mssql_query($qry1);
		$nrow1	= mssql_num_rows($res1);

		if ($nrow1 > 0)
		{
			echo "<font color=\"red\"><b>ERROR!</b></font>: Job # Already Exists!\n";
			exit;
		}
		else
		{
			$qry		= "exec dbo.sp_set_contr_to_job @officeid='".$_SESSION['officeid']."',@jobid='".$_REQUEST['jobid']."',@njobid='".$_REQUEST['njobid']."';";
			$res		= mssql_query($qry);
			
			if (isset($_REQUEST['accountingrelease']) and $_REQUEST['accountingrelease']==1)
			{
				//echo 'TEST<br>';
				$qry0	= "UPDATE jobs SET acc_status=1 WHERE officeid=".$_SESSION['officeid']." AND jobid='".$_REQUEST['jobid']."';";
				$res0	= mssql_query($qry0);
				
				//echo $qry0.'<br>';
			}

			//jobs_search();
			
			view_job_retail();
		}
	}
}

function delete_job()
{
    ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	if ($_REQUEST['call']=="delete_job1")
	{
		$qryA = "SELECT * FROM jobs WHERE officeid=".$_SESSION['officeid']." AND jobid='".$_REQUEST['jobid']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		$nrowA= mssql_num_rows($resA);

		$qryAb = "SELECT njobid,jadd,jobid FROM jdetail WHERE officeid=".$_SESSION['officeid']." AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_array($resAb);
		$nrowAb= mssql_num_rows($resAb);

		$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$rowA['jobid']."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);

		$acclist=explode(",",$_SESSION['aid']);
		if (!in_array($rowA['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to Delete this Job</b>";
			exit;
		}

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"delete_job2\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowA['sidm']."\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$rowA['njobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$rowA['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
		echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowA['custid']."\">\n";
		echo "<input type=\"hidden\" name=\"clname\" value=\"".$rowB['clname']."\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Confirm Delete Job/Addendum:</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">\n";

		if ($nrowAb > 1 && $jadd > 0)
		{
			echo "         <font color=\"red\">!ERROR! This Job cannot be Deleted, Addendum exists!</font>\n";
		}
		//else
		//{
		//	echo "         <font color=\"red\">!WARNING! This operation is not reversible!</font>\n";
		//}

		echo "		</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Job Id:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowA['njobid']."\" DISABLED>\n";
		echo "      </td>\n";
		echo "   </tr>\n";

		if ($_REQUEST['jadd'] > 0)
		{
			echo "   <tr>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Addn Id:</b></td>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
			echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowAb['jadd']."\" DISABLED>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
		}

		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowB['cfname']." ".$rowB['clname']."\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";

		if ($nrowAb > 1 && $jadd > 0)
		{
			echo "         <button type=\"submit\" DISABLED>Approve</button>\n";
		}
		else
		{
			echo "         <button type=\"submit\">Approve</button>\n";
		}

		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	elseif ($_REQUEST['call']=="delete_job2")
	{
		$qry	= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		$nrow	= mssql_num_rows($res);

		$qryA	= "SELECT id,njobid,jadd,jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
		$resA	= mssql_query($qryA);
		$rowA	= mssql_fetch_array($resA);
		$nrowA= mssql_num_rows($resA);

		$acclist=explode(",",$_SESSION['aid']);
		if (!in_array($row['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to Delete this Job</b>";
			exit;
		}

		if ($nrow > 0 && $nrowA == 1)
		{
			$qryB	= "exec dbo.sp_revert_job_to_contr @officeid='".$_SESSION['officeid']."',@jobid='".$rowA['jobid']."',@jadd='".$rowA['jadd']."';";
			$resB	= mssql_query($qryB);
			
			
			if ($rowA['jadd']!=0)
			{
				$qryCa	= "DELETE FROM jest..CommissionHistory WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowA['jobid']."' and jadd=".$rowA['jadd'].";";
				$resCa	= mssql_query($qryCa);
				
				$qryCb	= "DELETE FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowA['jobid']."' and jadd=".$rowA['jadd'].";";
				$resCb	= mssql_query($qryCb);
			}
			else
			{
				$qryCa	= "DELETE FROM jest..CommissionHistory WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowA['jobid']."' AND cbtype=4;";
				$resCa	= mssql_query($qryCa);
			}
			
			if ($_REQUEST['jadd'] > 0)
			{
				view_job_retail();
			}
			else
			{
				//job_search();
				
				job_contr_search();
			}
		}
	}
}

function job_contr_search()
{
	$acclist=explode(",",$_SESSION['aid']);
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	if (isset($_REQUEST['clname']) && strlen($_REQUEST['clname']) >= 3)
	{
		$sval=$_REQUEST['clname'];
	}
	else
	{
		$sval='';
	}

	echo "<table width=\"60%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\">Contract Search Tool</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Data Field</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Input Parameter</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Renov Only</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Sort by</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Order by</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"lname\">Customer Last Name</option>\n";
	//echo "                                 		<option value=\"cnum\">Contract #</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" name=\"sval\" size=\"20\" value=\"".$sval."\" title=\"Enter Full/Partial Customer Name or Contract Number in this Field\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"J1.jobid\" SELECTED>Contract #</option>\n";
	echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"C.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "                                 <td align=\"right\" valign=\"top\">\n";
	echo "                                    <select name=\"ctrinsdate\">\n";
	echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"20\" title=\"Begin Date\"> <a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a><br>";
	echo "                                 	<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"20\" title=\"End Date\"> <a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "         			</form>\n";
	echo "				</tr>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "				<tr>\n";
	echo "                                 <td align=\"center\" colspan=\"7\"><hr width=\"90%\"</td>\n";
	echo "				</tr>\n";

	if ($_SESSION['clev'] >= 5)
	{
		echo "										<tr>\n";
		echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "                              	<td align=\"right\" valign=\"bottom\">Salesman:</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"assigned\">\n";

		while ($row1 = mssql_fetch_array($res1))
		{
			if (in_array($row1['securityid'],$acclist))
			{
				$secl=explode(",",$row1['slevel']);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				echo "                                    	<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
			}
		}

		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                 		<option value=\"J1.jobid\" SELECTED>Contract #</option>\n";
		echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"ascdesc\">\n";
		echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
		echo "                                 		<option value=\"DESC\">Descending</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
		echo "         								</form>\n";
		echo "										</tr>\n";
	}

	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function updateMA()
{
	$qry0 = "select MAX(jadd) as mjadd from jest..jdetails where officeid=".$_SESSION['officeid']." and jobid='".$_REQUEST['jobid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	if ($row0['mjadd']==0)
	{
		$qry1 = "update jest..CommissionSchedule set amt=convert(money,'".$_REQUEST['amt']."') where csid='".$_REQUEST['csid']."';";
		$res1 = mssql_query($qry1);
		
		$qry2 = "select hid from jest..CommissionHistory where oid=".$_SESSION['officeid']." and jobid='".$_REQUEST['jobid']."' and htype='M';";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);
		$nrow2= mssql_num_rows($res2);
		
		if ($nrow2 == 1)
		{
			$qry2a = "update jest..CommissionHistory set amt=convert(money,'".$_REQUEST['amt']."') where hid='".$row2['hid']."';";
			$res2a = mssql_query($qry2a);
		}
	}

	view_job_retail();
}

function CommissionScheduleRW_Job($v)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$dbg=0;
	$tcomm=0;
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\">Commissions</td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	
	if ($dbg==1)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"7\">\n";
		
		echo "<pre>";
		print_r($v);
		echo '<br><br>';
		//print_r($comar);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 1 SR Specific Comm
	$qry1a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=1;";
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{			
		$tcomm=$tcomm + $row1a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Base Comm</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row1a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row1a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row1a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 6 Comms
	$qry6a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=6 order by adate desc;";
	$res6a = mssql_query($qry6a);
	$row6a = mssql_fetch_array($res6a);
    $nrow6a= mssql_num_rows($res6a);
	
	if ($nrow6a > 0)
	{		
		$tcomm=$tcomm + $row6a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">".$row6a['label']."</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row6a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row6a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row6a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 2 SR OU Specific Comm
	$qry2a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=2;";
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
    $nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{		
		$tcomm=$tcomm + $row2a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Over/Under Comm</font></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row2a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
			
		if ($row2a['type'] == 2)
		{
			echo '%';
		}
			
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row2a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div>\n";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 9 Comms Tiered
	$qry9a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=9 order by adate desc;";
	$res9a = mssql_query($qry9a);
	$row9a = mssql_fetch_array($res9a);
    $nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{		
		$tcomm=$tcomm + $row9a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Merit Bonus</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row9a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row9a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row9a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 8 Comms
	$qry8a  = "select top 1 * from jest..CommissionSchedule where oid=".$_SESSION['officeid']." and jobid='".$v['jobid']."' and cbtype=8 order by adate desc;";
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
    $nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a > 0)
	{
		$tcomm=$tcomm + $row8a['amt'];
		echo "           <tr>\n";
		//echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$row8a['label']."</b></td>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Commission Override</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row8a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($row8a['type'] == 2)
		//{
		//	echo '%';
		//}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row8a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint JMStooltip\" title=\"Minimum Commission Override enabled\"><img src=\"images/information.png\" width=\"11px\" height=\"11px\"></div>\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 0 Manual Adjust Comm
	$qry3a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=0 order by adate desc;";
	$res3a = mssql_query($qry3a);
	$row3a = mssql_fetch_array($res3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{		
		$tcomm=$tcomm + $row3a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Manual Adjust</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row3a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row3a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		if ($_SESSION['jlev'] >= 4 && $v['mjadd'] == 0 && $v['mas_prep'] == 0)
		{
			echo "					<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"updateMA\">\n";
			echo "						<input type=\"hidden\" name=\"csid\" value=\"".$row3a['csid']."\">\n";
			echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$v['njobid']."\">\n";
			echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$v['jobid']."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "						<input class=\"brdrtxtrght\" type=\"text\" name=\"amt\" id=\"ouo0\" value=\"".number_format($row3a['amt'], 2, '.', '')."\" size=\"7\" onChange=\"updTotalComm('ouo1','ouo2','ouo0','tcommamt');\">\n";
		}
		else
		{
			echo number_format($row3a['amt'], 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($_SESSION['jlev'] >= 4 && $v['mjadd'] == 0 && $v['mas_prep'] == 0)
		{
			echo "                  <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Update\" title=\"Update Manual Adjust\">\n";
		}
		
		echo "				</td>\n";
		echo "           </tr>\n";
		
		if ($_SESSION['jlev'] >= 4 && $v['mjadd'] == 0 && $v['mas_prep'] == 0)
		{
			echo "					</form>\n";
		}
	}
	
	//Grab Category 10 Fixed Manual Override Comm
	$qry10a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=10;";
	$res10a = mssql_query($qry10a);
	$row10a = mssql_fetch_array($res10a);
    $nrow10a= mssql_num_rows($res10a);
	
	if ($nrow10a==1)
	{			
		$tcomm=$tcomm + $row10a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Manual Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row10a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row10a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row10a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo1\">".number_format($row10a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo1\">".number_format($row10a['amt'], 2, '.', '')."</div>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 11 Percent Manual Override Comm
	$qry11a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=11;";
	$res11a = mssql_query($qry11a);
	$row11a = mssql_fetch_array($res11a);
    $nrow11a= mssql_num_rows($res11a);
	
	if ($nrow11a==1)
	{		
		$tcomm=$tcomm + $row11a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Manual Override</font></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row11a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
			
		if ($row11a['type'] == 2)
		{
			echo '%';
		}
			
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row11a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo2\">".number_format($row11a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo2\">".number_format($row11a['amt'], 2, '.', '')."</div>\n";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 3+ Comms
	/*$qry3a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry >=3 and active=1 order by secid asc,ctgry asc;";
	$res3a = mssql_query($qry3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{
		while ($row3a = mssql_fetch_array($res3a))
        {
            $comar[]=array(
						'cmid'=>$row3a['cmid'],
						'secid'=>$row3a['secid'],
						'catid'=>$row3a['ctgry'],
						'ctype'=>$row3a['ctype'],
						'rate'=>$row3a['rate'],
						'thresh'=>$row3a['thresh'],
						'd1'=>strtotime($row3a['d1']),
						'd2'=>strtotime($row3a['d2']),
						'active'=>$row3a['active'],
						'label'=>$row3a['name'],
						'amt'=>$row3a['amt']
					);
        }
	}
	
	if ($nrow3a > 0)
	{
		foreach ($comar as $cn => $cv)
		{
			if ($drange >= $cv['d1'] && $drange < $cv['d2'])
			{
				if ($cv['ctype']==1)
				{
					$ctype='fx';
				}
				elseif ($cv['ctype']==2)
				{
					$ctype='%';
				}
				else
				{
					$ctype='';
				}
				
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][cmid]\" value=\"".$cv['cmid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][secid]\" value=\"".$cv['secid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\" value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\" value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rate]\" value=\"".$cv['rate']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][thresh]\" value=\"".$cv['thresh']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d1]\" value=\"".$cv['d1']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d2]\" value=\"".$cv['d2']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\" value=\"".$cv['label']."\">\n";
				
				if ($cv['catid'] == 3)
				{
					
					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\">".$cv['label']."</td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
				elseif ($cv['catid'] == 4)
				{

					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\">".$cv['label']."</td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
			}
		}
	}*/
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Total Comm</td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div></font>";
	}
	else
	
	{
		echo "					<div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "              	<img src=\"images/pixel.gif\">\n";
	//echo "                  <input class=\"transnb\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
}

?>