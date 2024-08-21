<?php
	class qa_html_theme extends qa_html_theme_base
	{
		
		function html()
		{
			$this->output(
				'<html>',
				'<!-- Powered by KingMedia -->'
			);
			
			$this->head();
			$this->body();
			
			$this->output(
				'<!-- Powered by KingMedia -->',
				'</html>'
			);
		}
	
		function head()
		{
			$this->output(
				'<head>',
				'<meta http-equiv="content-type" content="'.$this->content['content_type'].'"/>'
			);
			
			$this->head_title();
			$this->head_metas();			
			$this->head_css();
			$this->head_custom_css();
			$this->output('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
			$this->head_links();
			if ( $this->template == 'question' ) {
			if (strlen(@$this->content['description'])) {
			$this->output('<meta property="og:image" content="'.$this->content['description'].'"/>');
			$this->output('<meta property="og:description" content="Click To Watch" />');
			$this->output('<meta itemprop="description" content="click to watch">');
			$this->output('<meta itemprop="image" content="'.$this->content['description'].'">');
			$this->output('<link rel="image_src" type="image/jpeg" href="'.$this->content['description'].'" />');
			}
			}
			$this->head_lines();
			$this->head_script();			
			$this->head_custom();			
			$this->output('</head>');
		}
		
		function head_metas()
		{
			if ( $this->template == 'question' ) {
			if (strlen(@$this->content['description'])) {
				$this->output('<meta name="description" content="'.$this->content['extra_description'].'..."/>');
			}
			} else {	
			if (qa_opt('show_custom_footer')) {
				$this->output('<meta name="description" content="'.qa_opt('custom_footer').'"/>');
			}
			}
			
			if (strlen(@$this->content['keywords'])) // as far as I know, meta keywords have zero effect on search rankings or listings
				$this->output('<meta name="keywords" content="'.$this->content['keywords'].'"/>');
		}
		
		function body()
		{
			$this->output('<BODY');			
			$this->body_tags();
			$this->output('>');
			
			$this->body_script();
			$this->output('<DIV CLASS="king-body">');
			$this->header();			
			$this->body_content();
			$this->body_footer();
			$this->output('</DIV>');
			$this->body_hidden();	
        	$this->fb_script();		            	
			$this->output('<script src="'.$this->rooturl.'bootstrap.js"></script>');   
			if ( $this->template == 'ask' || $this->template == 'question' ) {
				if (strlen(@$this->content['description'])) {
				} else {
			$this->output('<script src="'.$this->rooturl.'jquery.form.min.js"></script>'); 
      		$this->output('<script src="'.$this->rooturl.'imageupload.js"></script>'); 
			$this->output('<script src="'.$this->rooturl.'js/angular.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/app.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/sortable.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/jquery-ui.min.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/textAngular.min.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/textAngular-rangy.min.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/textAngular-sanitize.min.js"></script>'); 
			$this->output('<script src="'.$this->rooturl.'js/angular-file-upload-shim.min.js"></script>');
			$this->output('<script src="'.$this->rooturl.'js/angular-file-upload.min.js"></script>'); 
			$this->output('<link data-require="font-awesome@*" data-semver="4.3.0" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />');
			$this->output('<script src="'.$this->rooturl.'js/ng-videosharing-embed.min.js"></script>'); 
			} 
			}    
			if ( $this->template == 'question' ) {
			$this->output('<script src="'.$this->rooturl.'js/jquery.sticky.js"></script>');
			}
			$this->output('<script>$(function() {
			if ((window.self != window.top) && (window.frameElement.id=="idIframe")) {
			$(document.body).addClass("in-iframe");
			}
			});</script>');			
			$this->output('</BODY>');
		}	
				
		
		function body_content()
		{

			$this->body_prefix();
			$this->notices();			
	
			$this->body_header();			
			$this->output('<DIV id="king-body-wrapper">');
			$this->main();				
			$this->page_links();
            $this->output('</DIV>');
			$this->body_suffix();
			
		}
		
		function fb_script()
		{
$this->output('<div id="fb-root"></div>');
$this->output('<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=694517970611482";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>');
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
					
			if (!empty($this->content['notices']))
				$this->output(
					'<STYLE><!--',
					'.king-body-js-on .king-notice {display:none;}',
					'//--></STYLE>'
				);
		}

		function head_custom_css()
		{
		    if (qa_opt('show_home_description')) {
				$this->output('<STYLE><!--');	
				$this->output(''.qa_opt('home_description').'');
				$this->output('//--></STYLE>');
			}	
		}		
		
		function main()
		{			
            $content=$this->content;
			$q_view=@$this->content['q_view'];
			
			$this->output('<DIV CLASS="king-main'.(@$this->content['hidden'] ? ' king-main-hidden' : '').'">');
			$this->widgets('full', 'top');	
			$this->output('<DIV CLASS="king-nav-sub">');
			$this->king_cat();			
			$this->nav('sub');
			$this->title2();
			$this->output('</DIV>');			
			$this->output('<DIV CLASS="leftside">');			
			$this->widgets('main', 'top');
			$this->widgets('main', 'high');			
			$this->main_parts($content);
			if (!empty($q_view)) {
			$this->maincom();	
			}			
			$this->output('</DIV>');

			$this->widgets('main', 'low');			
			$this->suggest_next();			
			$this->widgets('main', 'bottom');
			$this->output('</DIV> <!-- END king-main -->', '');
			$this->output('<input type="checkbox" id="checkbox-menu5" class="hide">');
			$this->output('<DIV CLASS="solyan">');				
			$this->widgets('full', 'high');
			$this->sidepanel();				
			$this->output('<label class="side-menu" for="checkbox-menu5"></label>');
			$this->output('</DIV>');			
			
		}

		function viewtop()
		{
		$q_view=@$this->content['q_view'];
		$favorite=@$this->content['favorite'];	
		

				
		if ( $this->template == 'question' ) {
			$this->output('<DIV CLASS="sharetop">');
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
			$this->socialshare();			
			$this->get_prev_q();
			$this->get_next_q();
			$this->output('</DIV>');
			$this->output('</DIV>');
			$this->output('<div class="sharetop_fix"></div>');

			
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
		$this->output('<li>');
			$this->search();
		$this->output('</li>');
		}
		
			
		function header()
		{
		    $q_view=@$this->content['q_view'];

			$this->output('<input type="checkbox" id="checkbox-menu4" class="hide">');
		    $this->output('<header CLASS="king-headerf">');
			$this->output('<input type="checkbox" id="checkbox-menu2" class="hide">');	
			$this->output('<headertop class="king-header-top">');
			$this->output('<DIV CLASS="king-header">');		
			$this->logo();
			$this->output('<div class="leftmenu">');			
			$this->nav_main_sub();
			$this->output('</div>');			
		
			$this->output('<DIV CLASS="userpanel">');
			$this->output('<ul>');		
			
			if ((qa_user_maximum_permit_error('permit_post_q')!='level')) {
			$this->kingsubmit();
			}
			if (!qa_is_logged_in()) {
				$this->output('<li>');
				$this->output('<a class="reglink" href="'.qa_path_html('register').'">'.qa_lang_html('main/nav_register').'</a>');		
				$this->output('</li>');
				$this->output('<li>');
				$this->output('<label class="reglink" for="checkbox-menu2">'.qa_lang_html('main/nav_login').'</label>');	
				$this->output('</li>');				
			}			
            $this->userpanel();				

			$this->nav_user_search();
			$this->output('</ul>');
			$this->output('</DIV>');	
			
            $this->output('</DIV>');
			$this->output('<label class="modal-backdrop" for="checkbox-menu2"></label>');
            $this->userpanel2();	
			
			$this->output('</headertop>');	
			
			$this->logo2();
			$this->output('<label class="mobile-header" for="checkbox-menu4"></label>');
			
		
			$this->output('</header>');
			if ( $this->template == 'ask' ) {
			$this->imgupload();
			}						
			if (isset($this->content['error']))
				$this->error(@$this->content['error']);			

		}

		function logo2()
		{
			$this->output(
				'<div class="king-logo2">',
				$this->content['logo'],
				'</div>'
			);
		}
		
		function kingsubmit()	
		{
		$this->output('<li>');
		$this->output('<div class="king-submit">');	
		if (!qa_user_maximum_permit_error('permit_post_q')) {
		$this->output('<a href="'.qa_path_html('ask').'" class="kingaddimg">Make a Post</a>');		
		} else {	
		$this->output('<label for="checkbox-menu2" class="kingaddimg">Make a Post</label>');		
		}

		$this->output('</div>');
		$this->output('</li>');
		}
		
		function userpanel()
		{
        $handle= qa_get_logged_in_handle();
        $user=qa_db_select_with_pending(
        qa_db_user_account_selectspec($handle, false)
        );		
		if (qa_is_logged_in()) {
		$this->output('<li>');
		$this->output('<div class="king-userp">');
		$this->output('<div class="king-havatar">');
		$this->output(''.qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_message_list_size'), true).'');
		$this->output('</div>');
		$this->output('<DIV CLASS="usrname">');             
		$this->logged_in();
		$this->output('</DIV>');

		$this->output('</div>');
		$this->output('<div class="king-dropdown">');
		$this->output('<div class="arrow"></div>');		
		$this->nav('user');
		$this->output('</div>');
		$this->output('</li>');
		} 	
		} 	
		
		function nav_main_sub()
		{
			$this->output('<DIV CLASS="king-nav-main">');	
			$this->nav('main');
			$this->output('</DIV>');
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
		
		function imgupload()
		{
		
		if (!qa_user_maximum_permit_error('permit_close_q')) {
			$this->output('<input type="checkbox" id="checkbox-menu3" class="hide">');			
			$this->output('<div class="imgupload">');			
			$this->output('<div class="img-dialog">');
			$this->output('<form action="'.qa_opt('site_url').'king-include/processupload.php" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">');
			$this->output('<input name="ImageFile" id="imageInput" type="file" />');
			$this->output('<img id="inputprev" src="'.$this->rooturl.'img/dote.png" />');
			$this->output('<input type="submit"  id="submit-btn" value="Upload" />');
			$this->output('<img src="'.$this->rooturl.'img/loader.svg" id="loading-img" style="display:none;" alt="Please Wait"/>');
			$this->output('</form>');
			$this->output('<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>');
			$this->output('<div id="output"></div>');
            $this->output('</div>');
            $this->output('<label class="modal-backdrop" for="checkbox-menu3"></label>');			
			$this->output('</div>');
		} else {
			$this->output('<input type="checkbox" id="checkbox-menu3" class="hide">');			
			$this->output('<div class="imgupload">');			
			$this->output('<div class="img-dialog">');
			$this->output('<div class="nopermission">');
			$this->output('<span>'.qa_lang_html('users/no_permission').'</span>');
			if (!qa_is_logged_in()) {
			$this->output('<a class="reglink" href="'.qa_path_html('login').'">'.qa_lang_html('main/nav_login').'</a>');
			$this->output('<a class="reglink" href="'.qa_path_html('register').'">'.qa_lang_html('main/nav_register').'</a>');
			}			
			$this->output('</div>');
            $this->output('</div>');
            $this->output('<label class="modal-backdrop" for="checkbox-menu3"></label>');			
			$this->output('</div>');
		}
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
		
		
		function king_userpanel()
		{

		    $this->output('<div class="king-userpanel">');
			$this->output('<ul>');
			if (!qa_is_logged_in()) {
			$this->output('<li><a class="king-user-login" href="'.qa_path_html('login').'">'.qa_lang_html('main/nav_login').'</a></li>');
			$this->output('<li><a class="king-user-reg" href="'.qa_path_html('register').'">'.qa_lang_html('main/nav_register').'</a></li>');
			} else {
			$this->output('<li><a class="king-user-logout" href="'.qa_path_html('logout').'">'.qa_lang_html('main/nav_logout').'</a></li>');
			}
			$this->output('</ul>');			
			$this->output('</div>');

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
			$this->output('<script src="'.$this->rooturl.'jquery.magnific-popup.min.js"></script>');
			$this->output('<script type="text/javascript">
      $(document).ready(function() {
	          $(\'.popup\').magnificPopup({
          disableOn: 700,
          type: \'iframe\',
          mainClass: \'mfp-fade\',
          removalDelay: 160,
          preloader: true,
		  alignTop: true,
          fixedContentPos: true,
		  showCloseBtn: true,
		  callbacks: {
        open: function() {	
		}	  
          }
        });
	  $(document).ajaxStop(function() {
        $(\'.popup\').magnificPopup({
          disableOn: 700,
          type: \'iframe\',
          mainClass: \'mfp-fade\',
          removalDelay: 160,
          preloader: true,
		  alignTop: true,
          fixedContentPos: true,
		  showCloseBtn: true,
		  callbacks: {
        open: function() {
		}	  
          }
        });
		 }); 
      });  
	  
    </script>');
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
			$this->output('<div class="box king-q-list-item'.rtrim(' '.@$q_item['classes']).'" '.@$q_item['tags'].'>');
			$this->q_item_main($q_item);
			$this->output('</div>');
		}
		
		function q_item_stats($q_item)
		{
			$this->output('<DIV CLASS="king-q-item-stats">');
			
			$this->voting($q_item);


			$this->output('</DIV>');
		}
		
		function q_item_main($q_item)
		{
		    $this->output('<div class="king-q-item-main">');					
			$this->q_item_content($q_item);		
			$this->socialshare2($q_item);			
			$this->output('<DIV CLASS="yoriz">');
			$this->post_meta_where($q_item, 'metah');
			$this->post_avatar_meta($q_item, 'king-q-item');		
			$this->q_item_title($q_item);
			$this->extra_main($q_item);
			$this->output('<DIV CLASS="yorizbottom">');
            $this->a_count($q_item);			
			$this->view_count($q_item);
			$this->voting2($q_item);
            $this->q_item_buttons($q_item);	
            $this->output('</DIV>');			
			$this->output('</DIV>');
			$this->output('</div>');
		}
		
		function extra_main($q_item)
		{
		require_once QA_INCLUDE_DIR.'king-db-metas.php';
		$extrac = qa_db_postmeta_get($q_item['raw']['postid'], 'qa_q_extra');
		$pid = $q_item['raw']['postid'];
		$maxlength = '140';
		$result = qa_db_query_sub('SELECT postid, LEFT(content, #) content, format FROM ^posts WHERE postid IN (#)', $maxlength + 1, $pid);
		$postinfo = qa_db_read_all_assoc($result, 'postid');
		$blockwordspreg = qa_get_block_words_preg();
		
		if (isset($postinfo[$q_item['raw']['postid']])) {
			$thispost = $postinfo[$q_item['raw']['postid']];
			$extrac = qa_viewer_text($extrac, $thispost['format'], array('blockwordspreg' => $blockwordspreg));
			$extrac = substr($extrac, 0, $maxlength);
			$search = array('#\<(.*?)\>#', '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/(.*?)>/', '@((www|https://)[^ ]+)@', '#\<(.*?)#');
			$replace = array('');	
			$extrac2 = preg_replace($search, $replace, $extrac);
			
			$this->output('<span class="extra_on_list">'.$extrac2.'...</span>');
		}
		
		}
		
		function socialshare2($q_item)
		{
	
		$pid = $q_item['raw']['postid'];
		$query = qa_db_query_sub("SELECT postid, content, format FROM ^posts WHERE postid = $pid");
		$cont = qa_db_read_one_assoc($query);
		$text=qa_viewer_text($cont['content'], $cont['format']);
		$url2 = qa_path_html(qa_q_request($q_item['raw']['postid'], $q_item['raw']['title']), null, qa_opt('site_url'));
           	$this->output('<ul class="socialshare" data-url="'.$url2.'">');
			$this->output('<li class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u='.$url2.'" target="_blank" title="Share on Facebook" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><span class="fa fa-facebook" data-href="'.$url2.'"></span></a></li>');
			$this->output('<li class="twitter" data-share-text=""><a href="https://twitter.com/intent/tweet?url='.$url2.'" data-url="'.$url2.'" title="Share on Twitter" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"><span class="fa fa-twitter" data-url="'.$url2.'"></span></a></li>');
			$this->output('<li class="googleplus"><a href="https://plus.google.com/share?url='.$url2.'" target="_blank" title="Share on Google Plus" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"></a></li>');
			$this->output('<li class="pinterest"><a href="//www.pinterest.com/pin/create/button/?url='.$url2.'&amp;media='.$text.'&amp;description=" title="Pin It" target="_blank" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"></a></li>');												
			$this->output('</ul>');
			$this->output('<a class="popup qview" href="'.$url2.'" ></a>');
	    }			
		
		function voting2($post)
		{
			if (isset($post['vote_view'])) {				
				$this->vote_count($post);
			}
		}		
    
		function q_item_content($q_item)
		{		
		$pid = $q_item['raw']['postid'];
		$query = qa_db_query_sub("SELECT postid, content, format FROM ^posts WHERE postid = $pid");
		$cont = qa_db_read_one_assoc($query);
		$text=qa_viewer_text($cont['content'], $cont['format']);
				
		if (!empty($text)) {
			$this->output('<A class="boxa" HREF="'.$q_item['url'].'" style="background-image:url('.$text.');">');
			$this->output('<span class="king-hover"></span>');
			$this->output('</A>');
		}	
        else {
			$this->output('<a href="'.$q_item['url'].'" class="king-nothumb"></a>');
		}			

		}
		
		function q_item_title($q_item)
		{
			$this->output(
				'<DIV CLASS="king-q-item-title">',
				'<A HREF="'.$q_item['url'].'">'.$q_item['title'].'</A>',
				'</DIV>'
			);
			
		}
		

		
		function sidepanel()
		{
			$this->output('<div class="king-sidepanel">');
			$this->widgets('side', 'top');
			$this->sidebar();
			$this->widgets('side', 'high');
			$this->nav('cat', 1);
			$this->widgets('side', 'low');
			$this->output_raw(@$this->content['sidepanel']);
			$this->feed();
			$this->widgets('side', 'bottom');
			$this->footer();			
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
			if (!empty($q_view['extra'])) {
			$cextra = $q_view['extra']['content'];
			} else {
			$cextra = '';
			}
			$search = array("\\n",  "\x00", "\\",  "\r",  "'",  '"', "\x1a");
			$replace = array("", "", "", "", "", '', "");
			$cextra2 = str_replace($search, $replace, $cextra);


				$this->output_raw($cextra2 = $this->embed_replace($cextra2));

				
		}			

		function embed_replace($text) {
			
			$w  = '600px';
			
			$h = '338px';
			
			$w2 = '100%';
			
			$h2 = 'auto';
					
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

				'facebook'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*facebook\.com\/video\.php\?\S*v=([A-Za-z0-9_-]+)[^< ]*',						
						'<div class="fb-video" data-allowfullscreen="true" data-href="https://www.facebook.com/video.php?v=$1&type=1"></div>'
					)
				),
				
				'soundcloud'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*soundcloud\.com\/([-\%_\/.a-zA-Z0-9]+\/[-\%_\/.a-zA-Z0-9]+)[^< ]*',
						'<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://soundcloud.com/$1&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>'
					)
				),				
				
				'image'=>array(
					array(
						'(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(png|jpg|jpeg|gif|bmp))[^< ]*',
						'<img src="$1" />'
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
									$page_for_hash = preg_replace("/\\\/","",$res);
									if (preg_match("@,\"hash2\":\"([\w\d]*)\",@",$page_for_hash,$matches)) {
									$result["vk_hash"] = $matches[1];	
									}
								
								$replace1 = preg_replace('|_|i','&id=',$img);
								$text = '<iframe src="//vk.com/video_ext.php?oid='.$replace1.'&hash='.$result["vk_hash"].'&hd=2" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe>';								
							
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

		function title2()
		{
		if (( $this->template == 'tag' ) || ( $this->template == 'tags' ) || ( $this->template == 'search' ) || ( $this->template == 'qa' ) || ( $this->template == 'ask' ) || ( $this->template == 'hot' ) ) {
		    $this->output('<DIV CLASS="title2">');	
			$this->title();
			$this->output('</DIV>');
		}	
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
			$this->output('<DIV CLASS="kim-avatar">');
			$this->post_avatar($q_view, 'king-q-view');
			$this->output('</DIV>');
			$this->post_meta_who($q_view, 'meta');
			$this->output('</DIV>');		
		}
		
		function q_view($q_view)
		{

				
			if (!empty($q_view)) {
				$this->output('<DIV CLASS="king-q-view'.(@$q_view['hidden'] ? ' king-q-view-hidden' : '').rtrim(' '.@$q_view['classes']).'"'.rtrim(' '.@$q_view['tags']).'>');
				if (strlen(@$this->content['description'])) {
				$this->viewtop();
				$this->output('<DIV CLASS="share_mobile">');
				$this->socialshare();	
				$this->output('</DIV>');				
				}			
				$this->q_view_content($q_view);						
				$this->a_count($q_view);
				$this->q_view_main($q_view);
				$this->q_view_clear();
				$this->post_tags($q_view, 'king-q-view');				
				$this->output('<DIV CLASS="rightview">');
			    $this->kim($q_view);
				$this->view_count($q_view);
				$this->post_meta_when($q_view, 'meta');
		if (isset($q_view['main_form_tags']))
			$this->output('<FORM '.$q_view['main_form_tags'].'>');
			$this->q_view_buttons($q_view);	
	    if (isset($q_view['main_form_tags'])) {
			$this->form_hidden_elements(@$q_view['buttons_form_hidden']);
			$this->output('</FORM>');
		}				
				$this->output('</DIV>');
				$this->output('</DIV> <!-- END king-q-view -->', '');
			}
		}	

		function q_view_content($q_view)
		{
			if (!empty($q_view['content'])) {
				$cover = $this->content['description'];
				$cover = preg_replace('/thumb_/', '', $cover);
				$this->output('<div class="king-q-view-cover" style="background-image:url(\''.$cover.'\'); ">');
				$this->page_title_error();				
				$this->output('</div>');
			}
		}		
		
		function socialshare()
		{	
			
			$this->output('<div class="share-overlay twitter-ready fb-ready">');
			$this->output('<a href="https://twitter.com/share?url='.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).'&amp;text=&amp;related=vineapp">');
			$this->output('<div class="share-badge twitter">');
			$this->output('<div class="icon"></div><p>0</p>');
			$this->output('</div></a>');
			$this->output('<a  href="#" onclick="popUp=window.open(https://www.facebook.com/sharer.php?u='.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).', popupwindow, scrollbars=yes,width=800,height=400);popUp.focus();return false">');
			$this->output('<div class="share-badge facebook">');
			$this->output('<div class="icon"></div><p>0</p>');
			$this->output('</div></a>');
			$this->output('<div class="share-badge googleshare"><a href="https://plus.google.com/share?url='.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).'" target="_blank" title="Share on Google Plus" onclick="javascript:window.open(this.href, \'_blank\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"></a></div>');
			if (strlen(@$this->content['description'])) {
			$this->output('<div class="share-badge pinshare"><a href="//www.pinterest.com/pin/create/button/?url='.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).'&amp;media='.$this->content['description'].'&amp;description=" title="Pin It" target="_blank" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600\');return false;"></a></div>');
			}
			$this->output('</div>');			

            $this->output('<script type="text/javascript">    
  var pageUrl = "'.qa_path_html(qa_q_request($this->content['q_view']['raw']['postid'], $this->content['q_view']['raw']['title']), null, qa_opt('site_url')).'";
      function formatCount(count) {
        var countK = count / 1000;
        if (countK < 1) {
          return count;
        } else if (countK < 10) {
          var countStr = count + "";
          return [countStr.substr(0, 1), countStr.substr(1, 3)].join(",");
        } else {
          return (Math.floor(countK * 10) / 10) + "K";
        }
      }

      $.ajax({
        url: "https://graph.facebook.com/?ids=" + pageUrl,
        success: function(data) {
          if (data && data[pageUrl] && data[pageUrl].shares) {
            $(".share-badge.facebook p").html(formatCount(data[pageUrl].shares));
          }
          $(".share-overlay").addClass("fb-ready");
        },
        error: function() { $(".share-overlay").addClass("fb-ready"); }
      });
      $.ajax({
        url: "https://cdn.api.twitter.com/1/urls/count.json?url=" + pageUrl,
        dataType: "jsonp",
        success: function(data) {
          if (data && data.count) {
            $(".share-badge.twitter p").html(formatCount(data.count));
          }
          $(".share-overlay").addClass("twitter-ready");
        },
        error: function() { $(".share-overlay").addClass("twitter-ready"); }
      });

      var $tweetButton = $(".share-badge.twitter").closest("a");
      var $fbShareButton = $(".share-badge.facebook").closest("a");

      var description = "";
      if (description.length > 116) {
        description = description.substr(0, 115).trim() + "\u2026";
      }
      var tweetOptions = {
        url: pageUrl,
        text: description,
        related: "vineapp"
      };
      $tweetButton.attr("href", "https://twitter.com/share?" + $.param(tweetOptions));
      $tweetButton.click(function(event) {
        event.preventDefault();
        var width = 550;
        var height = 380;
        var tweetWindow = window.open(this.href, "tweetWindow", "width=" + width + ",height=" + height);
        tweetWindow.moveTo((screen.width - width) / 2,(screen.height - height) / 2);
      });

      $fbShareButton.attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(pageUrl));
      $fbShareButton.click(function(event) {
        event.preventDefault();
        var width = 670;
        var height = 350;
        var fbWindow = window.open(this.href, "tweetWindow", "width=" + width + ",height=" + height);
        fbWindow.moveTo((screen.width - width) / 2,(screen.height - height) / 2);
      });
</script>');		
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

		function vote_count($post)
		{
			// You can also use $post['upvotes_raw'], $post['downvotes_raw'], $post['netvotes_raw'] to get
			// raw integer vote counts, for graphing or showing in other non-textual ways
			
			$this->output('<div class="king-vote-count '.(($post['vote_view']=='updown') ? 'king-vote-count-updown' : 'king-vote-count-net').'"'.@$post['vote_count_tags'].'>');

				$this->output_split($post['netvotes_view'], 'king-netvote-count');

			$this->output('</div>');
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

			$this->nav('footer');
			$this->attribution();
			$this->output('<ul class="socialicons">                
                <li class="facebook"><a href="#" target="_blank" title="Join our Facebook page!"> </a></li>
                <li class="twitter"><a href="#" target="_blank" title="Follow us on Twitter!"> </a></li>
				<li class="googleplus"><a href="#" target="_blank" title="Follow us on Google+!"> </a></li>
				<li class="youtube"><a href="#" target="_blank" title="Follow us on Youtube !"> </a></li>
				<li class="pinterest"><a href="#" target="_blank" title="Follow us on Pinterest!"> </a></li>
            </ul>');			
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
			
			$this->output('<A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-prev-q '.qa_opt('button_style').'">'.qa_opt('previous_lable').'</A>');

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

			$this->output('<A HREF="'. qa_q_path_html($pid, $title) .'" title="'. $title .'" CLASS="king-next-q '.qa_opt('button_style').'">'.qa_opt('next_lable').'</A>');
			
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

	function waiting_template()
	{
     	$this->output('<span id="king-waiting-template" class="king-waiting"></span>');
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
				'2013 ©  <A HREF="/">'.$this->content['site_title'].'</A> | All rights reserved',
				'</DIV>'
		);
	}
	
}
	
