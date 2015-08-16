<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * �ҵ� Controller ��
 *
 */
// ------------------------------------------------------------------------
class MY_Controller extends Controller
{
	function __construct()
	{
		parent::Controller();

		date_default_timezone_set('Asia/Shanghai');

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		$this->OutputStatus = STATUS_OK;
		$this->OutputArray = array();
		//$this->load->library('validation');
	}

	protected function setErrorOutput($value)
	{
		if ($value != '')
		{
			$this->OutputStatus = STATUS_ERROR;
			$this->OutputArray[] = $value;
		}
	}

	// ��׼��װ����ͼ�ļ�
	protected function loadView($name)
	{
		$this->load->view($name, array(
			'Status' => $this->OutputStatus,
			'Messages' => $this->OutputArray
		));
	}

	function checkVersion($version)
	{
		return !($version < OTHELLO_VERSION);
	}
}