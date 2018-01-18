import React from 'react';
import {Card, CardHeader, CardText} from 'material-ui/Card';

const TaskList = ({ tasks }) => {
  return (
    <div>
      {tasks.map((task) => {
        let hasDescription = (!!task.description)
        return (
        <Card key={task.id}>
          <CardHeader
            title={task.title || `${task.description.slice(0, 40)} ...`}
            subtitle={task.key}
            actAsExpander={hasDescription}
            showExpandableButton={hasDescription}
          />
          {hasDescription &&
          <CardText expandable={true}>{task.description}</CardText>
          }
        </Card>
        )
      })}
    </div>
  );
};

export default TaskList;
