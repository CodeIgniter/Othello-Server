<?php

// �û����߿�����
class Online extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}

	// ��ȡ������Ϣ
	function get()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('online_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');

			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');

			if ($this->form_validation->run() == FALSE)
			{
				throw new Exception();
			}

			$get = $this->form_validation->get_all_gets();

			if (!$this->user_model->checkServerPassword($get['username'], $get['style']))
			{
				$this->form_validation->set_error_string('�Ƿ����ʣ�');
				throw new Exception();
			}

			if (!$this->checkVersion($get['ver']))
			{
				$this->form_validation->set_error_string('�Բ�����������汾���ͣ����½�Ҹ���԰BBS���������°汾����ַ��http://bbs.ourhf.com');
				throw new Exception();
			}

			$result = $this->online_model->get($get['username']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				foreach ($result as $row)
				{
					$data = array(
						$row['UserName'],
						$row['Name'],
						$row['Face'],
						$row['GameTimes'],
						$row['Win'] + $row['Lose'] + $row['Draw'],
						$row['Score'],
						$row['IP'],
						$row['LANIP'],
						$row['Port'],
					);
					$this->OutputArray[] = $data;
				}
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û���û����ߣ�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}
}

/* End of file online.php */
/* Location: ./system/application/controllers/online.php */