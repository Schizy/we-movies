We Movies
---------

A technical test in Symfony to manipulate the API from TMDB.

How to install the project:
---------------------------
- `docker compose up -d`
- `docker compose exec php composer install`
- `docker compose exec php npm install`
- `docker compose exec php npm build` 

Then you need an API KEY from TMDB to put in the `.env` file or in `.env.local` (`TMDB_API_KEY`)

You can access the website here : `http://127.0.0.1:8800`


TO DO:
------

- Create a CacheDecorator for the client to check cache or call inner method
- 