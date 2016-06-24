# Badges4Languages

## Description
Gives a certification to a teacher or a student.


### About

A student or a teacher can obtain a badge (Mozilla OpenBadges) by himself.
Then, a teacher can confirm the student level by giving him a certification too.

**For the moment, only Self Certification is available !
You could have some conflict if you use "WP CSV to Database" plugin at the same time**

### General Information
[Read general teorical information](/README-general-information.md).

#### WP CSV to Database
Import/Export data from CSV to Database.
[Official link](https://wordpress.org/plugins/wp-csv-to-database/)


## Requirements
This is a plugin for Wordpress (tested on 4.3)


## Installation

1. Clone (or copy) this repository to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


## Notes

* You could have a right problem when you want to import a CSV file (not allowed to import it) on a multisite Wordpress. Go to the the multisite "network settings", then to the category "Upload file types", and add "csv".

* When you import a CSV file, it is possible that you have an error message, but if you have respected the form, the CSV will be imported.


## Screenshots

### Book Information in the dashboard.
![General information.](assets/GeneralInformation.png)

![Related books.](assets/RelatedBooks.png)







## Changelog

1.1.2
* Documentation + Cleaning of the code + Minor corrections;
* More information in the JSON Files;
* A first release can be deployed.

1.1.1
* Amelioration of the OpenBadges Certification.

1.1.0
* Get Mozilla OpenBadges Certification available.

1.0.5
* Sending an email to receive the certification (email ok, but certification page not implemented yet).

1.0.2
* Custom Post with his information can be displayed on the user's ordPress website;
* 1 Custom Post per page;
* Translation possible to other language.

1.0.1
* Creation of the submenu 'CSV File Upload' which allowed a user to import a .csv file into a database table and to export a db table into a .csv file;
* Documentation and simplification of the code.

1.0.0
* Initial version;
* Creation of the Custom Type 'Badge School';
* Creation of the categories : 'Teacher Levels', 'Student Levels', 'Skills' and 'Tags'.








-- TO DO LIST --
* Change 'wp' prefix of 'wp_csv_to_db' file to avoid conflict if you install the plugin
* Implement a page to give issuer information (because now information is in the code).


## Credits

Uses the [WordPress Plugin Boilerplate](http://wppb.io/).