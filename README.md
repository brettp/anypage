Anypage
=======
![Elgg 2.3](https://img.shields.io/badge/Elgg-2.3-orange.svg?style=flat-square)

Static page management UI for Elgg

Features
--------

* Create static pages with custom slugs (page addresses)
* Pages can be populated by an editor or from a view

Examples
--------

Defined slug: /privacy_policy
View file: views/default/anypage/privacy_policy.php

Defined slug: /about/users
View file: views/default/anypage/about/users.php

Defined page: about/users/index.html
View file: views/default/anypage/about/users/index.html

Dev Notes
---------

To add a custom layout, use `'layouts','anypage'` plugin hook.
