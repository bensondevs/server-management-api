## Order

-------------------------------------------------------
### 1. All
-------------------------------------------------------

**Endpoint:** `/api/orders/all`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:**
Payload name | Required | Validation | Description		
-------------|----------|------------|-------------
page | Optional | string | Email or username of logging in user

**cURL Request Example:**
```
curl --location --request GET '{base_url}/api/orders/all' \
	--header 'Authorization: Bearer 3|BxWL8OFOeuVV6IGhXhzrLP8AytXmcY70P8GeKNd1' \
	--header 'Accept: application/json'
```

**Response Attributes:**
Attribute Name 	|	Type	|	Description		
----------------|-----------|---------------
orders | Object | Object of orders, contains the pagination, all the orders in current page stored within attribute of `data` 

**Success Response Example:**
```json
{
    "orders": {
        "current_page": 1,
        "data": [
            {
                "id": "1058d210-08a6-11ec-bb9a-cd46145edd4b",
                "order_number": 109,
                "status": 1,
                "status_description": "Paid",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "10599520-08a6-11ec-b6c0-c347f45916f6",
                "container": null,
                "plan": {
                    "id": "1059b8c0-08a6-11ec-b523-21ed79acca60",
                    "order_id": "1058d210-08a6-11ec-bb9a-cd46145edd4b",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-29T08:49:47.000000Z",
                    "updated_at": "2021-08-29T08:49:47.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "10599520-08a6-11ec-b6c0-c347f45916f6",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "1058d210-08a6-11ec-bb9a-cd46145edd4b",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-29T08:49:47.000000Z",
                    "updated_at": "2021-08-29T08:49:47.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "14e4d030-08a6-11ec-9ff7-c7cd6be563df",
                "order_number": 110,
                "status": 1,
                "status_description": "Paid",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "14e58190-08a6-11ec-83bc-f9bb02337692",
                "container": null,
                "plan": {
                    "id": "14e5a560-08a6-11ec-ba0d-c1af80f36a52",
                    "order_id": "14e4d030-08a6-11ec-9ff7-c7cd6be563df",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-29T08:49:54.000000Z",
                    "updated_at": "2021-08-29T08:49:54.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "14e58190-08a6-11ec-83bc-f9bb02337692",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "14e4d030-08a6-11ec-9ff7-c7cd6be563df",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-29T08:49:54.000000Z",
                    "updated_at": "2021-08-29T08:49:54.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "22ae5930-07b9-11ec-b00e-594de952aaa0",
                "order_number": 103,
                "status": 2,
                "status_description": "Activated",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "22b0cfd0-07b9-11ec-a81d-0f195937c519",
                "container": null,
                "plan": {
                    "id": "22b10910-07b9-11ec-a855-133324f6e629",
                    "order_id": "22ae5930-07b9-11ec-b00e-594de952aaa0",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-28T04:33:47.000000Z",
                    "updated_at": "2021-08-28T04:33:47.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "22b0cfd0-07b9-11ec-a81d-0f195937c519",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "22ae5930-07b9-11ec-b00e-594de952aaa0",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:33:47.000000Z",
                    "updated_at": "2021-08-28T04:33:47.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "43655950-07b9-11ec-a6c2-91ea5c88959c",
                "order_number": 104,
                "status": 2,
                "status_description": "Activated",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "4366aa00-07b9-11ec-88bf-675ff7516c07",
                "container": null,
                "plan": {
                    "id": "43679720-07b9-11ec-a5a2-4dd2a422c909",
                    "order_id": "43655950-07b9-11ec-a6c2-91ea5c88959c",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-28T04:34:42.000000Z",
                    "updated_at": "2021-08-28T04:34:42.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "4366aa00-07b9-11ec-88bf-675ff7516c07",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "43655950-07b9-11ec-a6c2-91ea5c88959c",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:34:42.000000Z",
                    "updated_at": "2021-08-28T04:34:42.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "517c2300-07b9-11ec-9911-a9e976af1a5c",
                "order_number": 105,
                "status": 2,
                "status_description": "Activated",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "517d7ab0-07b9-11ec-b526-89b8f5f1d6f5",
                "container": null,
                "plan": {
                    "id": "517da350-07b9-11ec-882b-ad343dbd3587",
                    "order_id": "517c2300-07b9-11ec-9911-a9e976af1a5c",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-28T04:35:05.000000Z",
                    "updated_at": "2021-08-28T04:35:05.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "517d7ab0-07b9-11ec-b526-89b8f5f1d6f5",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "517c2300-07b9-11ec-9911-a9e976af1a5c",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:35:05.000000Z",
                    "updated_at": "2021-08-28T04:35:05.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "58b54710-07b9-11ec-bb33-9f6a6133cde9",
                "order_number": 106,
                "status": 2,
                "status_description": "Activated",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "58b7a8d0-07b9-11ec-b363-2d3d5b174187",
                "container": null,
                "plan": {
                    "id": "58b7e6b0-07b9-11ec-8ec8-bd59784cd5a2",
                    "order_id": "58b54710-07b9-11ec-bb33-9f6a6133cde9",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-28T04:35:18.000000Z",
                    "updated_at": "2021-08-28T04:35:18.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "58b7a8d0-07b9-11ec-b363-2d3d5b174187",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "58b54710-07b9-11ec-bb33-9f6a6133cde9",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:35:18.000000Z",
                    "updated_at": "2021-08-28T04:35:18.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "7ee5b0e0-07b9-11ec-8afd-0b201c4e20c5",
                "order_number": 107,
                "status": 2,
                "status_description": "Activated",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "0%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "7ee6dd60-07b9-11ec-a1c3-758117939b9a",
                "container": null,
                "plan": {
                    "id": "7ee70a20-07b9-11ec-adb2-2b768c6877e3",
                    "order_id": "7ee5b0e0-07b9-11ec-8afd-0b201c4e20c5",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 1,
                    "note": null,
                    "created_at": "2021-08-28T04:36:22.000000Z",
                    "updated_at": "2021-08-28T04:36:22.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "7ee6dd60-07b9-11ec-a1c3-758117939b9a",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "7ee5b0e0-07b9-11ec-8afd-0b201c4e20c5",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:36:22.000000Z",
                    "updated_at": "2021-08-28T04:36:22.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "f03796d0-07b7-11ec-8dd6-e5ec708dd12e",
                "order_number": 2,
                "status": 4,
                "status_description": "Destroyed",
                "amount": "70.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "27%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "f0396240-07b7-11ec-9633-878695ea48fb",
                "container": null,
                "plan": {
                    "id": "f03ab1a0-07b7-11ec-be7c-d5321b503f3d",
                    "order_id": "f03796d0-07b7-11ec-8dd6-e5ec708dd12e",
                    "service_plan_id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                    "quantity": 7,
                    "note": "This is seeder created order",
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "service_plan": {
                        "id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                        "plan_name": "standard",
                        "plan_code": "standard",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef0968c0-07b7-11ec-a511-47f2d428f565",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                            "status": 1,
                            "currency": 2,
                            "price": 10,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef89aa0-07b7-11ec-9f73-eb93fb7021e2",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096550-07b7-11ec-82e7-b5ef00c0c0eb",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                                "status": 1,
                                "currency": 1,
                                "price": 10,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef0968c0-07b7-11ec-a511-47f2d428f565",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef7a290-07b7-11ec-9712-75fe92d20d7b",
                                "status": 1,
                                "currency": 2,
                                "price": 10,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "f0396240-07b7-11ec-9633-878695ea48fb",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "f03796d0-07b7-11ec-8dd6-e5ec708dd12e",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "f0467d20-07b7-11ec-a22c-e3ab011e9ad7",
                "order_number": 5,
                "status": 0,
                "status_description": "Unpaid",
                "amount": "80.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "20%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "f047f300-07b7-11ec-8ec5-39f3e90f5b95",
                "container": null,
                "plan": {
                    "id": "f048e450-07b7-11ec-8286-af31ba21890c",
                    "order_id": "f0467d20-07b7-11ec-a22c-e3ab011e9ad7",
                    "service_plan_id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                    "quantity": 4,
                    "note": "This is seeder created order",
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "service_plan": {
                        "id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                        "plan_name": "advanced",
                        "plan_code": "advanced",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096ea0-07b7-11ec-a470-2b12964b656b",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                            "status": 1,
                            "currency": 2,
                            "price": 20,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eefac170-07b7-11ec-b6b7-afb9abf12053",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096bc0-07b7-11ec-8831-e18888dbb8e9",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                                "status": 1,
                                "currency": 1,
                                "price": 20,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096ea0-07b7-11ec-a470-2b12964b656b",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef9b790-07b7-11ec-8d0d-bb43073352ca",
                                "status": 1,
                                "currency": 2,
                                "price": 20,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "f047f300-07b7-11ec-8ec5-39f3e90f5b95",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "f0467d20-07b7-11ec-a22c-e3ab011e9ad7",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "deleted_at": null
                }
            },
            {
                "id": "f04ac9f0-07b7-11ec-902a-47a6d89c25d8",
                "order_number": 6,
                "status": 4,
                "status_description": "Destroyed",
                "amount": "0.00",
                "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                "disk_size": null,
                "vat_size": "26%",
                "vat_amount": 0,
                "total_amount": 0,
                "payment_id": "f04c49c0-07b7-11ec-8aef-b9062c5a156e",
                "container": null,
                "plan": {
                    "id": "f04d3920-07b7-11ec-af73-b1426e286338",
                    "order_id": "f04ac9f0-07b7-11ec-902a-47a6d89c25d8",
                    "service_plan_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                    "quantity": 7,
                    "note": "This is seeder created order",
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "service_plan": {
                        "id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                        "plan_name": "free",
                        "plan_code": "free",
                        "status": 1,
                        "time_quantity": 1,
                        "time_unit": 2,
                        "description": "Random created plan.",
                        "created_at": "2021-08-28T04:25:11.000000Z",
                        "updated_at": "2021-08-28T04:25:11.000000Z",
                        "pricing": {
                            "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                            "priceable_type": "App\\Models\\ServicePlan",
                            "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                            "status": 1,
                            "currency": 2,
                            "price": 0,
                            "created_at": "2021-08-28T04:25:11.000000Z",
                            "updated_at": "2021-08-28T04:25:11.000000Z",
                            "deleted_at": null
                        },
                        "pricings": [
                            {
                                "id": "eef6bef0-07b7-11ec-8320-df41ddcc1121",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef095930-07b7-11ec-af00-9bc41c290350",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 1,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            },
                            {
                                "id": "ef096020-07b7-11ec-81f9-29d0309a1652",
                                "priceable_type": "App\\Models\\ServicePlan",
                                "priceable_id": "eef5d740-07b7-11ec-acd1-69137405cb32",
                                "status": 1,
                                "currency": 2,
                                "price": 0,
                                "created_at": "2021-08-28T04:25:11.000000Z",
                                "updated_at": "2021-08-28T04:25:11.000000Z",
                                "deleted_at": null
                            }
                        ]
                    }
                },
                "payment": {
                    "id": "f04c49c0-07b7-11ec-8aef-b9062c5a156e",
                    "user_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
                    "order_id": "f04ac9f0-07b7-11ec-902a-47a6d89c25d8",
                    "methods": 1,
                    "amount": 0,
                    "status": 1,
                    "payment_reference": null,
                    "billing_address": null,
                    "vendor_api_response": null,
                    "created_at": "2021-08-28T04:25:13.000000Z",
                    "updated_at": "2021-08-28T04:25:13.000000Z",
                    "deleted_at": null
                }
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 7,
        "last_page_url": "/?page=7",
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
                "url": "/?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "/?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "/?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "/?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "/?page=6",
                "label": "6",
                "active": false
            },
            {
                "url": "/?page=7",
                "label": "7",
                "active": false
            },
            {
                "url": "/?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "/?page=2",
        "path": "/",
        "per_page": 10,
        "prev_page_url": null,
        "to": 10,
        "total": 65
    }
}
```

-------------------------------------------------------
### 2. Place Order
-------------------------------------------------------

**Endpoint:** `/api/orders/all`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json
Authorization | Bearer {api_token}

**Parameters:**
Payload name | Required | Validation | Description      
-------------|----------|------------|-------------
service_plan_id | Required | string, exist in service plan | Selected service plan
quantity | Required | integer | Quantity of order
datacenter_id | Required | string, exist datacenter | Selected datacenter
hostname | Required | string | Hostname of the container


**cURL Request Example:**
```
curl --location --request POST 'http://localhost:8001/api/orders/place' \
--header 'Authorization: Bearer 6|xobNcJGIBGiUZ9j35b4v8liI9gUz9ZL4O0fwr9qY' \
--header 'Accept: application/json' \
--form 'service_plan_id="eef5d740-07b7-11ec-acd1-69137405cb32"' \
--form 'quantity="1"' \
--form 'datacenter_id="ef0d2320-07b7-11ec-b609-d74f16f0a65f"' \
--form 'hostname="www.test.com"' \
--form 'disk_size="5000"'
```

**Response Attributes:**

Attribute Name  |   Type    |   Description     
----------------|-----------|---------------
data | Object | Object of order, contains the information about order, service plan and customer information
status | string | Status of the response request
message  | string | Message response from API 

**Success Response Example:**
```json
{
    "status": "success",
    "message": "Successfully place order data",
    "order": {
        "customer_id": "eda9ce20-07b7-11ec-b23b-633bf6c87c1c",
        "vat_size_percentage": "0%",
        "meta_container": {
            "datacenter_id": "ef0d2320-07b7-11ec-b609-d74f16f0a65f",
            "hostname": "www.test.com",
            "disk_size": "5000"
        },
        "id": "427d3a50-1129-11ec-93c5-e9caa74d761f",
        "order_number": 111,
        "expired_at": "2021-09-12T04:49:04.852846Z",
        "updated_at": "2021-09-09T04:49:04.000000Z",
        "created_at": "2021-09-09T04:49:04.000000Z",
        "amount": 0,
        "total_amount": 0,
        "plan": null
    }
}
```