<?php
echo $Status . "\r";

if (is_array($Messages))
{
	$data = array();
	foreach ($Messages as $item)
	{
		if (is_array($item))
		{
			// ��������
			$data[] = implode('|', $item);
		}
		else
		{
			// ��������ֱ�����
			echo implode('|', $Messages);
			break;
		}
	}

	if (!empty($data))
	{
		echo implode("\r", $data);
	}
}
else
{
	echo $Messages;
}