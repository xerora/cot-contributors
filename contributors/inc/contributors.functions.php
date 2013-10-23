<?php defined('COT_CODE') or die('Wrong URL');

$GLOBALS['contributors_user_columns'] = 'u.user_id,u.user_name,u.user_country,u.user_gender';

if(cot_plugin_active('userimages'))
{
	require_once cot_incfile('userimages', 'plug');
	$GLOBALS['contributors_user_columns'] .= ',u.user_avatar';
}

function contributors_build($location, array $contributors, $limit = 0)
{
	global $R, $cfg;

	$contributors_count = count($contributors);
	$contributors = $contributors_count > $limit && $limit > 0 ? array_slice($contributors, 0, $limit) : $contributors;
	$contributors_count_difference = (int)($contributors_count - count($contributors));

	$contributors_resource_list = array();
	foreach($contributors as $contributor)
	{
		$resource_params = contributors_params($contributor);
		if(isset($R["contributors_{$location}_list"]))
		{
			$contributors_resource_list[] = cot_rc("contributors_{$location}_list", $resource_params);
		}
		else
		{
			$contributors_resource_list[] = $resource_params['link'];
		}

	}

	$contributors_resource_list = implode($cfg['plugin']['contributors']['list_sep'], $contributors_resource_list);

	return array(
		$contributors_resource_list, 
		$contributors,
		$contributors_count,
		$contributors_count_difference
	);
}

function contributors_params($row)
{
	global $L;
	$id = (int)$row['user_id'];
	$name = htmlspecialchars($row['user_name']);

	$params = array(
		'id' => $id,
		'name' => $name,
		'link' => cot_build_user($id, $name),
		'gender' => ($row['user_gender'] == '' || $row['user_gender'] == 'U') ? '' : $L['Gender_' . $row['user_gender']],
		'country' => cot_build_country($row['user_country']),
		'countryflag' => cot_build_flag($row['user_country']),
		'url' => cot_url('users', 'm=details&id='.$id.'&u='.$name)
	);

	if(cot_plugin_active('userimages'))
	{
		$params += array(
			'avatar' => cot_userimages_build($row['user_avatar']),
			'avatar_src' => $row['user_avatar']
		);
	}
	return $params;
}

function contributors_generate_tags($row, $prefix = 'CONTRIBUTOR_')
{
	$params = contributors_params($row);
	$tags = array();
	foreach($params as $name => $value)
	{
		$tags[$prefix.strtoupper($name)] = $value;
	}
	return $tags;
}