# CRM Module

- Run `php artisan fresh-module Contacts --seed=yes --connection=crm` to delete old migration history, re-migrate migrations from Database/Migrations directory and optionally run modules seed operation.
- set connection for relation `->setConnection('other_connection_name')->`
