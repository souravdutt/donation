# Donation!
**Let's make something useful for this world.**

_**Donation**_ is an open-source Laravel-based initiative designed to empower individuals and organizations worldwide to easily initiate charity campaigns. With this project, anyone can create, customize, and launch their own charity platform, enabling the global community to contribute and support causes that matter most.

## Demo
You can see the demo [Here](https://demo-donation.techgeeta.com/)

## Installation guid
* Clone repo:
```git clone https://github.com/souravdutt/donation.git```

* Open project
```cd donation```

* Download vendors/libraries
```composer install```
    * Meanwhile you can create a new Database

* Create `.env` file
```cp .env.example .env```

* Generate app key
```php artisan key:generate```

* add DB info in `.env` file
    ```
    DB_DATABASE=<YOUR DB NAME>
    DB_USERNAME=<YOUR DB USERNAME>
    DB_PASSWORD=<YOUR DB PASSWORD>
    ```

* Add stripe API keys in `.env` file
    ```
    STRIPE_KEY=<your_api_key>
    STRIPE_SECRET=<your_secret_key>
    STRIPE_WEBHOOK_SECRET=<your_webhook_secret>
    ```

* Add checkout information in `.env` file
    ```
    DONATION_CURRENCY=INR
    MIN_DONATION_AMOUNT=1000

    TRUST_NAME="School for Blind and Disabled Children"
    TRUST_ADDRESS="Delhi Gate"
    TRUST_CITY="Malerkotla"
    TRUST_ZIPCODE="148024"
    TRUST_COUNTRY="India"
    TRUST_PHONE="+919999999999"
    TRUST_EMAIL="test@gmail.com"
    ```

* Migrate db tables
```php artisan migrate```

* Seed data into required tables *(For Testing)*
    ```
    php artisan db:seed
    ```
    - Please note we use countries states and cities to show location to the donors.
    - https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/sql/world.sql

### Want to contribute?
Contributions and suggestions are welcomed! Feel free to fork the project, make enhancements, and submit pull requests to help improve the platform's functionality and impact.

Join us in making a difference with _Donation_ Project!

### License?
MIT License
