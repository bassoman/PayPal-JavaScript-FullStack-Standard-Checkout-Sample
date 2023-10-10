// create dues years selector element
var annualPrimaryDues = 20.0;
var annualFamilyDues = 3;
var currentYear = new Date().getFullYear();
var currentMonth = new Date().getMonth();
var tnxfee = 0.0349;
var serviceUse = 0.49;

var jkl_render = function (template, node) {
	node.innerHTML = template;
};

var generate_years = function () {
  // let newMember = document.getElementById('newchecked').checked;
  // let cDues = 20;
  let ele = '<label for="dues-thru">Pay dues through year: </label><select name="dues-thru" id="dues-thru" onchange="computeAmount(this)">';
  ele = ele + "<option value='{\"year\": 0, \"dues\": 0}'>Select</option>";
  yr = parseInt(currentYear)
  for (y of [0, 1, 2, 3, 4]) {
    // console.log(newMember);
    // if (newMember) { cDues = 10 } else { cDues = 20 };
    ele = ele + ''.concat("<option value='{\"year\": ",(yr+parseInt(y)),", \"dues\": ",20*(y+1),"}'>",(yr+parseInt(y)),"</option>");
  }
  ele = ele + '</select>'
  return ele;
};
jkl_render(generate_years(), document.querySelector('#input-pick-years'));

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
        }
      } catch (error) {
        console.error(error);
        resultMessage(
          `Sorry, your transaction could not be processed...<br><br>${error}`,
        );
      }
    },
  })
  .render("#paypal-button-container");

// Example function to show a result to the user. Your site's UI library can be used instead.
function resultMessage(message) {
  const container = document.querySelector("#result-message");
  container.innerHTML = message;
}

function computeAmount(input) {
  // console.log(input1.id);
  document.getElementById('amount').value = 0;
  switch(input.id) {
    case 'tnxfee':
      // code block
      break;
    case 'newchecked':
      // code block
      break;
    case 'dues':
      // code block
      break;
    case 'donation':
      // code block
      break;
    case 'dues-thru':
        // code block
        // console.log(document.getElementById('dues-thru').value)
        break;
    default:
      // code block
  }
  var amt = 0;
  // var amt = document.getElementById('amount');
  amt = JSON.parse(document.getElementById('dues-thru').value).dues;
  //  + parseFloat(document.getElementById('donation').value);

  if (amt > 0) {
    document.getElementById('paypal-button-container').hidden = false
  } else {
    document.getElementById('paypal-button-container').hidden = true
  }
  let newMember = document.getElementById('newchecked').checked;
  if (newMember) {
    var n = 12.0 - currentMonth;
    console.log(n);
    amt = amt - (n/12*annualPrimaryDues);
  }

  let payFee = document.getElementById('tnxfee').checked;
  if (amt > 0) {
    if (payFee) {
      amt = amt + (tnxfee * amt) + serviceUse;
    }
    document.getElementById('amount').value = amt.toFixed(2);
  }
  // console.log(amt.toFixed(2))
}