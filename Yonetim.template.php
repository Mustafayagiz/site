<?php
/*
	jensen - 23.12.2018
*/

function template_main()
{
	global $txt, $scripturl, $modSettings, $context, $settings;
	if($context['user']['is_guest'])
	{
	
		echo "<script type='text/javascript'>alert('Giriş yapmadan yönetim ekibini görüntüleyemezsiniz.');
		window.location='", $scripturl, "?action=login';
		</script>";
	}

	echo '<br/><div class="tborder" id="yonetimliste">
			<table cellspacing="0" class="table_grid">
			<thead>
			<tr class="catbg">
				<th width="15%" style="padding-left: 15px; font-size: 0.85em;" scope="col" class="first_th">Yönetici</th>
				<th width="10%" style="padding-left: 16px; font-size: 0.85em;" scope="col" class="stats windowbg">Pozisyon</th>
				<th width="5%" style="padding-left: 16px; font-size: 0.85em;" scope="col" class="last_th">Rank</th>
			</tr></thead>
		<tbody id="liste" class="content"></div>';

	echo '<tr class="windowbg2">';
		echo '<td class="stats windowbg"><a href="', $scripturl, '?action=profile;u=1" style="color: #1a7488;">jensen</a></td>';
		echo '<td class="stats windowbg">asd</td>';
		echo '<td class="lastpost windowbg2">Rank Görseli</div></td>';
	echo '</tr>';

	echo '</tbody></table></div>';

	echo '<br/><div class="tborder" id="helperliste">
			<table cellspacing="0" class="table_grid">
			<thead>
			<tr class="catbg">
				<th width="15%" style="padding-left: 15px; font-size: 0.85em;" scope="col"class="first_th">Helper</th>
				<th width="10%" style="padding-left: 16px; font-size: 0.85em;" scope="col" class="stats windowbg">Pozisyon</th>
				<th width="5%" style="padding-left: 16px; font-size: 0.85em;" scope="col" class="last_th">Rank</th>
			</tr></thead>
		<tbody id="liste" class="content"></div>';

	echo '<tr class="windowbg2">';
		echo '<td class="stats windowbg"><a href="', $scripturl, '?action=profile;u=1" style="color: #884d13;">jensen</a></td>';
		echo '<td class="stats windowbg">Helper</td>';
		echo '<td class="lastpost windowbg2">Rank Görseli</td>';
	echo '</tr>';

	echo '</tbody></table>';
}

?>