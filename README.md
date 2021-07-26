# Frisbo Technical Take-at-home Project

Hello dear candidate!

This is the Frisbo Take-at-home project. It is designed to take at most 2-3 hours of a Senior Developer's time to complete.

Don't worry, we don't actually measure the time it took for you to complete it. We simply value your time and don't want to take more than we should so it only serves as a general guideline.

Key things we look at:

- If the tasks have been completed
- Respecting SOLID principles
- Code readability and uniform style
- Use of types (Typescript for React / strict, type hinting and docblocks for PHP)

Everything else is subjective anyway! When we'll be reviewing the code together, we'll be asking questions and you'll be able to defend your solution. This is another thing we value, the way you can clearly communicate what you did to the team.

# Tasks

For this take-at-home project, you'll be creating a backend to serve as a proxy for the real Frisbo API and a minimal frontend to display data from your backend.
The projects are already pre-configured so you don't spend alot of time configuring them and so that you can focus on the code itself.
In the email you've received, you got some test credentials you can safely use to the Frisbo API. The proxy will only use those credentials so feel free to cache the token for those if you wish.

1. Create a minimal route in the backend with the following specifications:

    `GET /api/organizations`
    - it will use the Frisbo API credentials you've received
    - it will simply return the results from the API call for `https://api.frisbo.ro/v1/organizations`  

2. Create a minimal route in the backend with the following specifications:

    `GET /api/orders?organization_id=<organizationId>`
    - it will use the Frisbo API credentials you've received
    - if no `organization_id` parameter exists, it will agreggate the results of the Frisbo API calls for `https://api.frisbo.ro/v1/organizations/{organizationId}/orders` for each organization your user is assigned to
    - if `organization_id` parameter exists, it will get the results of the Frisbo API calls for `https://api.frisbo.ro/v1/organizations/{organizationId}/orders` 
    - it will output the first 100 orders of the aggregated result (in case organization_id exists) or the direct result (in case organization_id exists) by ordering them in descending order by ordered_date field

3. On the frontend, modify the default component and add a dropdown for organizations and a table for orders. The table will have the following columns:
    - Order Id
    - Organization Name
    - Ordered Date
    - Number of products
    - Fulfillment status

4. Populate the dropdown created above by a call to your backend organizations route. By default, the dropdown for organizations will have the `ALL` value. The labels for the organizations need to be the `alias` property the backend returns.

5. Populate the table with orders by calling your backend orders route with the parameter `organization_id` taken from the dropdown. If `ALL` is selected, then no organization_id parameter will be sent. The mappings for the table are as following:
    - Order Id -> `order_id` field of the order
    - Organization Name -> the `alias` of the organization with id `organization_id` from the order
    - Ordered Date -> `ordered_date` field of the order
    - Number of products -> a count of the elements of the `products` field from the order
    - Fulfillment status -> `fulfillment.status` field of the order

Don't worry about the CSS and styling of the Frontend, we won't judge that!


# Frisbo API Quick Overview

## Authentication

Authentication is made by sending the username/email to the auth endpoint. In return, you will get when the token will expire (in seconds) and the bearer token you will need to use for each request.

Here is how a curl request looks like:

```
curl --location --request POST 'http://api.frisbo.ro/v1/auth/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "eamil@email.com",
    "password": "emailpassword"
}'
```

And here is an example response:

```
{
    "access_token": "123412ejbvj12vhvjv123vczvsdada", // this is the bearer token
    "id_token": "214cx2kn1bkl4bn1l2nln412lbbvhivcucyh1n",
    "scope": "openid profile email",
    "expires_in": 55168,
    "token_type": "Bearer"
}
```

The token will need to be passed in the header section of the following requests like this: `Authorization: Bearer <access_token>`

## Organizations

The organizations route will get you the organization your account has access to. For the purposes of this project, we'll assign 2 organizations to your account.

Example response:

```
[
    {
        "organization_id": 1123,
        "is_active": 1,
        "name": "SC TEST ORG SRL",
        "alias": "Metrosin",
        "website": "http://",
        "first_run_flag": 1,
        "created_at": "2018-09-05 08:35:59",
        "updated_at": "2020-03-08 16:44:23",
        "vat_registration_number": "RO123141",
        "trade_register_registration_number": "J08/123/5151",
        "description": "Test company",
        "contract_start_date": "2012-04-23 18:25:44",
        "contract_end_date": "2012-04-23 18:25:44",
        "address_id": 2,
        "contact_id": 2,
        "contacts": [
            {
                "person_id": 2,
                "name": "Mihail Sandu Andrei",
                "email": "mihail.sandu@fakemail.com",
                "phone": "0722926999",
                "created_at": "2018-09-05 08:35:59",
                "updated_at": "2020-10-01 07:12:31"
            }
        ],
        "addresses": [
            {
                "id": 2,
                "name": "Acasa",
                "street": "Unirii 23",
                "city": "Constanta",
                "county": "Constanta",
                "country": "Romania",
                "zip": "1234",
                "hash": "2d40558df915b0e4840fe1058de4e7a2",
                "phone": "0722926999",
                "locker": "",
                "is_shipping_address": 1,
                "is_billing_address": 1,
                "created_at": "2018-09-05 08:35:59",
                "updated_at": "2020-10-01 07:12:32"
            }
        ]
    },
    ...
]
```

## Orders

For the purposes of this project, we have already pushed some test orders for your organizations.
The route to GET the orders looks like this: `GET api.frisbo.ro/v1/organizations/{organizationId}/orders`

Note that the route is paginated, but for the purposes of this project you will only get the first page. Here is an example response:

```
{
    "current_page": 1,
    "first_page_url": "https://api.frisbo.ro/v1/organizations/{organizationId}/orders?page=1",
    "from": 1,
    "last_page": 23,
    "last_page_url": "https://api.frisbo.ro/v1/organizations/{organizationId}/orders?page=23",
    "next_page_url": "https://api.frisbo.ro/v1/organizations/{organizationId}/orders?page=2",
    "path": "https://api.frisbo.ro/v1/organizations/{organizationId}/orders",
    "per_page": 100,
    "prev_page_url": null,
    "to": 100,
    "total": 2264,
    "data": [
        {
            "order_id": 542151,
            "shipping_customer_id": 104596,
            "organization_id": 1,
            "status": "invoiced",
            "reason_status": "",
            "shipping_address_id": 887143,
            "order_group_id": null,
            "ordered_date": "2021-07-01 10:11:34",
            "total": 19.12,
            "notes": "Payment method: Bank transfer; Client notes: ",
            "packing_notes": "",
            "shipped_with": "Mock",
            "shipped_date": null,
            "returned_date": null,
            "delivery_date": null,
            "canceled_date": null,
            "preferred_delivery_time": null,
            "created_at": "2021-07-23 11:47:02",
            "updated_at": "2021-07-23 12:03:18",
            "channel_id": 1,
            "warehouse_id": 180,
            "order_reference": "hgfre3ww",
            "billing_address_id": 887144,
            "billing_customer_id": 104596,
            "discount": "0.000000",
            "invoiced_date": null,
            "shipping_cost": "10",
            "shipping_tracking_number": "113542151",
            "cash_on_delivery": 0,
            "is_manual": 1,
            "parent_order_id": null,
            "transport_tax": 0,
            "delivery_status": "sent",
            "wms_status": "received_by_warehouse",
            "return_tracking_number": "",
            "return_status": "",
            "is_postponed": 0,
            "use_workflow": 2,
            "fulfillment_id": null,
            "personal_pickup": 0,
            "notification_url": "",
            "products": [
                {
                    "product_id": 45674,
                    "order_id": 542151,
                    "created_at": "2021-07-23 11:47:03",
                    "updated_at": "2021-07-23 11:47:03",
                    "price": 19.12,
                    "quantity": 1,
                    "total": 19.12,
                    "vat": 0,
                    "discount": "0",
                    "name": "Hummingbird printed t-shirt - Size : S- Color : White",
                    "is_virtual": 0,
                    "price_with_vat": 19.12,
                    "product": {
                        "product_id": 45674,
                        "type": "product",
                        "sku": "demo_1",
                        "has_serial_number": 0,
                        "is_packing_material": 0,
                        "name": "Hummingbird printed t-shirt",
                        "unique_id": null,
                        "organization_id": 1,
                        "created_at": "2019-12-05 14:27:28",
                        "updated_at": "2019-12-05 14:27:28",
                        "upc": "undefined",
                        "external_code": "undefined",
                        "vat": "0",
                        "ean": "undefined",
                        "deleted_at": null
                    }
                }
            ],
            "shipping_customer": {
                "customer_id": 104596,
                "email": "costinlemnaru@gmail.com",
                "first_name": "costin",
                "last_name": "lemnaru",
                "phone": "0731346544",
                "organization_id": 1,
                "vat_registration_number": null,
                "trade_register_registration_number": ".",
                "created_at": "2019-12-10 13:17:23",
                "updated_at": "2021-07-01 10:11:34"
            },
            "billing_customer": {
                "customer_id": 104596,
                "email": "costinlemnaru@gmail.com",
                "first_name": "costin",
                "last_name": "lemnaru",
                "phone": "0731346544",
                "organization_id": 1,
                "vat_registration_number": null,
                "trade_register_registration_number": ".",
                "created_at": "2019-12-10 13:17:23",
                "updated_at": "2021-07-01 10:11:34"
            },
            "billing_address": {
                "id": 887144,
                "name": "Fabricii 105 ",
                "street": "Fabricii 105 ",
                "city": "Cluj-Napoca",
                "county": "Cluj-Napoca",
                "country": "Romania",
                "zip": "495000",
                "hash": "06f790496308e03272c5226c7d3cb363",
                "phone": "0731346544",
                "locker": "",
                "is_shipping_address": 0,
                "is_billing_address": 1,
                "created_at": "2021-07-01 10:11:34",
                "updated_at": "2021-07-01 10:11:34"
            },
            "shipping_address": {
                "id": 887143,
                "name": "Fabricii 105 ",
                "street": "Fabricii 105 ",
                "city": "Cluj-Napoca",
                "county": "Cluj-Napoca",
                "country": "Romania",
                "zip": "495000",
                "hash": "c193355ab970cbb02513826d05d79c17",
                "phone": "0731346544",
                "locker": "",
                "is_shipping_address": 1,
                "is_billing_address": 0,
                "created_at": "2021-07-01 10:11:34",
                "updated_at": "2021-07-01 10:11:34"
            },
            "invoices": [
                {
                    "id": 483709,
                    "invoice_number": "468",
                    "order_number": 542151,
                    "status": "invoiced",
                    "invoice_date": "2021-07-23 00:00:00",
                    "total": 19.12,
                    "organization_id": 1,
                    "created_at": "2021-07-23 11:47:16",
                    "updated_at": "2021-07-23 11:47:16",
                    "channel_id": 1,
                    "invoice_series": "2775b",
                    "discount": "0.000000",
                    "transport_tax": 0
                }
            ],
            "attachments": [
                {
                    "id": 989197,
                    "file_name": "awbs/awb-113542151.pdf",
                    "file_size": "31557",
                    "type": "awb",
                    "source": "spaces",
                    "created_at": "2021-07-23 11:47:17",
                    "updated_at": "2021-07-23 11:47:17",
                    "pivot": {
                        "order_id": 542151,
                        "attachment_id": 989197
                    }
                },
                {
                    "id": 989199,
                    "file_name": "invoices/202107234823-invoice-2775b468.pdf",
                    "file_size": "30325",
                    "type": "invoice",
                    "source": "spaces",
                    "created_at": "2021-07-23 11:48:23",
                    "updated_at": "2021-07-23 11:48:23",
                    "pivot": {
                        "order_id": 542151,
                        "attachment_id": 989199
                    }
                }
            ],
            "serial_numbers": [
                {
                    "id": 55975,
                    "product_id": 45674,
                    "order_id": 542151,
                    "serial_number": "12313123123",
                    "created_at": "2021-07-23 11:48:21",
                    "updated_at": "2021-07-23 11:48:21"
                }
            ],
            "settings": [],
            "fulfillment": {
                "id": 170951,
                "order_id": 542151,
                "status": "Ready for picking",
                "error": null,
                "message": null,
            },
            "returns": []
        },
        ...
    ]
}
```



