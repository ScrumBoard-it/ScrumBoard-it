import React from 'react';
import {Card, CardHeader, CardText, CardActions} from 'material-ui/Card';
import FlatButton from 'material-ui/FlatButton';

const TaskList = ({ tasks, onAdd }) => {
  return (
    <div>
      {tasks.map((task) => {
        let hasDescription = (!!task.description)
        return (
        <Card key={task.id} className="task-container">
          <CardHeader
            title={task.title || `${task.description.slice(0, 40)} ...`}
            subtitle={task.key}
            actAsExpander={hasDescription}
            showExpandableButton={hasDescription}
          />
          {hasDescription &&
          <CardText expandable={true}>{task.description}</CardText>
          }
          <CardActions>
            <FlatButton label="Add for print" onClick={() => {onAdd(task)}} />
          </CardActions>
        </Card>
        )
      })}
    </div>
  );
};

export default TaskList;
