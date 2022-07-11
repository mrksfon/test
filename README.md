# Description : There are 4 tables in database album,artist,favorite,user. There are two roles for each user,

# ROLE_USER and ROLE_ADMIN

# User with ROLE_ADMIN can CRUD users,albums,and artists

# USER WITH ROLE_USER can Favorite and Unfavorite certain albums

### START PROJECT

## git clone https://github.com/mrksfon/test.git

## cd test

## composer install

## npm install

## fill the .env with your local variables like db_user,db_password,db_database

## run from command line php bin/console doctrine:database:create

## run from command line php bin/console doctrine:migrations:migrate

## to populate users there is UserFixture

## run from command line php bin/console doctrine:fixtures:load

## to fetch data from the api

## run from command line php bin/console fetch_artist with argument which present name of the artist

## for example php bin/console fetch_artist Marko

## update SPOTIFY_API_KEY in the .env file The last one is sended on email

## if that api_key does not work email me at markostevic96@hotmail.com for the latest api_key

## I will send it immediately

## run from command line symfony server:start

## Test the application !!!
