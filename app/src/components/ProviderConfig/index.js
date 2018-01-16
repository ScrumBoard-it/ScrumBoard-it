import React, { Component } from 'react';

// const token = 'bdd3081f7f85b51052a5abeb5e0097ffc8a1e9ff';
// const url = 'https://n72gehvp72.execute-api.us-east-1.amazonaws.com/dev/boards';
// const headers = new Headers();
// headers.append('Authorization', 'Bearer ' + token);

// fetch(url, {
//   headers: headers
// }).then((response) => {
//   return response.json();
// }).then((json) => {
//   console.info(json);
//   this.setState({boards: json.boards});
// }).catch((error) => {
//   console.error(error);
// })

export default (({provider, onSubmit}) => {
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
