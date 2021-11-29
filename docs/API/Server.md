## Server

-------------------------------------------------------
### 1. All
-------------------------------------------------------

**Endpoint:** `/api/servers/all`

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
curl --location --request GET '{base_url}/api/orders/all' \
    --header 'Authorization: Bearer 3|BxWL8OFOeuVV6IGhXhzrLP8AytXmcY70P8GeKNd1' \
    --header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name  |   Type    |   Description     
----------------|-----------|---------------
orders | Object | Object of orders, contains the pagination, all the orders in current page stored within attribute of `data` 

**Success Response Example:**
```json
{
    "servers": {
        "current_page": 1,
        "data": [
            {
                "id": "299d4700-fcb3-11eb-bf23-cb5b732a62f5",
                "datacenter_id": "299cba90-fcb3-11eb-a01d-43fa60dce922",
                "server_name": "server1.diskray.com",
                "status": "active",
                "created_at": "2021-08-14T03:53:19.000000Z",
                "updated_at": "2021-08-14T03:53:19.000000Z",
                "complete_server_name": "Europe-Lithuania-server1.diskray.com"
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
        "to": 1,
        "total": 1
    }
}
```