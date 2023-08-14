# Block Factoring Frontend API

## Autorization

    Header key: Autorization; value Bearer 1|xJWgsGbUORaJOPD4Cu1N9dH4SBaHrLomGmttB1mb

## Base url 
    %base_url% for example https:://blockdfactoring.kz

## HTTP Status Codes

    200 OK. Successful request
    400 Bad Request. Returns JSON with the error message
    401 Unauthorized. Authorization is required or has been failed
    403 Forbidden. Action is forbidden
    404 Not Found. Data requested cannot be found
    429 Too Many Requests. Your connection has been rate limited
    500 Internal Server. Internal Server Error
    503 Service Unavailable. Service is down for maintenance
    504 Gateway Timeout. Request timeout expired

## Pagination

Parameters:

    Parameter   Description
    limit       Number of results per call.
    offset      Number of results offset.
    sort        Sort direction.
                Accepted values: ASC (ascending order), DESC (descending order)
    by 	        Filter type.
                Accepted values: id, timestamp
    from 	    Interval initial value.
                If filter by timestamp is used, then parameter type is DateTime; otherwise — Number.
    till 	    Interval end value.
                If filter by timestamp is used, then parameter type is DateTime; otherwise — Number.

## Ticker

### Get all avalible tickers

GET /api/0/ticker

Response:

```
{
    [
    "SM0825": {
        "ask": "9550",
        "bid": "9530",
        "last": "9530",
        "price": "10000"
        "quantity": "150",
        "volume": "1500000",
        "expected_return": "10.2"
        "maturity_date": "2023-09-11",
        "debitor": "Small",
        "category": "Косметика",
        "timestamp": "2023-01-01T00:00:01"
    },
    "M010923": {
        "ask": "9555",
        "bid": "9540",
        "last": "9550",
        "price": "10000"
        "quantity": "100",
        "volume": "1000000",
        "expected_return": "7.9"
        "maturity_date": "2023-09-01",
        "debitor": "Magnum",
        "category": "Химия",
        "timestamp": "2023-01-01T00:00:01"
    }
    ]
}
```

## Assets

### Get my assets

GET /api/0/asset

Response:

```
{
    [
        "SM0825": {
            "total_qty": "24",
            "blocked_qty": "5",
            "timestamp": "2023-01-01T00:00:01"
        },
        "M010923": {
            "total_qty": "33",
            "bblocked_qty": "0",
            "timestamp": "2023-01-01T00:00:01"
        }
    ]
}
```

## Wallet

### Get my wallet

GET /api/0/wallet

Resrponse:

```
{
    {
        "total": "1230000",
        "blocked": "50000"
    }
}
```

## Transactions

### Get my transactions

GET /api/0/transaction

Response:

```
{
    [
        {

            "id": "12345",
            "type": "replenishment",
            "amount": "100000",
            "timestamp": "2023-05-10T09:00:00"
        },
        {   
            "id": "12346",
            "type": "buy",
            "amount": "4567",
            "timestamp": "2023-05-10T09:20:00"
        },
        {
            "id": "12347",
            "type": "sell",
            "amount": "4577",
            "timestamp": "2023-05-10T11:22:00"
        },
        {
            "id": "12394",
            "type": "withdrawal",
            "amount": "100340",
            "timestamp": "2023-06-02T08:00:00"
        }
    ]
}
```

## Order

### Create order

POST /api/0/order

```
{
    "ticker": "SM0825",
    "side": "buy",
    "type": "market",
    "quantity": "150"
}
```

```
{
    "ticker": "SM0825",
    "side": "sell",
    "type": "limit",
    "quantity": "50",
    "price": 10001
}
```

### Get orders

GET /api/0/order

Response:

```
{
    [
        {
            "id": "12345675"
            "ticker": "SM0825",
            "side": "buy",
            "type": "market",
            "quantity": "150"
            "price": "9995"
            "filled": "0",
            "status": "active"
        },
        {
            "id": "12345679"
            "ticker": "SM0825",
            "side": "sell",
            "type": "market",
            "quantity": "99"
            "price": "9997"
            "filled": "51",
            "status": "partialfilled"
        }
    ]
}
```

### Cancel order

PUT /api/0/order

```
{"order_id": "123457566"}
```

## Depth of market

### Show
GET /api/0/dom/ticker

Response:

```
{
    [
        {
            "qty": "57",
            "price": "9990",
            "side": "sell"
        },
        {
            "qty": "4",
            "price": "9850",
            "side": "sell"
        },
        {
            "qty": "13",
            "price": "9780",
            "side": "sell"
        },
        {
            "qty": "8",
            "price": "9600",
            "side": "sell"
        },
        {
            "qty": "73",
            "price": "9550",
            "side": "sell"
        },
        {
            "qty": "173",
            "price": "9530",
            "side": "buy"
        },
        {
            "qty": "5",
            "price": "9540",
            "side": "buy"
        },
        {
            "qty": "31",
            "price": "9535",
            "side": "buy"
        },
        {
            "qty": "61",
            "price": "9530",
            "side": "buy"
        },
        {
            "qty": "66",
            "price": "9527",
            "side": "buy"
        },
    ]
}
```