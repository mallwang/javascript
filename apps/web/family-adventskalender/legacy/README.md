# Family Adventskalender

This repository contains the source code of the Family Adventskalender, which is a progressive web app (PWA) built on [Angular](https://angular.io) and [Ionic](https://ionicframework.com).

## Prerequisites

#### Install NodeJS

NodeJS can be installed via the official [NodeJS installer](https://nodejs.org/en/) or via the [node version manager](https://www.sitepoint.com/quick-tip-multiple-versions-node-nvm/), which lets you easily switch between different versions of NodeJS.

## Installation

#### Clone repository

```bash
git clone git@gitlab.com:markus.allwang/family-adventskalender.git
```

#### Install all dependencies

```bash
cd family-adventskalender
npm install
```

#### Exclude environment config files

Because the angular environment files will automatically configured on every local start based on environment variables and contain sensitive values they should not be committed to git. Instead of manually reverting them everytime (which could be error-prone), the files can be ignored from the start by configuring git to assume the files as unchanged. Adding to gitignore is no option because the files are already tracked after the checkout.

```bash
git update-index --assume-unchanged src/environments/environment.production.ts
git update-index --assume-unchanged src/environments/environment.ts
```

**Note:** once there are new variables to be added to the environment files, this configuration needs to be reverted, then the changes need to be checked in and finally the same command should be run again.

## Development

#### Mandatory configuration

The application will be configured using environment variables read from a dotenv file (.env), for local development all of the default values are fine. There are no mandatory settings required.

#### Overwrite default configuration

There are multiple configuration settings used to configure the application. For local development the default values are fine, but they can be easily overwritten by setting the specific value in the .env file **before** starting the application.

```bash
NODE_ENV                            The environment the application is running in
                                    (default: 'development')

REQUIRED_MONTH                      Required month the user is allowed to open the presents
                                    (default: 11) = december
```

#### Start

Running `npm run start:dev` will configure the environment files and starts and opens the application in a new browser window/tab on http://localhost:4200 with live-reloading at any changes.

## Test

#### Setup Puppeteer

This project uses Puppeteer instead of Selenium/Webdriver for running unit and end-to-end tests. Puppeteer downloads a recent version of Chromium from a Google server by default. Puppeteer is a development dependency and will be automatically installed via `npm install`.

#### Executing tests locally

For executing tests locally, installing all dependencies is enough.

Run `npm run test` to execute the unit tests in watch mode via [Karma](https://karma-runner.github.io) using [Puppeteer](https://pptr.dev/).

Each version of Puppeteer works only with a certain version of Chromium, therefore it must be downloaded before executing end-to-end tests. The webdriver-manager can be updated using `npm run webdriver:update`.

Run `npm run test:e2e` to execute the end-to-end tests via [Protractor](http://www.protractortest.org/) using [Puppeteer](https://pptr.dev/).

#### Testing in Gitlab CI

For running Puppeteer inside the Gitlab CI pipeline it requires some additional dependencies, e.g. for Unix see https://github.com/GoogleChrome/puppeteer/blob/master/docs/troubleshooting.md#chrome-headless-doesnt-launch-on-unix. Therefore we are using a Docker image already containing these dependencies.

Run `npm run test:ci` to execute the unit tests via [Karma](https://karma-runner.github.io) using [Puppeteer](https://pptr.dev/).

## Build

#### Bundle the sources and create build artifacts

```bash
npm run build:[dev|prod]
```

All sources of the app are now located inside the **www** folder.
