# Family Library Backend

This subdirectory contains the backend component of the Family Library, which is a NodeJS application built using [NestJS](https://nestjs.com/).

## Functionality

The backend application consists of the following parts / provides the following functionality:

- using NestJS version 6.10

- a healthcheck is setup to check if the application is up

- integrate OpenAPI and provide a swagger api documentation

## Setup

#### Automatic Setup

There exists a bash script **setup-local-environment.sh** for setting up the local development machine automatically, please run the script using administrator access.  
**Note: installing the dependencies can take a while**

#### Manual Setup

1. Install dependencies

```bash
npm install
```

## Development

#### Mandatory configuration

The backend component will be configured using environment variables read from a dotenv file (.env), for local development all of the default values are fine. There are no mandatory settings required.

#### Overwrite default configuration

There are multiple configuration settings used to configure the backend application. For local development the default values are fine, but they can be easily overwritten by setting the specific value in the .env file **before** starting the application.

```bash
### BACKEND (/components/backend/src/.env)
NODE_ENV                            The environment the backend application is running in
                                    (default: 'development')

HOST                                The address the application is running on
                                    (default: 'localhost')

PORT                                The port the application is running on
                                    (default: 3000)
```

#### Start

Now, by running `npm run start:dev` the backend will start in development mode and will reload on changes.
