import React, { Component } from 'react';

const TaskList = ({ tasks }) => {
  return (
    <div>
      <ul>
        {tasks.map((task) => <li>{task.description}</li>)}
      </ul>
    </div>
  );
};

export default TaskList;
