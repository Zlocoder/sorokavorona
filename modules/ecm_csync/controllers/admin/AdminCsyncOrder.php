<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncOrderController extends AdminController
{

	public function __construct()
	{

		$this->bootstrap = true;
		parent::__construct();


	}

	public function renderList()
	{

		$this->module = new Ecm_csync();
		$path = _PS_MODULE_DIR_."ecm_csync";
		include($path. '/init/defines_inc.php');
		$statuses_ = OrderState::getOrderStates((int)$id_lang);
		$employees_ = Employee::getEmployees();
		foreach($statuses_ as $status)
			{
				$id       = $status['id_order_state'];
				$statuses["$id"] =  $status['name'];
			}
		foreach($employees_ as $employee_)
			{
				$id       = $employee_['id_employee'];
				$employees["$id"] =  $employee_['firstname']." ".$employee_['lastname'];
			}
		$size_s = (count($statuses)>10)?count($statuses)/1.5:count($statuses);
		$status_selected = explode(",",$fo_statuses);
		$send_ = $this->module->checked($send_orders);
		$fod_ = $this->module->checked($fod);
		$fods_ = $this->module->checked($fods);
		$deliv_ = $this->module->checked($deliv);
		$curr_ = $this->module->checked($curr);
		$p_data_ = $this->module->checked($p_data);
		$this->context->smarty->assign(
			array(
				'send'=> $send_,
				'fod' => $fod_,
				'fods' => $fods_,
				'deliv' => $deliv_,
				'prefix' => $prefix,
				'date' => $order_date_min,
				'employees' => $employees,
				'statuses' => $statuses,
				'size_s' => $size_s,
				'status_selected' => $status_selected,
				'employee_selected' => $employee,
				'curr_1C' => $curr_,
				'curr_custom' => $custom_curr,
				'p_data' => $p_data_,
				)
		);
		$more = $this->module->display($path, 'views/displayOrder.tpl');
		return $more.parent::renderList();
	}

}

