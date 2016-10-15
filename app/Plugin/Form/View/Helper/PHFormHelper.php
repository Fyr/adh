<?php
/**
 * Wrapper for standart Form helper
 * Customizes default HTML inputs
 */
App::uses('FormHelper', 'View/Helper');
App::uses('FieldTypes', 'Form.Vendor');
class PHFormHelper extends FormHelper {
	// var $helpers = array('Form', 'Html');
	protected $lInline = false;

	public function create($model, $options = array()) {
		$options['class'] = (isset($options['class']) && $options['class']) ? $options['class'] : 'form-horizontal';

		if (!isset($options['inputDefaults'])) {
			if ($options['class'] == 'form-horizontal') {
				$options['inputDefaults'] = (isset($options['inputDefaults']) && $options['inputDefaults']) ? $options['inputDefaults'] : array(
					'div' => 'form-group',
					'class' => 'form-control input-xlarge',
					'label' => array('class' => 'col-md-3 control-label'),
					'between' => '<div class="col-md-9">',
					'after' => '</div>',
					//'error' => array('attributes' => array('wrap' => 'div', 'class' => 'col-md-9'))
				);
			} elseif ($options['class'] == 'form-inline') {
				$this->lInline = true;
				$options['inputDefaults'] = (isset($options['inputDefaults']) && $options['inputDefaults']) ? $options['inputDefaults'] : array(
					'div' => 'form-group',
					'class' => 'form-control',
					'label' => array('class' => 'sr-only'),
					//'error' => array('attributes' => array('wrap' => 'div', 'class' => 'col-md-9'))
				);
			}
		}
		
		// Fix validation errors translation
		foreach($this->validationErrors as $_model => $fields) {
			if (is_array($fields)) {
				foreach($fields as $field => $messages) {
					foreach($messages as $i => $msg) {
						$this->validationErrors[$_model][$field][$i] = __($msg);
					}
				}
			}
		}
		return '<div class="portlet-body form tabbable-bordered">'.parent::create($model, $options);
	}

	public function isInline() {
		return $this->lInline;
	}

	public function end($options = null, $secureAttributes = array()) {
		return parent::end($options, $secureAttributes).'</div>';
	}

	public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);
		$options = $this->_parseOptions($options);
		if ($options['type'] == 'checkbox') {
			$options['format'] = array('before', 'label', 'between', 'input', 'after', 'error');
		} elseif ($options['type'] == 'text' || $options['type'] == 'textarea') {
			$options = array_merge(array('class' => 'input-xxlarge'), $options);
		}
		if ($this->isInline() && isset($options['icon'])) {
			/*
			$oldDefaults = $this->inputDefaults();
			$this->inputDefaults(array(
				'div' => 'form-group',
				'class' => 'form-control',
				'label' => array('class' => 'sr-only'),
				'between' => '<div class="input-icon"><i class="'.$options['icon'].'"></i>',
				'after' => '</div>'
			));
			*/
			$label = $this->_getLabel($fieldName, $options);
			preg_match('/\>(.+)\<\/label/', $label, $match);
			$options['placeholder'] = $match[1];
			$options['between'] = (isset($options['between'])) ? $options['between'] : '<div class="input-icon"><i class="'.$options['icon'].'"></i>';
			$options['after'] = (isset($options['after'])) ? $options['after'] : '</div>';
			unset($options['icon']);
			$_ret = parent::input($fieldName, $options);
			return $_ret;
		}
		return parent::input($fieldName, $options);
	}

	public function submit($fieldName = 'Save', $options = array()) {
		$options = array_merge(array('class' => 'btn blue', 'type' => 'submit', 'div' => false), $options);
		return $this->button($fieldName, $options);
	}
	
	public function button($fieldName, $options = array()) {
	    $options = array_merge(array('class' => 'btn defalut', 'type' => 'button', 'div' => false), $options);
		return parent::button($fieldName, $options);
	}

	public function date($fieldName, $options = array()) {
		$this->Html->script('vendor/xdate', array('inline' => false));
		$this->setEntity('_'.$fieldName);
		$options = $this->_parseOptions($options);
		$options['between'].= '<div class="input-group input-small date date-picker">';
		$button = '<span class="input-group-btn"><button type="button" class="btn default"><i class="fa fa-calendar"></i></button></span>';
		$options['after'] = $this->hidden($fieldName).$button.'</div>'.$options['after'];
		return $this->input('_'.$fieldName, $options);
	}

	public function inlineCheckboxes($checkboxes) {

	}

	public function textOnly($label, $text) {
		return $this->_View->element('Form.form_text', compact('label', 'text'));
	}
	
	/**
	 * Create a input with CKEditor
	 *
	 * @param string $name - field name
	 * @param array $options - input options
	 * @return string

	public function editor($fieldName, $options = array()) {
        $this->Html->script('vendor/ckeditor/ckeditor', array('inline' => false));
        $this->Html->css('/js/vendor/ckeditor/fixes', array('inline' => false));
        $options = array_merge(array('class' => 'ckeditor'), $options); // 
        // $options['class'] = 'ckeditor '.$options['class'];
        
        if (isset($options['fullwidth']) && $options['fullwidth']) {
            return '<div class="control-group"><div class="clearfix"></div><div class="shadow text-center">'.$this->textarea($fieldName, $options).'</div></div>';
        }
        
        return parent::input($fieldName, $options);
    }
	 */

	public function renderForm($form, $values = array()) {
		$html = '';
		$aDefaultOptions = array(
			FieldTypes::STRING => array(),
			FieldTypes::INT => array('class' => 'form-control input-small'),
			FieldTypes::CHECKBOX => array('type' => 'checkbox'),
			FieldTypes::TEXTAREA => array('type' => 'textarea')
		);
		foreach($form as $field) {
			$id = $field['PMFormField']['id'];
			$type = $field['PMFormField']['field_type'];
			$options = $aDefaultOptions[$type];
			$options['id'] = 'PMFormValue'.$id;
			$options['name'] = 'data[PMFormValue]['.$id.']';
			if ($type == FieldTypes::CHECKBOX) {
				$options['value'] = 1;
				$options['checked'] = Hash::get($values, $id) && true;
			} else {
				$options['value'] = Hash::get($values, $id);
			}
			$options['label'] = array('text' => $field['PMFormField']['label_'.$this->getLang()], 'class' => 'col-md-3 control-label');
			$html.= $this->input('PMFormValue.value', $options);
		}
		return $html;
	}
}