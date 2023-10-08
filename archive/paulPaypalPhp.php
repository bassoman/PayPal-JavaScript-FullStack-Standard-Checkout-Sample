
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <title>AppFormQuickKey8</title>
        <meta charset="UTF-8">
        <meta name="description" content="Page for Dues Renewal and PayPal interface">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Brad Traversy">
        <!--See: https://github.com/bradtraversy/node_paypal_sdk_sample/blob/master/app.js -->

        <!-- cache directives suggested by Jon Lancelle 9/14/2023 -->
        <!--
        <meta http-equiv="refresh" content="0" />
        -->

        <!--
        <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        -->
        <!-- END cache directives suggestions-->


        <!-- The little php code in the links below force the css sheets to reload each time -->
        <link href="css/Layout.css?v1=1696751530"       media="all" rel="stylesheet"  type="text/css">
        <link href="css/AppFormQuick.css?v2=1696751530" media="all" rel="stylesheet"  type="text/css">
        <!-- Email Javascript code fragment -->
        <style>
            /* The container */
            .yrcontainer {
                display: block;
                position: relative;
                padding-left: 35px;
                margin-bottom: 12px;
                cursor: pointer;
                font-size: 22px;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            /* Hide the browser's default checkbox */
            .yrcontainer input {
                position: absolute;
                opacity: 0;
                cursor: pointer;
                height: 0;
                width: 0;
            }
            
            /* Create a custom checkbox */
            .yrcheckmark {
                position: absolute;
                top: 0;
                left: 0;
                height: 25px;
                width:  25px;
                background-color: #eee;
                border: 1px solid var(--main-text-color);
            }

            /* On mouse-over, add a grey background color */
            .yrcontainer:hover input ~ .yrcheckmark {
                background-color: #ccc;
            }

            /* When the checkbox is checked, add a blue background */
            .yrcontainer input:checked ~ .yrcheckmark {
                background-color: #2196F3;
            }


            /* Create the checkmark/indicator (hidden when not checked) */
            .yrcheckmark:after {
                content: "";
                position: absolute;
                display: none;
            }

            /* Show the checkmark when checked */
            .yrcontainer input:checked ~ .yrcheckmark:after {
                display: block;
            }

            /* Style the checkmark/indicator */
            .yrcontainer .yrcheckmark:after {
                left: 9px;
                top: 5px;
                width: 5px;
                height: 10px;
                border: solid white;
                border-width: 0 3px 3px 0;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
            }

            .spacer {
                width: 1.75in;
                margin: 0.75in;
            }

            .h3 {
                align: center;
            }
        </style>
    </head>

    <body>
        <!-- The page header --> 
        <table width=723px>
            <tr>
                <td colspan = 2>
                    <div class = "header">
                        <!-- **** This fragment is: /includes/CommonHeader.php -->
<!-- Introduce header images and label 'logo' used to detect mouse-click -->
<img class="center" src="/images/shim.gif" alt="" height="10" width="780">
<img class="center" id="logo" src="/logo/wa6tow-logo.gif" alt="" width="135" height="135">
<img class="center" src="/images/shim.gif" alt="" height="10">

<script>
  window.onload = addListeners;

  function addListeners(){
    document.getElementById('logo').addEventListener("click",GoToHomePage,false);
    var img = document.createElement('img');
    img.setAttribute('src', '../logo/wa6tow-logo.gif');
  }

  // Functions associated with return to home page
  hostname = window.location.origin; // can be deleted, for debug

  function GoToHomePage() {
    window.location.href = window.location.origin;
  }
</script>
<!-- **** End of fragment: /includes/CommonHeader.php -->
                        <img class="center" src="../images/PayPalOptions_bnr.gif" height="60" alt="PayPalOptions">
                    </div> <!-- header ends -->
                </td>
            </tr>

            <tr>
                <td colspan=2>
                    <fieldset style="background: inherit;  width: 100%"; >
                        <legend style = "align-left">Paypal Details</legend>
                        <div id = "fieldShield">
                            <h3>Dues Renewal form for the Coastside Amateur Radio Club</h3>
                            <p style="align:left">
                                Currently, the basic membership fee is $20.00/year for the primary 
                                membership in a household. Additional members in a household 
                                are $3.00/year each.

                            <p style="align-left"> 
                                For Associate (unlicensed) members, enter 
                                the first initial and last name of the Associate as a simple 
                                text string (e.g. J_SMITH) with no space.
                                
                            <p style="align-left"> 
                                If you are a new member, your membership fee for the current year 
                                will be pro-rated. The month you are joining will be determined 
                                from the form submission date. Please check the box below if you 
                                are a new member.

                            
                            <p style="align-left"> 
                                <label for = "NewMember">NewMember?
                                <input  type="checkbox" 
                                        id="newchecked"
                                        onclick="newmember_change()">
                                </label>

                            <p style="align-left">   
                                Paypal charges a transaction fee of 3.49% plus a base of 49¢ 
                                for the use of their service. This form calculates that fee and 
                                allows members to offset this fee when they are paying their dues.
                                For each transaction, the form calculates the amount estimated to
                                be received by the club and the amount estimated to be received
                                by PayPal, as well as the total amount of the transaction.</p>

                            <p style="align-left">
                                Please Check Below to select which year(s) you wish to renew for. 
                                Each checked box adds one annual fee when they are paying their dues.</p>

                            <table id="formtable">
                                <tr>
                                    <table id="yeartab">
                                        <tr>
                                            <td><var class="spacer"></td></td>
                                            <td><var class="spacer"></td></td>
                                            <td>
                                                <label class="yrcontainer">2023
                                                    <input  type="checkbox"
                                                            name="yrchecked" 
                                                            onclick="years_change()">
                                                    <span class="yrcheckmark"></span>
                                                </label>
                                            </td>
                                            <td><var class="spacer"></td></td>
                                            <td>
                                                <label class="yrcontainer">2024
                                                    <input  type="checkbox" checked
                                                            name="yrchecked" 
                                                            onclick="years_change()">
                                                    <span class="yrcheckmark"></span>
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><var class="spacer"></td></td>
                                            <td><var class="spacer"></td></td>
                                            <td>
                                                <label class="yrcontainer">2025
                                                <input  type="checkbox" checked
                                                            name="yrchecked"
                                                            onclick="years_change()">
                                                    <span class="yrcheckmark"></span>
                                                </label>
                                            </td>
                                            <td><var class="spacer"></td></td>
                                            <td>
                                                <label class="yrcontainer">2026
                                                    <input  type="checkbox" 
                                                            name="yrchecked" 
                                                            onclick="years_change()">
                                                    <span class="yrcheckmark"></span>
                                                </label>
                                            </td>
                                        <tr>
                                    </table>
                                </tr>

                                <!-- Years Count-->
                                <tr>
                                    <td><div><p id ="numChecked"></p></div></td>
                                </tr>

                                <!-- For the following callsigns -->
                                <tr>
                                    <td><label for="callsigns">Callsigns (Primary & Family): </label>
                                    <td>
                                        <input type="text" 
                                            name="callsigns" 
                                            id="callsigns" 
                                            style="width: 300px; font-size: 12pt; text-transform: uppercase;"
                                            value = "TESTMEMBER1 TESTMEMBER2"
                                            oninput="callsign_change()">
                                    </td>
                                </tr>

                                <!-- Now Time -->
                                <tr>
                                    <td><label for="NowTime">Server Time:</label></td>
                                    <td><div><p id ="NowTime"></p></div></td>
                                </tr>

                                <!-- ProRata -->
                                <tr>
                                    <td><label for="ProRata">Pro Rata Factor: </label></td>
                                    <td><div><p id="ProRata"></p></div></td>
                                </tr>

                                <!-- Primary -->
                                <tr> 
                                    <td><label for="primary">Primary:</label></td>
                                    <td><div><p id ="primary"></p></div></td>
                                </tr>

                                <!-- Family -->
                                <tr>
                                    <td><label for="family">Family:</label></td>
                                    <td><div><p id="family"></p></div></td>
                                </tr>
                            
                                <!-- Repeater Fund Donation -->
                                <tr>
                                    <td><label for="repeater">Repeater Fund: </label>
                                    <td>
                                        <input type="text" 
                                            name="repeater" 
                                            id="repeater" 
                                            checked
                                            style="width: 45px; font-size: 12pt;"
                                            value = "72.65";
                                            oninput="repeater_change()">
                                    </td>
                                </tr>

                                <!-- Digipeater Fund Donation -->
                                <tr>
                                    <td><label for="digipeater">Digipeater/APRS Fund: </label>
                                    <td>
                                        <input type="text" 
                                            name="digipeater" 
                                            id="digipeater" 
                                            checked
                                            style="width: 45px; font-size: 12pt;"
                                            value = "0";
                                            oninput="digipeater_change()">
                                    </td>
                                </tr>     

                                <!-- Subtotal Donation -->
                                <tr>
                                    <td><label for="subtotal">Subtotal:</label></td>
                                    <td><div><p id="subtotal"></p></div></td>
                                </tr>

                                <!-- Paypal fee checkbox -->
                                <tr>
                                    <td><label for="checked">Include paypal fee? :</label></td>
                                    <td>
                                        <input  type="checkbox"
                                                name="checked" checked
                                                id = "checked"
                                                width = "150%"
                                                height = "150%"
                                                value = "on";
                                                oninput="amount_change()">
                                    </td>
                                </tr>

                                <!-- Optional -->
                                <tr>
                                    <td><label for="optional">Optional Paypal Fee:</label></td>
                                    <td><div><p id="optional"></p></div></td>
                                </tr>

                                <!-- Paypal Receives -->
                                <tr>
                                    <td><label for="paypal">Paypal Receives:</label></td>
                                    <td><div><p id="paypal"></p></div></td>
                                </tr>

                                <!-- Club Receives -->
                                <tr>
                                    <td><label for="club">Club Receives:</label></td>
                                    <td><div><p id="club"></p></div></td>
                                </tr>

                                <!-- Total -->
                                <tr>
                                    <td><label for="total">Total Charges:</label></td>
                                    <td><div><p id="total"></p></div></td>
                                </tr>

                                <tr><td colspan="2">
                                    <!-- Submit button -->
                                    <button id="gonow" onClick="onSubmit()">Pay Now</button>
                                </td></tr>

                                <!-- MyOwn Temporary Container -->
                                <tr>
                                    <td colspan="2">
                                        <div id="my-detail-container" 
                                            style="margin-left: 20%; 
                                                    width:60%; 
                                                    text-align: center;" >
                                        </div>
                                    </td>
                                </tr>

                                <!-- The PayPal Container -->                                 
                                <tr>
                                    <td colspan="2">
                                        <div id="paypal-button-container" style="margin-left: 35%; width:30%;" >
                                            <div style="text-align: center;">
                                                <div id="smart-button-container" ></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </div>
                    </fieldset>

                            <!-- The footer -->
                            <tr>
                                <td colspan="2">
                                    <div class="bottom">
                                        <!-- **** This fragment is: /includes/CommonFooter.php -->
Last modified: September 22, 2023 14:21:45<br>For questions or comments about this website:
      Contact webmaster at: <a href='mailto:info@CoastsideARC.org'>info@CoastsideARC.ORG</a>
      <br>Copyright &copy; 2000-2023, Coastside Amateur Radio Club,
      All Rights Reserved<!-- **** End of fragment: /includes/CommonFooter.php -->
                                    </div> <!-- bottom end -->
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </table>
    </body>

    <!-- Begin Brad's Code Contribution from github, as referenced above in the <head> -->
    <script type="text/javascript">
        
        const express = require('express');
        const ejs = require('ejs');
        const paypal = require('paypal-rest-sdk');
    
        jon_cred = false;

        if (jon_cred == true) {
            // Use Jon's sandbox credentials
            sbp =       "sb-nqa5927347739@personal.example.com";
            sbb =       "sb-479yha27298123@business.example.com";
            client_ID = "AfCdrJWhUY4jCGKxamdGyFCmbyDH-WXEtygBpCK631ogaD-ex0ErZqz12PsVsbYplbfnywAGDOAJWDlz";
            secret =    "EN_jhGbuaw1OoW3lxIj8H3AMbteWVw_VjB9S_s3S0plqV6h2ZrIHue8Ln--6YFtNGyRQvzjTIiFjTluY";
        } else {
            // Use Paul's sandbox credentials
            sbp =       "sb-mxmr4322172943@personal.example.com";
            sbb =       "sb-t9ga4322172945@business.example.com";
            client_ID = "AeCsscwxPGlGu-9UtOzb7UDOPdIWe-wn3zybK9Zc5f7nsCH0R0es93zxDi97s1dDGYa93S6zWJU81G5-";
            secret =    "EOo983vYorvcbLHZrJ1ySYOsMQ-wS0DDWcylxj0uWBNFsUxGnim-_1CDSFNC5q2XAb3S6Gnur6YyxnUA";
        }

        const app = express();

        app.set('view engine', 'ejs');

        app.get('/', (req, res) => res.render('index'));

        app.post('/pay', (req, res) => {
        const create_payment_json = {
            "intent": "sale",
            "payer": {
                "payment_method": "paypal"
            },
            "redirect_urls": {
                "return_url": "http://localhost:3000/success",
                "cancel_url": "http://localhost:3000/cancel"
            },
            "transactions": [{
                "item_list": {
                    "items": [{
                        "name": "Red Sox Hat",
                        "sku": "001",
                        "price": "25.00",
                        "currency": "USD",
                        "quantity": 1
                    }]
                },
                "amount": {
                    "currency": "USD",
                    "total": "25.00"
                },
                "description": "Dues Payment to CARC"
            }]
        };

        paypal.payment.create(create_payment_json, function (error, payment) {
        if (error) {
            throw error;
        } else {
            for(let i = 0;i < payment.links.length;i++){
                if(payment.links[i].rel === 'approval_url'){
                res.redirect(payment.links[i].href);
                }
            }
        }
        });

        });

        app.get('/success', (req, res) => {
        const payerId = req.query.PayerID;
        const paymentId = req.query.paymentId;

        const execute_payment_json = {
            "payer_id": payerId,
            "transactions": [{
                "amount": {
                    "currency": "USD",
                    "total": "25.00"
                }
            }]
        };

        paypal.payment.execute(paymentId, execute_payment_json, function (error, payment) {
            if (error) {
                console.log(error.response);
                throw error;
            } else {
                console.log(JSON.stringify(payment));
                res.send('Success');
            }
        });
        });

        app.get('/cancel', (req, res) => res.send('Cancelled'));

        app.listen(3000, () => console.log('Server Started'));
    </script>
</html>