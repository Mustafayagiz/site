<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

// The only template in the file.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	$aktifuye = $aktifziyaretci = $aktifgizliuye = 0;
	foreach ($context['members'] as $member)
	{
		if(!$member['is_guest'])
		{
			$aktifuye++;
		}
		else
		{
			$aktifziyaretci++;
		}
		if($member['is_hidden'])
		{
			$aktifgizliuye++;
		}
	}

	// Display the table header and linktree.
	echo '
	<div class="main_section" id="whos_online">
		<form action="', $scripturl, '?action=who" method="post" id="whoFilter" accept-charset="', $context['character_set'], '">
			<div class="topic_table" id="mlist">';
		if($aktifgizliuye > 0)
		{
			echo '<br/><font color="#383838" size="5">', $aktifuye ,' kayıtlı kullanıcı çevrimiçi ve ', $aktifgizliuye, ' gizli kullanıcı çevrimiçi <font size="2">(son ', $modSettings['lastActive'], ' dakika içinde aktif olan kullanıcılar)</font></font>';
		}
		else
		{
			echo '<br/><font color="#383838" size="5">', $aktifuye ,' kayıtlı kullanıcı çevrimiçi <font size="2">(son ', $modSettings['lastActive'], ' dakika içinde aktif olan kullanıcılar)</font></font>';
		}
		if($aktifziyaretci > 0)
		{
			echo '<br/><p style="margin-top: 2px; color:#383838; font-size: 0.9em;">', $aktifziyaretci , ' ziyaretçi çevrimiçi</p>';
		}
				echo '<div class="pagesection">
					<div class="pagelinks floatleft">', $context['page_index'], '</div>
				</div>
				<table class="table_grid" cellspacing="0">
					<thead>
						<tr class="catbg">';

		
			echo '<th style="font-size: 1.0em; padding: 0 1.0em;" scope="col" class="lefttext first_th" width="50%"><a href="', $scripturl, '?action=who;start=', $context['start'], ';show=', $context['show_by'], ';sort=user', $context['sort_direction'] != 'down' && $context['sort_by'] == 'user' ? '' : ';asc', '" rel="nofollow">', $txt['who_user'], ' ', $context['sort_by'] == 'user' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
							<th style="padding: 0 1.3em;" scope="col" class="lefttext" width="30%">', $txt['who_action'], '</th>
							<th style="padding: 0 1.3em;" scope="col" class="lefttext last_th" width="10%"><a href="', $scripturl, '?action=who;start=', $context['start'], ';show=', $context['show_by'], ';sort=time', $context['sort_direction'] == 'down' && $context['sort_by'] == 'time' ? ';asc' : '', '" rel="nofollow">', $txt['who_time'], ' ', $context['sort_by'] == 'time' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
						</tr>
					</thead>
					<tbody>';

	// For every member display their name, time and action (and more for admin).
	$alternate = 0;

	foreach ($context['members'] as $member)
	{
		// $alternate will either be true or false. If it's true, use "windowbg2" and otherwise use "windowbg".
		echo '
						<tr class="windowbg', $alternate ? '2' : '', '">
							<td>';

		// Guests don't have information like icq, msn, y!, and aim... and they can't be messaged.
		
		echo '<span class="member', $member['is_hidden'] ? ' hidden' : '', '">
									', $member['is_guest'] ? $member['name'] : '<a href="' . $member['href'] . '" title="' . $txt['profile_of'] . ' ' . $member['name'] . '"' . (empty($member['color']) ? '' : ' style="color: ' . $member['color'] . '"') . '>' . $member['name'] . '</a>', '
								</span>';
		if(!$member['is_guest'])
		{
			if($member['online']['is_online'])
				echo '<div style="background-color: #55BF00;" class="cevrimicidurum" style="margin-left: 0px;"></div>';
		}
		if (!empty($member['ip']))
			echo '
								(<a href="' . $scripturl . '?action=', ($member['is_guest'] ? 'trackip' : 'profile;area=tracking;sa=ip;u=' . $member['id']), ';searchip=' . $member['ip'] . '">' . $member['ip'] . '</a>)';

		echo '
							</td>
							<td><font color="#323A45">', $member['action'], '</font></td>
							<td nowrap="nowrap"><font color="#323A45">', $member['time'], '</font></td>
						</tr>';

		// Switch alternate to whatever it wasn't this time. (true -> false -> true -> false, etc.)
		$alternate = !$alternate;
	}

	// No members?
	if (empty($context['members']))
	{
		echo '
						<tr class="windowbg2">
							<td colspan="3" align="center">
							', $txt['who_no_online_' . ($context['show_by'] == 'guests' || $context['show_by'] == 'spiders' ? $context['show_by'] : 'members')], '
							</td>
						</tr>';
	}

	echo '
					</tbody>
				</table>
			</div>
			<div class="pagesection">
				<div class="pagelinks floatleft">', $context['page_index'], '</div>';
		echo '</div>
		</form>
	</div>';
}

function template_credits()
{
	global $context, $txt;

	// The most important part - the credits :P.
	echo '
	<div class="main_section" id="credits">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['credits'], '</h3>
		</div>';

	foreach ($context['credits'] as $section)
	{
		if (isset($section['pretext']))
		echo '
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<p>', $section['pretext'], '</p>
			</div>
			<span class="botslice"><span></span></span>
		</div>';

		if (isset($section['title']))
		echo '
		<div class="cat_bar">
			<h3 class="catbg">', $section['title'], '</h3>
		</div>';

		echo '
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>';

		foreach ($section['groups'] as $group)
		{
			if (isset($group['title']))
				echo '
					<dt>
						<strong>', $group['title'], '</strong>
					</dt>
					<dd>';

			// Try to make this read nicely.
			if (count($group['members']) <= 2)
				echo implode(' ' . $txt['credits_and'] . ' ', $group['members']);
			else
			{
				$last_peep = array_pop($group['members']);
				echo implode(', ', $group['members']), ' ', $txt['credits_and'], ' ', $last_peep;
			}

			echo '
					</dd>';
		}

		echo '
				</dl>';

		if (isset($section['posttext']))
			echo '
				<p class="posttext">', $section['posttext'], '</p>';

		echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>';
	}

	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $txt['credits_copyright'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>
					<dt><strong>', $txt['credits_forum'], '</strong></dt>', '
					<dd>', $context['copyrights']['smf'];

	echo '
					</dd>
				</dl>';

	if (!empty($context['copyrights']['mods']))
	{
		echo '
				<dl>
					<dt><strong>', $txt['credits_modifications'], '</strong></dt>
					<dd>', implode('</dd><dd>', $context['copyrights']['mods']), '</dd>
				</dl>';
	}

	echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>
	</div>';
}
?>