const {getBabelLoader} = require('react-app-rewired');  

module.exports = function override(config, env) {
  config.module.rules = config.module.rules.concat({
    parser: {
      amd: false
    },
  });

  return config;
}
