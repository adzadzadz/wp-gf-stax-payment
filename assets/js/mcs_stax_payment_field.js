
// Create js class
class adz_Stax_Payment_Field {

  stax_api_config_mode = 'live'; // 'sandbox' or 'live
  stax_api_key_live = 'Balloon-Artistry-8f7ce18b661d';
  stax_api_key_sandbox = 'My-Custom-Software-77504d270a67';
  form_id = undefined;
  form_element_id = undefined;
  form = undefined;
  field_element_id = undefined;
  field_id = undefined;

  // Constructor
  constructor(form_id, field_element_id, field_id) {
    console.log('adzStaxPaymentField constructor');
    this.form_id = form_id;
    this.form_element_id = 'gform_' + form_id;
    this.form = document.getElementById(this.form_element_id);
    console.log("field_id", form_id, field_id)
    this.field_element_id = field_element_id;
    this.field_id = field_id;
    this.init();
  }

  // Init
  init() {
    console.log('adzStaxPaymentField init', this.stax_api_config_mode);
    this.set_form_values();
    this.ui_elements();
    this.activate_stax_payment_field();
    this.observe_price_total();
    // this.hide_submit_button();
    // this.get_payment_extra_details();
  }

  observe_name_fields() {
    // Full Name
    jQuery("#input_5_22").on('change', function () {
      console.log('First name changed');
    });
  }

  get_stax_api_key() {
    if (this.stax_api_config_mode == 'sandbox') {
      return this.stax_api_key_sandbox;
    }
    if (this.stax_api_config_mode == 'live') {
      return this.stax_api_key_live;
    }
  }

  trigger_submit_button() {
    let submit_button = jQuery(this.form).find(`input[type="submit"].gform_button`);
    submit_button.removeAttr('disabled');
    submit_button.click();
  }

  hide_submit_button() {
    let stax_input = jQuery(this.form).find(`input[name="input_${this.field_id}"]`);
    console.log('stax_input', stax_input);
    if (stax_input.length > 0) {
      let submit_button = jQuery(this.form).find(`input[type="submit"].gform_button`);
      console.log('submit_button', submit_button);
      submit_button.hide();
    }
  }

  set_form_values() {
    // Form values
    this.first_name = jQuery(this.form).find('.adz_stax_field[name="first-name"]').val();
    this.last_name = jQuery(this.form).find('.adz_stax_field[name="last-name"]').val();
    this.person_name = jQuery(this.form).find('#input_5_22').val();

    console.log('person_name', this.person_name);
    // this.email = jQuery(this.form).find('.adz_stax_field[name="email"]').val();
    this.email = jQuery(this.form).find('#input_5_3').val();

    this.phone = jQuery(this.form).find('.adz_stax_field[name="phone"]').val();
    this.company = jQuery(this.form).find('.adz_stax_field[name="company"]').val();
    this.address_1 = jQuery(this.form).find('.adz_stax_field[name="address_1"]').val();
    this.address_2 = jQuery(this.form).find('.adz_stax_field[name="address_2"]').val();
    this.address_city = jQuery(this.form).find('.adz_stax_field[name="address_city"]').val();
    this.address_state = jQuery(this.form).find('.adz_stax_field[name="address_state"]').val();
    this.address_zip = jQuery(this.form).find('.adz_stax_field[name="address_zip"]').val();
    this.address_country = jQuery(this.form).find('.adz_stax_field[name="address_country"]').val();
    this.notes = jQuery(this.form).find('.adz_stax_field[name="notes"]').val();

    this.bank_name = jQuery(this.form).find('.adz_stax_field[name="bank-name"]').val();
    this.account_number = jQuery(this.form).find('.adz_stax_field[name="account-number"]').val();
    this.routing_number = jQuery(this.form).find('.adz_stax_field[name="routing-number"]').val();
    // get the account type that is checked
    this.account_type = jQuery(this.form).find('.adz_stax_field_radio[name="account-type"]:checked').val();
    // get the bank holder type that is checked
    this.bank_holder_type = jQuery(this.form).find('.adz_stax_field_radio[name="bank-holder-type"]:checked').val();
  }

  ui_elements() {
    // UI elements
    this.success_element = document.querySelector(".success");
    this.error_element = document.querySelector(".error");
    this.loader_element = document.querySelector(".loader");
  }

  get_payment_extra_details() {
    this.set_form_values()
    return {
      firstname: this.first_name ?? '',
      lastname: this.last_name ?? '',
      email: this.email ?? '',
      phone: this.phone ?? '',
      company: this.company ?? '',
      address_1: this.address_1 ?? '',
      address_2: this.address_2 ?? '',
      address_city: this.address_city ?? '',
      address_state: this.address_state ?? '' ?
        address_zip : this.address_zip ?? '',
      address_country: this.address_country ?? '',
      notes: this.notes ?? '',
      person_name: this.person_name ?? '',
      method: "bank",
      bank_type: this.account_type,
      bank_name: this.bank_name,
      bank_account: this.account_number,
      bank_routing: this.routing_number,
      bank_holder_type: this.bank_holder_type,
      total: this.get_total()
    };
  }

  observe_price_total() {
    // Get form total value and insert it to the pay button using jquery
    var total_query = '#' + this.form_element_id + ' input.ginput_total';
    console.log('total', jQuery(total_query).val());
    this.set_button_total(jQuery(total_query).val());
    var stax_payment_field = this;
    jQuery(document).on('change', total_query, function () {
      var total = jQuery(total_query).val();
      console.log('Total: ', total);
      stax_payment_field.set_button_total(total);
    });
  }

  get_total() {
    // use jquery
    let total_query = `#${this.form_element_id} .adz_stax_field_group_${this.form_id} #paybutton`;
    let total = jQuery(total_query).data("total");
    console.log('total', total);
    // remove the currency
    total = total.replace('$', '');
    return total;
  }

  set_button_total(total) {
    let pay_button_query = `#${this.form_element_id} .adz_stax_field_group_${this.form_id} #paybutton`;
    let pay_button = jQuery(pay_button_query);
    let button_text = total.includes('$') ? 'Pay ' + total : 'Pay $' + total;

    pay_button.data('total', total);
    pay_button.html(button_text);

    return total;
  }

  activate_stax_payment_field() {
    console.log('activate_stax_payment_field', this.form_element_id);

    var payButton = document.querySelector('#paybutton');
    // var tokenizeButton = document.querySelector('#tokenizebutton');

    // Init Stax JS SDK
    var staxJs = new StaxJs(this.get_stax_api_key(), {});

    var stax_payment_field = this;

    // Pay with bank
    var submit_button = jQuery(this.form).find(`input[type="submit"].gform_button`);
    submit_button.on('click', function (event) {
      // payButton.onclick = (event) => {
      let field_wrapper_id = `#field_${stax_payment_field.form_id}_${stax_payment_field.field_id}`;
      let field_wrapper = jQuery(field_wrapper_id);
      console.log('field_wrapper', field_wrapper.attr('data-conditional-logic'));
      if (field_wrapper.attr('data-conditional-logic') == 'hidden') {
        console.log('Conditional logic is hidden');
        return;
      }

      event.preventDefault();
      
      stax_payment_field.success_element.classList.remove("visible");
      stax_payment_field.error_element.classList.remove("visible");
      stax_payment_field.loader_element.classList.add("visible");

      staxJs
        .pay(stax_payment_field.get_payment_extra_details())
        .then((completedTransaction) => {
          // completedTransaction is the successful transaction record
          console.log('successful payment:', completedTransaction);
          if (completedTransaction.id) {
            stax_payment_field.success_element.querySelector(".token").textContent = "Success!"
            // completedTransaction.payment_method_id;
            stax_payment_field.success_element.classList.add("visible");
            stax_payment_field.loader_element.classList.remove("visible");

            // Set value of hidden field to invoice id
            jQuery(stax_payment_field.form).find(`input[name="input_${stax_payment_field.field_id}"]`).val(completedTransaction.id);
            // stax_payment_field.field_element_id.value = completedTransaction.id;
            submit_button.closest('form').submit();
          }
        })
        .catch(err => {
          // handle errors here
          console.log('Error Handling');
          console.log(err);
          // if err has key status and value ATTEMPTED, then the transaction was attempted but failed
          var text_errors = '';
          if ('status' in err && err.status == 'ATTEMPTED') {
            text_errors = `<div>${err.payment_attempt_message}</div>`;
          } else {
            
            for (const key in err) {
              if (err.hasOwnProperty(key)) {
                const errorArr = err[key];
                console.log(`${key}:`);
                errorArr.forEach(errors => {
                  text_errors += `<div>${errors}</div>`;
                });
              }
            }
          }
          jQuery(stax_payment_field.error_element).html(text_errors);
          // stax_payment_field.error_element.textContent = 'Unsuccessful payment';
          stax_payment_field.error_element.classList.add("visible");
          stax_payment_field.loader_element.classList.remove("visible");
        });
      // }
    });

  }

}