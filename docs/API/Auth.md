## Authentication

-------------------------------------------------------
### 1. Login
-------------------------------------------------------

**Endpoint:** `/api/auth/login`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json

**Parameters:**

Payload name | Required | Validation | Description		
--------------|----------|------------|---------------
identity | Required | string | Email or username of logging in user
password | Required | string | Password of logging in user

**cURL Request Example:**
```
curl --location --request POST '{base_url}/api/auth/login' \
    --header 'Accept: application/json' \
    --form 'identity="admin"' \
    --form 'password="admin"'
```

**Response Attributes:**
Attribute Name 	|	Type	|	Description		
----------------|-----------|---------------
data | Object | Object of user data with `token` to authenticte request in authenticated API endpoint
status | String | Request Processing status
message | String | Message response for the user

**Success Response Example:**
```json
{
    "status": "success",
    "message": "Successfully login",
    "user": {
        "id": "293b42d0-fcb3-11eb-8ff3-69097724d34f",
        "account_type": "personal",
        "first_name": "Diskray",
        "middle_name": null,
        "last_name": "User",
        "country": "Lithuania",
        "address": "Diskray Road",
        "vat_number": "ID98831267312",
        "username": "user",
        "email": "user@diskray.lt",
        "email_verified_at": "2021-08-14T03:53:18.000000Z",
        "company_name": "Benson Devs",
        "newsletter": 1,
        "notes": "Cool",
        "created_at": "2021-08-14T03:53:18.000000Z",
        "updated_at": "2021-08-14T03:53:18.000000Z",
        "token": "40|xMEGjWX19VvCMQbWjJdl6nrnQAGBTcVucGIc2wdt"
    }
}
```

-------------------------------------------------------
### 2. Register
-------------------------------------------------------

**Endpoint:** `/api/auth/register`

**Method:** `POST`

**Headers:**

Header Name | Value		
-------------|-------------
Accept | `application/json`

**Parameters:**

 Payload name | Required | Validation | Description		
--------------|----------|------------|------------
first_name | Required | string | First Name of the new user
middle_name | Optional | string | Password of logging in user
last_name | Required | string | Password of logging in user
country | Required | string | Country of the user
address | Required | string | Address of the user
company_name | Optional | string | Name of the company
newsletter | Optional | boolean | Set this to true, then the user will recieve newsletter each time update published
username | Required | string, unique | Unique username of the user
email | Required | string, unique | Unique email of the user
password | Required | string, has numerical, has lowercase, has uppercase, has special character | Password of the user
confirm_password | Required | string, same as password | The same password to confirm the password created

**cURL Request Example:**
```
curl --location --request POST '{base_url}/api/auth/register' \
    --header 'Accept: application/json' \
    --form 'first_name="Jon"' \
    --form 'middle_name="L"' \
    --form 'last_name="Doe"' \
    --form 'country="Lithuania"' \
    --form 'address="777, Random Address"' \
    --form 'company="Any Other Company"' \
    --form 'username="jondoe"' \
    --form 'email="johndoe@test.com"' \
    --form 'password="#JohnDoe123"' \
    --form 'confirm_password="#JohnDoe123"' \
    --form 'newsletter="1"'
```

**Response Attributes:**

Attribute Name | Type | Description
---------------|------|-------------
data | boolean | Register process status
status | string | Status of the request
message | string | Response message from the API

**Success Response Example:**
```json
{
    "status": "success",
    "message": "Successfully registered as user, please confirm email address to complete .",
}
```

-------------------------------------------------------
### 3. Logout
-------------------------------------------------------

**Endpoint:** `/api/auth/logout`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:** N/A

**cURL Request Example:**
```
curl --location --request POST '{base_url}/api/auth/logout' \
    --header 'Authorization: Bearer 2|ZJnuFkyqbRx5Lw0NHThEGKO9CooQaPIWFIsGHMTO' \
    --header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name | Type | Description
---------------|------|-------------
data | boolean | Logout process status
status | string | Status of the request
message | string | Response message from the API

**Success Response Example:**
```json
{
    "status": "success",
    "message": "Successfully logged out."
}
```