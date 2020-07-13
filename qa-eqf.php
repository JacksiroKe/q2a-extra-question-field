<?php
if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

class qa_eqf {
	const plugin						= 'eqf';
	const lang							= 'eqf_lang';
	const field_base_name				= 'extra';
	const field_count					= 'eqf_count';
	const field_count_dfl				= 1;
	const field_count_max				= 20;
	const maxfile_size					= 'eqf_maxfile_size';
	const maxfile_size_dfl				= 2097152;
	const only_image					= 'eqf_only_image';
	const only_image_dfl				= false;	// can't change true.
	const image_maxwidth				= 'eqf_image_maxwidth';
	const image_maxwidth_dfl			= 600;
	const image_maxheight				= 'eqf_image_maxheight';
	const image_maxheight_dfl			= 600;
	const thumb_size					= 'eqf_thumb_size';
	const thumb_size_dfl				= 100;
	const lightbox_effect				= 'eqf_lightbox_effect';
	const lightbox_effect_dfl			= false;	// can't change true.
	const field_active					= 'eqf_active';
	const field_active_dfl				= false;	// can't change true.
	const field_prompt					= 'eqf_prompt';
	const field_note					= 'eqf_note';
	const field_note_height				= 2;
	const field_type					= 'eqf_type';
	const field_type_dfl				= 'text';
	const field_type_text				= 'text';
	const field_type_text_label			= 'eqf_type_text';
	const field_type_textarea			= 'textarea';
	const field_type_textarea_label		= 'eqf_type_textarea';
	const field_type_check				= 'checkbox';
	const field_type_check_label		= 'eqf_type_checkbox';
	const field_type_select				= 'select';
	const field_type_select_label		= 'eqf_type_select';
	const field_type_radio				= 'select-radio';
	const field_type_radio_label		= 'eqf_type_radio';
	const field_type_file				= 'file';
	const field_type_file_label			= 'eqf_type_file';
	const field_option					= 'eqf_option';
	const field_option_height			= 2;
	const field_option_rows_dfl			= 3;
	const field_option_ext_error		= 'eqf_option_ext_error';
	const field_attr					= 'eqf_attr';
	const field_default					= 'eqf_default';
	const field_form_pos				= 'eqf_form_pos';
	const field_form_pos_dfl			= 'content';
	const field_form_pos_top			= 'top';
	const field_form_pos_top_label		= 'eqf_form_pos_top';
	const field_form_pos_custom			= 'custom';
	const field_form_pos_custom_label	= 'eqf_form_pos_custom';
	const field_form_pos_title			= 'title';
	const field_form_pos_title_label	= 'eqf_form_pos_title';
	const field_form_pos_category		= 'category';
	const field_form_pos_category_label	= 'eqf_form_pos_category';
	const field_form_pos_content		= 'content';
	const field_form_pos_content_label	= 'eqf_form_pos_content';
	const field_form_pos_extra			= 'extra';
	const field_form_pos_extra_label	= 'eqf_form_pos_extra';
	const field_form_pos_tags			= 'tags';
	const field_form_pos_tags_label		= 'eqf_form_pos_tags';
	const field_form_pos_notify			= 'notify';
	const field_form_pos_notify_label	= 'eqf_form_pos_notify';
	const field_form_pos_bottom			= 'bottom';
	const field_form_pos_bottom_label	= 'eqf_form_pos_bottom';
	const field_display					= 'eqf_display';
	const field_display_dfl				= false;	// can't change true.
	const field_label					= 'eqf_label';
	const field_page_pos				= 'eqf_page_pos';
	const field_page_pos_dfl			= 'below';
	const field_page_pos_upper			= 'upper';
	const field_page_pos_upper_label	= 'eqf_page_pos_upper';
	const field_page_pos_inside			= 'inside';
	const field_page_pos_inside_label	= 'eqf_page_pos_inside';
	const field_page_pos_below			= 'below';
	const field_page_pos_below_label	= 'eqf_page_pos_below';
	const field_page_pos_hook			= '[*attachment^*]';
	const field_hide_blank				= 'eqf_hide_blank';
	const field_hide_blank_dfl			= false;	// can't change true.
	const field_required				= 'eqf_required';
	const field_required_dfl			= false;	// can't change true.
	const save_button					= 'eqf_save_button';
	const dfl_button					= 'eqf_dfl_button';
	const saved_message					= 'eqf_saved_message';
	const reset_message					= 'eqf_reset_message';
	
	var $directory;
	var $urltoroot;

	var $eqf_count;
	var $eqf_maxfile_size;
	var $eqf_only_image;
	var $eqf_image_maxwidth;
	var $eqf_image_maxheight;
	var $eqf_thumb_size;
	var $eqf_lightbox_effect;
	var $extra_fields;
	var $eqf_note_height;
	var $eqf_option_height;

	public function __construct() {
		$this->eqf_count = self::field_count_dfl;
		$this->eqf_maxfile_size = self::maxfile_size_dfl;
		$this->eqf_only_image = self::only_image_dfl;
		$this->eqf_image_maxwidth = self::image_maxwidth_dfl;
		$this->eqf_image_maxheight = self::image_maxheight_dfl;
		$this->eqf_thumb_size = self::thumb_size_dfl;
		$this->eqf_lightbox_effect = self::lightbox_effect_dfl;
		$this->init_extra_fields($this->eqf_count);
		$this->eqf_note_height = self::field_note_height;
		$this->eqf_option_height = self::field_option_height;
	}
	
	function init_extra_fields($count) {
		$this->extra_fields = array();
		for($key=1; $key<=$count; $key++) {
			$this->extra_fields[(string)$key] = array(
				'active' => self::field_active_dfl,
				'prompt' => qa_lang_html_sub(self::lang.'/'.self::field_prompt.'_default',$key),
				'note' => '',
				'type' => self::field_type_dfl,
				'attr' => '',
				'option' => qa_lang_html_sub(self::lang.'/'.self::field_option.'_default',$key),
				'default' => qa_lang_html_sub(self::lang.'/'.self::field_default.'_default',$key),
				'form_pos' => self::field_form_pos_dfl,
				'display' => self::field_display_dfl,
				'label' => qa_lang_html_sub(self::lang.'/'.self::field_label.'_default',$key),
				'page_pos' => self::field_page_pos_dfl,
				'displayblank' => self::field_hide_blank_dfl,
				'required' => self::field_required_dfl,
			);
		}
	}

	function load_module($directory, $urltoroot) {
		$this->directory=$directory;
		$this->urltoroot=$urltoroot;
	}

	function option_default($option) {
		if ($option==self::field_count) return $this->eqf_count;
		if ($option==self::maxfile_size) return $this->eqf_maxfile_size;
		if ($option==self::only_image) return $this->eqf_only_image;
		if ($option==self::image_maxwidth) return $this->eqf_image_maxwidth;
		if ($option==self::image_maxheight) return $this->eqf_image_maxheight;
		if ($option==self::thumb_size) return $this->eqf_thumb_size;
		if ($option==self::lightbox_effect) return $this->eqf_lightbox_effect;
		foreach ($this->extra_fields as $key => $extra_field) {
			if ($option==self::field_active.$key) return $extra_field['active'];
			if ($option==self::field_prompt.$key) return $extra_field['prompt'];
			if ($option==self::field_note.$key) return $extra_field['note'];
			if ($option==self::field_type.$key) return $extra_field['type'];
			if ($option==self::field_option.$key) return $extra_field['option'];
			if ($option==self::field_attr.$key) return $extra_field['attr'];
			if ($option==self::field_default.$key) return $extra_field['default'];
			if ($option==self::field_form_pos.$key) return $extra_field['form_pos'];
			if ($option==self::field_display.$key) return $extra_field['display'];
			if ($option==self::field_label.$key) return $extra_field['label'];
			if ($option==self::field_page_pos.$key) return $extra_field['page_pos'];
			if ($option==self::field_hide_blank.$key) return $extra_field['displayblank'];
			if ($option==self::field_required.$key) return $extra_field['required'];
		}
	}

}

/*
	Omit PHP closing tag to help avoid accidental output
*/