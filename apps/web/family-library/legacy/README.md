# Family Library

This repository is a monorepo containing both the Family Library Frontend Component and the Family Library Backend Component managed by using [Lerna](https://lerna.js.org/).

## Functionality

The repository contains the following functionality:

- using Lerna for managing this repository as monorepo

- a CICD pipeline is setup with different stages

- using Git Hooks for format the source code using Prettier and check the commit message using Commitlint

- setup release management using Lerna

## Setup

#### Automatic Setup

There exists a bash script **setup-local-environment.sh** for setting up the local development machine automatically, please run the script using administrator access.  
**Note: installing the dependencies can take a while**

#### Manual Setup

1. Install monorepo dependencies (incl. git hooks)

```bash
npm install
```

2. Install frontend dependencies

```bash
cd components/frontend
npm install
cd ..
```

3. Exclude environment config files  
   (Because the angular environment files will automatically configured on every local start based on environment variables and contain sensitive values they should not be commited to git. Instead of manually reverting them everytime which could be error-prone, the files can be ignored from the start by telling git to assume the files as unchanged. Adding to gitignore is no option because the files are already tracked after the checkout)

```bash
git update-index --assume-unchanged components/frontend/src/environments/environment.production.ts
git update-index --assume-unchanged components/frontend/src/environments/environment.ts
```

4. Install backend dependencies

```bash
cd components/backend
npm install
```

## Development

All components are located in the `components` directory, they can either be started individually or all-at-once using [Lerna](https://lerna.js.org/) which is able to run a given command for all components in the directory.

#### Mandatory configuration

The components will be configured using environment variables read from a dotenv file (.env), for local development all of the default values are fine. There are no mandatory settings required.

#### Overwrite default configuration

There are multiple configuration settings used to configure both the frontend and the backend application individually. For local development the default values are fine, but they can be easily overwritten by setting the specific value in the .env file **before** starting the application.

```bash
### FRONTEND (/components/frontend/.env)
NODE_ENV                            The environment the frontend application is running in
                                    (default: 'development')

BACKEND_BASE_URL                    The URL the backend is running on
                                    (default: 'http://localhost:3000/v1')


### BACKEND (/components/backend/src/.env)
NODE_ENV                            The environment the backend application is running in
                                    (default: 'development')

HOST                                The address the application is running on
                                    (default: '0.0.0.0')

PORT                                The port the application is running on
                                    (default: 3000)
```

#### Start all components

By running `npm run start:dev` all components will start in development mode and will reload on changes.
