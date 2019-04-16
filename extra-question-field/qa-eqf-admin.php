<?php
/*
	Blog Post by Jackson Siro
	https://www.github.com/Jacksiro/Q2A-Blog-Post-Plugin

	Description: Blog Post Plugin Admin pages manager

*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../../');
	exit;
}

	require_once QA_INCLUDE_DIR . 'db/admin.php';
	require_once QA_INCLUDE_DIR . 'db/maxima.php';
	require_once QA_INCLUDE_DIR . 'db/selects.php';
	require_once QA_INCLUDE_DIR . 'app/options.php';
	require_once QA_INCLUDE_DIR . 'app/admin.php';
	require_once QA_INCLUDE_DIR . 'qa-theme-base.php';
	require_once QA_INCLUDE_DIR . 'qa-app-blobs.php';
	require_once QA_PLUGIN_DIR . 'extra-question-field/qa-eqf.php';

	class qa_html_theme_layer extends qa_html_theme_base {
		var $plugin_directory;
		var $plugin_url;
		
		public function __construct($template, $content, $rooturl, $request)
		{
			global $qa_layers;
			$this->plugin_directory = $qa_layers['Extra Question Field Admin']['directory'];
			$this->plugin_url = $qa_layers['Extra Question Field Admin']['urltoroot'];
			qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		}
		
		function doctype()
		{
			global $qa_request;
			$adminsection = strtolower(qa_request_part(1));
			$errors = array();
			$securityexpired = false;
			
			if (strtolower(qa_request_part(1)) == 'eqf') {
				$this->template = $adminsection;
				$this->bp_navigation($adminsection);
				$this->content['suggest_next']="";
				$this->content['error'] = $securityexpired ? qa_lang_html('admin/form_security_expired') : qa_admin_page_error();
				$this->content['title'] = qa_lang_html('admin/admin_title') . ' - ' . qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::PLUGIN.'_nav');
				$this->content = $this->eqf_admin();
			}
			qa_html_theme_base::doctype();
		}
		
		function nav_list($bp_navigation, $class, $level=null)
		{
			if($this->template=='admin') {
				if ($class == 'nav-sub') {
					$bp_navigation['eqf'] = array(
						'label' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::PLUGIN.'_nav'),
						'url' => qa_path_html('admin/eqf'),
					);
				}
				if ( $this->request == 'admin/eqf' ) $bp_navigation = array_merge(qa_admin_sub_navigation(), $bp_navigation);
			}
			if(count($bp_navigation) > 1 ) 
				qa_html_theme_base::nav_list($bp_navigation, $class, $level=null);	
		}
		
		function bp_navigation($request)
		{
			$this->content['navigation']['sub'] = qa_admin_sub_navigation();
			$this->content['navigation']['sub']['eqf'] = array(
				'label' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::PLUGIN.'_nav'),	
				'url' => qa_path_html('admin/eqf'),  
				'selected' => ($request == 'eqf' ) ? 'selected' : '',
			);
			return 	$this->content['navigation']['sub'];
		}
		
		function eqf_admin()
		{
			$eqf = new qa_eqf;			$saved = '';
			$error = false;
			$error_active = array();
			$error_prompt = array();
			$error_note = array();
			$error_type = array();
			$error_attr = array();
			$error_option = array();
			$error_default = array();
			$error_form_pos = array();
			$error_display = array();
			$error_label = array();
			$error_page_pos = array();
			$error_hide_blank = array();
			$error_required = array();
			
			for($key=1; $key<=qa_eqf::FIELD_COUNT_MAX; $key++) {
				$error_active[$key] = $error_prompt[$key] = $error_note[$key] = $error_type[$key] = $error_attr[$key] = $error_option[$key] = $error_default[$key] = $error_form_pos[$key] = $error_display[$key] = $error_label[$key] = $error_page_pos[$key] = $error_hide_blank[$key] = $error_required[$key] = '';
			}
			if (qa_clicked(qa_eqf::SAVE_BUTTON)) {
				qa_opt(qa_eqf::FIELD_COUNT, qa_post_text(qa_eqf::FIELD_COUNT.'_field'));
				qa_opt(qa_eqf::MAXFILE_SIZE, qa_post_text(qa_eqf::MAXFILE_SIZE.'_field'));
				qa_opt(qa_eqf::ONLY_IMAGE, (int)qa_post_text(qa_eqf::ONLY_IMAGE.'_field'));
				qa_opt(qa_eqf::IMAGE_MAXWIDTH, qa_post_text(qa_eqf::IMAGE_MAXWIDTH.'_field'));
				qa_opt(qa_eqf::IMAGE_MAXHEIGHT, qa_post_text(qa_eqf::IMAGE_MAXHEIGHT.'_field'));
				qa_opt(qa_eqf::THUMB_SIZE, qa_post_text(qa_eqf::THUMB_SIZE.'_field'));
				qa_opt(qa_eqf::LIGHTBOX_EFFECT, (int)qa_post_text(qa_eqf::LIGHTBOX_EFFECT.'_field'));
				$eqf->init_extra_fields(qa_post_text(qa_eqf::FIELD_COUNT.'_field'));
				foreach ($eqf->extra_fields as $key => $extra_field) {
					if (trim(qa_post_text(qa_eqf::FIELD_PROMPT.'_field'.$key)) == '') {
						$error_prompt[$key] = qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PROMPT.'_error');
						$error = true;
					}
					if (qa_post_text(qa_eqf::FIELD_TYPE.'_field'.$key) != qa_eqf::FIELD_TYPE_TEXT
					&& qa_post_text(qa_eqf::FIELD_TYPE.'_field'.$key) != qa_eqf::FIELD_TYPE_TEXTAREA
					&& qa_post_text(qa_eqf::FIELD_TYPE.'_field'.$key) != qa_eqf::FIELD_TYPE_FILE
					&& trim(qa_post_text(qa_eqf::FIELD_OPTION.'_field'.$key)) == '') {
						$error_option[$key] = qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_OPTION.'_error');
						$error = true;
					}
					/*
					if ((bool)qa_post_text(qa_eqf::FIELD_DISPLAY.'_field'.$key) && trim(qa_post_text(qa_eqf::FIELD_LABEL.'_field'.$key)) == '') {
						$error_label[$key] = qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_LABEL.'_error');
						$error = true;
					}
					*/
				}
				foreach ($eqf->extra_fields as $key => $extra_field) {
					qa_opt(qa_eqf::FIELD_ACTIVE.$key, (int)qa_post_text(qa_eqf::FIELD_ACTIVE.'_field'.$key));
					qa_opt(qa_eqf::FIELD_PROMPT.$key, qa_post_text(qa_eqf::FIELD_PROMPT.'_field'.$key));
					qa_opt(qa_eqf::FIELD_NOTE.$key, qa_post_text(qa_eqf::FIELD_NOTE.'_field'.$key));
					qa_opt(qa_eqf::FIELD_TYPE.$key, qa_post_text(qa_eqf::FIELD_TYPE.'_field'.$key));
					qa_opt(qa_eqf::FIELD_OPTION.$key, qa_post_text(qa_eqf::FIELD_OPTION.'_field'.$key));
					qa_opt(qa_eqf::FIELD_ATTR.$key, qa_post_text(qa_eqf::FIELD_ATTR.'_field'.$key));
					qa_opt(qa_eqf::FIELD_DEFAULT.$key, qa_post_text(qa_eqf::FIELD_DEFAULT.'_field'.$key));
					qa_opt(qa_eqf::FIELD_FORM_POS.$key, qa_post_text(qa_eqf::FIELD_FORM_POS.'_field'.$key));
					qa_opt(qa_eqf::FIELD_DISPLAY.$key, (int)qa_post_text(qa_eqf::FIELD_DISPLAY.'_field'.$key));
					qa_opt(qa_eqf::FIELD_LABEL.$key, qa_post_text(qa_eqf::FIELD_LABEL.'_field'.$key));
					qa_opt(qa_eqf::FIELD_PAGE_POS.$key, qa_post_text(qa_eqf::FIELD_PAGE_POS.'_field'.$key));
					qa_opt(qa_eqf::FIELD_HIDE_BLANK.$key, (int)qa_post_text(qa_eqf::FIELD_HIDE_BLANK.'_field'.$key));
					qa_opt(qa_eqf::FIELD_REQUIRED.$key, (int)qa_post_text(qa_eqf::FIELD_REQUIRED.'_field'.$key));
				}
				$saved = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::SAVED_MESSAGE);
			}
			if (qa_clicked(qa_eqf::DFL_BUTTON)) {
				$eqf->init_extra_fields(qa_eqf::FIELD_COUNT_MAX);
				foreach ($eqf->extra_fields as $key => $extra_field) {
					qa_opt(qa_eqf::FIELD_ACTIVE.$key, (int)$extra_field['active']);
					qa_opt(qa_eqf::FIELD_PROMPT.$key, $extra_field['prompt']);
					qa_opt(qa_eqf::FIELD_NOTE.$key, $extra_field['note']);
					qa_opt(qa_eqf::FIELD_TYPE.$key, $extra_field['type']);
					qa_opt(qa_eqf::FIELD_OPTION.$key, $extra_field['option']);
					qa_opt(qa_eqf::FIELD_ATTR.$key, $extra_field['attr']);
					qa_opt(qa_eqf::FIELD_DEFAULT.$key, $extra_field['default']);
					qa_opt(qa_eqf::FIELD_FORM_POS.$key, $extra_field['form_pos']);
					qa_opt(qa_eqf::FIELD_DISPLAY.$key, (int)$extra_field['display']);
					qa_opt(qa_eqf::FIELD_LABEL.$key, $extra_field['label']);
					qa_opt(qa_eqf::FIELD_PAGE_POS.$key, $extra_field['page_pos']);
					qa_opt(qa_eqf::FIELD_HIDE_BLANK.$key, (int)$extra_field['displayblank']);
					qa_opt(qa_eqf::FIELD_REQUIRED.$key, (int)$extra_field['required']);
				}
				$eqf->extra_field_count = qa_eqf::FIELD_COUNT_DFL;
				qa_opt(qa_eqf::FIELD_COUNT,$eqf->extra_field_count);
				$eqf->init_extra_fields($eqf->extra_field_count);
				$eqf->extra_field_maxfile_size = qa_eqf::MAXFILE_SIZE_DFL;
				$eqf->extra_field_only_image = qa_eqf::ONLY_IMAGE_DFL;
				$eqf->extra_field_image_maxwidth = qa_eqf::IMAGE_MAXWIDTH_DFL;
				$eqf->extra_field_image_maxheight = qa_eqf::IMAGE_MAXHEIGHT_DFL;
				$eqf->extra_field_thumb_size = qa_eqf::THUMB_SIZE_DFL;
				$eqf->extra_field_lightbox_effect = qa_eqf::LIGHTBOX_EFFECT_DFL;
				qa_opt(qa_eqf::THUMB_SIZE,$eqf->extra_field_thumb_size);
				$saved = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::RESET_MESSAGE);
			}
			if ($saved == '' && !$error) {
				$eqf->extra_field_count = qa_opt(qa_eqf::FIELD_COUNT);
				if(!is_numeric($eqf->extra_field_count))
					$eqf->extra_field_count = qa_eqf::FIELD_COUNT_DFL;
				$eqf->init_extra_fields($eqf->extra_field_count);
				$eqf->extra_field_maxfile_size = qa_opt(qa_eqf::MAXFILE_SIZE);
				if(!is_numeric($eqf->extra_field_maxfile_size))
					$eqf->extra_field_maxfile_size = qa_eqf::MAXFILE_SIZE_DFL;
				$eqf->extra_field_image_maxwidth = qa_opt(qa_eqf::IMAGE_MAXWIDTH);
				if(!is_numeric($eqf->extra_field_image_maxwidth))
					$eqf->extra_field_image_maxwidth = qa_eqf::IMAGE_MAXWIDTH_DFL;
				$eqf->extra_field_image_maxheight = qa_opt(qa_eqf::IMAGE_MAXHEIGHT);
				if(!is_numeric($eqf->extra_field_image_maxheight))
					$eqf->extra_field_image_maxheight = qa_eqf::IMAGE_MAXHEIGHT_DFL;
				$eqf->extra_field_thumb_size = qa_opt(qa_eqf::THUMB_SIZE);
				if(!is_numeric($eqf->extra_field_thumb_size))
					$eqf->extra_field_thumb_size = qa_eqf::THUMB_SIZE_DFL;
			}
			$rules = array();
			foreach ($eqf->extra_fields as $key => $extra_field) {
				$rules[qa_eqf::FIELD_PROMPT.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_NOTE.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_TYPE.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_OPTION.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_ATTR.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_DEFAULT.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_FORM_POS.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_DISPLAY.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_LABEL.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_PAGE_POS.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_HIDE_BLANK.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
				$rules[qa_eqf::FIELD_REQUIRED.$key] = qa_eqf::FIELD_ACTIVE.'_field'.$key;
			}
			qa_set_display_rules($qa_content, $rules);

			$ret = array();
			if($saved != '' && !$error)
				$this->content['form']['ok'] = $saved;

			$fields = array();
			$fieldoption = array();
			for($i=qa_eqf::FIELD_COUNT_DFL;$i<=qa_eqf::FIELD_COUNT_MAX;$i++) {
				$fieldoption[(string)$i] = (string)$i;
			}
			$fields[] = array(
				'id' => qa_eqf::FIELD_COUNT,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_COUNT.'_label'),
				'value' => qa_opt(qa_eqf::FIELD_COUNT),
				'tags' => 'NAME="'.qa_eqf::FIELD_COUNT.'_field"',
				'type' => 'select',
				'options' => $fieldoption,
				'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_COUNT.'_note'),
			);
			$fields[] = array(
				'id' => qa_eqf::MAXFILE_SIZE,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::MAXFILE_SIZE.'_label'),
				'value' => qa_opt(qa_eqf::MAXFILE_SIZE),
				'tags' => 'NAME="'.qa_eqf::MAXFILE_SIZE.'_field"',
				'type' => 'number',
				'suffix' => 'bytes',
				'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::MAXFILE_SIZE.'_note'),
			);
			$fields[] = array(
				'id' => qa_eqf::ONLY_IMAGE,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::ONLY_IMAGE.'_label'),
				'type' => 'checkbox',
				'value' => (int)qa_opt(qa_eqf::ONLY_IMAGE),
				'tags' => 'NAME="'.qa_eqf::ONLY_IMAGE.'_field"',
			);
			$fields[] = array(
				'id' => qa_eqf::IMAGE_MAXWIDTH,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::IMAGE_MAXWIDTH.'_label'),
				'value' => qa_opt(qa_eqf::IMAGE_MAXWIDTH),
				'tags' => 'NAME="'.qa_eqf::IMAGE_MAXWIDTH.'_field"',
				'type' => 'number',
				'suffix' => qa_lang_html('admin/pixels'),
				'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::IMAGE_MAXWIDTH.'_note'),
			);
			$fields[] = array(
				'id' => qa_eqf::IMAGE_MAXHEIGHT,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::IMAGE_MAXHEIGHT.'_label'),
				'value' => qa_opt(qa_eqf::IMAGE_MAXHEIGHT),
				'tags' => 'NAME="'.qa_eqf::IMAGE_MAXHEIGHT.'_field"',
				'type' => 'number',
				'suffix' => qa_lang_html('admin/pixels'),
				'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::IMAGE_MAXHEIGHT.'_note'),
			);
			$fields[] = array(
				'id' => qa_eqf::THUMB_SIZE,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::THUMB_SIZE.'_label'),
				'value' => qa_opt(qa_eqf::THUMB_SIZE),
				'tags' => 'NAME="'.qa_eqf::THUMB_SIZE.'_field"',
				'type' => 'number',
				'suffix' => qa_lang_html('admin/pixels'),
				'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::THUMB_SIZE.'_note'),
			);
			$fields[] = array(
				'id' => qa_eqf::LIGHTBOX_EFFECT,
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::LIGHTBOX_EFFECT.'_label'),
				'type' => 'checkbox',
				'value' => (int)qa_opt(qa_eqf::LIGHTBOX_EFFECT),
				'tags' => 'NAME="'.qa_eqf::LIGHTBOX_EFFECT.'_field"',
			);
			$type = array(qa_eqf::FIELD_TYPE_TEXT => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_TEXT_LABEL)
						, qa_eqf::FIELD_TYPE_TEXTAREA => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_TEXTAREA_LABEL)
						, qa_eqf::FIELD_TYPE_CHECK => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_CHECK_LABEL)
						, qa_eqf::FIELD_TYPE_SELECT => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_SELECT_LABEL)
						, qa_eqf::FIELD_TYPE_RADIO => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_RADIO_LABEL)
						, qa_eqf::FIELD_TYPE_FILE => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE_FILE_LABEL)
			);

			$form_pos = array();
			$form_pos[qa_eqf::FIELD_FORM_POS_TOP] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_TOP_LABEL);
			if(qa_opt('show_custom_ask'))
				$form_pos[qa_eqf::FIELD_FORM_POS_CUSTOM] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_CUSTOM_LABEL);
			$form_pos[qa_eqf::FIELD_FORM_POS_TITLE] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_TITLE_LABEL);
			if (qa_using_categories())
				$form_pos[qa_eqf::FIELD_FORM_POS_CATEGORY] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_CATEGORY_LABEL);
			$form_pos[qa_eqf::FIELD_FORM_POS_CONTENT] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_CONTENT_LABEL);
			if (qa_opt('extra_field_active'))
				$form_pos[qa_eqf::FIELD_FORM_POS_EXTRA] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_EXTRA_LABEL);
			if (qa_using_tags())
				$form_pos[qa_eqf::FIELD_FORM_POS_TAGS] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_TAGS_LABEL);
			$form_pos[qa_eqf::FIELD_FORM_POS_NOTIFY] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_NOTIFY_LABEL);
			$form_pos[qa_eqf::FIELD_FORM_POS_BOTTOM] = qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS_BOTTOM_LABEL);

			$page_pos = array(qa_eqf::FIELD_PAGE_POS_UPPER => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PAGE_POS_UPPER_LABEL)
							, qa_eqf::FIELD_PAGE_POS_INSIDE => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PAGE_POS_INSIDE_LABEL)
							, qa_eqf::FIELD_PAGE_POS_BELOW => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PAGE_POS_BELOW_LABEL)
			);
			
			foreach ($eqf->extra_fields as $key => $extra_field) {
				$fields[] = array(
					'id' => qa_eqf::FIELD_ACTIVE.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_ACTIVE.'_label',$key),
					'type' => 'checkbox',
					'value' => (int)qa_opt(qa_eqf::FIELD_ACTIVE.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_ACTIVE.'_field'.$key.'" ID="'.qa_eqf::FIELD_ACTIVE.'_field'.$key.'"',
					'error' => $error_active[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_PROMPT.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PROMPT.'_label',$key),
					'value' => qa_html(qa_opt(qa_eqf::FIELD_PROMPT.$key)),
					'tags' => 'NAME="'.qa_eqf::FIELD_PROMPT.'_field'.$key.'" ID="'.qa_eqf::FIELD_PROMPT.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PROMPT.'_note',$key),
					'error' => $error_prompt[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_NOTE.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_NOTE.'_label',$key),
					'type' => 'textarea',
					'value' => qa_opt(qa_eqf::FIELD_NOTE.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_NOTE.'_field'.$key.'" ID="'.qa_eqf::FIELD_NOTE.'_field'.$key.'"',
					'rows' => $eqf->extra_field_note_height,
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_NOTE.'_note',$key),
					'error' => $error_note[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_TYPE.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE.'_label',$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_TYPE.'_field'.$key.'" ID="'.qa_eqf::FIELD_TYPE.'_field'.$key.'"',
					'type' => 'select',
					'options' => $type,
					'value' => @$type[qa_opt(qa_eqf::FIELD_TYPE.$key)],
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_TYPE.'_note',$key),
					'error' => $error_type[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_OPTION.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_OPTION.'_label',$key),
					'type' => 'textarea',
					'value' => qa_opt(qa_eqf::FIELD_OPTION.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_OPTION.'_field'.$key.'" ID="'.qa_eqf::FIELD_OPTION.'_field'.$key.'"',
					'rows' => $eqf->extra_field_option_height,
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_OPTION.'_note',$key),
					'error' => $error_option[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_ATTR.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_ATTR.'_label',$key),
					'value' => qa_html(qa_opt(qa_eqf::FIELD_ATTR.$key)),
					'tags' => 'NAME="'.qa_eqf::FIELD_ATTR.'_field'.$key.'" ID="'.qa_eqf::FIELD_ATTR.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_ATTR.'_note',$key),
					'error' => $error_attr[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_DEFAULT.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_DEFAULT.'_label',$key),
					'value' => qa_html(qa_opt(qa_eqf::FIELD_DEFAULT.$key)),
					'tags' => 'NAME="'.qa_eqf::FIELD_DEFAULT.'_field'.$key.'" ID="'.qa_eqf::FIELD_DEFAULT.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_DEFAULT.'_note',$key),
					'error' => $error_default[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_FORM_POS.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS.'_label',$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_FORM_POS.'_field'.$key.'" ID="'.qa_eqf::FIELD_FORM_POS.'_field'.$key.'"',
					'type' => 'select',
					'options' => $form_pos,
					'value' => @$form_pos[qa_opt(qa_eqf::FIELD_FORM_POS.$key)],
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_FORM_POS.'_note',$key),
					'error' => $error_form_pos[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_DISPLAY.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_DISPLAY.'_label',$key),
					'type' => 'checkbox',
					'value' => (int)qa_opt(qa_eqf::FIELD_DISPLAY.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_DISPLAY.'_field'.$key.'" ID="'.qa_eqf::FIELD_DISPLAY.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_DISPLAY.'_note',$key),
					'error' => $error_display[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_LABEL.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_LABEL.'_label',$key),
					'value' => qa_html(qa_opt(qa_eqf::FIELD_LABEL.$key)),
					'tags' => 'NAME="'.qa_eqf::FIELD_LABEL.'_field'.$key.'" ID="'.qa_eqf::FIELD_LABEL.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_LABEL.'_note',$key),
					'error' => $error_label[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_PAGE_POS.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PAGE_POS.'_label',$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_PAGE_POS.'_field'.$key.'" ID="'.qa_eqf::FIELD_PAGE_POS.'_field'.$key.'"',
					'type' => 'select',
					'options' => $page_pos,
					'value' => @$page_pos[qa_opt(qa_eqf::FIELD_PAGE_POS.$key)],
					'note' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_PAGE_POS.'_note',str_replace('^', $key, qa_eqf::FIELD_PAGE_POS_HOOK)),
					'error' => $error_page_pos[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_HIDE_BLANK.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_HIDE_BLANK.'_label',$key),
					'type' => 'checkbox',
					'value' => (int)qa_opt(qa_eqf::FIELD_HIDE_BLANK.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_HIDE_BLANK.'_field'.$key.'" ID="'.qa_eqf::FIELD_HIDE_BLANK.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_HIDE_BLANK.'_note',$key),
					'error' => $error_hide_blank[$key],
				);
				$fields[] = array(
					'id' => qa_eqf::FIELD_REQUIRED.$key,
					'label' => qa_lang_html_sub(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_REQUIRED.'_label',$key),
					'type' => 'checkbox',
					'value' => (int)qa_opt(qa_eqf::FIELD_REQUIRED.$key),
					'tags' => 'NAME="'.qa_eqf::FIELD_REQUIRED.'_field'.$key.'" ID="'.qa_eqf::FIELD_REQUIRED.'_field'.$key.'"',
					'note' => qa_lang(qa_eqf::PLUGIN.'/'.qa_eqf::FIELD_REQUIRED.'_note',$key),
					'error' => $error_required[$key],
				);
			}
			$this->content['custom'] = '<p>This plugin is a fork from <a href="http://www.cmsbox.jp/">sama55@CMSBOX\'s</a> plugin now no longer available</p>';	
			$this->content['form']['fields'] = $fields;
			$this->content['form']['style'] = 'wide';
			
			$buttons = array();
			$buttons[] = array(
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::SAVE_BUTTON),
				'tags' => 'NAME="'.qa_eqf::SAVE_BUTTON.'" ID="'.qa_eqf::SAVE_BUTTON.'"',
			);
			$buttons[] = array(
				'label' => qa_lang_html(qa_eqf::PLUGIN.'/'.qa_eqf::DFL_BUTTON),
				'tags' => 'NAME="'.qa_eqf::DFL_BUTTON.'" ID="'.qa_eqf::DFL_BUTTON.'"',
			);
			$this->content['form']['buttons'] = $buttons;
			
			return $this->content;
		}
		
		
	}

/*
	Omit PHP closing tag to help avoid accidental output
*/
