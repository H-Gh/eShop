# eShop sample project

## Setup
- Clone the project by ``git clone https://github.com/H-Gh/eShop.git``
- Run ``docker-compose up``
- After a while, run ``docker container exec -it app bash``
- Execute these commands:
    - ``cp .env.example .env`` to create ``.env`` file.
    - ``vim .env`` to configure database info. (root password is ``ZsdY0ZPd16``)
    - Run ``php artisan migrate`` to migrate database.
    - Run ``php artisan db:seed`` to seed database.
    
## Run
A full api documentation exists under following link:

``https://documenter.getpostman.com/view/2089487/TWDXmvnF``

Open it and import it to the post man application. Define your environment variables and run the application.

**Notice**: The port to access application is ``8080``.