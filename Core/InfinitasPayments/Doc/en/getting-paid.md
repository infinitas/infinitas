There are a number of ways that you can request and recieve money through the InfinitasPayments plugin. A brief overview of each method is available below.

------

### Directly (initiated by the user)

#### API

Many payment gateways offer transactions through an API which is generally more secure for stores and customers. There is less chance of form tampering or communication interception. InfinitasPayments provides a simple unified interface for interactig with payment gateway APIs that makes it much easier to accept payments through various providers.

The basic overview for payments via the provider APIs is as follows:

- The user submits an order
- The details are forwarded to the provider which returns a special key.
- The user is then forwarded to the provider to accept the payment.
- Once the payment is successful either a new request is made to the provider, or the details are returned at a later date.

##### PayPal [Express Checkout](https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECGettingStarted)

An overview of PayPal express:

- Display the order details for confirmation
- User accepts and details are sent in the backend to PayPal
- PayPal returns a token for the transaction
- User is redirected to PayPal with the token
- After accepting the payment the user is redirected back to the site.
- Another request is made to PayPal for the outcome
- PayPal returns details of the transaction such as the total, billing details, user information etc.
- Events are triggered for the requesting plugin to finalise the order and do what is required.

> If a transaction is cancelled by a user an event is still triggered, but with information about the cancellation.

#### Forms

Accepting payments with forms is generally much quicker to implement from scratch and used on sites where languages such as PHP are not available. These methods can be integrated with HTML only so does not require all the backend work, but does leave site owners susceptible to form tampering.

##### PayPal [Payments Standard](https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_cart_upload)

PayPal Payments Standard is a more simple approach to requesting payments that uses forms.

------

### Indirectly (recurring billing)

Normally the person making the payment is **present** for the transaction, either entring their credit card details or logging intot the provider to accept the charge. When it comes to recurring payments this is not practical so it becomes necessary to request payments without the user being involved.

The basic flow of this is as follows.

- The user subscribes to your service and a recurring billing request is made to the provider
- The user acknowledges the request, sometimes with a soft limit or some other conditions
- The billing details are stored such as when, how much and tokens used to request future payments
- Requests are sent out periodically as agreed to get the payments

> Recurring can either be for a set number of payments or installments, or continuously until either party cancels the billing

#### PayPal [Express recurring](https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECRecurringPayments)

The process for recurring billing is similar to the PayPal Express Checkout except instead of requesting money, a request is made for permission to bill the user periodically.