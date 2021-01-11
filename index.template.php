<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.14
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
   global $context, $settings, $options, $txt;

   /* Use images from default theme when using templates from the default theme?
      if this is 'always', images from the default theme will be used.
      if this is 'defaults', images from the default theme will only be used with default templates.
      if this is 'never' or isn't set at all, images from the default theme will not be used. */
   $settings['use_default_images'] = 'never';

   /* What document type definition is being used? (for font size and other issues.)
      'xhtml' for an XHTML 1.0 document type definition.
      'html' for an HTML 4.01 document type definition. */
   $settings['doctype'] = 'xhtml';

   /* The version this template/theme is for.
      This should probably be the version of SMF it was created for. */
   $settings['theme_version'] = '2.0';

   /* Set a setting that tells the theme that it can render the tabs. */
   $settings['use_tabs'] = true;

   /* Use plain buttons - as opposed to text buttons? */
   $settings['use_buttons'] = true;

   /* Show sticky and lock status separate from topic icons? */
   $settings['separate_sticky_lock'] = true;

   /* Does this theme use the strict doctype? */
   $settings['strict_doctype'] = false;

   /* Does this theme use post previews on the message index? */
   $settings['message_index_preview'] = false;

   /* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
   $settings['require_theme_strings'] = false;
}

// The main sub template above the content.
function template_html_above()
{
   global $context, $settings, $options, $scripturl, $txt, $modSettings;
   $enable_favicon = true; // Use "true" to enable, "false" to disable.

   // Show right to left and the character set for ease of translating.
   echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

   echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">'; // fontawesome
   echo '<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">'; // ubuntu font

   //echo '<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/reset.css" />'; // table vs. fix

   echo '<script>
            var bildirim_durum = 0, profil_durum = 0;
            function bildirim_click()
            {
               if(bildirim_durum == 0)
               {
                  document.getElementById("bildirim_ekran").removeAttribute("style");
                  document.getElementById("profil_ekran").style.display = "none";
                  profil_durum = 0;
                  bildirim_durum = 1;
               }
               else if(bildirim_durum == 1)
               {
                  document.getElementById("bildirim_ekran").style.display = "none";
                  bildirim_durum = 0;
               }
            }
            function profil_click()
            {
               if(profil_durum == 0)
               {
                  document.getElementById("profil_ekran").removeAttribute("style");
                  document.getElementById("bildirim_ekran").style.display = "none";
                  bildirim_durum = 0;
                  profil_durum = 1;
               }
               else if(profil_durum == 1)
               {
                  document.getElementById("profil_ekran").style.display = "none";
                  profil_durum = 0;
               }
            }
        </script>';

   // The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
   echo '
   <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

   // Some browsers need an extra stylesheet due to bugs/compatibility issues.
   foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
      if ($context['browser']['is_' . $cssfix])
         echo '
   <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/', $cssfix, '.css" />';

   // RTL languages require an additional stylesheet.
   if ($context['right_to_left'])
      echo '
   <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

   // Here comes the JavaScript bits!
   echo '
   <script type="text/javascript" src="', $settings['theme_url'], '/scripts/script.js?fin20"></script>
   <script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
   <script type="text/javascript"><!-- // --><![CDATA[
      var smf_theme_url = "', $settings['theme_url'], '";
      var smf_theme_url = "', $settings['theme_url'], '";
      var smf_images_url = "', $settings['images_url'], '";
      var smf_scripturl = "', $scripturl, '";
      var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
      var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
      var fPmPopup = function ()
      {
         if (confirm("' . $txt['show_personal_messages'] . '"))
            window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
      }
      addLoadEvent(fPmPopup);' : '', '
      var ajax_notification_text = "', $txt['ajax_in_progress'], '";
      var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
   // ]]></script>';

   echo '
   <meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
   <meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
   <meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
   <title>', $context['page_title'], '</title>';

   // Don't alter this code. Alter the statement under the global declarations.
   if ( $enable_favicon  )
         {
      echo '<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />';
                echo '<link rel="icon" href="/favicon.ico" type="image/x-icon" />';
         }

   // Please don't index these Mr Robot.
   if (!empty($context['robot_no_index']))
      echo '
   <meta name="robots" content="noindex" />';

   // Present a canonical url for search engines to prevent duplicate content in their indices.
   if (!empty($context['canonical_url']))
      echo '
   <link rel="canonical" href="', $context['canonical_url'], '" />';

   // Show all the relative links, such as help, search, contents, and the like.
   echo '
   <link rel="help" href="', $scripturl, '?action=help" />
   <link rel="search" href="', $scripturl, '?action=search" />
   <link rel="contents" href="', $scripturl, '" />';

   // If RSS feeds are enabled, advertise the presence of one.
   if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
      echo '
   <link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

   // If we're viewing a topic, these should be the previous and next topics, respectively.
   if (!empty($context['current_topic']))
      echo '
   <link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
   <link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

   // If we're in a board, or a topic for that matter, the index will be the board's index.
   if (!empty($context['current_board']))
      echo '
   <link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

   echo '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
   <link rel="manifest" href="/site.webmanifest">
   <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#bf3232">
   <meta name="msapplication-TileColor" content="#da532c">
   <meta name="theme-color" content="#ffffff">';
   // Output any remaining HTML headers. (from mods, maybe?)
   echo $context['html_headers'];

   echo '</head><body>';
}

function template_body_above()
{
   global $context, $settings, $user_info, $user_settings, $options, $scripturl, $txt, $modSettings;
   echo !empty($settings['forum_width']) ? '<div id="wrapper" style="width: 100%">' : '', '';

   echo '<div style="background-color: #23272A;"><div id="baslik">';
   if(!$context['user']['is_logged'])
   {
      echo '<div id="butonlar"> <div class="buton" id="girisbuton"><a style="color: white;" href="', $scripturl, '?action=login"><i class="fas fa-power-off"></i>Giriş Yap</a></div> <div class="buton" id="kayitbuton"><a style="color: white;" href="', $scripturl, '?action=register"><i class="far fa-edit"></i>Kayıt Ol</a></div> </div>';
   }
   else
   {
      echo '<div id="anabuton"><div id="altbutonbir"><div id="arabuton"><form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
               <input type="text" name="search" title="Anahtar kelimeleri arayın" placeholder="', $txt['search'], '" required="required" class="arabutonbaslik" /><button type="submit" name="submit" title="', $txt['search'], '" class="arabutonbas"><i style="color: white; margin-left: 5px;" class="fas fa-search"></i></button><button disabled title="', $txt['search_advanced'], '" class="gelismisara"><a href="', $scripturl, '?action=search"><i style="color: white; margin-left: 5px;" class="fas fa-cog"></i></a></button>
                  <input type="hidden" name="advanced"/>';
      if(allowedTo('admin_forum'))
      {
         echo '&nbsp;&nbsp;<a style="color: white; font-size: 14px;" href="', $scripturl, '?action=admin"><i class="fas fa-cogs"></i>Yönetim Paneli</a>';
      }
      //if(allowedTo('moderate_forum'))
      echo '&nbsp;&nbsp;<a style="color: white; font-size: 14px;" href="', $scripturl, '?action=moderate"><i class="fab fa-font-awesome-flag"></i></i>Moderasyon</a>';

      // Search within current topic?
      if (!empty($context['current_topic']))
         echo '<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
      // If we're on a certain board, limit it to this board ;).
      elseif (!empty($context['current_board']))
         echo '<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

      echo '</form></div></div>';
      echo '<div id="altbutoniki"><div id="butonlar">';

      echo '<div class="buton" id="baslikprofil"><div class="acilirmenu">
         <button id="BTNProfil" onclick="profil_click()" class="acilirbuton">';

      if(!empty($context['user']['avatar']['image']))
      {
         echo '<div id="menuavatar">', $context['user']['avatar']['image'],'</div>', $context['user']['name'], ' <i class="fas fa-caret-down"></i></button>';
      }
      else
      {
         echo '', $context['user']['name'], ' <i class="fas fa-caret-down"></i></button>';
      }

      echo '<div id="profil_ekran" style="display:none;" class="acilirbutonalt">
            <i id="menuucgen" class="fas fa-caret-up fa-2x"></i>
            <a href="', $scripturl, '?action=profile"><i class="fas fa-user"></i>Profil</a><hr/>
            <a href="', $scripturl, '?action=profile;area=forumprofile"><i class="fas fa-sliders-h"></i>Kullanıcı Kontrol Paneli</a><hr/>
            <a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '"><i class="fas fa-power-off"></i>Çıkış Yap</a>
         </div>
      </div></div>';

      echo '<div class="buton" id="baslikbildirim"><div class="acilirmenu">
         <button id="BTNBildirim" onclick="bildirim_click()" class="acilirbuton">
         <i class="fas fa-bell"></i>Bildirimler', ($context['user']['unread_messages'] > 0 ? '<strong class="bildirim"> '.($context['user']['unread_messages']). '</strong> ' : '') ,' <i class="fas fa-caret-down"></i> 
         </button>
         </div>';

         echo '<div id="bildirim_ekran" style="display:none;" class="acilirbutonalt">
            <i id="menuucgenbildirim" class="fas fa-caret-up fa-2x"></i>
            <a href="', $scripturl, '?action=pm"><i class="fas fa-envelope"></i>Özel Mesajlar ', ($context['user']['unread_messages'] > 0 ? '<strong style="color: white;" class="bildirim"> '.$context['user']['unread_messages']. '</strong> ' : '') ,' </a><hr/>
            <a href=" "><i class="fas fa-bullhorn"></i></i>Sunucu Bildirimleri</a>
         </div>';

         echo '</div>';



      echo '</div> </div> </div>';


   }
   echo '</div></div>';
   echo '<div id="altbaslik" data-type="background" data-speed="3"><div class="sitebanner"><a href="', $scripturl, '"><div style="max-width: 1280px; margin: 0 auto;"><div id="sitelogo"></div></div></a></div></div>';

   // The main content should go here.
   echo '<div style="max-width: 1280px; margin: 0 auto;">';

   // Show a random news item? (or you could pick one from news_lines...)
   if(!empty($settings['enable_news']) && !empty($context['random_news_line']))
   {
      echo '<div class="duyurular"><p>', $context['random_news_line'], '</p></div>';
   }

   echo '<div id="bulundugukategori">', theme_linktree(),'</div>';
   echo '<div id="content_section"><div class="frame">
      <div id="main_content_section">';
   // Custom banners and shoutboxes should be placed here, before the linktree.

   // Show the navigation tree.
}

function template_body_below()
{
   global $context, $settings, $options, $scripturl, $txt, $modSettings;
   echo '</div>
<div id="bulundugukategorialt">', theme_linktree(),'</div> <div id="forumzaman">', strftime('%d %B %Y, %A - %T'),'</div>
   </div></div>'; 
   // Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!

   // Show the load time?
   if ($context['show_load_time'])
      echo '
      <p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

   echo '</div>';
   echo '', !empty($settings['forum_width']) ? '</div></div>' : '</div>';
   echo '<div id="altbilgiler">';
   echo '<div style="background-color: #b5b5b5;"> <div style="max-width: 1280px; margin: 0 auto;"> <div id="altbilgi"> <div id="altbilgii"><font size="5" color="#23272a">Adonis Roleplay</font><br/><br/>Bu sunucu, bir SAMP sunucusu olmakla beraber forumda yapılan bütün işlemler kullanıcı sorumluluğundadır.</div> <div id="telif">', theme_copyright(), '<br/><span style="font-size: 0.86em;">Adonis Roleplay © 2018 - ', date("Y"),'</span></div> <div id="sosyalmedya"><a target="_blank" href="https://www.facebook.com/adonisroleplayy"><i class="fab fa-facebook-square"></i></a>&nbsp;&nbsp;<a target="_blank" href="https://www.youtube.com/channel/UC0MA87qlwKQlttmm2L2QIuA?view_as=subscriber"><i class="fab fa-youtube"></i></a></div> </div> </div> </div>';
   echo '<div style="background-color: #23272a;"> <div style="max-width: 1280px; margin: 0 auto;"> <div id="altbilgiii">';
   if(!$context['user']['is_guest'])
   {
      echo '<a href="', $scripturl, '?action=markasread;sa=all;', $context['session_var'] , '=' , $context['session_id'], '"><i class="fas fa-trash-alt"></i>Tümünü okunmuş say</a><a style="margin-left: 10px;" href="' . $scripturl . '?action=mlist"><i class="fas fa-users"></i>Üyeler</a><a style="margin-left: 10px;" href="' . $scripturl . '?action=yonetim"><i class="fas fa-shield-alt"></i>Yönetim</a><a style="margin-left: 10px;" href="mailto:adonisroleplay01@gmail.com" target="_top"><i class="fas fa-envelope"></i>Bizimle iletişime geçin </div> </div></div>';
   }
   else
   {
      echo '<a href="mailto:adonisroleplay01@gmail.com" target="_top"><i class="fas fa-envelope"></i>Bizimle iletişime geçin </div> </div></div>';
   }
}

function template_html_below()
{
   global $context, $settings, $options, $scripturl, $txt, $modSettings;
   echo '</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
   global $context, $settings, $options, $shown_linktree, $scripturl;

   // If linktree is empty, just return - also allow an override.
   if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
      return;

   echo '
   <div class="navigate_section">
      <ul>';

   // Each tree item has a URL and name. Some may have extra_before and extra_after.
   foreach ($context['linktree'] as $link_num => $tree)
   {
      echo '<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

      // Show something before the link?
      if (isset($tree['extra_before']))
         echo $tree['extra_before'];

      // Show the link, including a URL if it should have one.
      if($tree['url'] == $scripturl)
      {
         echo $settings['linktree_link'] && isset($tree['url']) ? '
            <a href="' . $tree['url'] . '"><i class="fas fa-home fa-sm"></i><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';
      }
      else
      {
         echo $settings['linktree_link'] && isset($tree['url']) ? '
            <a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';
      }
      
      

      // Show something after the link...?
      if (isset($tree['extra_after']))
         echo $tree['extra_after'];

      // Don't show a separator for the last one.
      if ($link_num != count($context['linktree']) - 1)
         echo '<i style="margin-left: 6px;" class="fas fa-angle-left fa-xs"></i>';
      echo '</li>';
   }
   echo '</ul></div>';
   $shown_linktree = true;
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
   global $settings, $context, $txt, $scripturl;

   if (!is_array($strip_options))
      $strip_options = array();

   // List the buttons in reverse order for RTL languages.
   if ($context['right_to_left'])
      $button_strip = array_reverse($button_strip, true);

   // Create the buttons...
   $buttons = array();
   foreach ($button_strip as $key => $value)
   {
      if (!isset($value['test']) || !empty($context[$value['test']]))
         $buttons[] = '
            <li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
   }

   // No buttons? No button strip either.
   if (empty($buttons))
      return;

   // Make the last one, as easy as possible.
   $buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

   echo '
      <div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
         <ul>',
            implode('', $buttons), '
         </ul>
      </div>';
}

?>