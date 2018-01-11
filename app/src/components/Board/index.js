import React, { Component } from 'react';

class Board extends Component {
  render() {
    return (
      <tr>
        <td>{this.props.id}</td>
        <td><a href="#" data-id={this.props.id}>{this.props.name}</a></td>
      </tr>
    );
  }
}

export default Board;
