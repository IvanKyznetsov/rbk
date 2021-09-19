RBK
==============

Прописать явки до базы в .env файле

composer install

php bin/console doctrine:migrations:migrate

bin/console rbkScraper 1 #Запуск первого мода, собирающиего url новостей 

bin/console rbkScraper 2 #Запуск второго мода, который идет по собранным url

/rbk/news #Все новости

/rbk/post/{id} #Страница котнкретной новости