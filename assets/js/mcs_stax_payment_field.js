
// Create js class
class Mcs_Stax_Payment_Field {

  form_id = undefined;
  form_element_id = undefined;
  form = undefined;
  field = undefined;

  // Constructor
  constructor(form_id, field) {
    console.log('McsStaxPaymentField constructor');
    this.form_id = form_id;
    this.form_element_id = 'gform_' + form_id;
    this.form = document.getElementById(this.form_element_id);
    console.log("Field ID: ", field);
    this.field = field;
    this.init();
  }

  // Init
  init() {
    console.log('McsStaxPaymentField init');
    this.set_form_values();
    this.ui_elements();
    this.activate_stax_payment_field();
    this.observe_price_total();
    this.get_payment_extra_details();
  }

  set_form_values() {
    // Form values
    this.first_name = jQuery(this.form).find('.mcs_stax_field[name="first-name"]').val();
    this.last_name = jQuery(this.form).find('.mcs_stax_field[name="last-name"]').val();
    this.account_number = jQuery(this.form).find('.mcs_stax_field[name="account-number"]').val();
    this.routing_number = jQuery(this.form).find('.mcs_stax_field[name="routing-number"]').val();
    this.email = jQuery(this.form).find('.mcs_stax_field[name="email"]').val();
    this.phone = jQuery(this.form).find('.mcs_stax_field[name="phone"]').val();
    this.company = jQuery(this.form).find('.mcs_stax_field[name="company"]').val();
    this.address_1 = jQuery(this.form).find('.mcs_stax_field[name="address_1"]').val();
    this.address_2 = jQuery(this.form).find('.mcs_stax_field[name="address_2"]').val();
    this.address_city = jQuery(this.form).find('.mcs_stax_field[name="address_city"]').val();
    this.address_state = jQuery(this.form).find('.mcs_stax_field[name="address_state"]').val();
    this.address_zip = jQuery(this.form).find('.mcs_stax_field[name="address_zip"]').val();
    this.address_country = jQuery(this.form).find('.mcs_stax_field[name="address_country"]').val();
    this.notes = jQuery(this.form).find('.mcs_stax_field[name="notes"]').val();
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
      firstname: this.first_name,
      lastname: this.last_name,
      // email: "test@test.test",
      // phone: '5555555555',
      // company: "Company INC",
      // address_1: "100 S Orange Ave",
      // address_2: "",
      // address_city: "Orlando",
      // address_state: "FL",
      // address_zip: "32811",
      // address_country: "USA",
      // notes: "this customer created while tokenizing a bank account",
      person_name: this.first_name + ' ' + this.last_name,
      method: "bank",
      bank_type: "savings",
      bank_name: "Bank INC",
      bank_account: this.account_number,
      bank_routing: this.routing_number,
      bank_holder_type: "personal",
      total: this.get_total()
    };
  }

  observe_price_total() {
    // Get form total value and insert it to the pay button using jquery
    var total_query = '#' + this.form_element_id + ' .mcs_stax_total_payment input';
    this.set_total(jQuery(total_query).val());
    var stax_payment_field = this;
    jQuery(document).on('change', total_query, function () {
      var total = jQuery(this).val();
      console.log('Total: ', total);
      stax_payment_field.set_total(total);
    });
  }

  get_total() {
    // use jquery
    let total_query = `#${this.form_element_id} .mcs_stax_field_group_${this.form_id} #paybutton`;
    let total = jQuery(total_query).data("total");
    // remove the currency
    total = total.replace('$', '');
    return total;
  }

  set_total(total) {
    let pay_button_query = `#${this.form_element_id} .mcs_stax_field_group_${this.form_id} #paybutton`;
    let pay_button = jQuery(pay_button_query);
    pay_button.data('total', total);
    pay_button.html('Pay ' + total);
    return total;
  }

  activate_stax_payment_field() {
    console.log('activate_stax_payment_field', this.form_element_id);

    var payButton = document.querySelector('#paybutton');
    var tokenizeButton = document.querySelector('#tokenizebutton');

    // Init Stax JS SDK
    var staxJs = new StaxJs('My-Custom-Software-77504d270a67', {});

    var stax_payment_field = this;

    // Pay with bank
    payButton.onclick = (event) => {
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

            // Set value of hidden field to payment method id
            stax_payment_field.field.value = completedTransaction.url;
            stax_payment_field.form.submit();
          }
        })
        .catch(err => {
          // handle errors here
          // console.log('unsuccessful payment:', err);
          console.log(err);
          stax_payment_field.error_element.textContent = 'unsuccessful payment';
          stax_payment_field.error_element.classList.add("visible");
          stax_payment_field.loader_element.classList.remove("visible");
        });
    }
    
  }

}