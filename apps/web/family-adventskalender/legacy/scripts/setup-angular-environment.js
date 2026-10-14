/**
 * This file is intended for creating the angular environment file based on environment variables
 * read from the .env file inside the parent directory.
 *
 * ### PARAMETERS ##############################################################
 * No parameters required.
 *
 * ### USED ENVIRONMENT VARIABLES ##############################################
 * - REQUIRED_MONTH:      Required month the user is allowed to open the presents.
 * - NODE_ENV:            Name of the node environment the application runs in.
 * - USE_HASH:            Whether the Angular hash location strategy should be used.
 *
 * ### USAGE ###################################################################
 * node setup-environment.ts
 */

const fs = require('fs');
const path = require('path');
const dotenv = require('dotenv');

// Read all values from the .env file and map them into process.env variables
dotenv.config({path: path.resolve(__dirname, '..', '.env')});

const environment = process.env.NODE_ENV || 'development';
const targetPath = './src/environments'; // Angular environments folder
const fileName = environment === 'development' ? 'environment.ts' : `environment.${environment}.ts`;
const targetFile = `${targetPath}/${fileName}`;
const useHash = process.env.USE_HASH === 'true';

// Content of the Angular environment file to be consumed by Angular services
const envConfigFile = `/**
 * PLEASE DO NOT CHANGE THE FILE BECAUSE ITS GENERATED AUTOMATICALLY.
 * ANY MANUAL CHANGES WILL BE OVERWRITTEN AND COULD BE EASILY LOST.
 */
export const environment = {
  production: ${environment === 'production'},
  requiredMonth: ${process.env.REQUIRED_MONTH || 11},
  useHash: ${useHash}
};
`;

// Create the target file
fs.writeFile(targetFile, envConfigFile, (err) => {
  if (err) {
    console.error('Environment file could not be created due to error:', err);
    process.exit(1);
  }

  console.log(`Created Angular environment file "${targetFile}" with content:\n\n`, envConfigFile);
});
