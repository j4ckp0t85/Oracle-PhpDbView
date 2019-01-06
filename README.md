# Oracle-PhpDbView
Show tables/fields/rows for Oracle DB(s) using php

A simple solution to be used to quickly search tables or fields (and rows for parametric tables) and take annotations on-the-fly.
Designed to be used together with sql developer.

Files to edit: index (link to databases) and config.php

Naming convention for parametric tables: all starts with a specific string (e.g. LTABLE...)

Connection to Oracle DB using Oracle instant client and php oci driver (https://www.oracle.com/technetwork/articles/dsl/technote-php-instant-12c-2088811.html).

Tables and columns are stored as session vars and populates datalist for searching (keyword search: at least 3 chars).
