<?php
class qa_html_theme extends qa_html_theme_base
{

	function html()
	{
		$this->output(
			'<html>'
		);

		$this->head();
		$this->body();

		$this->output(
			'</html>'
		);
	}
	
	function head()
	{
		$this->output(
			'<head>',
			'<script src="'.$this->rooturl.'theme.js"></script>',
			'<meta http-equiv="content-type" content="'.$this->content['content_type'].'"/>'
		);

		$this->head_title();
		$this->head_metas();			
		$this->head_css();
		$this->head_custom_css();
		$this->output('<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">');
		$this->head_links();
		if ( $this->template == 'question' ) {
			if (strlen(@$this->content['description'])) {
				$pagetitle=strlen($this->request) ? strip_tags(@$this->content['title']) : '';
				$headtitle=(strlen($pagetitle) ? ($pagetitle.'') : '');			

				$this->output('<meta property="og:url" content="'.$this->content['canonical'].'" />');		
				$this->output('<meta property="og:type" content="article" />');
				$this->output('<meta property="og:title" content="'.$headtitle.'" />');
				$this->output('<meta property="og:description" content="Click To Watch" />');
				$this->output('<meta property="og:image" content="'.$this->content['description'].'"/>');
				$this->output('<meta name="twitter:card" content="summary_large_image">');
				$this->output('<meta name="twitter:title" content="'.$headtitle.'">');
				$this->output('<meta name="twitter:description" content="'.$headtitle.'">');
				$this->output('<meta name="twitter:image" content="'.$this->content['description'].'">');
				$this->output('<meta itemprop="description" content="click to watch">');
				$this->output('<meta itemprop="image" content="'.$this->content['description'].'">');
				$this->output('<link rel="image_src" type="image/jpeg" href="'.$this->content['description'].'" />');
			}
		}
		$this->head_lines();
		$this->head_script();

$this->output('<link rel="manifest" href="/pwa/manifest.json">');

$this->output('<link rel="shortcut icon" href="/favicon.png">');


		$this->output('<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">');

		$this->output('<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&amp;display=swap" rel="stylesheet">');
		$this->output('<script type="text/javascript" src="'.$this->rooturl.'main.js"></script>');	
		$this->output('<script src="/pwa/sw.js"></script>');
		$this->head_custom();	




$this->output('<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>');

$this->output('<a href=\'#\' class=\'scrollToTop\'><i class=\'fas fa-chevron-up\'></i></a>');

 $this->output('<script>
$(document).ready(function(){

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $(\'.scrollToTop\').fadeIn();
        } else {
            $(\'.scrollToTop\').fadeOut();
        }
    });

    //Click event to scroll to top
    $(\'.scrollToTop\').click(function(){
        $(\'html, body\').animate({scrollTop : 0},800);
        return false;
    });

});
</script>');

$this->output('<script>
 // Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $(\'header\').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $(\'header\').removeClass(\'nav-down\').addClass(\'nav-up\');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $(\'header\').removeClass(\'nav-up\').addClass(\'nav-down\');
        }
    }
    
    lastScrollTop = st;
}
</script>');





		$this->output('</head>');
	}

	function body()
	{
		$actual_link = (isset($_SERVER['HTTPS']) ? "https://" : "https://") . ''.@$_SERVER['HTTP_HOST'].strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/').'';
		$this->output('<BODY');			
		$this->body_tags();
		$this->output('>');

		$this->body_script();
		$this->body_header();
		$this->body_content();
		$this->body_footer();            	
		$this->output('<script src="'.$this->rooturl.'bootstrap.min.js"></script>');
		$this->output('<script src="'.$this->rooturl.'quickstart.js"></script>'); 
		if ( $this->template == 'ask' || $this->template == 'video' || $this->template == 'news' || $this->template == 'question' ) {
			$this->output('<script src="'.$this->rooturl.'jquery.form.min.js"></script>'); 
			$this->output('<script src="'.$this->rooturl.'imageupload.js"></script>'); 	
			$this->output('<script src="'.$this->rooturl.'jquery.uploadfile.js"></script>'); 
		}
		$this->output('<script src="'.$this->rooturl.'videoplayer/video.js"></script>');
		$this->output('<script>
			videojs.options.flash.swf = "'.$this->rooturl.'videoplayer/video-js.swf";
			</script>');			
		if ( $this->template == 'news' || ($this->template=='question' && strpos(qa_get_state(),'edit')!==false) ) {
			$this->output('<script src="'.$this->rooturl.'tinymce/tinymce.min.js"></script>'); 
			 




	

			$this->output('<script type="text/javascript">tinymce.init({
				selector: \'#extra, #q_extra\',
				images_upload_url: \''.$actual_link.'/king-include/newsupload.php\',
				images_upload_base_path: \''.$actual_link.'/king-include/\',
				images_upload_credentials: false,
				theme: \'inlite\',
				plugins: \'image table link paste textpattern autolink\',
				insert_toolbar: \'quickimage quicktable\',
				selection_toolbar: \'bold italic | quicklink h2 h3 blockquote\',
				inline: true,
				paste_data_images: false,
				relative_urls: false,
				remove_script_host: false
			});</script>');	
		}	
		$this->output('</BODY>');
	}	


	function body_content()
	{
		$q_view=@$this->content['q_view'];
		$this->body_prefix();
		$this->notices();			
		$this->header();	

		$this->output('<DIV CLASS="king-nav-sub">');
		$this->nav('sub');
		if (!empty($q_view)) {
			$this->viewtop();
		}
		$this->submitnav();
		$this->output('</DIV>');			
		$this->output('<DIV id="king-body-wrapper">');
		$this->main();				
		$this->page_links();
		$this->footer();
		$this->output('</DIV>');
		$this->widgets('full', 'low');
		$this->widgets('full', 'bottom');
		$this->body_suffix();

	}

	function body_header()
	{
		$this->output('<DIV class="ads">');
		if (isset($this->content['body_header']))
			$this->output_raw($this->content['body_header']);

		$this->output('</DIV>');
	}


	function head_css()
	{
		$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->rooturl.$this->css_name().'"/>');		

		if (isset($this->content['css_src']))
			foreach ($this->content['css_src'] as $css_src)
				$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$css_src.'"/>');
			$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.$this->rooturl.'videoplayer/video-js.css"/>');

			if (!empty($this->content['notices']))
				$this->output(
					'<STYLE type="text/css" ><!--',
					'.king-body-js-on .king-notice {display:none;}',
					'//--></STYLE>'
				);
		}
		
		function head_custom_css()
		{
			if (qa_opt('show_home_description')) {
				$this->output('<STYLE type="text/css"><!--');	
				$this->output(''.qa_opt('home_description').'');
				$this->output('//--></STYLE>');
			}	
		}		

		
		function main()
		{			
			$content=$this->content;
			$q_view=@$this->content['q_view'];
			
			$this->output('<DIV CLASS="king-main'.(@$this->content['hidden'] ? ' king-main-hidden' : '').'">');
			$this->output('<input type="checkbox" id="csize" class="hide" />');
			$this->output('<DIV CLASS="leftside">');			
			$this->widgets('main', 'top');
			$this->widgets('main', 'high');			
			$this->main_parts($content);			
			$this->output('</DIV>');
			$this->output('<DIV CLASS="solyan">');				
			$this->widgets('full', 'high');
			if ( $this->template == 'question' ) {
				$this->kim($q_view);
			}
			$this->sidepanel();					
			$this->output('</DIV>');
			if (!empty($q_view)) {
				$this->maincom();	
			}

			$this->widgets('main', 'low');			
			$this->suggest_next();			
			$this->widgets('main', 'bottom');
			$this->output('</DIV> <!-- END king-main -->', '');
			
		}

		function viewtop()
		{
			$q_view=@$this->content['q_view'];
			$favorite=@$this->content['favorite'];	



			if ( $this->template == 'question' ) {
				$this->output('<DIV CLASS="paylas">');


				if (isset($q_view['main_form_tags']))
					$this->output('<FORM '.$q_view['main_form_tags'].'>');			
				$this->voting($q_view);	
				if (isset($q_view['main_form_tags'])) {
					$this->form_hidden_elements(@$q_view['voting_form_hidden']);
					$this->output('</FORM>');
				}			
				if (isset($favorite))
					$this->output('<FORM '.$favorite['form_tags'].'>');			
				$this->favorite();	
				if (isset($favorite)) {
					$this->form_hidden_elements(@$favorite['form_hidden']);
					$this->output('</FORM>');
				}			
				if (isset($q_view['main_form_tags']))
					$this->output('<FORM '.$q_view['main_form_tags'].'>');
				$this->q_view_buttons($q_view);	
				if (isset($q_view['main_form_tags'])) {
					$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
					$this->output('</FORM>');
				}			
				$this->socialshare();			
				$this->get_prev_q();
				$this->get_next_q();
				$this->output('</DIV>');



			}	


		} 

		function submitnav() 
		{
			$selected = '';
			$selected1 = '';
			$selected2 = '';
			if ( $this->template == 'ask' ) {
				$selected = 'king-nav-sub-selected';
			} elseif ( $this->template == 'video' ) {
				$selected1 = 'king-nav-sub-selected';
			} elseif ( $this->template == 'news' ) {
				$selected2 = 'king-nav-sub-selected';
			}
			if ( $this->template == 'ask' || $this->template == 'video' || $this->template == 'news' ) {
				$this->output('<ul class="king-nav-sub-list">');
				if (!qa_opt('disable_image')) {
					$this->output('<li class="king-nav-sub-item king-nav-sub-recent"><a href="'.qa_path_html('submit').'" class="king-nav-sub-link '.$selected.'">'.qa_lang_html('main/home_image').'</a></li>');
				}
				if (!qa_opt('disable_video')) {
					$this->output('<li class="king-nav-sub-item king-nav-sub-recent"><a href="'.qa_path_html('video').'" class="king-nav-sub-link '.$selected1.'">'.qa_lang_html('main/home_video').'</a></li>');
				}
				if (!qa_opt('disable_news')) {
					$this->output('<li class="king-nav-sub-item king-nav-sub-recent"><a href="'.qa_path_html('news').'" class="king-nav-sub-link '.$selected2.'">'.qa_lang_html('main/home_news').'</a></li>');
				}
				$this->output('</ul>');
			}
		}
		
		
		function maincom()
		{		

			$content=$this->content;

			/*if (isset($content['main_form_tags']))
			$this->output('<FORM '.$content['main_form_tags'].'>');*/



			if ( $this->template == 'question' ) {			
				$this->output('<DIV CLASS="maincom">');				
				$this->output('<ul class="nav nav-tabs">');
				if ( qa_opt('allow_close_questions') ) {
					$this->output('<li class="active"><a href="#comments" data-toggle="tab">Comments</a></li>');
					$active ='';
				}
				else {
					$active ='active';
				}
				if ( qa_opt('follow_on_as') ) {
					$this->output('<li><a href="#fbcomments" data-toggle="tab"><i class="fbicon"></i> Comments</a></li>');
				}
				$this->output('</ul>');		

				$this->output('<div class="tab-content">');
				if ( qa_opt('allow_close_questions') ) {
					$this->output('<div class="tab-pane active" id="comments">');
					$this->main_partsc($content);
					$this->output('</div>');
				}
				if ( qa_opt('follow_on_as') ) {
					$this->output('<div class="tab-pane '.$active.'" id="fbcomments">');
					$this->fbyorum();
					$this->output('</div>');
				}
				$this->output('</div>');
				$this->output('</div>');
			}
			/*if (isset($content['main_form_tags']))
			$this->output('</FORM>');*/		


		}
		function main_partsc($content)
		{
			foreach ($content as $key => $part) {
				$this->main_partc($key, $part);
			}

		}

		
		function main_partc($key, $part)
		{
			$partdiv=(
				(strpos($key, 'custom')===0) ||
				(strpos($key, 'a_form')===0) ||
				(strpos($key, 'a_list')===0)

			);

			if ($partdiv)
				$this->output('<div class="king-part-'.strtr($key, '_', '-').'">'); // to help target CSS to page parts

			if (strpos($key, 'custom')===0)
				$this->output_raw($part);
			
			elseif (strpos($key, 'a_list')===0)
				$this->a_list($part);		

			elseif (strpos($key, 'a_form')===0)
				$this->a_form($part);

			if ($partdiv)
				$this->output('</div>');

		}	
		
		function main_part($key, $part)
		{
			$partdiv=(
				(strpos($key, 'custom')===0) ||
				(strpos($key, 'form')===0) ||
				(strpos($key, 'q_list')===0) ||
				(strpos($key, 'q_view')===0) ||
				(strpos($key, 'ranking')===0) ||
				(strpos($key, 'message_list')===0) ||
				(strpos($key, 'nav_list')===0)
			);

			if ($partdiv)
				$this->output('<div class="king-part-'.strtr($key, '_', '-').'">'); // to help target CSS to page parts

			if (strpos($key, 'custom')===0)
				$this->output_raw($part);

			elseif (strpos($key, 'form')===0)
				$this->form($part);

			elseif (strpos($key, 'q_list')===0)
				$this->q_list_and_form($part);

			elseif (strpos($key, 'q_view')===0)
				$this->q_view($part);

			elseif (strpos($key, 'ranking')===0)
				$this->ranking($part);

			elseif (strpos($key, 'message_list')===0)
				$this->message_list_and_form($part);

			elseif (strpos($key, 'nav_list')===0) {
				$this->part_title($part);		
				$this->nav_list($part['nav'], $part['type'], 1);
			}

			if ($partdiv)
				$this->output('</div>');
		}	
		
		
		function nav_user_search()
		{				
			$this->search();
		}
		
		function nav_main_sub()
		{
			$this->output('<DIV CLASS="king-nav-main">');			
			$this->nav('main');
			$this->navuser();			
			$this->output('</DIV>');
		}

		function navuser()
		{
			if (qa_is_logged_in()) {
				$this->output('<li class="king-nav-main-item king-nav-acc">');
				$this->output('<a href="'.qa_path_html('account').'" class="king-nav-main-link" ><i class="fas fa-user-circle"></i>'.qa_lang_html('main/nav_account').'</a>');
				$this->output('</li>');		
				$this->output('<li class="king-nav-main-item king-nav-fav">');
				$this->output('<a href="'.qa_path_html('favorites').'" class="king-nav-main-link" ><i class="fas fa-heart"></i>'.qa_lang_html('main/nav_updates').'</a>');
				$this->output('</li>');
				$this->output('<li class="king-nav-main-item">');
				$this->output('<a class="king-nav-main-link" href="'.qa_path_html('logout').'"><i class="fas fa-sign-out-alt"></i>'.qa_lang_html('main/nav_logout').'</a>');
				$this->output('</li>');	
			} else {
				$this->output('<li class="king-nav-main-item">');
				$this->output('<a class="king-nav-main-link" href="'.qa_path_html('login').'"><i class="fas fa-sign-in-alt"></i>'.qa_lang_html('main/nav_login').'</a>');
				$this->output('</li>');	
				$this->output('<li class="king-nav-main-item">');
				$this->output('<a class="king-nav-main-link" href="'.qa_path_html('register').'"><i class="fas fa-user-plus"></i>'.qa_lang_html('main/nav_register').'</a>');
				$this->output('</li>');				
			}

		}

		function header()
		{

			$this->output('<input type="checkbox" id="checkbox-menu" class="hide" />');			
			$this->output('<header CLASS="king-headerf">');
			$this->output('<DIV CLASS="king-header">');
			$this->output('<label class="icon-menu" for="checkbox-menu"></label>');
			$this->output('<aside class="leftmenu">');
			$this->nav_main_sub();	
			$this->output('</aside>');			
			$this->logo();
			$this->output('<input type="checkbox" id="checkbox-menu2" class="hide">');			
			$this->output('<DIV CLASS="userpanel">');
			$this->output('<ul>');
			$this->userpanel2();			
			$this->output('<label class="modal-backdrop" for="checkbox-menu2"></label>');
			if (!qa_is_logged_in()) {
				$this->output('<li>');
				$this->output('<a class="reglink" href="'.qa_path_html('register').'">'.qa_lang_html('main/nav_register').'</a>');		
				$this->output('</li>');
				$this->output('<li>');
				$this->output('<label class="reglink" for="checkbox-menu2">'.qa_lang_html('main/nav_login').'</label>');	
				$this->output('</li>');				
			}
			$this->userpanel();				
			if ((qa_user_maximum_permit_error('permit_post_q')!='level')) {
				$this->kingsubmit();
			}

$this->output('<li class="theme-button"><a href="https://madway.net/madway.apk" class="theme-toggle" target="_blank"><i class="fab fa-android fa-lg"></i></a></li>');

			$this->output('<li class="theme-button"><span onclick="changeTheme()" class="theme-toggle"><i class="fas fa-lightbulb fa-lg"></i></span></li>');



			$this->output('<li class="search-button"><span data-toggle="dropdown" data-target=".king-search" aria-expanded="false" class="search-toggle"><i class="fas fa-search fa-lg"></i></span></li>');
			$this->output('</ul>');
			$this->output('</DIV>');
			
			$this->output('</DIV>');	
			if ( $this->template == 'ask' || $this->template == 'news' || ($this->template=='question' && strpos(qa_get_state(),'edit')!==false) ) {
				$this->multipleupload();
			}
			if ($this->template == 'video') {
				if (qa_opt('links_in_new_window')) {
					$this->videoupload();
				}
			}
			$this->output('</header>');	
			if (qa_using_categories()) {
				$this->king_cats();
			}			
			$this->nav_user_search();
			if ($this->template == 'user-following' || $this->template == 'user-follower' || $this->template == 'user' ||  $this->template == 'user-wall' || $this->template == 'user-questions' || $this->template == 'account' || $this->template == 'favorites') {
				$this->profile_page();
			}			
			$this->widgets('full', 'top');			
			if (isset($this->content['error']))
				$this->error(@$this->content['error']);			

		}
		public function king_cats()
		{
			$this->output('<div class="king-cat-main">');
			$this->output('<a href="'.qa_path_html('categories').'" class="king-cat-link">'.qa_lang_html('main/nav_categories').'</a>');
			$this->output('<div class="king-cat">');			
			$categories=qa_db_single_select(qa_db_category_nav_selectspec(null, true)); 
			$this->content['navigation']['cat']=qa_category_navigation($categories);
			$this->nav('cat', 4);
			$this->output('</div>');
			$this->output('</div>');
		}
		function profile_page()
		{
			$handle = qa_request_part(1);
			if (!strlen($handle)) {
				$handle = qa_get_logged_in_handle();
			}
			
			$user=qa_db_select_with_pending(
				qa_db_user_account_selectspec($handle, false)
			);
			$asize =800;
			$size =400;

			$userpoints=qa_get_logged_in_points();

			$pointshtml=($userpoints==1) 
			? qa_lang_html_sub('main/1_point', '1', '1') 
			: qa_lang_html_sub('main/x_points', qa_html(number_format($userpoints))); 

			$defaultBlobId = qa_opt('avatar_default_blobid'); 
			if (qa_opt('avatar_allow_gravatar') && ($user['flags'] & QA_USER_FLAGS_SHOW_GRAVATAR))  
				$html = sprintf(  
					'%s://www.gravatar.com/avatar/%s?s=%s',  
					qa_is_https_probably() ? 'https' : 'http',  
					md5(strtolower(trim($user['email']))),  
					$asize  
				);  
			elseif (qa_opt('avatar_allow_upload') && ($user['flags'] & QA_USER_FLAGS_SHOW_AVATAR) && isset($user['avatarblobid']))  
				$html = qa_path('image', array('qa_blobid' => $user['avatarblobid'], 'qa_size' => $asize), qa_opt('site_url'), QA_URL_FORMAT_PARAMS);  
			elseif ((qa_opt('avatar_allow_gravatar') || qa_opt('avatar_allow_upload')) && qa_opt('avatar_default_show') && !empty($defaultBlobId))  
				$html = qa_path('image', array('qa_blobid' => qa_opt('avatar_default_blobid'), 'qa_size' => $asize), qa_opt('site_url'), QA_URL_FORMAT_PARAMS);  
			else  
				$html = null;


			$this->output('<div class="king-profile-page" style="background-image:url('.$html.')">');		

			$this->output('<div class="king-profile">');
			$this->output('<span class="king-profile-avatar">'.qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $size, $size, $size, true).'</span>');
			$this->output('<span class="king-profile-title">'.qa_get_points_title_html(@$user['points'], qa_get_points_to_titles()).'</span>');		
			$this->output('<span class="king-profile-usrname">'.$user['handle'].'</span>'); 
			$this->output('<span class="king-profile-lvl">' . qa_user_level_string($user['level']) . '</span>');	
			$this->output('<span class="king-profile-point">'.$user['points'].' '.qa_lang_html('admin/points_title').'</span>');
			$favorite=@$this->content['favorite'];
			if (isset($favorite)) {
				$this->output('<form ' . $favorite['form_tags'] . '>');
			}
			$this->favorite2();

			if (isset($favorite)) {
				$formhidden = isset($favorite['form_hidden']) ? $favorite['form_hidden'] : null;
				$this->form_hidden_elements($formhidden);
				$this->output('</form>');
			}
		$userid = $user['userid']; //id of currently logged in user 
		$getFCount = qa_db_read_all_assoc(qa_db_query_sub("
			SELECT userid FROM qa_userfavorites
			WHERE userid = '$userid'
			and entitytype = 'U'
			"));
		$getFCount = count($getFCount); //count item in array 
		$getCount = qa_db_read_all_assoc(qa_db_query_sub("
			SELECT entityid FROM qa_userfavorites
			WHERE entityid = '$userid'
			and entitytype = 'U'
			"));
		$getCount = count($getCount);	
		$this->output('<div class="king-follow">');
		$this->output('<span><strong>'.$getFCount.'</strong>Following</span> <span><strong>'.$getCount.'</strong>Followers</span> ');
		$this->output('</div>');
		$this->output('</div>');
		$this->output('</div>');
	}

	public function favorite2()
	{
		$favorite = isset($this->content['favorite']) ? $this->content['favorite'] : null;
		if (isset($favorite)) {
			$favoritetags = isset($favorite['favorite_tags']) ? $favorite['favorite_tags'] : '';
			$this->output('<span class="king-following" ' . $favoritetags . '>');
			$this->favorite_inner_html2($favorite);
			$this->output('</span>');
		}
	}

	public function title()
	{
		if (isset($this->content['title']))
			$this->output($this->content['title']);
	}

	public function favorite_inner_html2($favorite)
	{
		$this->favorite_button(@$favorite['favorite_add_tags'], 'king-favorite');
		$this->favorite_button(@$favorite['favorite_remove_tags'], 'king-unfavorite');
	}

	public function favorite_button($tags, $class)
	{
		if (isset($tags)) {
			if ($class == 'king-favorite') {
				$follow = qa_lang_html('main/nav_follow');
			} else {
				$follow = qa_lang_html('main/nav_unfollow');
			}
			$this->output('<input '.$tags.' type="submit" value="'.$follow.'" class="'.$class.'-button"/>');
		}	
	}

	function kingsubmit()	
	{
		if ( !qa_opt('disable_image') || !qa_opt('disable_video') || !qa_opt('disable_news') ) {
			$this->output('<li>');
			$this->output('<div class="king-submit">');

			$this->output('<span class="kingadd" data-toggle="dropdown" data-target=".king-submit" aria-expanded="false" role="button"><i class="fa fa-plus fa-lg" aria-hidden="true"></i></span>');
			$this->output('<div class="king-dropdown2">');
			$this->output('<div class="arrow"></div>');
			if (!qa_opt('disable_image')) {
				$this->output('<a href="'.qa_path_html('ask').'" class="kingaddimg"><i class="fas fa-image"></i> '.qa_lang_html('main/home_image').'</a>');
			}
			if (!qa_opt('disable_video')) {	
				$this->output('<a href="'.qa_path_html('video').'" class="kingaddvideo"><i class="fas fa-video"></i> '.qa_lang_html('main/home_video').'</a>');
			}
			if (!qa_opt('disable_news')) {	
				$this->output('<a href="'.qa_path_html('news').'" class="kingaddnews"><i class="fas fa-newspaper"></i> '.qa_lang_html('main/home_news').'</a>');
			}		
			$this->output('</div>');		
			$this->output('</div>');
			$this->output('</li>');
		}
	}

	function userpanel()
	{
		$handle= qa_get_logged_in_handle();
		$user=qa_db_select_with_pending(
			qa_db_user_account_selectspec($handle, false)
		);		
		if (qa_is_logged_in()) {
			$this->output('<li>');
			$this->output('<div class="king-havatar" data-toggle="dropdown" data-target=".king-dropdown" aria-expanded="false" >');
			$this->output(''.qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_message_list_size'), true).'');
			$this->output('</div>');
			$this->output('<div class="king-dropdown">');
			$this->output('<div class="arrow"></div>');
			$this->useravatar();		
			$this->nav('user');
			$this->output('</div>');
			$this->output('</li>');
		} 	
	} 	

	function userpanel2()
	{		
		if (!qa_is_logged_in()) {
			$login=@$this->content['navigation']['user']['login'];		


			$this->output('<div class="modal-dialog">
				<div class="modal-header">
				<label type="button" class="close" for="checkbox-menu2">&times;</label>
				<h4 class="modal-title">Login</h4>
				</div>');
			$this->output('<div class="modal-body">');
			$this->output('<form action="'.$login['url'].'" method="post">													
				<input type="text" id="king-userid" name="emailhandle" placeholder="'.trim(qa_lang_html('users/email_handle_label'), ':').'" />							
				<input type="password" id="king-password" name="password" placeholder="'.trim(qa_lang_html('users/password_label'), ':').'" />
				<div id="king-rememberbox"><input type="checkbox" name="remember" id="king-rememberme" value="1"/>
				<label for="king-rememberme" id="king-remember">'.qa_lang_html('users/remember').'</label></div>
				<input type="hidden" name="code" value="'.qa_html(qa_get_form_security_code('login')).'"/>
				<input type="submit" value="Sign in" id="king-login" name="dologin" />
				<label type="button" class="closebtn" for="checkbox-menu2">Close</label>
				</form>');
			$this->output('</div>');		
			$this->output('<div class="modal-footer">');
			$this->nav('user');
			$this->output('</div>');					
			$this->output('</div>');
		}


	}


	function multipleupload()
	{
		$mpageurl = (isset($_SERVER['HTTPS']) ? "https://" : "https://") . ''.@$_SERVER['HTTP_HOST'].strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/').'/';
		$maxcount = qa_opt('image_max_file_count');
		$maxsize = qa_opt('image_max_upload');
		define( 'MB', 1048576 );
		$month_folder = 'uploads/' . date("Y") . '/' . date("m") . '/';
		$this->output('<script>
			$(document).ready(function()
			{
				$("#multipleupload").uploadFile({url: "'.qa_path_to_root().'king-include/multipleupload.php",
				dragDrop: true,
				fileName: "myfile",
				maxFileSize: '.$maxsize * MB.',
				maxFileCount: '.$maxcount.',
				showFileSize: false,
				returnType: "json",
				showDelete: true,
				showDownload: true,
				showPreview:true,
				acceptFiles:"image/*",
				allowedTypes: "jpg,png,gif,jpeg,webp",
				downloadStr: "Add",
				previewHeight: "250px",
				previewWidth: "250px",
				statusBarWidth:"auto",
				dragdropWidth:"auto",
				deleteCallback: function (data, pd) {
					for (var i = 0; i < data.length; i++) {
						$.post("'.qa_path_to_root().'king-include/multipledelete.php", {op: "delete",name: data[i]},
						function (resp,textStatus, jqXHR) {
                //Show Message	
							pd.statusbar.hide("slow");
							});
						}
						pd.statusbar.hide(); //You choice.

						},
						downloadCallback:function(filename,pd)
						{
							html = "<input id=\'download\' class=\'hide\' value=\''.$mpageurl.'king-include/'.$month_folder.'"+filename+"\' name=\'submit_image[]\' />";
							html += "<input id=\'content\' class=\'hide\' value=\''.$mpageurl.'king-include/'.$month_folder.'thumb_"+filename+"\' name=\'content\' />";
							return html; 
						}
						}); 
						});
						</script>');
	}

	function videoupload()
	{
		$mpageurl = (isset($_SERVER['HTTPS']) ? "https://" : "https://") . ''.@$_SERVER['HTTP_HOST'].strtr(dirname($_SERVER['SCRIPT_NAME']), '\\', '/').'/';
		$maxvideosize = qa_opt('video_max_upload');
		define( 'MB', 1048576 );	
		$this->output('<script>
			$(document).ready(function()
			{
				$("#multipleupload").uploadFile({url: "'.qa_path_to_root().'king-include/videoupload.php",
				dragDrop: true,
				fileName: "myfile",
				maxFileSize: '.$maxvideosize * MB.',
				maxFileCount: 1,
				showFileSize: false,
				returnType: "json",
				showDelete: true,
				showDownload: true,
				showPreview:true,
				allowedTypes: "mp4",
				allowedTypes: "webm",
				downloadStr: "Add",
				previewHeight: "auto",
				previewWidth: "100%",
				multiple: false,
				statusBarWidth:"auto",
				dragdropWidth:"auto",
				deleteCallback: function (data, pd) {
					for (var i = 0; i < data.length; i++) {
						$.post("'.qa_path_to_root().'king-include/videodelete.php", {op: "delete",name: data[i]},
						function (resp,textStatus, jqXHR) {
                //Show Message	
							pd.statusbar.hide("slow");
							});
						}
						pd.statusbar.hide(); //You choice.

						},
						downloadCallback:function(filename,pd)
						{
							$(\'#king-vidthumb\').addClass("king-active");
							$(\'#imgprev\').attr({"src":"'.$mpageurl.'king-include/videos/"+filename+".jpg"});
							html = "<input id=\'download\' class=\'hide\' value=\''.$mpageurl.'king-include/videos/"+filename+".jpg\' name=\'submit_image\' />";
							html += "<input id=\'content\' class=\'hide\' value=\''.$mpageurl.'king-include/videos/"+filename+".mp4\' name=\'content\' />";
							html += "<input id=\'content\' class=\'hide\' value=\''.$mpageurl.'king-include/videos/"+filename+".webm\' name=\'content\' />";
							return html; 		


						}
						}); 
						});
						</script>');
	}		


	function form_text_multi_row($field, $style)
	{
		$this->output('<TEXTAREA '.@$field['tags'].' ROWS="5" COLS="40" CLASS="king-form-'.$style.'-text">'.@$field['value'].'</TEXTAREA>');
	}


	function q_list_and_form($q_list)
	{
		if (!empty($q_list)) {
			$this->part_title($q_list);

			if (!empty($q_list['form']))
				$this->output('<form '.$q_list['form']['tags'].'>');

			$this->q_list($q_list);
			$this->output('<div id="loading"></div>');
			if (!empty($q_list['form'])) {
					unset($q_list['form']['tags']); // we already output the tags before the qs
					$this->q_list_form($q_list);
					$this->output('</form>');			
				}
			}
		}	
		
		function king_cat()
		{
			if (qa_using_categories()) {
				$this->output('<div class="king-cat-main">');
				$this->output('<ul><li>');
				$this->output('<a href="'.qa_path_html('categories').'" class="king-cat-link">'.qa_lang_html('main/nav_categories').'</a>');
				$this->output('<div class="king-cat">');			
				$categories=qa_db_single_select(qa_db_category_nav_selectspec(null, true)); 
				$this->content['navigation']['cat']=qa_category_navigation($categories);
				$this->nav('cat', 4);
				$this->output('</div>');
				$this->output('</li></ul>');
				$this->output('</div>');
			}	
		}
		
		
		function q_list($q_list)
		{
			if (isset($q_list['qs'])) {				
				
				$this->q_list_items($q_list['qs']);

			}
		}

		function q_list_items($q_items)
		{
			if ( $this->template == 'question' ) {
				foreach ($q_items as $q_item)
					$this->king_related($q_item);				
			}		
			else {
				$this->output('<div id="container">');
				foreach ($q_items as $q_item)
					$this->q_list_item($q_item);
				$this->output('</div>');	
				
				$this->output('<script src="'.$this->rooturl.'jquery.infinitescroll.min.js"></script>');

				$this->output('<script type="text/javascript">
					var ias = $.ias({
						container: "#container",
						item: ".box",
						pagination: ".king-page-links-list",
						next: ".king-page-next",
						delay: 0,
						negativeMargin: 200
						});

						ias.extension(new IASSpinnerExtension());
						</script>');				
			}	
		}	

		function king_related($q_item)
		{
			$this->output('<div class="king-related">');					
			$this->q_item_content($q_item);		
			$this->q_item_title($q_item);			
			$this->output('</div>');
		}		
		
		function q_list_item($q_item)
		{
			$this->output('<div class="king-q-list-item'.rtrim(' '.@$q_item['classes']).'" '.@$q_item['tags'].'>');
			$this->q_item_main($q_item);
			$this->output('</div> <!-- END king-q-list-item -->', '');
		}
		
		function q_item_stats($q_item)
		{
			$this->output('<DIV CLASS="king-q-item-stats">');
			
			$this->voting($q_item);


			$this->output('</DIV>');
		}
		
		function q_item_main($q_item)
		{
			$this->output('<div class="box">');
			$this->output('<div class="boxleft">');
			$this->post_avatar_meta($q_item, 'king-q-item');
			$this->voting($q_item);
			$this->socialshare2($q_item);			
			$this->output('</div>');			
			$this->post_meta_where($q_item, 'metah');		
			$this->q_item_content($q_item);	
			
			$this->output('<DIV CLASS="yoriz">');			
			$this->q_item_title($q_item);
			$this->output('<DIV CLASS="yorizbottom">');
			$this->a_count($q_item);			
			$this->view_count($q_item);
			$this->voting2($q_item);
			$this->q_item_buttons($q_item);	
			$this->output('</DIV>');			
			$this->output('</DIV>');
			$this->output('</div>');
		}
		function socialshare2($q_item)
		{		
			$pid = $q_item['raw']['postid'];
			$query = qa_db_query_sub("SELECT postid, content, format FROM ^posts WHERE postid = $pid");
			$cont = qa_db_read_one_assoc($query);
			$text=qa_viewer_text($cont['content'], $cont['format']);
			$url2 = qa_path_html(qa_q_request($q_item['raw']['postid'], $q_item['raw']['title']), null, qa_opt('site_url'));


			$this->output('<ul class="socialshare" data-url="'.$url2.'">');
			$this->output('<li class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u='.$url2.'" target="_blank" title="Share on Facebook" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><span data-url="'.$url2.'"><i style="color: #38529a" class="fab fa-facebook fa-xl"></i></span></a></li>');
			$this->output('<li class="twitter" data-share-text=""><a href="https://twitter.com/intent/tweet?url='.$url2.'" data-url="'.$url2.'" title="Share on Twitter" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><span data-url="'.$url2.'"><i style="color: #46c0fb" class="fab fa-twitter fa-xl"></i></span></a></li>');
			$this->output('<li class="reddit"><a href="https://www.reddit.com/submit?url='.$url2.'&title=Share" title="Share on Reddit" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><span data-url="'.$url2.'"><i style="color: #ff4500" class="fab fa-reddit fa-xl"></i></span></a></li>');
			$this->output('<li class="tumblr"><a href="https://www.tumblr.com/share/link?url='.$url2.'" title="Share on Tumblr" onclick="javascript:window.open(this.href, \'_blank\');return false;"><span data-url="'.$url2.'"><i style="color: #375672" class="fab fa-tumblr fa-xl"></i></span></a></li>');
			$this->output('</ul>');
		}
		function voting2($post)
		{
			if (isset($post['vote_view'])) {				
				$this->vote_count($post);
			}
		}		
		
		function q_item_content($q_item)
		{
			require_once QA_INCLUDE_DIR.'king-db-metas.php';
			$extrac = qa_db_postmeta_get($q_item['raw']['postid'], 'qa_q_extra');
			$pid = $q_item['raw']['postid'];
			$query = qa_db_query_sub("SELECT postid, content, format FROM ^posts WHERE postid = $pid");
			$cont = qa_db_read_one_assoc($query);
			$text=qa_viewer_text($cont['content'], $cont['format']);

			if (strstr($extrac,".gif")) {

				$qstart = '<i class="fas fa-play fa-2x"></i>';
				$extrac2 = $extrac;

			} elseif (strstr($extrac,"youtube.com") || strstr($extrac,"vimeo.com") || strstr($extrac,"twitter.com") || strstr($extrac,"vine.co") || strstr($extrac,"dailymotion.com")) {

				$qstart = '<i class="fas fa-play fa-2x"></i>';
				$extrac2 = $extrac;
			} else {

				$qstart ='';
				$extrac2 = '';

			}

			if (!empty($text)) {
				$this->output('<div class="box_thumb">');
				$this->output('<A href="'.$q_item['url'].'" class="king-hover"></A>');
				$this->output('<div data-embed="'.$extrac2.'" class="qstart">'.$qstart.'');
				$this->output_raw('<img class="item-img" src="'.$text.'"></img>');
				$this->output('</div>');
				$this->output('</div>');
			}	
			else {
				$this->output('<a href="'.$q_item['url'].'" class="king-nothumb"></a>');
			}	
		}
		
		function q_item_title($q_item)
		{
			$pid = $q_item['raw']['postid'];
			$query = qa_db_query_sub("SELECT postid, content, postformat, format FROM ^posts WHERE postid = $pid");
			$cont = qa_db_read_one_assoc($query);			
			$text2=qa_viewer_text($cont['postformat'], $cont['format']);
			$postformat = '';
			if ($text2=='V') {
				$postformat = '<a class="king-post-format-video" href="'.qa_path_html('type').'">'.qa_lang_html('main/home_video').'</a>';
			} elseif ($text2=='I') {
				$postformat = '<a class="king-post-format-image" href="'.qa_path_html('type', array('by' => 'images')).'">'.qa_lang_html('main/home_image').'</a>';
			} elseif ($text2=='N') {
				$postformat = '<a class="king-post-format-image" href="'.qa_path_html('type', array('by' => 'news')).'">'.qa_lang_html('main/home_news').'</a>';
			}

$this->output(
				'<DIV CLASS="king-q-item-title">',
				'<A HREF="'.$q_item['url'].'">'.$q_item['title'].'</A>',
				'</DIV>'
			);

			$this->output(''.$postformat.'');
			
			
		}
		

		
		function sidepanel()
		{
			$this->output('<div class="king-sidepanel none">');
			$this->widgets('side', 'top');
			$this->sidebar();
			$this->widgets('side', 'high');
			$this->nav('cat', 1);
			$this->widgets('side', 'low');
			$this->output_raw(@$this->content['sidepanel']);
			$this->feed();
			$this->widgets('side', 'bottom');
			$this->output('</div>', '');
		}
		
		function nav($navtype, $level=null)
		{
			$navigation=@$this->content['navigation'][$navtype];
			
			if (($navtype=='user') || isset($navigation)) {

				
				if ($navtype=='user')
					
					
				// reverse order of 'opposite' items since they float right
					foreach (array_reverse($navigation, true) as $key => $navlink)
						if (@$navlink['opposite']) {
							unset($navigation[$key]);
							$navigation[$key]=$navlink;
						}

						$this->set_context('nav_type', $navtype);
						$this->nav_list($navigation, 'nav-'.$navtype, $level);
						$this->nav_clear($navtype);
						$this->clear_context('nav_type');



					}
				}

				function useravatar()
				{       
					$handle= qa_get_logged_in_handle();
					$user=qa_db_select_with_pending(
						qa_db_user_account_selectspec($handle, false)
					);
					$this->output('<DIV CLASS="usrname">');

					$this->logged_in();
					$this->output('</DIV>');
				}
				
		function logged_in() // adds points count after logged in username
		{
			qa_html_theme_base::logged_in();
			
			if (qa_is_logged_in()) {
				$userpoints=qa_get_logged_in_points();
				
				$pointshtml=($userpoints==1)
				? qa_lang_html_sub('main/1_point', '1', '1')
				: qa_lang_html_sub('main/x_points', qa_html(number_format($userpoints)));

				$this->output(
					'<SPAN CLASS="king-logged-in-points">',
					''.$pointshtml.'',
					'</SPAN>'
				);
			}
		}

		function q_view_main($q_view)
		{
			$this->output('<DIV CLASS="king-q-view-main">');		
			$this->output('<div class="king-q-view-content">');
			$this->q_view_extra($q_view);
			$this->output('</div>');
			$this->q_view_follows($q_view);
			$this->q_view_closed($q_view);			
			$this->c_list(@$q_view['c_list'], 'king-q-view');
			$this->output('</DIV> <!-- END king-q-view-main -->');	
		}


		function q_view_extra($q_view)
		{
			if (!empty($q_view['extra']))
				$this->output_raw($q_view['extra']['content'] = $this->embed_replace($q_view['extra']['content']));


		}			

		function embed_replace($text) {
			
			$w  = '600px';
			
			$h = '338px';
			
			$w2 = '100%';
			
			$h2 = 'auto';

			$vidthumb = $this->content['description'];
			$vidthumb = preg_replace('/thumb_/', '', $vidthumb);
			
			$types = array(
				'youtube'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*youtube\.com\/watch\?\S*v=([A-Za-z0-9_-]+)[^< ]*',
						'<iframe width="'.$w.'" height="'.$h.'" src="https://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen></iframe>'
					),
					array(
						'https{0,1}:\/\/w{0,3}\.*youtu\.be\/([A-Za-z0-9_-]+)[^< ]*',
						'<iframe width="'.$w.'" height="'.$h.'" src="https://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen></iframe>'
					)
				),
				'vimeo'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*vimeo\.com\/([0-9]+)[^< ]*',
						'<iframe src="https://player.vimeo.com/video/$1?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe>')
				),
				'twitter'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*twitter\.com\/i\/status\/([0-9]+)[^< ]*',
						'<amp-twitter class="no-bottom" width="'.$w.'" height="'.$h.'" layout="responsive" data-tweetid="$1" data-cards="hidden"></amp-twitter>')
				),
				'metacafe'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*metacafe\.com\/watch\/([0-9]+)\/([a-z0-9_]+)[^< ]*',
						'<embed flashVars="playerVars=showStats=no|autoPlay=no" src="https://www.metacafe.com/fplayer/$1/$2.swf" width="'.$w.'" height="'.$h.'" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_$1" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>'
					)
				),
				'vine'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*vine\.co\/v\/([A-Za-z0-9_-]+)[^< ]*',
						'<iframe class="vine-embed" src="https://vine.co/v/$1/embed/simple?audio=1" width="'.$w.'" height="480px" frameborder="0"></iframe>'
					)
				),				

				'instagram'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*instagram\.com\/p\/([A-Za-z0-9_-]+)[^< ]*',
						'<iframe src="//instagram.com/p/$1/embed/" width="'.$w.'" height="'.$w.'" frameborder="0" scrolling="no" allowtransparency="true"></iframe>'
					)
				),	
				
				'dailymotion'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*dailymotion\.com\/video\/([A-Za-z0-9]+)[^< ]*',
						'<iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="https://www.dailymotion.com/embed/video/$1?wmode=transparent"></iframe>'
					)
				),	

				'vk'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*vk\.com\/video([\-\_A-Za-z0-9]+)[^< ]*',
						'$1','img'
					)
				),	
				
				'mailru'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*my.mail.ru\/mail\/([\-\_\/.a-zA-Z0-9]+)[^< ]*',
						'<iframe src="https://videoapi.my.mail.ru/videos/embed/mail/$1" width="'.$w.'" height="'.$h.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
					)
				),
				
				'soundcloud'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*soundcloud\.com\/([-\%_\/.a-zA-Z0-9]+\/[-\%_\/.a-zA-Z0-9]+)[^< ]*',
						'<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://soundcloud.com/$1&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>'
					)
				),		

				'facebook'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*facebook\.com\/video\.php\?\S*v=([A-Za-z0-9_-]+)[^< ]*',						
						'<div class="fb-video" data-allowfullscreen="true" data-href="https://www.facebook.com/video.php?v=$1&type=1"></div>'
					)
				),				

				'facebook2'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*facebook\.com\/([A-Z.a-z0-9_-]+)\/videos\/([A-Za-z0-9_-]+)[^< ]*',						
						'<div class="fb-video" data-allowfullscreen="true"  data-href="/$1/videos/$2/?type=1"></div>'
					)
				),
				
				'image'=>array(
					array(
						'(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(png|jpg|jpeg|webp|gif|bmp))[^< ]*',
						'<img src="$1" style="max-width:'.$w2.';height:'.$h2.';display:block;" />'
					)
				),

				'xhamster'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*xhamster\.com\/movies\/([0-9]+)\/(.*?)[^< ]*',
						'<iframe src="https://xhamster.com/xembed.php?video=$1" width="'.$w.'" height="'.$h.'" scrolling="no" allowfullscreen></iframe>'
					)
				),

				'okru'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*ok\.ru\/video\/([A-Za-z0-9]+)[^< ]*',
						'<iframe width="'.$w.'" height="'.$h.'" src="https://ok.ru/videoembed/$1" frameborder="0" allowfullscreen></iframe>'
					)
				),

				'coub'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*coub.com\/view\/([\-\_\/.a-zA-Z0-9]+)[^< ]*',
						'<iframe src="//coub.com/embed/$1?muted=true&autostart=true&originalSize=false&hideTopBar=false&startWithHD=false" allowfullscreen="true" frameborder="0" width="'.$w.'" height="'.$h.'"></iframe>'
					)
				),

				'vidme'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*vid\.me\/([A-Za-z0-9_-]+)[^< ]*',						
						'<iframe src="https://vid.me/e/$1" width="'.$w.'" height="'.$h.'" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen scrolling="no"></iframe>'
					)
				),	

				'gfycat'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*gfycat\.com\/([A-Z.a-z0-9_-]+)[^< ]*',
						'<iframe src="https://gfycat.com/ifr/SomeZigzagGodwit" frameborder="0" scrolling="no" width="'.$w.'" height="'.$h.'" allowfullscreen></iframe>'
					)
				),		
				
				'twitch'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*twitch\.tv\/([A-Za-z0-9]+)[^< ]*',
						'<iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="https://player.twitch.tv/?channel=$1"></iframe>'
					)
				),
				
				'rutube'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*rutube\.ru\/video\/([A-Z.a-z0-9_-]+)\/\?\S*pl_type=source\&\S*pl_id=([A-Za-z0-9_-]+)[^< ]*',						
						'<iframe width="'.$w.'" height="'.$h.'" src="//rutube.ru/pl/?pl_id=$2&pl_type=source&pl_video=$1" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>'
					)
				),		
				
				'mp4'=>array(
					array(
						'(https*:\/\/[-\%_\/.a-zA-Z0-9+]+\.(mp4))[^< ]*',
						'<video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="'.$w2.'" height="'.$h.'" poster="'.$vidthumb.'"  data-setup=\'{"example_option":true}\'> <source src="$1" type=\'video/mp4\' /> </video>'
					)
				),

				'webm'=>array(
					array(
						'(https*:\/\/[-\%_\/.a-zA-Z0-9+]+\.(webm))[^< ]*',
						'<video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="'.$w2.'" height="'.$h.'" poster="'.$vidthumb.'"  data-setup=\'{"example_option":true}\'> <source src="$1" type=\'video/webm\' /> </video>'
					)
				),
				
				'mp3'=>array(
					array(
						'(https*:\/\/[-\%_\/.a-zA-Z0-9]+\.mp3)[^< ]*',qa_opt('embed_mp3_player_code'),'mp3'
					)
				),
				'gmap'=>array(
					array(
						'(https*:\/\/maps.google.com\/?[^< ]+)',
						'<iframe width="'.qa_opt('embed_gmap_width').'" height="'.qa_opt('embed_gmap_height').'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="$1&amp;ie=UTF8&amp;output=embed"></iframe><br /><small><a href="$1&amp;ie=UTF8&amp;output=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>','gmap'
					)
				),
			);

foreach($types as $t => $ra) {
	foreach($ra as $r) {
		if( (!isset($r[2])));

		if(isset($r[2]) && @$r[2] == 'img' && preg_match('/MSIE [5-7]/',$_SERVER['HTTP_USER_AGENT']) == 0) {
			preg_match_all('/'.$r[0].'/',$text,$imga);
			if(!empty($imga)) {
				foreach($imga[1] as $img) {

					$res = file_get_contents("https://vk.com/video$img");
					preg_match('/property="og:video" content="(.*?)"/', $res, $output);
					$result1 = $output[1];
					preg_match('@(embed_hash=?([^/]+))@', $result1, $matches);
					$replace1 = preg_replace('|_|i','&id=',$img);
					$text = '<iframe src="//vk.com/video_ext.php?oid='.$replace1.'&hash='.$matches[2].'&hd=2" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe>';
				}
			}
			continue;
		}
		$text = preg_replace('/<a[^>]+>'.$r[0].'<\/a>/i',$r[1],$text);
		$text = preg_replace('/(?<![\'"=])'.$r[0].'/i',$r[1],$text);
	}
}
return $text;
}			

function fbyorum()
{
	$this->output('<DIV CLASS="fbyorum">');
	$this->output('<div class="fb-comments" data-href="'.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).'" data-numposts="14" data-width="100%" ></div>');
	$this->output('</DIV>');
}			

function page_title_error()
{
	$this->output('<DIV CLASS="baslik">');	
	$this->output('<H9>');
	$this->title();
	$this->output('</H9>');
	$this->output('</DIV>');
}


function q_view_buttons($q_view)
{
	if (!empty($q_view['form'])) {
		$this->output('<DIV CLASS="king-q-view-buttons">');				
		$this->form($q_view['form']);				
		$this->output('</DIV>');
	}
}

function kim($q_view)
{
	$this->output('<DIV CLASS="kim">');
	$this->post_avatar($q_view, 'king-q-view');
	$this->post_meta_who($q_view, 'meta');
	$this->output('</DIV>');		
}

function q_view($q_view)
{
	$pid = $q_view['raw']['postid'];
	$query = qa_db_query_sub("SELECT postid, content, postformat, format FROM ^posts WHERE postid = $pid");
	$cont = qa_db_read_one_assoc($query);			
	$text2=qa_viewer_text($cont['postformat'], $cont['format']);				
	if (!empty($q_view)) {
		$this->output('<DIV CLASS="king-q-view'.(@$q_view['hidden'] ? ' king-q-view-hidden' : '').rtrim(' '.@$q_view['classes']).'"'.rtrim(' '.@$q_view['tags']).'>');
		$this->output('<label class="csizebutton" for="csize"></label>');	
		$this->a_count($q_view);
		if ( $text2 !== 'N' ) {
			$this->q_view_main($q_view);
		}
		$this->q_view_clear();

		$this->output('<DIV CLASS="rightview">');				
		$this->page_title_error();	
		if ( $text2 == 'N' ) {
			$this->q_view_main($q_view);
		}				
		$this->post_tags($q_view, 'king-q-view');
		$this->view_count($q_view);
		$this->post_meta_when($q_view, 'meta');
		$this->output('</DIV>');
		if (qa_opt('show_ad_post_below')) {
			$this->output('<div class="ad-below">');		
			$this->output(''.qa_opt('ad_post_below').'');
			$this->output('</div>');
		}				
		$this->output('</DIV> <!-- END king-q-view -->', '');
	}
}	

function socialshare()
{	
	$pagetitle=strlen($this->request) ? strip_tags(@$this->content['title']) : '';
	$headtitle=(strlen($pagetitle) ? ($pagetitle) : '');
	$shareurl= qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url'));

	$this->output('<div class="share-overlay twitter-ready fb-ready">');
	$this->output('<a class="share-badge facebook" title="Share on Facebook" href="https://www.facebook.com/sharer/sharer.php?u='.$shareurl.'" target="_blank" rel="nofollow" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;">');
	$this->output('<i class="fab fa-facebook fa-lg"></i>');
	$this->output('</a>');			
	$this->output('<a class="share-badge twitter" href="https://twitter.com/share?text='.$headtitle.'&amp;url='.$shareurl.'" title="Share on Twitter" rel="nofollow" target="_blank" onclick="avascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><i class="fab fa-twitter-square"></i></a>');
	if (strlen(@$this->content['description'])) {
		$this->output('<a class="share-badge pinshare" href="//www.pinterest.com/pin/create/button/?url='.$shareurl.'&amp;media='.$this->content['description'].'&amp;description=" title="Pin It" target="_blank" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><i class="fab fa-pinterest-square"></i></a>');
	}
	$this->output('</div>');							
}		

function a_list_item($a_item)
{
	$extraclass=@$a_item['classes'].($a_item['hidden'] ? ' king-a-list-item-hidden' : ($a_item['selected'] ? ' king-a-list-item-selected' : ''));

	$this->output('<DIV CLASS="king-a-list-item '.$extraclass.'" '.@$a_item['tags'].'>');



	$this->a_item_main($a_item);
	$this->a_item_clear();

	$this->output('</DIV> <!-- END king-a-list-item -->', '');
}	


function a_item_main($a_item)
{
	$this->output('<div class="king-a-item-main">');



	$this->output('<DIV CLASS="commentmain">');

	if ($a_item['hidden'])
		$this->output('<DIV CLASS="king-a-item-hidden">');
	elseif ($a_item['selected'])

		$this->output('<DIV CLASS="king-a-item-selected">');

	$this->error(@$a_item['error']);
	$this->output('<DIV CLASS="a-top">');
	$this->post_avatar_meta($a_item, 'king-a-item');

	$this->post_meta_who($a_item, 'meta');
	$this->a_item_content($a_item);
	$this->output('</DIV>');


	$this->output('<DIV CLASS="a-alt">');
	$this->a_selection($a_item);
	if (isset($a_item['main_form_tags']))
				$this->output('<form '.$a_item['main_form_tags'].'>'); // form for voting buttons
			
			$this->voting($a_item);
			
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['voting_form_hidden']);
				$this->output('</form>');
			}
			if (isset($a_item['main_form_tags']))
				$this->output('<form '.$a_item['main_form_tags'].'>'); // form for buttons on answer

			$this->a_item_buttons($a_item);	
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
				$this->output('</FORM>');
			}			
			$this->post_meta_when($a_item, 'meta');
			$this->output('</DIV>');
			
			$this->output('</DIV>');
			

			

			if ($a_item['hidden'] || $a_item['selected'])

				$this->output('</DIV>');
			
			if (isset($a_item['main_form_tags']))
				$this->output('<FORM '.$a_item['main_form_tags'].'>'); // form for buttons on answer			
			$this->c_list(@$a_item['c_list'], 'king-a-item');
			if (isset($a_item['main_form_tags'])) {
				$this->form_hidden_elements(@$a_item['buttons_form_hidden']);
				$this->output('</FORM>');
			}			
			$this->c_form(@$a_item['c_form']);

			$this->output('</DIV> <!-- END king-a-item-main -->');
		}
		

		function a_item_buttons($a_item)
		{

			if (!empty($a_item['form'])) {
				$this->output('<DIV CLASS="king-a-item-buttons">');			
				$this->form($a_item['form']);
				$this->output('</DIV>');
			}			
		}
		
		function post_avatar_meta($post, $class, $avatarprefix=null, $metaprefix=null, $metaseparator='<br/>')
		{			
			$this->output('<span class="'.$class.'-avatar-meta">');
			$this->post_avatar($post, $class, $avatarprefix);		
			$this->output('</span>');	
		}
		

		function post_meta($post, $class, $prefix=null, $separator='<BR/>')
		{
			$this->output('<SPAN CLASS="'.$class.'-meta">');
			
			if (isset($prefix))
				$this->output($prefix);
			
			$order=explode('^', @$post['meta_order']);
			
			foreach ($order as $element)
				switch ($element) {
					case 'who':
					$this->post_meta_who($post, $class);
					break;

					case 'when':
					$this->post_meta_when($post, $class);
					break;	
					
				}
				
				$this->post_meta_flags($post, $class);




				$this->output('</SPAN>');
			}

			function post_meta_who($post, $class)
			{
				if (isset($post['who'])) {
					$this->output('<SPAN CLASS="'.$class.'-who">');

					if (strlen(@$post['who']['prefix']))
						$this->output('<SPAN CLASS="'.$class.'-who-pad">'.$post['who']['prefix'].'</SPAN>');

					if (isset($post['who']['data']))
						$this->output('<SPAN CLASS="'.$class.'-who-data">'.$post['who']['data'].'</SPAN>');

					if (isset($post['who']['title']))
						$this->output('<SPAN CLASS="'.$class.'-who-title">'.$post['who']['title'].'</SPAN>');
					
				// You can also use $post['level'] to get the author's privilege level (as a string)

					if (isset($post['who']['points'])) {
						$post['who']['points']['prefix']=''.$post['who']['points']['prefix'];
						$post['who']['points']['suffix'].='';
						$this->output_split($post['who']['points'], $class.'-who-points');
					}

					if (strlen(@$post['who']['suffix']))
						$this->output('<SPAN CLASS="'.$class.'-who-pad">'.$post['who']['suffix'].'</SPAN>');

					$this->output('</SPAN>');
				}
			}
			function post_meta_when($post, $class)
			{
				$this->output_split(@$post['when'], $class.'-when');
			}

			function c_item_main($c_item)
			{
				$this->error(@$c_item['error']);
				$this->post_avatar_meta($c_item, 'king-c-item');
				$this->post_meta_who($c_item, 'meta');
				if (isset($c_item['expand_tags']))
					$this->c_item_expand($c_item);
				elseif (isset($c_item['url']))
					$this->c_item_link($c_item);
				else
					$this->c_item_content($c_item);

				$this->output('<DIV CLASS="king-c-item-footer">');			
				$this->c_item_buttons($c_item);
				$this->post_meta_when($c_item, 'meta');
				$this->output('</DIV>');
			}

			function voting_inner_html($post)
			{
				$this->vote_buttonsup($post);			
				$this->vote_count($post);
				$this->vote_buttonsdown($post);
				$this->vote_clear();
			}

			function vote_buttonsup($post)
			{
				$this->output('<DIV CLASS="'.(($post['vote_view']=='updown') ? 'king-vote-buttons-updown' : 'king-vote-buttons-netup').'">');

				switch (@$post['vote_state'])
				{
					case 'voted_up':
					$this->post_hover_button($post, 'vote_up_tags', '+', 'king-vote-one-button king-voted-up');
					break;
					
					case 'voted_up_disabled':
					$this->post_disabled_button($post, 'vote_up_tags', '+', 'king-vote-one-button king-vote-up');
					break;				
					
					case 'up_only':
					$this->post_hover_button($post, 'vote_up_tags', '+', 'king-vote-first-button king-vote-up');
					
					break;

					case 'enabled':
					$this->post_hover_button($post, 'vote_up_tags', '+', 'king-vote-first-button king-vote-up');
					
					break;

					default:
					$this->post_disabled_button($post, 'vote_up_tags', '', 'king-vote-first-button king-vote-up');
					
					break;
				}

				$this->output('</DIV>');
			}

			function vote_buttonsdown($post)
			{
				$this->output('<DIV CLASS="'.(($post['vote_view']=='updown') ? 'king-vote-buttons-updown' : 'king-vote-buttons-netdown').'">');

				switch (@$post['vote_state'])
				{

					

					
					case 'voted_down':
					$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'king-vote-one-button king-voted-down');
					break;
					
					case 'voted_down_disabled':
					$this->post_disabled_button($post, 'vote_down_tags', '&ndash;', 'king-vote-one-button king-vote-down');
					break;
					
					case 'up_only':
					
					$this->post_disabled_button($post, 'vote_down_tags', '', 'king-vote-second-button king-vote-down');
					break;

					case 'enabled':
					
					$this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'king-vote-second-button king-vote-down');
					break;

					default:
					
					$this->post_disabled_button($post, 'vote_down_tags', '', 'king-vote-second-button king-vote-down');
					break;
				}

				$this->output('</DIV>');
			}

			function footer()
			{
				$this->output('<div class="king-footer">');
				$this->output('<ul class="socialicons">');
				if (qa_opt('footer_fb')) {
					$this->output('<li class="facebook"><a href="'.qa_opt('footer_fb').'" target="_blank" title="Join our Facebook page!"> </a></li>');
				}			
				if (qa_opt('footer_twi')) {    
					$this->output('<li class="twitter"><a href="'.qa_opt('footer_twi').'" target="_blank" title="Follow us on Twitter!"> </a></li>');
				}
				if (qa_opt('footer_google')) {	
					$this->output('<li class="googleplus"><a href="'.qa_opt('footer_google').'" target="_blank" title="Follow us on Google+!"> </a></li>');
				}	
				if (qa_opt('footer_ytube')) {	
					$this->output('<li class="youtube"><a href="'.qa_opt('footer_ytube').'" target="_blank" title="Follow us on Youtube !"> </a></li>');
				}	
				if (qa_opt('footer_pin')) {	
					$this->output('<li class="pinterest"><a href="'.qa_opt('footer_pin').'" target="_blank" title="Follow us on Pinterest!"> </a></li>');
				}
				$this->output('</ul>');	
				$this->nav('footer');
				$this->attribution();			
				$this->footer_clear();

				$this->output('</div> <!-- END king-footer -->', '');
			}

			function feed()
			{
				$feed=@$this->content['feed'];

				if (!empty($feed)) {

				}
			}

			function get_prev_q(){

				$myurl=$this->request;
				$myurlpieces = explode("/", $myurl);
				$myurl=$myurlpieces[0];

				$query_p = "SELECT * 
				FROM ^posts 
				WHERE postid < $myurl
				AND type='Q'
				ORDER BY postid DESC
				LIMIT 1";

				$prev_q = qa_db_query_sub($query_p);

				while($prev_link = qa_db_read_one_assoc($prev_q, true)){

					$title = $prev_link['title'];
					$pid = $prev_link['postid'];

					$this->output('<div class="nav"><A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-prev-q '.qa_opt('button_style').'">'.qa_opt('previous_lable').'</A>');

			// echo '<A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-prev-q '.qa_opt('button_style').'">&larr; Prev Question</A>';
				}

			}


			function get_next_q(){	

				$myurl=$this->request;
				$myurlpieces = explode("/", $myurl);
				$myurl=$myurlpieces[0];


				$query_n = "SELECT * 
				FROM ^posts 
				WHERE postid > $myurl
				AND type='Q'
				ORDER BY postid ASC
				LIMIT 1";

				$next_q = qa_db_query_sub($query_n);

				while($next_link = qa_db_read_one_assoc($next_q, true)){

					$title = $next_link['title'];
					$pid = $next_link['postid'];

					$this->output('<A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-next-q '.qa_opt('button_style').'">'.qa_opt('next_lable').'</A></div>');

			//echo '<A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-next-q '.qa_opt('button_style').'">Next Question &rarr;</A>';
				}

			}		

			function message_item($message)
			{
				$this->output('<div class="king-message-item" '.@$message['tags'].'>');
				$this->post_avatar_meta($message, 'king-message');
				$this->message_content($message);
				$this->message_buttons($message);
				$this->output('</div> <!-- END king-message-item -->', '');
			}


			function nav_link($navlink, $class)
			{
				if (isset($navlink['url']))
					$this->output(
						'<a href="'.$navlink['url'].'" class="king-'.$class.'-link'.
						(@$navlink['selected'] ? (' king-'.$class.'-selected') : '').
						(@$navlink['favorited'] ? (' king-'.$class.'-favorited') : '').
						'"'.(strlen(@$navlink['popup']) ? (' title="'.$navlink['popup'].'"') : '').
						(isset($navlink['target']) ? (' target="'.$navlink['target'].'"') : '').'>'.$navlink['label'].
						'</a>'
					);

				else
					$this->output(
						'<span class="king-'.$class.'-nolink'.(@$navlink['selected'] ? (' king-'.$class.'-selected') : '').
						(@$navlink['favorited'] ? (' king-'.$class.'-favorited') : '').'"'.
						(strlen(@$navlink['popup']) ? (' title="'.$navlink['popup'].'"') : '').
						'>'.$navlink['label'].'</span>'
					);
				
				if (strlen(@$navlink['note']))
					$this->output('<span class="king-'.$class.'-note">'.$navlink['note'].'</span>');
			}

			function attribution()
			{
				$this->output('<title></title>');
				$this->output(
					'<DIV CLASS="king-attribution">',
					'2024 ©  <A HREF="/">'.$this->content['site_title'].'</A> | All rights reserved',
					'</DIV>'
				);
			}

		}

