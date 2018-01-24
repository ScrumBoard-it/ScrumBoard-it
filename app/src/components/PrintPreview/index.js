import React from 'react';
import FloatingActionButton from 'material-ui/FloatingActionButton';
import {ActionDelete} from 'material-ui/svg-icons';

import './PrintPreview.css'

const PrintPreview = ({tasks, onRemove}) => {
    return (
        <div className="print preview">
            <div className="container">
                { tasks.map((task) => (
                    <div className="task" key={task.id}>
                        <div className="btn-remove no-print">
                            <FloatingActionButton mini={true} secondary={true} onClick={() => {onRemove(task)}}>
                                <ActionDelete />
                            </FloatingActionButton>
                        </div>
                        {task.key &&
                        <div className="header">{task.key}</div>
                        }
                        <div className="content">
                            <div className="title">{task.title}</div>
                            <div className="description">{task.description}</div>
                        </div>
                    </div>
                )) }
            </div>
        </div>
    )
}

export default PrintPreview;