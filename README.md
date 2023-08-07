RBK
==============

Installation
------------
**1.** Step
```bash
docker-compose build
```
**2.** Step
```bash
docker-compose up
```
**3.** Step in php container
```bash
php bin/console doctrine:migrations:migrate
```

Run Scraper
------------
**1.** Step in php container (run mode 1 for collect urls)
```bash
php bin/console rbkScraper 1
```
**2.** Step in php container (run mode 2 for scrape urls)
```bash
php bin/console rbkScraper 2
```
