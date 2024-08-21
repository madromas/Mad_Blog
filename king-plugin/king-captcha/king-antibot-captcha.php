<?php

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}


	class qa_antibot_captcha {
	
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}
		
		function option_default($option)
		{
			if ($option=='antibotcaptcha_count')
				return 4;
			if ($option=='antibotcaptcha_charset')
				return 23456789;
			
		}

		function admin_form()
		{
			$saved=false;
			
			if (qa_clicked('antibotcaptcha_save_button')) {
				qa_opt('antibotcaptcha_count', qa_post_text('antibotcaptcha_count_field'));
				qa_opt('antibotcaptcha_charset', qa_post_text('antibotcaptcha_charset_field'));
				
				$saved=true;
			}
			
			$form=array(
				'ok' => $saved ? 'AntiBot Captcha settings saved' : null,
				
				'fields' => array(
					'count' => array(
						'label' => 'Symbol count:',
						'value' => qa_opt('antibotcaptcha_count'),
						'tags' => 'NAME="antibotcaptcha_count_field"',
						'type' => 'number',
						// 'error' => $this->antibotcaptcha_error(),
					),
					
					'charset' => array(
						'label' => 'Character set:',
						'value' => qa_opt('antibotcaptcha_charset'),
						'tags' => 'NAME="antibotcaptcha_charset_field"',
					),
					
				),

				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'NAME="antibotcaptcha_save_button"',
					),
				),
			);
			
			return $form;
		}
	
		function allow_captcha()
		{
			return true;
		}

		
		function form_html(&$qa_content, $error)
		{
			require_once $this->directory.'AntiBotCaptcha.php';
			$secimg = new AntiBotCaptcha($this->urltoroot);
			$secimg->setCount(qa_opt('antibotcaptcha_count'));
			$secimg->setCharset(qa_opt('antibotcaptcha_charset'));
			return $secimg->qa_captcha_html(qa_lang_html('misc/captcha_error'));
		}


		function validate_post(&$error)
		{
			if ($this->allow_captcha())
			{
				require_once $this->directory.'AntiBotCaptcha.php';
				
				$answer=captcha_check_answer();
				
				if ($answer->is_valid)
					return true;

				$error=@$answer->error;
			}
			
			return false;
		}
	
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/