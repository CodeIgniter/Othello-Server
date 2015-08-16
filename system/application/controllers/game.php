<?php

// ��Ϸ������
class Game extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}

	// ��ʼ��Ϸ
	function start()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('game_model');

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
				$this->form_validation->set_error_string('�Ƿ���ʼ��Ϸ�����������û���ݣ�');
				throw new Exception();
			}

			$result = $this->game_model->start($get['username']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = array(
					$result['GameTimes'] - 1,
				);
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '��ʼ��Ϸ����';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// ȡ����Ϸ
	function cancel()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('game_model');

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
				$this->form_validation->set_error_string('�Ƿ��ж���Ϸ�����������û���ݣ�');
				throw new Exception();
			}

			$result = $this->game_model->cancel($get['username']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = array(
					$result['GameTimes'] + 1,
				);
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�ж���Ϸ����';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �������
	function over()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('game_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('tablename', '�����', 'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('partner', '������', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('state', '״̬', 'trim|required|integer');

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
				$this->form_validation->set_error_string('�Ƿ�������Ϸ�����������û���ݣ�');
				throw new Exception();
			}

			if (!$this->user_model->checkUserExist($get['partner']))
			{
				$this->form_validation->set_error_string('�޴��û���');
				throw new Exception();
			}

			$result = $this->game_model->over($get['username'], $get['partner'], $get['tablename'], $get['state']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				if ($result['Level'] == '0')
				{
					$this->OutputArray = array(
						'---',
						'---',
					);
				}
				else
				{
					$this->OutputArray = array(
						$result['Score'],
						$result['Bouns'],
					);
				}
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '������Ϸ����';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}
}

/* End of file game.php */
/* Location: ./system/application/controllers/game.php */