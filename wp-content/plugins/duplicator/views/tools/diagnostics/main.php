<?php
	wp_enqueue_script('dup-handlebars');
	require_once(DUPLICATOR_PLUGIN_PATH . '/assets/js/javascript.php'); 
	require_once(DUPLICATOR_PLUGIN_PATH . '/views/inc.header.php'); 
	require_once(DUPLICATOR_PLUGIN_PATH . '/classes/utilities/class.u.scancheck.php');

	global $wp_version;
	global $wpdb;
	
	$action_response = null;
	
	$ctrl_ui = new DUP_CTRL_UI();
	$ctrl_ui->setResponseType('PHP');
	$data = $ctrl_ui->GetViewStateList();

	$ui_css_srv_panel   = (isset($data->payload['dup-settings-diag-srv-panel'])  && $data->payload['dup-settings-diag-srv-panel'])   ? 'display:block' : 'display:none';
	$ui_css_opts_panel  = (isset($data->payload['dup-settings-diag-opts-panel']) && $data->payload['dup-settings-diag-opts-panel'])  ? 'display:block' : 'display:none';
	
	
	//POST BACK
	if (isset($_POST['action'])) {
		$action_result = DUP_Settings::DeleteWPOption($_POST['action']);
		switch ($_POST['action']) 
		{
			case 'duplicator_settings'		 : 	$action_response = __('Plugin settings reset.', 'duplicator');		break;
			case 'duplicator_ui_view_state'  : 	$action_response = __('View state settings reset.', 'duplicator');	 break;
			case 'duplicator_package_active' : 	$action_response = __('Active package settings reset.', 'duplicator'); break;		
			case 'clear_legacy_data': 
				DUP_Settings::LegacyClean();			
				$action_response = __('Legacy data removed.', 'duplicator');
				break;
		}
	} 
?>

<style>
	div#message {margin:0px 0px 10px 0px}
	div#dup-server-info-area { padding:10px 5px;  }
	div#dup-server-info-area table { padding:1px; background:#dfdfdf;  -webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px; width:100% !important; box-shadow:0 8px 6px -6px #777; }
	div#dup-server-info-area td, th {padding:3px; background:#fff; -webkit-border-radius:2px;-moz-border-radius:2px;border-radius:2px;}
	div#dup-server-info-area tr.h img { display:none; }
	div#dup-server-info-area tr.h td{ background:none; }
	div#dup-server-info-area tr.h th{ text-align:center; background-color:#efefef;  }
	div#dup-server-info-area td.e{ font-weight:bold }
	td.dup-settings-diag-header {background-color:#D8D8D8; font-weight: bold; border-style: none; color:black}
	.widefat th {font-weight:bold; }
	.widefat td {padding:2px 2px 2px 8px}
	.widefat td:nth-child(1) {width:10px;}
	.widefat td:nth-child(2) {padding-left: 20px; width:100% !important}
	textarea.dup-opts-read {width:100%; height:40px; font-size:12px}
</style>


<?php
$section        = isset($_GET['section']) ? $_GET['section'] : 'info';
$txt_diagnostic = "Information";
$txt_log        = "Logs";
$txt_support    = "Support";
$tools_url      = 'admin.php?page=duplicator-tools&tab=diagnostics';

switch ($section) {
    case 'info':
        echo "<div class='lite-sub-tabs'><b>{$txt_diagnostic}</b> &nbsp;|&nbsp; <a href='{$tools_url}&section=log'>{$txt_log}</a> &nbsp;|&nbsp; <a href='{$tools_url}&section=support'>{$txt_support}</a></div>";
        include(dirname(__FILE__) . '/information.php');
        break;
    case 'log':
        echo "<div class='lite-sub-tabs'><a href='{$tools_url}&section=info'>{$txt_diagnostic}</a>  &nbsp;|&nbsp;<b>{$txt_log}</b>  &nbsp;|&nbsp; <a href='{$tools_url}&section=support'>{$txt_support}</a></div>";
        include(dirname(__FILE__) . '/logging.php');
        break;
    case 'support':
        echo "<div class='lite-sub-tabs'><a href='{$tools_url}&section=info'>{$txt_diagnostic}</a> &nbsp;|&nbsp; <a href='{$tools_url}&section=log'>{$txt_log}</a> &nbsp;|&nbsp; <b>{$txt_support}</b> </div>";
        include(dirname(__FILE__) . '/support.php');

        break;
}
?>