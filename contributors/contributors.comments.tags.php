<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=comments.tags
[END_COT_EXT]
==================== */

require_once cot_incfile('contributors', 'plug');

global $contributors_user_columns, $contributors_list, $contributors_count, $contributors_count_diff;

$contributors = $db->query("SELECT $contributors_user_columns FROM $db_com AS c ".
	"LEFT JOIN $db_users AS u ON u.user_id=c.com_authorid ".
	"WHERE c.com_code=? GROUP BY c.com_authorid ORDER BY c.com_id ASC", $code)->fetchAll();

list(
	$contributors_list,
	$contributors,
	$contributors_count,
	$contributors_count_diff
	) = contributors_build('comments', $contributors, $cfg['plugin']['contributors']['limit']);

if($t->hasBlock('COMMENTS.CONTRIBUTOR_ROW'))
{
	foreach($contributors as $contributor)
	{
		$t->assign(contributors_generate_tags($contributor));
		$t->parse('COMMENTS.CONTRIBUTOR_ROW');
	}
}