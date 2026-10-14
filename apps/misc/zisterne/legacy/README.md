## Prerequisites
* switch into your desired directory
* install NodeJS globally
* install [NodeJS forever library](https://github.com/foreverjs/forever) globally
* install [NodeJS forever service for running Node app as a system service](https://github.com/zapty/forever-service) globally

## Installation
* clone whole project or download zip file and unzip it
* run 'sudo npm install --unsafe-perm' for installing dependencies
* change into folder 'PIGPIO' ('cd PIGPIO')
* run 'make'
* run 'sudo make install'
* run 'sudo node app.js' for running webserver

## Project Structure
```
YOUR_PROJECT_DIRECTORY
│   README.md (this readme file)
│   config.json (contains configuration variables) 
│   package.json (main project file for node)
│   app.js (main)
│
└───Sonstiges (some PDFs)
│
└───public (static resources directory)
│       │   index.html (main entry page for displaying fill height)
│       └───css (stylesheets)
│       └───fonts (glyphicons)
│       └───js (scripts)
│
└───PIGPIO (some C++ and wrapper files for C++)
```

## Todo
* how to set config variables
* how to access in local network with ip address