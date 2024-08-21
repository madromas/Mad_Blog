<?php

	class qa_tag_cloud {
	
	function option_default($option)
		{
			if ($option=='tag_cloud_count_tags')
				return 100;
			elseif ($option=='tag_cloud_font_size')
				return 24;
			elseif ($option=='tag_cloud_size_popular')
				return true;
		}

		
		function admin_form()
		{
			$saved=false;
			
			if (qa_clicked('tag_cloud_save_button')) {
				qa_opt('tag_cloud_count_tags', (int)qa_post_text('tag_cloud_count_tags_field'));
				qa_opt('tag_cloud_font_size', (int)qa_post_text('tag_cloud_font_size_field'));
				qa_opt('tag_cloud_size_popular', (int)qa_post_text('tag_cloud_size_popular_field'));
				$saved=true;
			}
			
			return array(
				'ok' => $saved ? 'Tag cloud settings saved' : null,
				
				'fields' => array(
					array(
						'label' => 'Maximum tags to show:',
						'type' => 'number',
						'value' => (int)qa_opt('tag_cloud_count_tags'),
						'suffix' => 'tags',
						'tags' => 'name="tag_cloud_count_tags_field"',
					),

				),
				
				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'name="tag_cloud_save_button"',
					),
				),
			);
		}
		
		function allow_template($template)
		{
			$allow=false;
			
			switch ($template)
			{
				case 'activity':
				case 'qa':
				case 'questions':
				case 'hot':
				case 'ask':
				case 'categories':
				case 'question':
				case 'tag':
				case 'tags':
				case 'unanswered':
				case 'user':
				case 'users':
				case 'search':
				case 'admin':
				case 'custom':
					$allow=true;
					break;
			}
			
			return $allow;
		}

		
		function allow_region($region)
		{
			return ($region=='side');
		}
		

		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			require_once QA_INCLUDE_DIR.'king-db-selects.php';
			
			$populartags=qa_db_single_select(qa_db_popular_tags_selectspec(0, (int)qa_opt('tag_cloud_count_tags')));
			
			reset($populartags);
			$maxcount=current($populartags);
			$themeobject->output('<DIV CLASS="headerb">');
			$themeobject->output('<div class="tagcloud">');
			$themeobject->output(
				'<h2 style="margin-top:0; padding-top:0;">',
				qa_lang_html('main/popular_tags'),
				'</h2>'
			);
			
			
			
			$maxsize=qa_opt('tag_cloud_font_size');
			$scale=qa_opt('tag_cloud_size_popular');
			
			foreach ($populartags as $tag => $count) {
				$size=number_format(($scale ? ($maxsize*$count/$maxcount) : $maxsize), 1);
				
				if (($size>=5) || !$scale)
					$themeobject->output('<a href="'.qa_path_html('tag/'.$tag).'" >'.qa_html($tag).'</a>');
			}
			
			$themeobject->output('</div>');
			$themeobject->output('</div>');
		}
	
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/