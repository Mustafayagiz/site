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

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Show some statistics if stat info is off.
	if (!$settings['show_stats_index'])
		echo '
	<div id="index_common_stats">
		', $txt['members'], ': ', $context['common_stats']['total_members'], ' &nbsp;&#8226;&nbsp; ', $txt['posts_made'], ': ', $context['common_stats']['total_posts'], ' &nbsp;&#8226;&nbsp; ', $txt['topics'], ': ', $context['common_stats']['total_topics'], '
		', ($settings['show_latest_member'] ? ' ' . $txt['welcome_member'] . ' <strong>' . $context['common_stats']['latest_member']['link'] . '</strong>' . $txt['newest_member'] : '') , '
	</div>';

	// Show the news fader?  (assuming there are things to show...)
	if ($settings['show_newsfader'] && !empty($context['fader_news_lines']))
	{
		echo '
	<div id="newsfader">
		<div class="cat_bar">
			<h3 class="catbg">
				<img id="newsupshrink" src="', $settings['images_url'], '/collapse.png" alt="*" title="', $txt['upshrink_description'], '" align="bottom" style="display: none;" />
				', $txt['news'], '
			</h3>
		</div>
		<ul class="reset" id="smfFadeScroller"', empty($options['collapse_news_fader']) ? '' : ' style="display: none;"', '>';

			foreach ($context['news_lines'] as $news)
				echo '
			<li>', $news, '</li>';

	echo '
		</ul>
	</div>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/fader.js"></script>
	<script type="text/javascript"><!-- // --><![CDATA[

		// Create a news fader object.
		var oNewsFader = new smf_NewsFader({
			sSelf: \'oNewsFader\',
			sFaderControlId: \'smfFadeScroller\',
			sItemTemplate: ', JavaScriptEscape('<strong>%1$s</strong>'), ',
			iFadeDelay: ', empty($settings['newsfader_time']) ? 5000 : $settings['newsfader_time'], '
		});

		// Create the news fader toggle.
		var smfNewsFadeToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_news_fader']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'smfFadeScroller\'
			],
			aSwapImages: [
				{
					sId: \'newsupshrink\',
					srcExpanded: smf_images_url + \'/collapse.png\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/expand.png\',
					altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
				}
			],
			oThemeOptions: {
				bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
				sOptionName: \'collapse_news_fader\',
				sSessionVar: ', JavaScriptEscape($context['session_var']), ',
				sSessionId: ', JavaScriptEscape($context['session_id']), '
			},
			oCookieOptions: {
				bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
				sCookieName: \'newsupshrink\'
			}
		});
	// ]]></script>';
	}

	echo '
	<div style="margin-top: 20px;" id="boardindex_table">';

	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach($context['categories'] as $category)
	{
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if(empty($category['boards']) && !$category['is_collapsed'])
			continue;

		echo '<table class="table_list">
			<tbody class="header" id="category_', $category['id'], '">
				<tr class="catbg">';

				if($category['is_collapsed'])
				{
					echo '<th style="padding-left: 10px; text-align: left;" width="30%" class="first_th"><div>', $category['link'], '</div></th>';
					echo '<th class="last_th">';
					if($category['can_collapse'])
						echo '<a class="collapse" href="', $category['collapse_href'], '">', $category['collapse_image'],'</a>';
					echo '</th>';
				}
				else
				{
					echo '<th width="1%" class="first_th" colspan="1">&nbsp;</th><th style="padding-left: 0px;" width="60%" class="lefttext" colspan="1"><div class="kategoribaslik">', $category['link'], '</div></th>';
					echo '<th width="5%" style="font-size: 13px; text-align: center;" class="lefttext" colspan="1">Konular</th>
					<th width="5%" style="font-size: 13px; text-align: center;" class="lefttext" colspan="1">İletiler</th>
					<th width="20%" style="font-size: 13px; padding-left: 14px; text-align: left;" class="lefttext" colspan="1">Son İleti</th>';

					echo '<th class="last_th">';
					if($category['can_collapse'])
						echo '<a class="collapse" href="', $category['collapse_href'], '">', $category['collapse_image'],'</a>';
					echo '</th>';
				}

		echo '</tr></tbody>';
		// Assuming the category hasn't been collapsed...
		if(!$category['is_collapsed'])
		{

		echo '
			<tbody class="content" id="category_', $category['id'], '_boards">';
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			foreach ($category['boards'] as $board)
			{
				echo '
				<tr id="board_', $board['id'], '" class="windowbg2">
					<td class="icon windowbg"', !empty($board['children']) ? ' rowspan="2"' : '', '>
						<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '">';

				// If the board or children is new, show an indicator.
				if ($board['new'] || $board['children_new'])
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'on', $board['new'] ? '' : '2', '.png" alt="', $txt['new_posts'], '" title="', $txt['new_posts'], '" />';
				// Is it a redirection board?
				elseif ($board['is_redirect'])
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'redirect.png" alt="*" title="*" />';
				// No new posts at all! The agony!!
				else
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'off.png" alt="', $txt['old_posts'], '" title="', $txt['old_posts'], '" />';

				echo '
						</a>
					</td>
					<td class="info">
						<a class="subject" href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a>';

				// Has it outstanding posts for approval?
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link">(!)</a>';

				echo '

						<p style="font-size: 0.8em;">', $board['description'] , '</p>';

				// Show the "Moderators: ". Each has name, href, link, and id. (but we're gonna use link_moderators.)
				if (!empty($board['moderators']))
					echo '
						<p style="font-size: 0.8em;" class="moderators">', count($board['moderators']) == 1 ? $txt['moderator'] : $txt['moderators'], ': ', implode(', ', $board['link_moderators']), '</p>';

				// Show some basic information about the number of posts, etc.
					echo '</td>
					<td class="stats windowbg">';
					if($board['is_redirect'])
						echo '<p>Yönlendirmeler</p>';
					else
						echo '<p>', comma_format($board['topics']),'</p>';
					echo '</td>
					<td class="stats windowbg">';
					
					echo '<p>', comma_format($board['posts']),'</p>';
					echo '</td>
					<td style="text-align: left;" class="stats windowbg">';

				/* The board's and children's 'last_post's have:
				time, timestamp (a number that represents the time.), id (of the post), topic (topic id.),
				link, href, subject, start (where they should go for the first unread post.),
				and member. (which has id, name, link, href, username in it.) */
				if(!empty($board['last_post']['id']))
					echo '
						<p>
						', $txt['in'], ' ', $board['last_post']['link'], '<br />
						', $txt['by'], ' ', $board['last_post']['member']['link'] , ' <i style="font-size: 0.7em; font-weight: bold;" class="fas fa-angle-right"></i><br />
						', $txt['on'], ' ', $board['last_post']['time'],'
						</p>';

				if($board['is_redirect'])
					echo '<p>&nbsp;</p>';

				echo '</td><td class="lastpost">&nbsp;</td></tr>';
				// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
				if (!empty($board['children']))
				{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
							id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
					foreach ($board['children'] as $child)
					{
						if (!$child['is_redirect'])
							$child['link'] = '<i style="color: '. ($child['new'] ? '#762828' : '#b5b5b5') . '" class="fas fa-file fa-sm"></i><a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a> <a href="' . $scripturl . '?action=unread;board=' . $child['id'] . '" title="' . $txt['new_posts'] . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')"><img src="' . $settings['lang_images_url'] . '/new.gif" class="new_posts" alt="" />' : '') . '</a>';
						else
							$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

						// Has it posts awaiting approval?
						if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
							$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

						$children[] = $child['new'] ? '<strong> ' . $child['link'] . '</strong>' : $child['link'];
					}
					echo '
					<tr id="board_', $board['id'], '_children">
						<td colspan="5" class="children windowbg">
							<strong>', $txt['parent_boards'], '</strong>: ', implode(', ', $children), '
						</td>
					</tr>';
				}
			}
		}
		echo '</tbody></table>';
	}
	echo '</div></div>';
	template_info_center();
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;
	echo '<div id="cevrimiciliste"><a style="color: #2c2f33;" href="' . $scripturl . '?action=who">Çevrimiçi Üyeler</a></div>';
	if(!empty($context['num_users_hidden']))
	{
		echo '<div id="cevrimiciuyeler"> Toplamda <strong>', $context['num_guests'] + $context['num_users_online'], '</strong> kullanıcı çevrimiçi: ', $context['num_users_online'], ' kayıtlı, ', $context['num_guests'], ' ziyaretçi ve ', $context['num_users_hidden'], ' gizli (son ', $modSettings['lastActive'], ' dakika içinde aktif olan kullanıcılar)<br/>En çok çevrimiçi <strong>', $modSettings['mostOnline'], '</strong>, ', timeformat($modSettings['mostDate']), '<br/><br/>Kayıtlı kullanıcılar: ', implode(', ', $context['list_users_online']), '';
		if(!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '<br /><i>' . implode(',&nbsp;&nbsp;', $context['membergroups']) . '</i></div>';
	}
	else
	{
		echo '<div id="cevrimiciuyeler"> Toplamda <strong>', $context['num_guests'] + $context['num_users_online'], '</strong> kullanıcı çevrimiçi: ', $context['num_users_online'], ' kayıtlı ve ', $context['num_guests'], ' ziyaretçi (son ', $modSettings['lastActive'], ' dakika içinde aktif olan kullanıcılar)<br/>En çok çevrimiçi <strong>', $modSettings['mostOnline'], '</strong>, ', timeformat($modSettings['mostDate']), '<br/><br/>Kayıtlı kullanıcılar: ', implode(', ', $context['list_users_online']), '';
		if(!empty($settings['show_group_key']) && !empty($context['membergroups']))
			echo '<br /><i>' . implode(',&nbsp;&nbsp;', $context['membergroups']) . '</i></div>';
	}

	echo '</div>
	<div id="istatistikler"><a href="' . $scripturl . '?action=stats">İstatistikler</a></div>';
	echo '<div id="forumistatistik">';
		echo '<div class="soniletiler">';
			if(!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
			{
				foreach ($context['latest_posts'] as $post)
				{
					echo '<strong>', $post['link'], '</strong> ', $txt['by'], ' ', $post['poster']['link'], ' (', $post['board']['link'], ')
								', $post['time'], '<br/>';
				}
			}
			if(!$context['user']['is_guest'])
				echo '<a href="', $scripturl, '?action=recent">', $txt['recent_view'], '</a><br/>';

			echo '<br/>Toplam mesaj <strong>', $context['common_stats']['total_posts'], '</strong> <span style="font-size: 0.8em;">•</span> Toplam konu <strong>', $context['common_stats']['total_topics'], '</strong> <span style="font-size: 0.8em;">•</span> Toplam üye <strong>', $context['common_stats']['total_members'], '</strong> <span style="font-size: 0.8em;">•</span> En yeni üye <strong>', $context['common_stats']['latest_member']['link'], '</strong><br/>
			';
	echo '</div>';

	// Info center collapse object.
	echo '
	<script type="text/javascript"><!-- // --><![CDATA[
		var oInfoCenterToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_header_ic']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'upshrinkHeaderIC\'
			],
			aSwapImages: [
				{
					sId: \'upshrink_ic\',
					srcExpanded: smf_images_url + \'/collapse.png\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/expand.png\',
					altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
				}
			],
			oThemeOptions: {
				bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
				sOptionName: \'collapse_header_ic\',
				sSessionVar: ', JavaScriptEscape($context['session_var']), ',
				sSessionId: ', JavaScriptEscape($context['session_id']), '
			},
			oCookieOptions: {
				bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
				sCookieName: \'upshrinkIC\'
			}
		});
	// ]]></script>';
}
?>