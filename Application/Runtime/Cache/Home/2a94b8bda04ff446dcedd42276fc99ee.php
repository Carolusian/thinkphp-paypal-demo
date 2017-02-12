<?php if (!defined('THINK_PATH')) exit();?><html>
  <head>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
paypal.Button.render({

  env: 'production', // Specify 'sandbox' for the test environment

  payment: function() {
    // Set up the payment here, when the buyer clicks on the button
  },

  onAuthorize: function(data, actions) {
    // Execute the payment here, when the buyer approves the transaction
  }

}, '#paypal-button');
    </script>
  </head>
  <body>
    <div id="paypal-button">
    </div>
  </body>
</html>