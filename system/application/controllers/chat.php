<?php

// ���������
class Chat extends MY_Controller {

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
		$this->load->model('chat_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');

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

			if (!$this->user_model->checkUser($get['username'], $get['password']))
			{
				$this->form_validation->set_error_string('����Ȩ���죡');
				throw new Exception();
			}

			$result = $this->chat_model->get();

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				foreach ($result as $row)
				{
					$data = array(
						$row['Name'],
						$row['ChatText'],
						$row['ChatDate']
					);
					$this->OutputArray[] = $data;
				}
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û���������ݣ�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// ����������Ϣ
	function send()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('chat_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('name', '�ǳ�', 'trim|required|max_length[15]');
			$this->form_validation->set_rules('text', '����', 'trim|required|max_length[100]');

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

			if (!$this->user_model->checkUser($get['username'], $get['password']))
			{
				$this->form_validation->set_error_string('����Ȩ���죡');
				throw new Exception();
			}

			$result = $this->chat_model->send($get);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				foreach ($result as $row)
				{
					$data = array(
						$row['Name'],
						$row['ChatText'],
						$row['ChatDate']
					);
					$this->OutputArray[] = $data;
				}
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û���������ݣ�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}
}

/* End of file chat.php */
/* Location: ./system/application/controllers/chat.php */