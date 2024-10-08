<?php
if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}
require_once QA_INCLUDE_DIR.'king-theme-base.php';
require_once QA_INCLUDE_DIR.'king-app-blobs.php';
require_once QA_PLUGIN_DIR.'king-extra/king-eqf.php';

class qa_html_theme_layer extends qa_html_theme_base {

	private $extradata;
	private $pluginurl;
	
	function doctype() {
		qa_html_theme_base::doctype();
		$this->pluginurl = qa_opt('site_url').'king-plugin/king-extra/';
		if($this->template == 'question') {
			if(isset($this->content['q_view']['raw']['postid']))
				$this->extradata = $this->qa_eqf_get_extradata($this->content['q_view']['raw']['postid']);
		}
	}


	function main() {
		if(($this->template == 'ask') || ($this->template == 'video')) {
			if(isset($this->content['form']['fields']))
				$this->qa_eqf_add_field(null, $this->content['form']['fields'], $this->content['form']);
		} else if(isset($this->content['form_q_edit']['fields'])) {
				$this->qa_eqf_add_field($this->content['q_view']['raw']['postid'], $this->content['form_q_edit']['fields'], $this->content['form_q_edit']);
		}
		qa_html_theme_base::main();
	}
	function page_title_error() {
	qa_html_theme_base::page_title_error();
		if(!isset($this->content['form_q_edit'])) {
			$this->qa_eqf_output($q_view, qa_eqf::FIELD_PAGE_POS_BELOW);
			$this->qa_eqf_clearhook($q_view);
		}
		
	}
	
	function qa_eqf_add_field($postid, &$fields, &$form) {
		global $qa_extra_question_fields;
		$multipart = false;
		for($key=qa_eqf::FIELD_COUNT_MAX; $key>=1; $key--) {
			if((bool)qa_opt(qa_eqf::FIELD_ACTIVE.$key)) {
				$field = array();
				$name = qa_eqf::FIELD_BASE_NAME.$key;
				$field['label'] = qa_opt(qa_eqf::FIELD_PROMPT.$key);
				$type = qa_opt(qa_eqf::FIELD_TYPE.$key);
				switch ($type) {
				case qa_eqf::FIELD_TYPE_FILE:
					$field['type'] = 'custom';
					$value = qa_db_single_select(qa_db_post_meta_selectspec($postid, 'qa_q_'.$name));
					$original = '';
					if(!empty($value)) {
						$blob = qa_read_blob($value);
						$format = $blob['format'];
						$bloburl = qa_get_blob_url($value);
						$imageurl = str_replace('qa=blob', 'qa=image', $bloburl);
						$filename = $blob['filename'];
						$original = $filename;
						$width = $this->qa_eqf_get_image_width($blob['content']);
						if($width > qa_opt(qa_eqf::THUMB_SIZE))
							$width = qa_opt(qa_eqf::THUMB_SIZE);
						if($format == 'jpg' || $format == 'jpeg' || $format == 'webp' || $format == 'png' || $format == 'gif')
							$original = '<IMG SRC="'.$imageurl.'&qa_size='.$width.'" ALT="'.$filename.'" ID="'.$name.'-thumb" CLASS="'.qa_eqf::FIELD_BASE_NAME.'-thumb"/>';
						$original = '<A HREF="'.$imageurl.'" TARGET="_blank" ID="'.$name.'-link" CLASS="'.qa_eqf::FIELD_BASE_NAME.'-link">' . $original . '</A>';
						$original .= '<INPUT TYPE="checkbox" NAME="'.$name.'-remove" id="'.$name.'-remove" CLASS="'.qa_eqf::FIELD_BASE_NAME.'-remove"/><label for="'.$name.'-remove">'.qa_lang(qa_eqf::PLUGIN.'/extra_field_remove').'</label><br>';
						$original .= '<INPUT TYPE="hidden" NAME="'.$name.'-old" id="'.$name.'-old" value="'.$value.'"/>';
					}
					$field['html'] = $original.'<INPUT TYPE="file" CLASS="king-form-tall-'.$type.'" NAME="'.$name.'">';
					$multipart = true;
					break;
				default:
					$field['type'] = qa_opt(qa_eqf::FIELD_TYPE.$key);
					$field['tags'] = 'NAME="'.$name.'"';
					$options = $this->qa_eqf_options(qa_opt(qa_eqf::FIELD_OPTION.$key));
					if (qa_opt(qa_eqf::FIELD_ATTR.$key) != '')
						$field['tags'] .= ' '.qa_opt(qa_eqf::FIELD_ATTR.$key);
					if ($field['type'] != qa_eqf::FIELD_TYPE_TEXT && $field['type'] != qa_eqf::FIELD_TYPE_TEXTAREA)
						$field['options'] = $options;
					if(is_null($postid))
						$field['value'] = qa_opt(qa_eqf::FIELD_DEFAULT.$key);
					else
						$field['value'] = qa_db_single_select(qa_db_post_meta_selectspec($postid, 'qa_q_'.$name));
					if ($field['type'] != qa_eqf::FIELD_TYPE_TEXT && $field['type'] != qa_eqf::FIELD_TYPE_TEXTAREA && is_array($field['options'])) {
						if($field['type'] == qa_eqf::FIELD_TYPE_CHECK) {
							if($field['value'] == 0)
								$field['value'] = '';
						} else
							$field['value'] = @$field['options'][$field['value']];
					}
					if ($field['type'] == qa_eqf::FIELD_TYPE_TEXTAREA) {
						if(isset($options[0]))
							$field['rows'] = $options[0];
						if(empty($field['rows']))
							$field['rows'] = qa_eqf::FIELD_OPTION_ROWS_DFL;
					}
					break;
				}
				$field['note'] = nl2br(qa_opt(qa_eqf::FIELD_NOTE.$key));
				if(isset($qa_extra_question_fields[$name]['error']))
					$field['error'] = $qa_extra_question_fields[$name]['error'];
				$this->qa_eqf_insert_array($fields, $field, $name, qa_opt(qa_eqf::FIELD_FORM_POS.$key));
			}
		}
		if($multipart) {
			$form['tags'] .= ' enctype="multipart/form-data"';
		}
	}
	function qa_eqf_insert_array(&$items, $insertitem, $insertkey, $findkey) {
		$newitems = array();
		if($findkey == qa_eqf::FIELD_FORM_POS_TOP) {
			$newitems[$insertkey] = $insertitem;
			foreach($items as $key => $item)
				$newitems[$key] = $item;
		} elseif($findkey == qa_eqf::FIELD_FORM_POS_BOTTOM) {
			foreach($items as $key => $item)
				$newitems[$key] = $item;
			$newitems[$insertkey] = $insertitem;
		} else {
			if(!array_key_exists($findkey, $items))
				$findkey = qa_eqf::FIELD_FORM_POS_DFL;
			foreach($items as $key => $item) {
				$newitems[$key] = $item;
				if($key == $findkey)
					$newitems[$insertkey] = $insertitem;
			}
		}
		$items = $newitems;
	}
	function qa_eqf_options($optionstr) {
		if(stripos($optionstr, '@EVAL') !== false)
			$optionstr = eval(str_ireplace('@EVAL', '', $optionstr));
		if(stripos($optionstr, '||') !== false)
			$items = explode('||',$optionstr);
		else
			$items = array($optionstr);
		$options = array();
		foreach($items as $item) {
			if(strstr($item,'==')) {
				$nameval = explode('==',$item);
				$options[$nameval[1]] = $nameval[0];
			} else
				$options[$item] = $item;
		}
		return $options;
	}
	function qa_eqf_output(&$q_view, $position) {
		$output = '';
		$isoutput = false;
		foreach($this->extradata as $key => $item) {
			if($item['position'] == $position) {
				$name = $item['name'];
				$type = $item['type'];
				$value = $item['value'];
				
				if ($type == qa_eqf::FIELD_TYPE_TEXTAREA)
					$value = nl2br($value);
				else if ($type == qa_eqf::FIELD_TYPE_CHECK)
					if ($value == '')
						$value = 0;
				if ($type != qa_eqf::FIELD_TYPE_TEXT && $type != qa_eqf::FIELD_TYPE_TEXTAREA && $type != qa_eqf::FIELD_TYPE_FILE) {
					$options = $this->qa_eqf_options(qa_opt(qa_eqf::FIELD_OPTION.$key));
					if(is_array($options))
						$value = @$options[$value];
				}
				
				if($value == '' && qa_opt(qa_eqf::FIELD_HIDE_BLANK.$key))
					continue;
				
				switch ($position) {
				case qa_eqf::FIELD_PAGE_POS_UPPER:
					$outerclass = 'king-q-view-extra-upper king-q-view-extra-upper'.$key;
					$innertclass = 'king-q-view-extra-upper-title king-q-view-extra-upper-title'.$key;
					$innervclass = 'king-q-view-extra-upper-content king-q-view-extra-upper-content'.$key;
					$inneraclass = 'king-q-view-extra-upper-link king-q-view-extra-upper-link'.$key;
					$innericlass = 'king-q-view-extra-upper-img king-q-view-extra-upper-img'.$key;
					break;
				case qa_eqf::FIELD_PAGE_POS_INSIDE:
					$outerclass = 'king-q-view-extra-inside king-q-view-extra-inside'.$key;
					$innertclass = 'king-q-view-extra-inside-title king-q-view-extra-inside-title'.$key;
					$innervclass = 'king-q-view-extra-inside-content king-q-view-extra-inside-content'.$key;
					$inneraclass = 'king-q-view-extra-inside-link king-q-view-extra-inside-link'.$key;
					$innericlass = 'king-q-view-extra-inside-img king-q-view-extra-inside-img'.$key;
					break;
				case qa_eqf::FIELD_PAGE_POS_BELOW:
					$outerclass = 'king-q-view-extra king-q-view-extra'.$key;
					$innertclass = 'king-q-view-extra-title king-q-view-extra-title'.$key;
					$innervclass = 'king-q-view-extra-content king-q-view-extra-content'.$key;
					$inneraclass = 'king-q-view-extra-link king-q-view-extra-link'.$key;
					$innericlass = 'king-q-view-extra-img king-q-view-extra-img'.$key;
					break;
				}
				$title = qa_opt(qa_eqf::FIELD_LABEL.$key);
				if ($type == qa_eqf::FIELD_TYPE_FILE && $value != '') {
					if(qa_blob_exists($value)) {
						$blob = qa_read_blob($value);
						$format = $blob['format'];
						$bloburl = qa_get_blob_url($value);
						$imageurl = str_replace('qa=blob', 'qa=image', $bloburl);
						$filename = $blob['filename'];
						$width = $this->qa_eqf_get_image_width($blob['content']);
						if($width > qa_opt(qa_eqf::THUMB_SIZE))
							$width = qa_opt(qa_eqf::THUMB_SIZE);
						$value = $filename;
						if($format == 'jpg' || $format == 'jpeg' || $format == 'webp' || $format == 'png' || $format == 'gif') {
							$value = '<IMG SRC="'.$imageurl.'&qa_size='.$width.'" ALT="'.$filename.'" TARGET="_blank"/>';
							$value = '<A HREF="'.$imageurl.'" CLASS="'.$inneraclass.' '.$innericlass.'" TITLE="'.$title.'">' . $value . '</A>';
						} else
							$value = '<A HREF="'.$bloburl.'" CLASS="'.$inneraclass.'" TITLE="'.$title.'">' . $value . '</A>';
					} else
						$value = '';
				}
				$output .= '<DIV CLASS="'.$outerclass.'">';
				$output .= '<DIV CLASS="'.$innertclass.'">'.$title.'</DIV>';
				$output .= '<DIV CLASS="'.$innervclass.'">'.$value.'</DIV>';
				$output .= '</DIV>';
				
				if(qa_opt(qa_eqf::FIELD_PAGE_POS.$key) != qa_eqf::FIELD_PAGE_POS_INSIDE)
					$this->output($output);
				else {
					if(isset($q_view['content'])) {
						$hook = str_replace('^', $key, qa_eqf::FIELD_PAGE_POS_HOOK);
						$q_view['content'] = str_replace($hook, $output, $q_view['content']);
					}
				}
				$isoutput = true;
			}
			$output = '';
		}
		if($isoutput)
			$this->output('<DIV style="clear:both;"></DIV>');
	}
	function qa_eqf_get_extradata($postid) {
		$extradata = array();
		for($key=1; $key<=qa_eqf::FIELD_COUNT_MAX; $key++) {
			if((bool)qa_opt(qa_eqf::FIELD_ACTIVE.$key) && (bool)qa_opt(qa_eqf::FIELD_DISPLAY.$key)) {
				$name = qa_eqf::FIELD_BASE_NAME.$key;
				$value = qa_db_single_select(qa_db_post_meta_selectspec($postid, 'qa_q_'.$name));
				if($value == '' && qa_opt(qa_eqf::FIELD_HIDE_BLANK.$key))
					continue;
				$extradata[$key] = array(
					'name'=>$name,
					'type'=>qa_opt(qa_eqf::FIELD_TYPE.$key),
					'position'=>qa_opt(qa_eqf::FIELD_PAGE_POS.$key),
					'value'=>$value,
				);
			}
		}
		return $extradata;
	}
	function qa_eqf_file_exist() {
		$fileexist = false;
		foreach($this->extradata as $key => $item) {
			if ($item['type'] == qa_eqf::FIELD_TYPE_FILE)
				$fileexist = true;
		}
		return $fileexist;
	}
	function qa_eqf_clearhook(&$q_view) {
		for($key=1; $key<=qa_eqf::FIELD_COUNT_MAX; $key++) {
			if(isset($q_view['content'])) {
				$hook = str_replace('^', $key, qa_eqf::FIELD_PAGE_POS_HOOK);
				$q_view['content'] = str_replace($hook, '', $q_view['content']);
			}
		}
	}
	function qa_eqf_get_image_width($content) {
		$image=@imagecreatefromstring($content);
		if (is_resource($image))
			return imagesx($image);
		else
			return null;
	}
}
/*
	Omit PHP closing tag to help avoid accidental output
*/