<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.tags
[END_COT_EXT]
==================== */

require_once cot_incfile('contributors', 'plug');

$contributors = $db->query("SELECT u.user_name,u.user_id FROM $db_forum_posts AS fp ".
	"LEFT JOIN $db_users AS u ON u.user_id=fp.fp_posterid ".
	"WHERE fp.fp_topicid=? GROUP BY fp.fp_posterid ORDER BY fp.fp_id ASC", $q)->fetchAll();

list(
	$contributors_list,
	$contributors,
	$contributors_count,
	$contributors_count_diff
	) = contributors_build('forums', $contributors, $cfg['plugin']['contributors']['limit']);

if($t->hasBlock('MAIN.CONTRIBUTOR_ROW'))
{
	foreach($contributors as $contributor)
	{
		$t->assign(contributors_generate_tags($contributor));
		$t->parse('MAIN.CONTRIBUTOR_ROW');
	}
}