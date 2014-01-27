ScrumBoard-it
=============

A tool to facilitate Scrum card printing from Atlassian Jira

Installation
------

```
php composer.phar install
```

Usage
------

configure app/config/parameters.yml
```yaml
parameters:
    jira_url:          http://your.jira
    jira_login:        login
    jira_password:     password
    sprint_id:         50
    jira_tag:          Post-it
```

http://your.jira/rest/greenhopper/latest/rapidview
get id of your board

http://your.jira/rest/greenhopper/latest/sprints/6
get id of your sprint
