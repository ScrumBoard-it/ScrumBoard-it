import React from 'react';

import './PrintPreview.css'

const PrintPreview = ({tasks}) => {
    return (
        <div className="print">
            <div className="container">
                { tasks.map((task) => (
                    <div className="task" key={task.id}>
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