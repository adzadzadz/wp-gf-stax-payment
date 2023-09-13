<?php

namespace MCS\Stax;

class GF_Payment_Field extends \GF_Field
{
    public $type = 'mcs_stax_payment';


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
			<div class="mcs_stax_field_group">
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
				<label>
					<span>First Name</span>
					<input name="first-name" class="mcs_stax_field" placeholder="Jane" value=""/>
				</label>
				<label>
					<span>Last Name</span>
					<input name="last-name" class="mcs_stax_field" placeholder="Doe" value=""/>
				</label>
				<label>
					<span>Email</span>
					<input name="email" class="mcs_stax_field" placeholder="test@test.test" value=""/>
				</label>
				<label>
					<span>Phone</span>
					<input name="phone" class="mcs_stax_field" placeholder="5555555555" value=""/>
				</label>
				<label>
					<span>Company</span>
					<input name="company" class="mcs_stax_field" placeholder="Company INC" value=""/>
				</label>
				<label>
					<span>Address 1</span>
					<input name="address_1" class="mcs_stax_field" placeholder="100 S Orange Ave" value=""/>
				</label>
				<label>
					<span>Address 2</span>
					<input name="address_2" class="mcs_stax_field" placeholder="" value=""/>
				</label>
				<label>
					<span>City</span>
					<input name="address_city" class="mcs_stax_field" placeholder="Orlando" value=""/>
				</label>
				<label>
					<span>State</span>
					<input name="address_state" class="mcs_stax_field" placeholder="FL" value=""/>
				</label>
				<label>
					<span>Zip</span>
					<input name="address_zip" class="mcs_stax_field" placeholder="32811" value=""/>
				</label>
				<label>
					<span>Country</span>
					<input name="address_country" class="mcs_stax_field" placeholder="USA" value=""/>
				</label>
				<label>
					<span>Notes</span>
					<input name="notes" class="mcs_stax_field" placeholder="this customer created while tokenizing a bank account" value=""/>
				</label>
			</div>

			<div class="mcs_stax_field_group mcs-stax-bank-account-details">
				<label>
					<span>Bank Name</span>
					<input type="text" name="bank-name" class="mcs_stax_field" placeholder="chase" value="Chase">
				</label>
				<label>
					<span>Account #</span>
					<input name="account-number" class="mcs_stax_field" placeholder="9876543210" value="9876543210" />
				</label>
				<label>
					<span>Routing #</span>
					<input name="routing-number" class="mcs_stax_field" placeholder="021000021" value="021000021" />
				</label>
			</div>

			<div class="mcs_stax_field_group mcs-stax-bank-account-type">
				<!-- Account Type radio button -->
				<label>
					<input type="radio" name="account-type" value="savings" checked>
					<span>Savings</span>
				</label>
				<label>
					<input type="radio" name="account-type" value="checking">
					<span>Checking</span>
				</label>
					
				<!-- Bank Holder Type -->
				<label>
					<input type="radio" name="bank-holder-type" value="personal" checked>
					<span>Personal</span>
				</label>
				<label>
					<input type="radio" name="bank-holder-type" value="business">
					<span>Business</span>
				</label>
			</div>

			<div class="mcs_stax_field_group mcs_stax_field_group_{$form_id}">
				<button id="paybutton" data-total="0">Pay $0.00</button>
			</div>
			<!-- <button id="verifybutton">verify $1</button> -->
			<div class="outcome">
				<div class="error"></div>
				<div class="success">
					Successful! The ID is
					<span class="token"></span>
				</div>
				<div class="loader" style="margin: auto">
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
			new Mcs_Stax_Payment_Field({$form_id}, {$field_id});
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
