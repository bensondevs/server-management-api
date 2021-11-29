## User

-------------------------------------------------------
### 1. Authenticated User
-------------------------------------------------------

**Endpoint:** `/api/users/authenticated`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:**
Payload name | Required | Validation | Description      
--------------|----------|------------|---------------
page | Optional | string | Email or username of logging in user

**cURL Request Example:**
```
curl --location --request GET '{base_url}/api/users/authenticated' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer 4|4rGlJp8MfgfIaoGkgMdRbrnQbUEdKENSZEVrC1lm'
```

**Response Attributes:**
Attribute Name  |   Type    |   Description     
----------------|-----------|---------------
user | Object | Object of user 

**Success Response Example:**
```json
{
    "user": {
        "first_name": "Simeon",
        "middle_name": "Sie",
        "last_name": "Bensona",
        "country": "Indonesia",
        "address": "Jalan Kolonel Sugiono 62 Purwodadi",
        "username": "admin",
        "email": "bensondevs@gmail.com",
        "company_name": "Benson Devs",
        "newsletter": 1
    }
}
```