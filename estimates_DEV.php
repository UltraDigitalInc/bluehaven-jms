<?php
error_reporting(E_ALL);
ini_set('display_errors','On');


function BaseMatrix()
{
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332) {
        echo __FUNCTION__.' ('.__LINE__.')<br>';
	}
    else {
        echo "Estimate Functions not ready for Testing. Disable Tester status if you need to run an Estimate. Maintenance -> Options";
        exit;
    }
	
    define('CODEACCESS',1);
	include ('estimates_func_DEV.php');
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=="CreateContract") {
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332) {
			CreateContractwTAX();
		}
		else {
			CreateContract();
		}
	}
    elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="search") {
		EstimateSearch();
	}
    elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="EstimateView") {
		EstimateView($_REQUEST['oid'],$_REQUEST['estid']);
	}
    elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="CreateEstimate") {
        $ce=CreateEstimate($_REQUEST['cid']);
		EstimateView($ce['oid'],$ce['estid']);
	}
    elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="EstimateCost") {
		EstimateCost($_REQUEST['oid'],$_REQUEST['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="view_retail_print") {
		viewest_retail_print($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="view_cost") {
		viewest_cost();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="view_cost_print") {
		viewest_cost_print($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="view_addnew") {
		if ($_SESSION['officeid']==69) {
			viewest_addnew_TED($_REQUEST['officeid'],$_REQUEST['estid']);
		}
		else {
			viewest_addnew_NEW($_SESSION['estid']);
		}
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="acc_adds") {
		if ($_SESSION['officeid']==69) {
			ajxEventProc(0);
			add_acc_items_TED($_REQUEST['officeid'],$_REQUEST['estid']);
		}
		else {
			add_acc_items($_SESSION['estid']);
		}
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="update_contract_amt") {
		update_contract_amt($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="acc_adds_addendum") {
		add_acc_items_add($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="addadj") {
		addadj_init($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="adjins") {
		addadj_ins($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_bid") {
		edit_bid();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_bid_jobmode_add") {
		edit_bid_jobmode_add();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_bid_jobmode_delete") {
		edit_bid_jobmode_delete();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_mpa_jobmode_add") {
		edit_mpa_jobmode_add();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_mpa_jobmode_delete") {
		edit_mpa_jobmode_delete();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_bid_update") {
		edit_bid_update();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit_bid_delete") {
		edit_bid_delete();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="bidins") {
		bid_addins($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="update") {
		updateest($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="insertest_add")  {
		insertest_add($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="remove_acc") {
		remove_acc($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="pop_update") {
		pop_updateest($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="delete_est1") {
		delete_est($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="delete_est2") {
		delete_est($_SESSION['estid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="search_results") {
		listest();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="list") {
		listest();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="set_digdate") {
		set_digdate();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="set_clsdate") {
		set_clsdate();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="set_condate") {
		set_condate();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="biddel") {
		edit_bid_jobmode_delete();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="mpadel") {
		edit_mpa_jobmode_delete();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='remove_est_adj_item') {
		remove_est_adj_item();
	}
}