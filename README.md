# Donation!
**Let's make something useful for this world.**

_**Donation**_ is an open-source Laravel-based initiative designed to empower individuals and organizations worldwide to easily initiate charity campaigns. With this project, anyone can create, customize, and launch their own charity platform, enabling the global community to contribute and support causes that matter most.

## Demo
You can see the demo [Here](https://demo-donation.techgeeta.com/)
Or have a quick look from screenshots:

|  Page   |   Image       |
|---------|---------------|
|  Home  | [<img src="https://github.com/souravdutt/donation/assets/49240259/fc91d5f3-d9a0-4b90-bad0-4a3cb6c7cc84" width="400" />](https://github.com/souravdutt/donation/assets/49240259/fc91d5f3-d9a0-4b90-bad0-4a3cb6c7cc84) |
|  Donate | [<img src="https://github.com/souravdutt/donation/assets/49240259/43f35a9e-f11f-4f8a-a452-016dff20b5fc" width="400" />](https://github.com/souravdutt/donation/assets/49240259/43f35a9e-f11f-4f8a-a452-016dff20b5fc) |
|  Album  | [<img src="https://github.com/souravdutt/donation/assets/49240259/1472c3db-47ee-4a7e-b553-be4ba0f5d84a" width="400" />](https://github.com/souravdutt/donation/assets/49240259/1472c3db-47ee-4a7e-b553-be4ba0f5d84a) |
| Contact | [<img src="https://github.com/souravdutt/donation/assets/49240259/456ce974-0770-4687-83ed-8f7773d5dc08" width="400" />](https://github.com/souravdutt/donation/assets/49240259/456ce974-0770-4687-83ed-8f7773d5dc08) |
| Admin: Login | [<img src="https://github.com/souravdutt/donation/assets/49240259/93f5d348-d481-4a7b-a0a1-f947195b06f5" width="400" />](https://github.com/souravdutt/donation/assets/49240259/93f5d348-d481-4a7b-a0a1-f947195b06f5) |
| Admin: Donations List | [<img src="https://github.com/souravdutt/donation/assets/49240259/29de406a-f3e4-4e01-9ef4-9e6e09b8a392" width="400" />](https://github.com/souravdutt/donation/assets/49240259/29de406a-f3e4-4e01-9ef4-9e6e09b8a392) |
| Admin: Manage Members | [<img src="https://github.com/souravdutt/donation/assets/49240259/8e3ef39e-8432-498a-92a8-7c3ff7abb972" width="400" />](https://github.com/souravdutt/donation/assets/49240259/8e3ef39e-8432-498a-92a8-7c3ff7abb972) |

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
