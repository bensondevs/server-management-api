## Container

-------------------------------------------------------
### 1. User Containers
-------------------------------------------------------

**Endpoint:** `/api/containers/user_containers`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:** N/A

**cURL Request Example:**

```
curl --location --request GET 'http://127.0.0.1:8001/api/containers/all' \
	--header 'Accept: application/json' \
	--header 'Authorization: Bearer 3|BxWL8OFOeuVV6IGhXhzrLP8AytXmcY70P8GeKNd1'
```

**Response Attributes:**

Attribute Name | Type | Description		
---------------|------|---------------
`containers` | array | The array to contain containers data.

**Success Response Example:**
```json
{
    "containers": [
        {
            "id": "9000002",
            "order_id": null,
            "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
            "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
            "server_id": "ef0e75a0-07b7-11ec-84a3-c7c335925975",
            "subnet_id": "f02198e0-07b7-11ec-a9a8-cdda3188e11d",
            "subnet_ip_id": "f02294c0-07b7-11ec-8ffc-4f9360c42212",
            "hostname": "www.test.com",
            "total_amount": 0,
            "client_email": "sbensona77@gmail.com",
            "status": 1,
            "disk_size": 50,
            "disk_array": 1,
            "breakpoints": 1,
            "order_date": "2021-09-01",
            "activation_date": "2021-09-09",
            "expiration_date": "2021-09-30",
            "created_at": "2021-09-01T13:16:27.000000Z",
            "updated_at": "2021-09-01T13:16:27.000000Z",
            "created_on_server_at": null,
            "system_installed_at": null
        },
        {
            "id": "9000003",
            "order_id": null,
            "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
            "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
            "server_id": "ef0e75a0-07b7-11ec-84a3-c7c335925975",
            "subnet_id": "f02198e0-07b7-11ec-a9a8-cdda3188e11d",
            "subnet_ip_id": "f02294c0-07b7-11ec-8ffc-4f9360c42212",
            "hostname": "www.test.com",
            "total_amount": 0,
            "client_email": "sbensona77@gmail.com",
            "status": 1,
            "disk_size": 50,
            "disk_array": 1,
            "breakpoints": 1,
            "order_date": "2021-09-01",
            "activation_date": "2021-09-09",
            "expiration_date": "2021-09-30",
            "created_at": "2021-09-01T13:16:27.000000Z",
            "updated_at": "2021-09-01T13:16:27.000000Z",
            "created_on_server_at": null,
            "system_installed_at": null
        }
    ]
}
```

-------------------------------------------------------
### 2. User Has Containers
-------------------------------------------------------

**Endpoint:** `/api/containers/user_has_container`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:** N/A

**cURL Request Example:**

```
curl --location --request GET 'http://localhost:8000/api/containers/user_has_container' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 7|6txi6hRdj3uvxlfarQK2Emn6x2RKovRCEwn6FkCe'
```

**Response Attributes:**

```json
{
    "has_container": true
}
```

-------------------------------------------------------
### 3. Current Container
-------------------------------------------------------

**Endpoint:** `/api/containers/current`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:** N/A

**cURL Request Example:**

```
curl --location --request GET 'http://localhost:8000/api/containers/user_has_container' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 7|6txi6hRdj3uvxlfarQK2Emn6x2RKovRCEwn6FkCe'
```