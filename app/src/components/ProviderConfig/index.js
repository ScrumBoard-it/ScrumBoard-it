import React, { Component } from 'react';

const ProviderConfig = (({provider, onSubmit}) => {
  return (
    <div>
      <h2>{provider}</h2>
      <form>
        <label htmlFor="token">Token</label>
        <input id="token" name="token" />
        <input type="button" value="Next" onClick={() => {onSubmit(document.getElementById("token").value)}} />
      </form>
    </div>
  );
});

export default ProviderConfig;