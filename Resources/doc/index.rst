Installation
============

1. Using Composer
-----------------

To install ScrumBoardItBundle with Composer just add the following to your
`composer.json` file:

.. code-block :: js

    // composer.json
    {
        // ...
        "require-dev": {
            // ...
            "canaltp/scrum-board-it-bundle": "dev-master"
        }
    }

.. note ::

    Please replace `dev-master` in the snippet above with the latest stable
    branch, for example ``1.0.*``.

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

.. code-block :: bash

    php composer.phar update

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

.. code-block :: php

    <?php

    // in AppKernel::registerBundles()
    if (in_array($this->getEnvironment(), array('dev', 'test'))) {
        // ...
        $bundles[] = new CanalTP\ScrumBoardItBundle\CanalTPScrumBoardItBundle();
        // ...
    }

Then configure the bundle with the required parameters in ``config_dev.yml`` :

.. code-block :: yaml

    assetic:
        // ...
        bundles:
            - CanalTPScrumBoardItBundle

    canal_tp_scrum_board_it:
        jira_url: "http://your.jira"

Then, you to have import routes in ``routing_dev.yml`` and add optionally a prefix :

.. code-block :: yaml

    _scrum_board_it:
        resource: "@CanalTPScrumBoardItBundle/Resources/config/routing.yml"


Then add in security.yml:

.. code-block :: yaml

    provider:
        jira_auth_provider:
            id: canaltp_jira_auth.user_provider
    firewall:
        jira_secured:
                pattern: /
                switch_user: false 
                context:     user
                provider: jira_auth_provider
                jira:
                    login_path: /login
                    check_path: /login_check
                    remember_me: true
                logout:
                    path: /logout
                    target: /login
                remember_me:
                    key: "%secret%"
                    lifetime: 300
                    path: /.*
                    domain: ~
                anonymous: ~

Finally you need to install assets

.. code-block :: bash

    php app/console assets:install --symlink
