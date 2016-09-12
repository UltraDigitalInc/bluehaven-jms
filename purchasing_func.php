<?php

function BaseMatrix()
{
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='search')
	{
		po_search();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='pending')
	{
		po_pending();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='new')
	{
		po_new();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='CheckRequest')
	{
		CheckRequestScreen();
	}
}


function CheckRequestScreen()
{
	
	//echo substr($_SESSION['lname'],0,3);
	//$_SESSION['SessHash']=md5($_SESSION['securityid'].'.'.substr($_SESSION['lname'],0,2));
	//echo 'SESSID:'.session_id().'<br>';
	//echo 'SESSHS:'.$_SESSION['SessHash'].'<br>';
	
?>

	<script type="text/javascript" src="js/jquery_checkrequest_func.js"></script>
	<input type="hidden" id="SessHash" value="<?php echo $_SESSION['SessHash'] ?>">
	<table class="outer" width="950px" cellpadding="0" cellspacing="0">
		<tr>
			<td align="left">
				<table>
					<tr>
						<td>
							<strong>Check Request Menu</strong>
						</td>
					</tr>
				</table>
			</td>
			<td align="right">
				<table>
					<tr>
						<td>
							<input class="JMStooltip" type="text" name="cr_sval" id="cr_sval" value="" title="Enter Payee or Description then click Search">
							<select id="cr_pptype" name="cr_pptype">
								<option value="0">Unprocessed</option>
								<option value="10">Pending</option>
								<option value="20">Hold</option>
								<option value="80">Rejected</option>
								<option value="90">Processed</option>
							</select>
							<button id="CRSearch">Search</button>
						</td>
						<td>
							<button id="CRPending">Pending</button>
						</td>
						<td>
							<button id="CRProcessed">Processed</button>
						</td>
						<td>
							<button id="OpenCRMaster">New</button>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
	<div id="CRResults">
	</div>
	
	<div id="CRMasterDialog">
		<table class="outer" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table width="375px">
						<tr>
							<td align="right" width="150px">Requestor</td>
							<td><?php echo $_SESSION['fname'].' '.$_SESSION['lname'];?><input type="hidden" id="chkRequestor" value="<?php echo $_SESSION['securityid'];?>"></td>
						</tr>
						<tr>
							<td align="right" width="150px">Request Date</td>
							<td><input type="text" id="d1"></td>
						</tr>
						<tr>
							<td align="right" width="150px">Request Type</td>
							<td>
								<select id="chkType">
									<option></option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right" width="150px">Payee</td>
							<td></td>
						</tr>
						<tr>
							<td align="right" width="150px">Amount</td>
							<td></td>
						</tr>
						<tr>
							<td align="right" width="150px">Notes</td>
							<td><textarea id="chkNotes" cols="30" rows="5"></textarea></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

<?php
}

?>