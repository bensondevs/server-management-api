## Payment

-------------------------------------------------------
### 1. Pay Order
-------------------------------------------------------

**Endpoint:** `/api/payments/pay_order`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:**
Payload name | Required | Validation | Description		
-------------|----------|------------|-------------
id | Required | string | ID of order that will be paid
billing_city | Required | string | City of user billing
billing_country | Required | string | Country of user billing
billing_line1 | Required | string | The address of user billing
billing_line2 | Optional | string | The address of user billing
billing_line3 | Optional | string | The address of user billing
billing_postcode | Required | string | Post code of user billing
billing_state | Required | string | State of the user billing

**cURL Request Example:**
```
curl --location --request POST 'http://localhost:8001/api/payments/pay_order/seb' \
--header 'Authorization: Bearer 6|xobNcJGIBGiUZ9j35b4v8liI9gUz9ZL4O0fwr9qY' \
--header 'Accept: application/json' \
--form 'order_id="69845700-fc31-11eb-8ce2-d5ee90d96996"' \
--form 'billing_city="Vilnius"' \
--form 'billing_country="Lithuania"' \
--form 'billing_line1="Another Road city"' \
--form 'billing_postcode="726731"' \
--form 'billing_state="Lithuania"'
```

**Response Attributes:**
Attribute Name  |   Type    |   Description     
----------------|-----------|---------------
data | Object | Object of payment, contains the information about payment. This object has `vendor_api_response` which come from external payment API. This contains `payment_link` that will be needed to redirect the user to move to payment page. If not, user can choose payment methods through links available inside `payment_methods`
status | string | Status of the response request
message  | string | Message response from API 