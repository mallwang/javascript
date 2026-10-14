#!/bin/bash

echo -e "----------------------------------------------------------------------------"
echo -e "setting up your local development machine..."
echo
echo -e "----------------------------------------------------------------------------"
echo -e "installing Family Library monorepo npm packages..."
npm install || exit
echo -e "Family Library monorepo npm packages successfully installed"
echo
echo -e "----------------------------------------------------------------------------"
echo -e "installing Family Library Frontend npm packages..."
cd components/frontend || exit
npm install || exit
#git update-index --assume-unchanged src/environments/environment.production.ts || exit
#git update-index --assume-unchanged src/environments/environment.ts || exit
echo -e "Family Library Frontend npm packages successfully installed"
echo
echo -e "----------------------------------------------------------------------------"
echo -e "installing Family Library Backend npm packages..."
cd ../backend || exit
npm install || exit
echo -e "Family Library Backend npm packages successfully installed"
echo
echo -e "----------------------------------------------------------------------------"
echo -e "successfully set up your local development machine!"
