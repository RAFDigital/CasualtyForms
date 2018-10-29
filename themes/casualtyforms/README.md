RAF Museum Casualty Forms Theme
===============================

This is a theme for the RAF Museum's Casualty Forms project, built off the
Demo theme by OctoberCMS.

## Combining CSS and JavaScript

This theme doesn't combine assets for performance reasons. To combine the stylesheets, replace the following lines in the default layout.

Uncombined stylesheets:

    <link href="{{ 'assets/css/vendor.css'|theme }}" rel="stylesheet">
    <link href="{{ 'assets/css/styles.css'|theme }}" rel="stylesheet">

Combined stylesheets:

    <link href="{{ [
        '@framework.extras',
        'assets/less/vendor.less',
        'assets/less/styles.less'
    ]|theme }}" rel="stylesheet">

> **Note**: October also includes an SCSS compiler, if you prefer.

Uncombined JavaScript:

    <script src="{{ 'assets/vendor/jquery.js'|theme }}"></script>
    <script src="{{ 'assets/vendor/bootstrap.js'|theme }}"></script>
    <script src="{{ 'assets/javascript/app.js'|theme }}"></script>
    {% framework extras %}

Combined JavaScript:

    <script src="{{ [
        '@jquery',
        '@framework',
        '@framework.extras',
        'assets/vendor/bootstrap.js',
        'assets/javascript/app.js'
    ]|theme }}"></script>

> **Important**: Make sure you keep the `{% styles %}` and `{% scripts %}` placeholder tags as these are used by plugins for injecting assets.
