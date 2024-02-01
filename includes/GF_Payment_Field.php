<?php

namespace adz\Stax;

class GF_Payment_Field extends \GF_Field
{
    public $type = 'adz_stax_payment';


    /**
	 * Return the field title, for use in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title() {
		return 'Stax';
	}

    /**
     * Render the field.
     */
    public function get_field_input($form, $value = '', $entry = null)
    {
        $id              = absint( $this->id );
		$form_id         = absint( $form['id'] );
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		// Prepare the value of the input ID attribute.
		$field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		$value = esc_attr( $value );

		// Get the value of the inputClass property for the current field.
		$inputClass = $this->inputClass;

		// Prepare the input classes.
		$size         = $this->size;
		$class_suffix = $is_entry_detail ? '_admin' : '';
		$class        = $size . $class_suffix . ' ' . $inputClass;

		// Prepare the other input attributes.
		$tabindex              = $this->get_tabindex();
		$logic_event           = ! $is_form_editor && ! $is_entry_detail ? $this->get_conditional_logic_event( 'keyup' ) : '';
		$placeholder_attribute = $this->get_field_placeholder_attribute();
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
		$invalid_attribute     = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
		$disabled_text         = $is_form_editor ? 'disabled="disabled"' : '';

		// // Prepare the input tag for this field.
		// $input = "<input name='input_{$id}' id='{$field_id}' type='text' value='{$value}' class='{$class}' {$tabindex} {$logic_event} {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$disabled_text}/>";

		// return sprintf( "<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $input );

		
        $field = <<<HTML
			<input 
				name='input_{$id}' 
				id='{$field_id}' 
				type='hidden' 
				value='{$value}' 
				class='{$class}' 
				{$tabindex} 
				{$logic_event} 
				{$placeholder_attribute} 
				{$required_attribute} 
				{$invalid_attribute} 
				{$disabled_text}/>

			<div class="gfield_div gform-field-label">Bank Account</div>
			<div class="adz_stax_field_group adz-stax-bank-account-details ">

				<div class="gfield--width-full">
					<label><span>Bank Name</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="bank-name" class="adz_stax_field large" placeholder="" value="">
					</div>
				</div>

				<div class="gfield--width-half">
					<label><span>Account #</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="account-number" class="adz_stax_field large" placeholder="" value="" />
					</div>
				</div>

				<div class="gfield--width-half">
					<label><span>Routing #</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="routing-number" class="adz_stax_field large" placeholder="" value="" />
					</div>
				</div>

				<div class="gfield_div">
					<legend class="gfield_label gform-field-label">Account Type</legend>
					<div class="ginput_container ginput_container_radio">
						<div class="gfield_radio">
							<!-- Account Type radio button -->
							<div class="gchoice">
								<input type="radio" class="adz_stax_field_radio gfield-choice-input" name="account-type" value="savings">
								<label class="gform-field-label gform-field-label--type-inline">
									Savings
								</label>
							</div>
							<div class="gchoice">
								<input type="radio" class="adz_stax_field_radio gfield-choice-input" name="account-type" value="checking" checked>
								<label class="gform-field-label gform-field-label--type-inline">
									Checking
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="gfield_div">
					<legend class="gfield_label gform-field-label">Bank Holder Type</legend>
					<div class="ginput_container ginput_container_radio">
						<div class="gfield_radio">
							<!-- Bank Holder Type -->
							<div class="gchoice">
								<input type="radio" class="adz_stax_field_radio gfield-choice-input" name="bank-holder-type" value="personal">
								<label class="gform-field-label gform-field-label--type-inline">
									Personal
								</label>
							</div>
							<div class="gchoice">
								<input type="radio" class="adz_stax_field_radio gfield-choice-input" name="bank-holder-type" value="business" checked>
								<label class="gform-field-label gform-field-label--type-inline">
									Business
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- <label class="gfield_div gform-field-label">Billing Details</label> -->
			<!-- <div class="adz_stax_field_group"> -->
				<!-- <div class="gfield--width-full">	
					<label><span>First Name</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="first-name" class="adz_stax_field large" placeholder="" value=""/>
					</div>
				</div>
				<div class="gfield--width-full">
					<label><span>Last Name</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="last-name" class="adz_stax_field large" placeholder="" value=""/>
					</div>
				</div>
				<div class="gfield--width-full">
					<label><span>Email</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="email" class="adz_stax_field large" placeholder="" value=""/>
					</div>
				</div> -->
				<!-- <label>
					<span>Phone</span>
					<input name="phone" class="adz_stax_field" placeholder="5555555555" value=""/>
				</label>
				<label>
					<span>Company</span>
					<input name="company" class="adz_stax_field" placeholder="Company INC" value=""/>
				</label>
				<label>
					<span>Address 1</span>
					<input name="address_1" class="adz_stax_field" placeholder="100 S Orange Ave" value=""/>
				</label>
				<label>
					<span>Address 2</span>
					<input name="address_2" class="adz_stax_field" placeholder="" value=""/>
				</label>
				<label>
					<span>City</span>
					<input name="address_city" class="adz_stax_field" placeholder="Orlando" value=""/>
				</label>
				<label>
					<span>State</span>
					<input name="address_state" class="adz_stax_field" placeholder="FL" value=""/>
				</label>
				<label>
					<span>Zip</span>
					<input name="address_zip" class="adz_stax_field" placeholder="32811" value=""/>
				</label>
				<label>
					<span>Country</span>
					<input name="address_country" class="adz_stax_field" placeholder="USA" value=""/>
				</label> -->
				<!-- <div class="gfield gfield--width-full">
					<label><span>Notes</span></label>
					<div class="ginput_container ginput_container_text">
						<input type="text" name="notes" class="adz_stax_field large" placeholder="" value=""/>
					</div>
				</div> -->
			<!-- </div> -->

			<div class="adz_stax_field_group adz_stax_field_group_{$form_id}">
				<button id="paybutton" data-total="0" style="display: none;">Pay $0.00</button>
			</div>

			<div class="adz_stax_field_group adz_stax_field_group_{$form_id}">
				<div class="outcome">
					<div class="error"></div>
					<div class="success">
						Successful! The ID is
						<span class="token"></span>
					</div>
					<div class="loader" style="margin: auto">
					</div>
				</div>
			</div>
		HTML;

        return $field;
    }


    public function get_form_editor_inline_script_on_page_render() 
	{
        // set the default field label for the simple type field
		$script = sprintf( "function SetDefaultValues_simple(field) {field.label = '%s';}", $this->get_form_editor_field_title() ) . PHP_EOL;

		// initialize the fields custom settings
		$script .= "jQuery(document).bind('gform_load_field_settings', function (event, field, form) {" .
		           "var inputClass = field.inputClass == undefined ? '' : field.inputClass;" .
		           "jQuery('#input_class_setting').val(inputClass);" .
		           "});" . PHP_EOL;

		// saving the simple setting
		$script .= "function SetInputClassSetting(value) {SetFieldProperty('inputClass', value);}" . PHP_EOL;

		return $script;
	}

    public function get_form_inline_script_on_page_render( $form ) 
	{
		$id              = absint( $this->id );
		$form_id         = absint( $form['id'] );
		$field_id = $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		return <<<JS
			new adz_Stax_Payment_Field({$form_id}, '{$field_id}', {$id});
		JS;
	}

    /**
	 * The settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
    public function get_form_editor_button()
    {
        return array(
            'group' => 'pricing_fields',
            'text'  => $this->get_form_editor_field_title(),
            'icon'  => $this->get_form_editor_field_icon(),
            'description' => $this->get_form_editor_field_description()
        );
    }

    /**
	 * Enable this field for use with conditional logic.
	 *
	 * @return bool
	 */
	public function is_conditional_logic_supported() {
		return true;
	}
    
    public function get_form_editor_field_settings() {
		return array(
			'label_setting',
			'description_setting',
			'rules_setting',
			'placeholder_setting',
			'input_class_setting',
			'css_class_setting',
			'size_setting',
			'admin_label_setting',
			'default_value_setting',
			'visibility_setting',
			'conditional_logic_field_setting',
		);
	}

}
