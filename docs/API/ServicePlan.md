## Service Plan

-------------------------------------------------------
### 1. All
-------------------------------------------------------

**Endpoint:** `/api/service_plans/all`

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
curl --location --request GET '{base_url}/api/service_plans/plans' \
    --header 'Authorization: Bearer 4|4rGlJp8MfgfIaoGkgMdRbrnQbUEdKENSZEVrC1lm' \
    --header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name 	|	Type	|	Description		
----------------|-----------|---------------
plans | Object | Object of plans, contains the pagination, all the plans in current page stored within attribute of `data` 

**Success Response Example:**
```json
{
    "plans": {
        "current_page": 1,
        "data": [
            {
                "id": "733a8e90-fcb3-11eb-8553-154ee85ecc19",
                "plan_code": "free",
                "plan_name": "free",
                "currency": "EUR",
                "subscription_fee": 0,
                "duration": 30,
                "description": "Random created plan."
            },
            {
                "id": "733b8200-fcb3-11eb-8afa-d5fe542d70b7",
                "plan_code": "standard",
                "plan_name": "standard",
                "currency": "EUR",
                "subscription_fee": 0,
                "duration": 30,
                "description": "Random created plan."
            },
            {
                "id": "733c28e0-fcb3-11eb-89fb-5bd811922ac2",
                "plan_code": "advanced",
                "plan_name": "advanced",
                "currency": "EUR",
                "subscription_fee": 0,
                "duration": 30,
                "description": "Random created plan."
            },
            {
                "id": "733cbb60-fcb3-11eb-a275-650e2ca16058",
                "plan_code": "custom",
                "plan_name": "custom",
                "currency": "EUR",
                "subscription_fee": 0,
                "duration": 30,
                "description": "Random created plan."
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "/?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "/",
        "per_page": 10,
        "prev_page_url": null,
        "to": 4,
        "total": 4
    }
}
```