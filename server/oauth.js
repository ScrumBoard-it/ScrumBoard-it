// @flow
'use strict';

import type {LambdaResponse} from './types';
const http = require('request-promise');

module.exports.config = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  const provider: string = event.pathParameters.provider;

  const data: ConfigResponse = {
    client_id: providerConfigs.github.client_id,
  }

  callback(null, {
    statusCode: 200,
    headers: {
      "Access-Control-Allow-Origin" : "*",
      "Access-Control-Allow-Credentials" : true
    },
    body: JSON.stringify(data),
  });
};

module.exports.token = (event: any, context: any, callback: (error: ?Error, data: ?LambdaResponse) => void) => {
  const provider: string = event.pathParameters.provider;
  const code: string = event.queryStringParameters && event.queryStringParameters.code;

  if (!code) {
    callback(null, {
      statusCode: 400,
      headers: {
        "Access-Control-Allow-Origin" : "*",
        "Access-Control-Allow-Credentials" : true
      },
      body: JSON.stringify({
        provider: 'scrumboard-it',
        error: 'Missing required parameter: code'
      }),
    })
    return;
  }

  http({
    method: 'POST',
    uri: 'https://github.com/login/oauth/access_token',
    body: {
      client_id: providerConfigs.github.client_id,
      client_secret: process.env.GITHUB_CLIENT_SECRET || '',
      code
    },
    json: true
  }).then(rawData => {
    if (rawData.error_description) {
      callback(null, {
        statusCode: 400,
        headers: {
          "Access-Control-Allow-Origin" : "*",
          "Access-Control-Allow-Credentials" : true
        },
        body: JSON.stringify({
          provider,
          error: rawData.error_description,
        }),
      });
    } else {
      const data: TokenResponse = {
        token: rawData.access_token,
        type: rawData.token_type,
      };

      callback(null, {
        statusCode: 200,
        headers: {
          "Access-Control-Allow-Origin" : "*",
          "Access-Control-Allow-Credentials" : true
        },
        body: JSON.stringify(data),
      });
    }
  }).catch((err) => {
    callback(null, {
      statusCode: err.statusCode,
      headers: {
        "Access-Control-Allow-Origin" : "*",
        "Access-Control-Allow-Credentials" : true
      },
      body: JSON.stringify({
        provider,
        error: err.error.error
      }),
    });
  });
};

const providerConfigs = {
  github: {
    client_id: '6f6efdfd73f205503f35',
  }
}

type TokenResponse = {
  token: string,
  type: string,
}

type ConfigResponse = {
  client_id: string,
}