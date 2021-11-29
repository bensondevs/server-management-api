## Region

-------------------------------------------------------
### 1. All
-------------------------------------------------------

**Endpoint:** `/api/regions/all`

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
curl --location --request GET '{base_url}/api/regions/all' \
	--header 'Authorization: Bearer 3|BxWL8OFOeuVV6IGhXhzrLP8AytXmcY70P8GeKNd1' \
	--header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name 	|	Type	|	Description		
----------------|-----------|---------------
regions | Object | Object of regions, contains the pagination, all the regions in current page stored within attribute of `data` 

**Success Response Example:**
```json
{
    "regions": {
        "current_page": 1,
        "data": [
            {
                "id": "47fc3cf0-b88c-11eb-9fa0-653f81448f27",
                "region_name": "Europe"
            },
            {
                "id": "47fd5150-b88c-11eb-b022-9506e2e8debe",
                "region_name": "Asia"
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
        "to": 2,
        "total": 2
    }
}
```