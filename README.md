# SearchEngine
### Стартуем проект
> Импортируем в базу файл [_**search.sql**_](search.sql)

> В файле [_**connect.php**_](vendor/connect.php) указывает данные к базе

>Загружаем проект на сервер/хостинг

### Полезные ссылки
>_https://domain.com/?q=Ваш%20заппос&t=text_
- **q** - _Ваш запрос для поиска_
- **t** - _Тип запроса **(text)**_

>_https://domain.com/vendor/parse_v2.php?url=https://domain.com_
- **url** - _Указываем ссылку от сайта, который хотим добавить в базу_
- **keywords** - _Будет применён, если сайт не имеет свой **<meta name="keywords"...**_
- **description** - _Будет применён, если сайт не имеет свой **<meta name="description"...**_

##### Поиск по базе, происходит в **_URL, DESCRIPTION, KEYWORDS, HOST, PATH, TITLE_**
