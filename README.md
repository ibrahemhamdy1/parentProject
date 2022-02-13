
# Services

- laravel app
## Folder Structure

- my-project contain laravel app
- mysql hold mysql configuration
- nginx hold nginx configuration
- php hold php configuration

## Laravel App

responsible for business layer logic

## Install

- Clone repo `git clone https://github.com/ibrahemhamdy1/parentProject.git`
- Then change your directory `cd parentProject`
- Run project `docker-compose up`

### Run the Seeders
    - cd 'parentProject/my-project' run 'php artisan db:seed'
### Run Tests

execute this command to migrate with alias `docker exec parentProject php vendor/bin/phpunit`


## Providers files

Will found on `src/storage/app/providers` folder

## Endpoints:
- Get all users
   GET | 'api/v1/users'
    # Available filter
        - Limit users per page'perPage'
        - Filter by using provider the filter is 'provider'
        - User status 'statusCode' is authorized, decline, refunded
        - User with balance range using 'balanceMin' and 'balanceMax'
        - User currency by using 'currency'

## Used providers
    -- Note if you going to add a new file need to be with the structure from one of the next files
    - DataProviderX.json
        '[
            {
                "parentAmount":100,
                "Currency":"USD",
                "parentEmail":"parent1@parent.eu",
                "statusCode":1,
                "registrationDate": "2018-11-30",
                "parentIdentification": "d3d29d70-1d25-11e3-8591-034165a3a613"
            },
            {
                "parentAmount":200,
                "Currency":"EGP",
                "parentEmail":"parent1@parent.eu",
                "statusCode":2,
                "registrationDate": "2018-11-30",
                "parentIdentification": "d3d29d70-1d25-11e3-8591-034165a3a613"
            }
        ]'

    - DataProviderY.json
        '[
            {
                "balance":300,
                "currency":"AED",
                "email":"parent2@parent.eu",
                "status": 300,
                "created_at": "22/12/2018",
                "id": "4fc2-a8d1"
            }
        ]'

