<?php
define('_DS', DIRECTORY_SEPARATOR);
define('FIX_ZIPSIZE', 2000000);
define('_PRICE_ID_', Configuration::get('_PRICE_ID_'));
define('_ID_LANG_', Configuration::get('_ID_LANG_'));
if (!_PS_UPLOAD_DIR_)
define('_PS_UPLOAD_DIR_',             _PS_ROOT_DIR_.'/upload/');
$start_time      = microtime(true);
$max_exec_time = min(30, @ini_get("max_execution_time"));
if (empty($max_exec_time)) $max_exec_time = 30;
$fix           = Configuration::get('_ecm_csync_fix_');
if ($fix == 1) {
    define('FIX_ZIP',"yes");
}
else {
    define('FIX_ZIP',"no");
}
$root_cat   = Configuration::get('PS_ROOT_CATEGORY');
$home_cat   = Configuration::get('PS_HOME_CATEGORY');
$ecm_ag_position   = Configuration::get('_ecm_ag_position_');
$ecm_a_position   = Configuration::get('_ecm_a_position_');
$fod             = Configuration::get('_ecm_csync_fod_');
$fods            = Configuration::get('_ecm_csync_fods_');
$install_date    = Configuration::get('_ecm_csync_install_date_');
$deliv           = Configuration::get('_ecm_csync_deliv_');
$send_orders     = Configuration::get('_ecm_csync_send_orders_');
$order_date_min            = Configuration::get('_ecm_csync_date_');
$prefix          = Configuration::get('_ecm_csync_prefix_');
$fo_statuses     = Configuration::get('_ecm_csync_final_orders_states_');
$employee        = Configuration::get('_ecm_csync_employee_');
$curr            = Configuration::get('_ecm_csync_curr_1C_');
$set_curr            = Configuration::get('_ecm_csync_set_curr_');
$custom_curr =  Configuration::get('_ecm_csync_custom_curr_');
$yn1           = Configuration::get('_ecm_csync_gen_');
$dwim = Configuration::get('_ecm_csync_dwim_');
$use_watermark = Configuration::get('_ecm_csync_wm_');
$gen_pr           = Configuration::get('_ecm_csync_gen_pr_');
$cs              = Configuration::get('_ecm_csync_cs_');
$col         = Configuration::get('_ecm_csync_col_');
$aqs             = Configuration::get('_ecm_csync_aqs_');
$zero = Configuration::get('_ecm_csync_zero_');
$redirect = Configuration::get('_ecm_csync_redirect_');
$visibility = Configuration::get('_ecm_csync_visibility_');
$zero_del = Configuration::get('_ecm_csync_zero_del_');
$redirect_del = Configuration::get('_ecm_csync_redirect_del_');
$visibility_del = Configuration::get('_ecm_csync_visibility_del_');
$qtp             = Configuration::get('_ecm_csync_qtp_');
$multifeat       = Configuration::get('_ecm_csync_multifeat_');
$c_features      = Configuration::get('_ecm_csync_features_');
$sc            = Configuration::get('_ecm_csync_sc_');
$category_product       = Configuration::get('_ecm_csync_category_product_');
$multicat        = Configuration::get('_ecm_csync_multicat_');
$idsc          = Configuration::get('_ecm_csync_idsc_');
$sc          = Configuration::get('_ecm_csync_sc_');
$cp1c            = Configuration::get('_ecm_csync_cp1c_');
$cp            = Configuration::get('_ecm_csync_cp_');
$status_export = Configuration::get('_ecm_csync_status_export_');
$qvant0  =Configuration::get('_ecm_csync_qvant0_');
$off         = Configuration::get('_ecm_csync_price_off_');
$spec_off         = Configuration::get('_ecm_csync_spec_price_off_');
$login_1c  = Configuration::get('_ecm_csync_login_1c_');
$pass_1c  = Configuration::get('_ecm_csync_pass_1c_');
$tax_rule_selected = Configuration::get('_ecm_csync_tax_rule_');
$id_lang         = Configuration::get('_ecm_csync_lang_');
$multilang       = Configuration::get('_ecm_csync_multilang_');
$clear_cache       = Configuration::get('_ecm_csync_clear_cache_');
$new1c           = Configuration::get('_ecm_csync_new1c_');
$n1c             = Configuration::get('_ecm_csync_n1c_');
$sd1c            = Configuration::get('_ecm_csync_sd1c_');
$d1c             = Configuration::get('_ecm_csync_d1c_');
$precat          = Configuration::get('_ecm_csync_precat_');
$st            = Configuration::get('_ecm_csync_st_');
$fa              = Configuration::get('_ecm_csync_fa_');
$diff            = Configuration::get('_ecm_csync_diff_');
$cross            = Configuration::get('_ecm_csync_cross_');
$analog            = Configuration::get('_ecm_csync_analog_');
$tags_           = Configuration::get('_ecm_csync_tags_');
$f_url           = Configuration::get('_ecm_csync_furl_product_');
$video_selected  = Configuration::get('_ecm_csync_videosel_');
$ecm_manufacturs = Configuration::get('_ecm_manufacturs_');
$up              = Configuration::get('_ecm_position_');
$qvant_entity  = Configuration::get('_ecm_csync_qvant_');
$s_d         = Configuration::get('_ecm_csync_sdesk_');
$shvd 		 = Configuration::get('_ecm_csync_shvd_');
$feature_man = Configuration::get('_ecm_csync_feature_man_');
$not_man = Configuration::get('_ecm_csync_not_man_');
$rme = Configuration::get('_ecm_csync_rme_');
$f_d         = Configuration::get('_ecm_csync_desk_');
$vid         = Configuration::get('_ecm_csync_vid_');
$fbools         = Configuration::get('_ecm_csync_fbools_');
$phone_login = Configuration::get('_ecm_csync_phonelogin_');
$on_stock = Configuration::get('_ecm_csync_on_stock_');
$pjs_installed =  Configuration::get('_ecm_csync_ecm_pjs_');
$productrating_installed =  Configuration::get('_ecm_csync_productrating_');
$delivery      = array(
    'np'  => (array('ecm_newpost' => Configuration::get('_ecm_csync_ecm_novaposhta_'))),
    'up'  => (array('ecm_ukrposhta' =>Configuration::get('_ecm_csync_ecm_ukrposhta_'))),
    'al'  => (array('ecm_autolux' =>Configuration::get('_ecm_csync_ecm_autolux_'))),
    'da'  => (array('ecm_delivery_auto' =>Configuration::get('_ecm_csync_ecm_delivery_auto_'))),
    'it'  => (array('ecm_intime' =>Configuration::get('_ecm_csync_ecm_intime_'))),
    'mexp'=> (array('ecm_meest_express' =>Configuration::get('_ecm_csync_ecm_meest_express_')))
);
$cprice = Db::getInstance()->getValue("SELECT `guid` FROM `"._DB_PREFIX_."optprice` WHERE `main` = 1");
$optprises        = Db::getInstance()->ExecuteS("SELECT * FROM `"._DB_PREFIX_."optprice`");
$p_data = Configuration::get('_ecm_csync_p_data_');
$new_prod = Configuration::get('PS_NB_DAYS_NEW_PRODUCT')*24*60*60;
//$fo_statuses = Configuration::get('_FINAL_ORDERS_STATES_');

$fq              = Configuration::get('_FQ_');




