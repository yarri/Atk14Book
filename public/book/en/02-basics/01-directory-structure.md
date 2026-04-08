Directory structure
===================

ATK14 keeps files in fixed, well-defined locations. Once you understand the structure, you'll find your way around any ATK14 project immediately — without reading any documentation.

```text
app/
    controllers/            controllers – handle HTTP requests
    fields/                 custom form fields
    forms/                  forms
    helpers/                helper functions for displaying data in templates
    layouts/                layout templates – define the arrangement of components on pages
    models/                 models – database access and business logic
    views/                  templates (Smarty)
        shared/             shared templates usable across controllers
    widgets/                classes defining the appearance of form fields
atk14/                      framework source code (don't touch this)
config/                     configuration files
    routers/                routers for defining the URL schema
db/migrations/              database migrations
lib/                        shared libraries specific to your project
local_config/               local configuration that is not versioned in Git
local_scripts/              shell scripts specific to this particular installation
locale/                     localisation dictionaries (gettext)
log/                        application logs
public/                     files accessible from the web: CSS, JS, images and other static content
robots/                     robots – scripts for periodic or background tasks
    lock/                   locks preventing concurrent execution of the same robot
scripts/                    shell scripts for application management
test/                       tests
    app/                    basic application tests
    controllers/            controller tests
    fields/                 form field tests
    fixtures/               sets of test data
    helpers/                view helper tests
    lib/                    shared library tests
    models/                 model tests
    routers/                router tests
tmp/                        temporary files (e.g. cache)
vendor/                     libraries installed via Composer
```

At first glance there seem to be a lot of directories — and that's true :) In practice you'll find that the vast majority of your time is spent in just four of them: `app/controllers`, `app/views`, `app/models`, and `app/forms`.

Namespaces
----------

Larger applications are often split into **namespaces** — sub-applications that share the database, models, shared templates, form fields, and helpers with the main application, but have their own controllers and views.

A typical example is the `admin` namespace, where the administrator has access to management tools. You create a namespace simply by adding subdirectories:

```text
app/controllers/admin/
app/forms/admin/
app/views/admin/
test/controllers/admin/
```

Another common namespace is `api`, which holds endpoints for machine-to-machine communication with the outside world.

Important configuration files
------------------------------

```text
config/
    settings.php            main application configuration file
    locale.yml              list of supported languages
    deploy.yml              settings for deploying the application to production
    database.yml            database credentials
```

If a configuration file with the same name is placed in the `local_config` directory, it will be loaded in preference. Typically, the production database connection is stored in `local_config/database.yml`.
