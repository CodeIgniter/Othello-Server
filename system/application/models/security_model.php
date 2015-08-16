<?php

// ��ȫ ģ��

class Security_model extends Model
{
    function __construct()
    {
        parent::Model();
    }

	// ����������ȫ�ַ���
	function makeCommonPassword()
	{
		$Security1 = '';
		for ($i = 0; $i < SECURITY_LENGTH; $i++)
		{
			$Security1 .= chr(mt_rand(48, 122));
		}

		$Security2 = '';
		for ($i = 0; $i < SECURITY_LENGTH; $i++)
		{
			$Security2 .= chr(mt_rand(48, 122));
		}

		// ���� 120 ����ǰ�İ�ȫ��
		$this->db->delete('security', array('DATE_ADD(MakeDate,INTERVAL 120 SECOND)<' => date('Y-m-d H:i:s')));

		$data = array(
		               'Security1' => $Security1,
		               'Security2' => $Security2,
		               'MakeDate' => date('Y-m-d H:i:s')
		            );
		$this->db->insert('security', $data);

		$id = $this->db->insert_id();

		$ret = array($id, AzDG_crypt($Security1), AzDG_crypt($Security2));

		return $ret;
	}

	// ��鹫����ȫ�ַ���
	function checkCommonPassword($id, $text)
	{
		$this->db->select('*')->from('security')->where('id', $id);

		$query = $this->db->get();

 		if ($query->num_rows() > 0)
 		{
 			$row = $query->row_array();
			return (AzDG_decrypt($text, $row['Security2']) == $row['Security1']);
 		}
 		else
 		{
 			return false;
 		}
	}
}