# Block Factoring Proxi API

## Start

    clone
    docker-compose up
    docker exec -it proxyapi_app_1 composer install
Create a lot.
```mermaid
sequenceDiagram
    Front->>ProxyAPI: Create LOT(name, quantity, date of maturity)
    ProxyAPI->>e-wallet: Create currency LO(A-Z)
    ProxyAPI->>e-wallet: Create wallets for participant LO(A-Z)
    ProxyAPI->>e-wallet: Replenishment wallet for lot's owner
    ProxyAPI->>LOT's storage: Store Lot's informations
```
Show lots
```mermaid
sequenceDiagram
    Front->>ProxyAPI: Get ticker
```

All things we will store here except DepthOfMarket

апи список кошельков упорядочить по названию и типу
пусттые кошелькии можно не показывать

tickers = contracts

profitability = (((cancellation price - current price) / current price) / days to cancellation) * 365   = % per year
