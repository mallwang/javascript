# Family Library Frontend

This subdirectory contains the frontend component of the Family Library, which is a web application built using [Angular](https://angular.io) and [SiMPL Marengo](https://simpl.code.siemens.io/simpl-marengo).

## Functionality

The frontend application consists of the following parts / provides the following functionality:

- using Angular version 9

- using [Clarity Design System](https://clarity.design/)

- using Guards for protecting routes against non-authenticated users and only grant access to the requested route if the user has the required role

- a global alert can be displayed on top of the page showing any information to the user

- a global error handler is setup for catching all client-side errors

- a global http interceptor is setup for catching all http-related errors

- a global http interceptor is setup for adding the JWT to any request to the backend

## Setup

#### Automatic Setup

There exists a bash script **setup-local-environment.sh** for setting up the local development machine automatically, please run the script using administrator access.  
**Note: installing the dependencies can take a while (up to 15 minutes)**

#### Manual Setup

1. Install dependencies

```bash
npm install
```

2. Exclude environment config files  
   (Because the angular environment files will automatically configured on every local start based on environment variables and contain sensitive values they should not be commited to git. Instead of manually reverting them everytime which could be error-prone, the files can be ignored from the start by telling git to assume the files as unchanged. Adding to gitignore is no option because the files are already tracked after the checkout.)

```bash
git update-index --assume-unchanged src/environments/environment.production.ts
git update-index --assume-unchanged src/environments/environment.ts
```

## Development

#### Mandatory configuration

The frontend component will be configured using environment variables read from a dotenv file (.env), for local development all of the default values are fine. There are no mandatory settings required.

#### Overwrite default configuration

There are multiple configuration settings used to configure the frontend application. For local development the default values are fine, but they can be easily overwritten by setting the specific value in the .env file **before** starting the application.

```bash
### FRONTEND (/components/frontend/.env)
NODE_ENV                The environment the frontend application is running in
                        (default: 'development')

BACKEND_BASE_URL        The URL the backend is running on
                        (default: 'http://localhost:3000/v1')
```

#### Start

Now, by running `npm run start:dev` the frontend will start in development mode and will reload on changes.
