<?php

// �û�������
class User extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}

	// �û���½
	function login()
	{
		$this->load->library('form_validation');
		$this->load->model('security_model');
		$this->load->model('user_model');

		try
		{
			$this->form_validation->set_rules('id', 'id', 'trim|required|integer');
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');

			$this->form_validation->set_rules('username', '�û���', 'trim|required');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('lanip', 'IP', 'trim|required');
			$this->form_validation->set_rules('port', '�˿�', 'trim|required|integer');

			if ($this->form_validation->run() == FALSE)
			{
				throw new Exception();
			}

			$get = $this->form_validation->get_all_gets();

			if (!$this->security_model->checkCommonPassword($get['id'], $get['style']))
			{
				$this->form_validation->set_error_string('�Ƿ����ʣ�');
				throw new Exception();
			}

			if (!$this->checkVersion($get['ver']))
			{
				$this->form_validation->set_error_string('�Բ�����������汾���ͣ����½�Ҹ���԰BBS���������°汾����ַ��http://bbs.ourhf.com');
				throw new Exception();
			}

			$users = $this->user_model->login($get['username'], $get['password'], $get['lanip'], $get['port']);

			if (!$users)
			{
				$this->form_validation->set_error_string('��ע�������û���������ƴд�Ƿ���ȷ�����������ʻ��ѱ�ɾ����');
				throw new Exception();
			}

			$this->OutputStatus = STATUS_OK;
			$this->OutputArray = $users;
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �û�ע��
	function register()
	{
		$this->load->library('form_validation');
		$this->load->model('security_model');
		$this->load->model('user_model');

		try
		{
			$this->form_validation->set_rules('id', 'id', 'trim|required|integer');
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');

			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[30]|valid_email');
			$this->form_validation->set_rules('face', 'Ф��', 'trim|integer');
			$this->form_validation->set_rules('name', '�ǳ�', 'trim|max_length[15]');
			$this->form_validation->set_rules('sex', '�Ա�', 'trim|integer');
			$this->form_validation->set_rules('age', '����', 'trim|integer');
			$this->form_validation->set_rules('country', '����/����', 'trim|max_length[20]');
			$this->form_validation->set_rules('state', 'ʡ��', 'trim|max_length[20]');
			$this->form_validation->set_rules('city', '����', 'trim|max_length[20]');

			if ($this->form_validation->run() == FALSE)
			{
				throw new Exception();
			}

			$get = $this->form_validation->get_all_gets();

			if (!$this->security_model->checkCommonPassword($get['id'], $get['style']))
			{
				$this->form_validation->set_error_string('�Ƿ����ʣ�');
				throw new Exception();
			}

			if (!$this->checkVersion($get['ver']))
			{
				$this->form_validation->set_error_string('�Բ�����������汾���ͣ����½�Ҹ���԰BBS���������°汾����ַ��http://bbs.ourhf.com');
				throw new Exception();
			}

			if (!$this->user_model->register($get))
			{
				$this->form_validation->set_error_string('�Բ����û����� E-mail �ѱ�ע�ᣡ������ע�ᣡ');
				throw new Exception();
			}

			$this->OutputStatus = STATUS_OK;
			$this->OutputArray = 'ע��ɹ���';
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �û��˳�
	function logout()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');

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

			if (!$this->user_model->logout($get['username'], $get['password']))
			{
				$this->form_validation->set_error_string('�Բ�������Ȩ���д˲�����');
				throw new Exception();
			}

			$this->OutputStatus = STATUS_OK;
			$this->OutputArray = '�˳��ɹ���';
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �鿴�û�����
	function view()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');

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

			$result = $this->user_model->get($get['username']);
			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = array(
					$result['Email'],
					$result['UserClass'],
					$result['Face'],
					$result['Name'],
					$result['Sex'],
					$result['Age'],
					$result['Country'],
					$result['State'],
					$result['City'],
					$result['Win'],
					$result['Lose'],
					$result['Draw'],
					$result['GameTimes'],
					$result['Score']
				);
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = '�޴��û���';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �༭�û�����
	function edit()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');

			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '������', 'trim|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('oldpassword', '������', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('email', 'Email', 'trim|max_length[30]|valid_email');
			$this->form_validation->set_rules('face', 'Ф��', 'trim|integer');
			$this->form_validation->set_rules('name', '�ǳ�', 'trim|max_length[15]');
			$this->form_validation->set_rules('sex', '�Ա�', 'trim|integer');
			$this->form_validation->set_rules('age', '����', 'trim|integer');
			$this->form_validation->set_rules('country', '����/����', 'trim|max_length[20]');
			$this->form_validation->set_rules('state', 'ʡ��', 'trim|max_length[20]');
			$this->form_validation->set_rules('city', '����', 'trim|max_length[20]');

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

			if (!$this->user_model->checkUser($get['username'], $get['oldpassword']))
			{
				$this->form_validation->set_error_string('����Ȩ�༭�û����ϣ�');
				throw new Exception();
			}

			if (!empty($get['email']) && $this->user_model->checkEmail($get['username'], $get['email']))
			{
				$this->form_validation->set_error_string('�Բ��𣬴� E-mail �ѱ�ע�ᣡ������ѡ�� E-mail��');
				throw new Exception();
			}

			if (!$this->user_model->edit($get))
			{
				$this->form_validation->set_error_string('�޴��û���');
				throw new Exception();
			}

			$this->OutputStatus = STATUS_OK;
			$this->OutputArray = '�޸ĳɹ���';
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}
}

/* End of file user.php */
/* Location: ./system/application/controllers/user.php */