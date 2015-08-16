<?php

// ��ֿ�����
class Table extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->view('welcome_message');
	}

	// �������
	function create()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('creator', '������', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('nickname', '�ǳ�', 'trim|max_length[15]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('type', '�������', 'trim|required|integer');
			$this->form_validation->set_rules('timer', '��ּ�ʱ��', 'trim|required|integer');
			$this->form_validation->set_rules('level', '����', 'trim|required|integer');
			$this->form_validation->set_rules('lanip', 'IP', 'trim|max_length[15]');
			$this->form_validation->set_rules('port', '�˿�', 'trim|required|integer');

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

			if (!$this->user_model->checkUser($get['creator'], $get['password']))
			{
				$this->form_validation->set_error_string('����Ȩ������֣�');
				throw new Exception();
			}

			$result = $this->table_model->create($get);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = array(
					$result['TableName'],
					$result['Creator'],
					$result['CreatorName'],
					$result['Visitor'],
					$result['VisitorName'],
					$result['Type'],
					$result['GameTimer'],
					$result['Level'],
					$result['LastTime'],
					$result['IP'],
					$result['LANIP'],
					$result['Port']
				);
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�Բ��𣡴�����Ѿ����ڣ������½�����';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// ��ȡ����б�
	function get()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

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
				$this->form_validation->set_error_string('�Բ�����������汾���ͣ�������Ҹ���԰BBS���������°汾����ַ��http://www.ourhf.com');
				throw new Exception();
			}

			$result = $this->table_model->get($get['username']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				foreach ($result as $row)
				{
					$data = array(
						$row['TableName'],
						$row['Creator'],
						$row['CreatorName'],
						$row['Visitor'],
						$row['VisitorName'],
						$row['Type'],
						$row['GameTimer'],
						$row['Level'],
						$row['LastTime'],
						$row['IP'],
						$row['LANIP'],
						$row['Port']
					);
					$this->OutputArray[] = $data;
				}
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û����֣�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �������
	function join()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('visitor', '������', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('nickname', '�ǳ�', 'trim|max_length[15]');

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

			if (!$this->user_model->checkUser($get['visitor'], $get['password']))
			{
				$this->form_validation->set_error_string('����Ȩ������֣�');
				throw new Exception();
			}

			$result = $this->table_model->join($get['name'], $get['visitor'], $get['nickname']);

			if ($result === false)
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�Բ���' . $get['visitor'] . ' �Ѽ������֣�������ѡ��������֣�';
			}
			elseif (empty($result))
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�Բ��𣡴���ֲ����ڣ�������ѡ��';
			}
			else
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = array(
					$result['TableName'],
					$result['Creator'],
					$result['CreatorName'],
					$result['Visitor'],
					$result['VisitorName'],
					$result['Type'],
					$result['GameTimer'],
					$result['Level'],
					$result['LastTime'],
					$result['IP'],
					$result['LANIP'],
					$result['Port']
				);
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �Զ��������
	function autojoin()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

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
				$this->form_validation->set_error_string('����Ȩ�Զ�������֣�');
				throw new Exception();
			}

			$result = $this->table_model->autojoin($get['username']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				foreach ($result as $row)
				{
					$data = array(
						$row['TableName'],
						$row['Creator'],
						$row['CreatorName'],
						$row['Visitor'],
						$row['VisitorName'],
						$row['Type'],
						$row['GameTimer'],
						$row['Level'],
						$row['LastTime'],
						$row['IP'],
						$row['LANIP'],
						$row['Port']
					);
					$this->OutputArray[] = $data;

					$data = array(
						$row['Email'],
						$row['UserClass'],
						$row['Face'],
						$row['Name'],
						$row['Sex'],
						$row['Age'],
						$row['Country'],
						$row['State'],
						$row['City'],
						$row['Win'],
						$row['Lose'],
						$row['Draw'],
						$row['GameTimes'],
						$row['Score']
					);
					$this->OutputArray[] = $data;
				}
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û����֣�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// ɾ�����
	function remove()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');

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
				$this->form_validation->set_error_string('����Ȩɾ����֣�');
				throw new Exception();
			}

			if ($this->table_model->remove($get['name'], $get['username']))
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = 'ɾ���ɹ���';
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�Բ��𣡴�����ѱ�ɾ����';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �˳����
	function quit()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('visitor', '������', 'trim|min_length[3]|max_length[15]');

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
				$this->form_validation->set_error_string('����Ȩ�˳���֣�');
				throw new Exception();
			}

			if ($this->table_model->quit($get['name'], $get['username'], $get['visitor']))
			{
				$this->OutputStatus = STATUS_OK;
				$this->OutputArray = '�˳��ɹ���';
			}
			else
			{
				$this->OutputStatus = STATUS_ERROR;
				$this->OutputArray = '�Ƿ��˳���֣�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// ��ȡ�������
	function view()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');

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

			$result = $this->table_model->detail($get['name']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$data = array(
					$result['TableName'],
					$result['Creator'],
					$result['CreatorName'],
					$result['Visitor'],
					$result['VisitorName'],
					$result['Type'],
					$result['GameTimer'],
					$result['Level'],
					$result['LastTime'],
					$result['IP'],
					$result['LANIP'],
					$result['Port'],
					getUserIp(),
				);
				$this->OutputArray = $data;
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û����֣�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}

	// �༭���
	function edit()
	{
		$this->load->library('form_validation');
		$this->load->model('user_model');
		$this->load->model('table_model');

		try
		{
			$this->form_validation->set_rules('style', 'style', 'trim|required');
			$this->form_validation->set_rules('ver', 'ver', 'trim|required|integer');
			$this->form_validation->set_rules('username', '�û���', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('password', '����', 'trim|required|min_length[32]|max_length[32]');
			$this->form_validation->set_rules('creator', '������', 'trim|required|min_length[3]|max_length[15]');
			$this->form_validation->set_rules('nickname', '�ǳ�', 'trim|max_length[15]');
			$this->form_validation->set_rules('name', '�����', 'trim|required|min_length[1]|max_length[15]');
			$this->form_validation->set_rules('type', '�������', 'trim|required|integer');
			$this->form_validation->set_rules('timer', '��ּ�ʱ��', 'trim|required|integer');
			$this->form_validation->set_rules('level', '����', 'trim|required|integer');
			$this->form_validation->set_rules('lanip', 'IP', 'trim|max_length[15]');
			$this->form_validation->set_rules('port', '�˿�', 'trim|required|integer');

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

			if (!$this->user_model->checkUser($get['creator'], $get['password']))
			{
				$this->form_validation->set_error_string('����Ȩ�޸���֣�');
				throw new Exception();
			}

			$result = $this->table_model->edit($get);

			if (!$result)
			{
				$this->form_validation->set_error_string('����Ȩ�޸���֣�');
				throw new Exception();
			}

			$result = $this->table_model->detail($get['name']);

			if ($result)
			{
				$this->OutputStatus = STATUS_OK;
				$data = array(
					$result['TableName'],
					$result['Creator'],
					$result['CreatorName'],
					$result['Visitor'],
					$result['VisitorName'],
					$result['Type'],
					$result['GameTimer'],
					$result['Level'],
					$result['LastTime'],
					$result['IP'],
					$result['LANIP'],
					$result['Port'],
					getUserIp(),
				);
				$this->OutputArray = $data;
			}
			else
			{
				$this->OutputStatus = STATUS_NONE;
				$this->OutputArray = 'û����֣�';
			}
		}
		catch (Exception $e)
		{
			$this->setErrorOutput(validation_errors());
		}

		$this->loadView('output');
	}
}

/* End of file table.php */
/* Location: ./system/application/controllers/table.php */