import React from 'react';
import RaisedButton from 'material-ui/RaisedButton';
import TextField from 'material-ui/TextField';
import Paper from 'material-ui/Paper';
import './ProviderConfig.css'

const submitForm = (onSubmit) => {
  onSubmit(document.getElementById("token").value)
}

const ProviderConfig = (({provider, onSubmit}) => {
  return (
    <Paper className="provider-config" zDepth={1}>
      <h2>{provider.charAt(0).toUpperCase() + provider.slice(1)} configuration</h2>
      <form>
        <TextField id="token" floatingLabelText="Oauth token" fullWidth={true} />
        <RaisedButton label="Next" primary={true} className="submit" onClick={() => {submitForm(onSubmit)}} />
      </form>
    </Paper>
  );
});

export default ProviderConfig;