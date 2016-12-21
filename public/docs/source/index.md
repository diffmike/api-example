---
title: Example API

language_tabs:
- bash
- javascript

includes:


---

#Example
<!-- START_b982a9c2785c94e078bbe534a1f12d68 -->
##1. Попытка авторизации пользователя
Если упешно - возвращаются данные пользователя
Если не успешно - попытка найти пользователя с таким email иначе создание нового

> Example request:

```bash
curl "http://example/api/login" \
-H "Accept: application/json" \
    -d "email"="sallie.jenkins@example.org" \
    -d "password"="blanditiis" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/login",
    "method": "POST",
    "data": {
        "email": "sallie.jenkins@example.org",
        "password": "blanditiis"
},
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": {
    "id": 96,
    "name": "Dr. Gust Jenkins",
    "email": "oreilly.vallie@example.net",
    "barcode": "ynXhWrzXnKjzVCHC",
    "created_at": "2016-09-08 09:18:24"
  },
  "meta": {
    "is_new": 0
  }
}
```

### HTTP Request
`POST /api/login`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | email |  required  | 
    password | string |  required  | Minimum: `6`

<!-- END_b982a9c2785c94e078bbe534a1f12d68 -->

<!-- START_0feebc29b5a3e74754172cc86934aa20 -->
##2. Список магазинов

> Example request:

```bash
curl "http://example/api/shops" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/shops",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": [
    {
      "id": 41,
      "title": "Laudantium animi temporibus sapiente.",
      "city": "Walkerburgh",
      "address": "33655 Wisoky Prairie Apt. 422\nDarrelview, VA 72473",
      "image": null,
      "created_at": "2016-09-08 09:18:21",
      "campaigns_count": 0,
      "max_discount": null
    },
    {
      "id": 45,
      "title": "Eius iusto debitis quaerat rerum corporis voluptatem.",
      "city": "Goodwinside",
      "address": "22961 Swift Junctions\nGreenholtberg, HI 00973",
      "image": null,
      "created_at": "2016-09-08 09:18:21",
      "campaigns_count": 0,
      "max_discount": null
    },
    {
      "id": 46,
      "title": "Natus et at quasi quo unde quidem eum est.",
      "city": "Fridabury",
      "address": "5896 Shanie Lodge\nNorth Corbin, RI 69190",
      "image": null,
      "created_at": "2016-09-08 09:18:21",
      "campaigns_count": 2,
      "max_discount": 83
    }
  ],
  "meta": {
    "pagination": {
      "total": 6,
      "count": 3,
      "per_page": 3,
      "current_page": 1,
      "total_pages": 2,
      "links": {
        "next": "http://example/api/shops?page=2"
      }
    }
  }
}
```

### HTTP Request
`GET /api/shops`

`HEAD /api/shops`


<!-- END_0feebc29b5a3e74754172cc86934aa20 -->

<!-- END_0feebc29b5a3e74754172cc86934aa2 -->
##3. Инфомация о магазине

> Example request:

```bash
curl "http://example/api/shops/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/shops/{id}",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": {
    "id": 49,
    "title": "voluptatem ullam.",
    "city": "Satterfieldberg",
    "address": "752 Pearline",
    "image": "http://example/storage/images/cb5a1d0c5972f5831df74a428dfba8fe.jpg",
    "created_at": "2016-09-08 09:18:21",
    "campaigns_count": null,
    "max_discount": null
  }
}
```

### HTTP Request
`GET /api/shops/{id}`

`HEAD /api/shops/{id}`


<!-- END_0feebc29b5a3e74754172cc86934aa2 -->

<!-- END_875b557320d9824542f32dcbe3df3f1a -->
##4. Список активных акций магазина

> Example request:

```bash
curl "http://example/api/shops/{id}/campaigns" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/shops/{id}/campaigns",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": [
    {
      "id": 18,
      "title": "Laboriosam voluptatem laudantium harum magni aperiam.",
      "image": "http://example/storage/images/8361465f0dc30b33e9e3b7c7987cb08b.jpg",
      "link": "http://www.hamill.com",
      "start": "1972-07-25",
      "finish": "2081-01-26"
    },
    {
      "id": 19,
      "title": "Omnis et pariatur id ea suscipit iste ducimus similique.",
      "image": "http://example/storage/images/1e3bd4c17ae6a1386c55f4dcf7ec93e7.jpg",
      "link": "https://parisian.net/sed-omnis-esse-ea-rerum-dolores.html",
      "start": "1995-02-14",
      "finish": "2017-05-21"
    }
  ]
}
```

### HTTP Request
`GET /api/shops/{id}/campaigns`

`HEAD /api/shops/{id}/campaigns`


<!-- END_875b557320d9824542f32dcbe3df3f1a -->

<!-- START_cc3023bbf39eccceb4cb801eca999a73 -->
##5. Детали акции со списком продуктов акции

> Example request:

```bash
curl "http://example/api/campaigns/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/campaigns/{id}",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": {
    "id": 18,
    "title": "Laboriosam voluptatem laudantium harum magni aperiam.",
    "image": "http://example/storage/images/8361465f0dc30b33e9e3b7c7987cb08b.jpg",
    "link": "http://www.hamill.com",
    "start": "1972-07-25",
    "finish": "2081-01-26",
    "products": {
      "data": [
        {
          "title": "Minus mollitia omnis dicta magni reiciendis.",
          "price": 55.68,
          "price_with_discount": 342.42,
          "discount": 13,
          "discount_start": "2016-12-12",
          "discount_finish": "2016-12-13",
          "discount_type": "с 12.12.2016 по 12.13.2016, в четверг, пятницу с 00:00 по 23:59 действует скидка 10%"
        },
        {
          "title": "Ratione tempora qui porro voluptatem.",
          "price": 893.59,
          "price_with_discount": 865.81,
          "discount": 63,
          "discount_start": "2016-12-12",
          "discount_finish": "2016-12-13",
          "discount_type": "с 12.12.2016 по 12.13.2016, в четверг, пятницу с 00:00 по 23:59 действует скидка 10%"
        }
      ]
    }
  }
}
```

### HTTP Request
`GET /api/campaigns/{id}`

`HEAD /api/campaigns/{id}`


<!-- END_cc3023bbf39eccceb4cb801eca999a73 -->


<!-- START_e01e5dd08fd66ad555d1c09d2025f3e -->
##6. Список ID всех активных акций

> Example request:

```bash
curl "http://example/api/campaigns/active-ids" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/campaigns/active-ids",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": [
    18,
    19
  ]
}
```

### HTTP Request
`GET /api/campaigns/active-ids`

`HEAD /api/campaigns/active-ids`


<!-- END_e01e5dd08fd66ad555d1c09d2025f3e -->


<!-- START_c13ce4396f6a39a4e7f87041b319a955 -->
##7. Получение информации о авторизованном пользователе

> Example request:

```bash
curl "http://example/api/users/details" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/users/details",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": {
    "id": 96,
    "name": "Dr. Gust Jenkins",
    "email": "oreilly.vallie@example.net",
    "barcode": "ynXhWrzXnKjzVCHC",
    "created_at": "2016-09-08 09:18:24"
  },
  "meta": {
    "last_month_discount": 123,
    "last_month_spent": 1234
  }
}
```

### HTTP Request
`GET /api/users/details`

`HEAD /api/users/details`


<!-- END_c13ce4396f6a39a4e7f87041b319a955 -->
<!-- START_ce01e5dd08fd66ad555d1c09d2025f3e -->
##8. Список чеков пользователя с товарами

> Example request:

```bash
curl "http://example/api/checks" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://example/api/checks",
    "method": "GET",
        "headers": {
    "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
console.log(response);
});
```

> Example response:

```json
{
  "data": [
    {
      "id": 10,
      "uid": "237741",
      "sum": 13.41,
      "sum_with_discount": 0,
      "shop_id": 49,
      "created_at": "2016-09-22 07:49:32"
    },
    {
      "id": 11,
      "uid": "485176",
      "sum": 9.1,
      "sum_with_discount": 0,
      "shop_id": 49,
      "created_at": "2016-09-22 08:18:02"
    }
  ]
}
```

### HTTP Request
`GET /api/checks`

`HEAD /api/checks`


<!-- END_ce01e5dd08fd66ad555d1c09d2025f3e -->
