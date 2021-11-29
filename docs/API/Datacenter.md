## Datacenter

-------------------------------------------------------
### 1. All
-------------------------------------------------------

**Endpoint:** `/api/datacenters/all`

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
curl --location --request GET '{base_url}/api/datacenters/all' \
	--header 'Authorization: Bearer 3|BxWL8OFOeuVV6IGhXhzrLP8AytXmcY70P8GeKNd1' \
	--header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name 	|	Type	|	Description		
----------------|-----------|---------------
datacenters | Object | Object of datacenters, contains the pagination, all the datacenters in current page stored within attribute of `data` 

**Success Response Example:**
```json
{
    "datacenters": {
        "current_page": 1,
        "data": [
            {
                "id": "47fef920-b88c-11eb-8bbe-5dab7ffa8a13",
                "region_name": "Europe",
                "datacenter_name": "Lithuania",
                "client_datacenter_name": "Lithuania",
                "location": "Lithuania",
                "status": "active"
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