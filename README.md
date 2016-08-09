# Badges4Languages

## Description
Gives a certification to a teacher or a student.

### About
A student or a teacher can obtain a badge (Mozilla OpenBadges) by himself.
Then, a teacher can confirm the student level by giving him a certification too.

### Copyright and License
[This project is licensed under the GNU GPL version 2.](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html).

### Plugin used : WP CSV to Database
Import/Export data from CSV to Database.
[Official link](https://wordpress.org/plugins/wp-csv-to-database/)

### Plugin used : Comments Ratings
Convert comments into reviews for your visitors. 
[Official link](https://wordpress.org/plugins/comments-ratings/)


## Requirements
This is a plugin for Wordpress (tested on 4.5.3)


## Installation

1. Clone (or copy) this repository to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


## Utilisation

1. First, you have to import you CSV data in the Database with the submenu 'Initialization - Import Data Into Database' into 'Badge School' Menu;
1. Next, you have to give the issuer's information thanks to the submenu 'Badge Issuer Information';
1. You can create a 'Badge School' Post which corresponds to a badge level (A1, A2, B1, T1, T2,....). The Badge School name must be explicit (A1, A2...) !
1. You can finally get a badge (self-certification badge or badge given by a teacher) or send it if you are a teacher.
For more information, please read the user guide.


## Notes

* You could have a permission problem when you want to import a CSV file (not allowed to import it) on a multisite Wordpress. Go to the multisite "network settings", then to the category "Upload file types", and add "csv" to the list.
* When you import a CSV file, it is possible that you have an error message, but if you have respected the form, the CSV will be imported.
* All of these errors are reported into the user guide, please read it before starting using our plugin.
* Badges Information are saved in 2 places (Json File and Database) for my security.





## Changelog

1.1.2
* Information message if you are not registered and you want to get a certification by yourself;
* Badges on the user profile with CSS;
* Saving more information on the Json File (in order to display the badges on the profile).

1.1.0
* Creation of the shortcode [send_badges], which allows a teacher to send a badge to a student from a website page/post (without going to the back end);
* Verify if issuer information are set or not, if not it is impossible to send a certification email;
* No pop-up message for the admin when he sends a badge to students;
* Json Files saved in "wp-content/uploads" now;
* Creation of the user role "Badges Editor" (Wordpress editor + same permission as a "Teacher" or "Academy");
* Only a member with the user role "Administrator", "Teacher" or "Academy" can see the section "Get the certification" on a Teacher Level Page;
* Modification of the certification email footer (info about the firm).
* Custom metabox for the custom post "badge" : a link associated to a language to have more information about a certification level can be added to the custom post.

1.0.1
* Creation of the user roles "Teacher", "Student" and "Academy";
* By default, a teacher and an academy can send a certification by email to a student;
* Only the administrator can create badges (custom post) and custom taxonomies (like "Student level" for example);
* User's capabilities can be changed thanks to "Members" plugin.

1.0.0
* CHANGELOG : CORRECTION OF THE VERSIONING
* A student can get a self-certification page on the badge page;
* An administrator or a teacher (user role : author) can send a badge a badge to one student to award him.

0.1.5
* Submenu 'Send badges to students' is accessible for super-admin, admin, editor and author.
* Teachers can receive self-certification badges (T1 to T6).

0.1.0
* Get Mozilla OpenBadges Certification available;
* Certification Page implemented.
* Administrator can send an email to student from admin panel. There is no gestion of user roles for the moment.
* More information in the JSON Files.

0.0.5
* Sending an email to receive the certification (email ok, but certification page not implemented yet).
* Custom Post with his information can be displayed on the user's ordPress website;
* 1 Custom Post per page;
* Translation possible to other language.

0.0.1
* Initial version;
* Creation of the Custom Type 'Badge School';
* Creation of the categories : 'Teacher Levels', 'Student Levels', 'Skills' and 'Tags'.
* Creation of the submenu 'CSV File Upload' which allowed a user to import a .csv file into a database table and to export a db table into a .csv file;



## TO DO LIST

* Send a badge to more than 1 student;
* Option menu : allow a user, when he deletes the plugin, to choose if he keeps or not the database tables;
* Create a PHP Object for the badge, which will simplify the signature of the function of creation of the JSON file (currently it's functionnable, but with a php object it will be clearer);
* User documentation : Title custom post must the same as Level custom taxonomy ; User doesn't have to create a page called "Accept Bagde" unless this page has been deleted (the site has to have always 1 page called like that, not more, not less). The same for "User Profile" page.
* Front End User Profile : the version is not official, there are 2 Javascript codes to test. When one of this version will be officially elected, please put it in the right file and delete the other (see commented sections in "single-front_end_user_profile.php")

## Credits

Uses the [WordPress Plugin Boilerplate](http://wppb.io/).
