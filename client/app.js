var jkl_render = function (template, node) {
	node.innerHTML = template;
};

const params = new URLSearchParams(document.location.search);
var formData = {
  "primaryDues": params.get("P1"),
  "associateDues": params.get("P2"),
  "repeaterDonation": params.get("P3"),
  "digipeaterDonation": params.get("P4"),
  "optionalFee": params.get("P5"),
  "callsign": params.get("P6")
};
document.querySelector('#primary-dues').value = parseFloat(formData.primaryDues).toFixed(2);
document.querySelector('#associate-dues').value = parseFloat(formData.associateDues).toFixed(2);
document.querySelector('#repeater-donation').value = parseFloat(formData.repeaterDonation).toFixed(2);
document.querySelector('#digipeater-donation').value = parseFloat(formData.digipeaterDonation).toFixed(2);
document.querySelector('#optional-fee').value = parseFloat(formData.optionalFee).toFixed(2);
document.querySelector('#callsign').value = formData.callsign;

computeAmount();

// jkl_render(annualPrimaryDues.toFixed(2), document.querySelector('#primary-dues'));
// jkl_render(annualFamilyDues.toFixed(2), document.querySelector('#family-dues'));
// jkl_render(tnxfee.toFixed(2), document.querySelector('#tnx-fee'));
// jkl_render(serviceUse.toFixed(2), document.querySelector('#service-use'));

// var generate_years = function () {
//   let ele = '<label for="dues-thru">Pay Primary Dues through year: </label><select name="dues-thru" id="dues-thru" onchange="computeAmount(this)">';
//   ele = ele + "<option value='{\"year\": 0, \"dues\": 0}'>Select</option>";
//   yr = parseInt(currentYear)
//   for (y of [0, 1, 2, 3, 4]) {
//     ele = ele + ''.concat("<option value='{\"year\": ",(yr+parseInt(y)),", \"dues\": ",annualPrimaryDues*(y+1),"}'>",(yr+parseInt(y)),"</option>");
//   }
//   ele = ele + '</select>'
//   return ele;
// };
// jkl_render(generate_years(), document.querySelector('#input-pick-years'));

window.paypal
  .Buttons({
    style: {
      shape: 'rect',
      //color:'blue', change the default color of the buttons
      layout: 'vertical', //default value. Can be changed to horizontal
    },
    async createOrder() {
      try {
        const response = await fetch("/api/orders", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          // use the "body" param to optionally pass additional order information
          // like product ids and quantities
          body: JSON.stringify({
            cart: [
              {
                id: "Payment",
                quantity: document.querySelector("#amount").value,
              },
            ],
          }),
        });

        const orderData = await response.json();

        if (orderData.id) {
          return orderData.id;
        } else {
          const errorDetail = orderData?.details?.[0];
          const errorMessage = errorDetail
            ? `${errorDetail.issue} ${errorDetail.description} (${orderData.debug_id})`
            : JSON.stringify(orderData);

          throw new Error(errorMessage);
        }
      } catch (error) {
        console.error(error);
        resultMessage(`Could not initiate PayPal Checkout...<br><br>${error}`);
      }
    },
    async onApprove(data, actions) {
      try {
        const response = await fetch(`/api/orders/${data.orderID}/capture`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
        });

        const orderData = await response.json();
        // Three cases to handle:
        //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
        //   (2) Other non-recoverable errors -> Show a failure message
        //   (3) Successful transaction -> Show confirmation or thank you message

        const errorDetail = orderData?.details?.[0];

        if (errorDetail?.issue === "INSTRUMENT_DECLINED") {
          // (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
          // recoverable state, per https://developer.paypal.com/docs/checkout/standard/customize/handle-funding-failures/
          return actions.restart();
        } else if (errorDetail) {
          // (2) Other non-recoverable errors -> Show a failure message
          throw new Error(`${errorDetail.description} (${orderData.debug_id})`);
        } else if (!orderData.purchase_units) {
          throw new Error(JSON.stringify(orderData));
        } else {
          // (3) Successful transaction -> Show confirmation or thank you message
          // Or go to another URL:  actions.redirect('thank_you.html');
          const transaction =
            orderData?.purchase_units?.[0]?.payments?.captures?.[0] ||
            orderData?.purchase_units?.[0]?.payments?.authorizations?.[0];
          resultMessage(
            `Transaction ${transaction.status}: ${transaction.id}<br><br>See console for all available details`,
          );
          console.log(
            "Capture result",
            orderData,
            JSON.stringify(orderData, null, 2),
          );
          // window.location.href = "https://coastsidearc.org/www.coastsidearc.org/paypalsuccess.php";
        }
      } catch (error) {
        console.error(error);
        resultMessage(
          `Sorry, your transaction could not be processed...<br><br>${error}`,
        );
        // window.location.href = "https://coastsidearc.org/www.coastsidearc.org/paypalcancel.php";
      }
      window.location.href = "https://coastsidearc.org/www.coastsidearc.org/paypalcancel.php";
    },
    onCancel(data) {
        // Show a cancel page, or return to cart
        window.location.href = "https://coastsidearc.org/www.coastsidearc.org/paypalcancel.php";
    }
  })
  .render("#paypal-button-container");

// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}

function computeAmount() {
  // console.log(input1.id);
  // switch(input.id) {
  //   case 'tnxfee':
  //     // code block
  //     break;
  //   case 'newchecked':
  //     // code block
  //     break;
  //   case 'dues':
  //     // code block
  //     break;
  //   case 'donation':
  //     // code block
  //     break;
  //   case 'dues-thru':
  //       // code block
  //       // console.log(document.getElementById('dues-thru').value)
  //       break;
  //   default:
  //     // code block
  // }
  document.getElementById('amount').value = 0;
  var amt = 0;
  amt = amt + parseFloat(formData.primaryDues);
  amt = amt + parseFloat(formData.associateDues);
  amt = amt + parseFloat(formData.repeaterDonation);
  amt = amt + parseFloat(formData.digipeaterDonation);
  amt = amt + parseFloat(formData.optionalFee);
  
  if (amt > 0) {
    document.getElementById('paypal-button-container').hidden = false
  } else {
    document.getElementById('paypal-button-container').hidden = true
  }

  // let newMember = document.getElementById('newchecked').checked;
  // if (newMember) {
  //   var n = 12.0 - currentMonth;
  //   console.log(n);
  //   amt = amt - (n/12*annualPrimaryDues);
  // }

  // let payFee = document.getElementById('tnxfee').checked;
  // if (amt > 0) {
  //   if (payFee) {
  //     amt = (tnxfee/100+1) * amt + serviceUse;
  //   }
  document.getElementById('amount').value = amt.toFixed(2);
  // console.log(amt.toFixed(2))
}