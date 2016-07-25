=== Badges4Languages Plugin ===
Contributors: Alexandre Levacher
Donate link: http://www.badges4languages.com
Tags: badges4languages, languages, clases, learn, badge, certification
Requires at least: 3.0.1
Tested up to: 4.5.3
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows to a user to get a student or teacher certification by himself or to receive it thanks to someone who has given him.

== Description ==

A student or a teacher can obtain a badge (Mozilla OpenBadges) by himself.
Then, a teacher can confirm the student level by giving him a certification too.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `badges4languages-plugin.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why have I got error when I want to import data from a CSV file into the Database ? =

This is a bug from a plugin we use: it could sometimes give you an error but the data are well imported into the Database.
Please read the user guide for more information.



== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.1.0 =
* Creation of the shortcode [send_badges], which allows a teacher to send a badge to a student from a website page/post (without going to the back end);
* Verify if issuer information are set or not, if not it is impossible to send a certification email;
* No pop-up message for the admin when he sends a badge to students;
* Json Files saved in "wp-content/uploads" now;
* Creation of the user role "Badges Editor" (Wordpress editor + same permission as a "Teacher" or "Academy");
* Only a member with the user role "Administrator", "Teacher" or "Academy" can see the section "Get the certification" on a Teacher Level Page;
* Modification of the certification email footer (info about the firm).
* Custom metabox for the custom post "badge" : a link associated to a language to have more information about a certification level can be added to the custom post.

= 1.0.1 =
* Creation of the user roles "Teacher", "Student" and "Academy";
* By default, a teacher and an academy can send a certification by email to a student;
* Only the administrator can create badges (custom post) and custom taxonomies (like "Student level" for example);
* User's capabilities can be changed thanks to "Members" plugin.

= 1.0 =
* A student can get a self-certification page on the badge page;
* An administrator or a teacher (user role : author) can send a badge a badge to one student to award him.

= 0.1.5 =
* Submenu 'Send badges to students' is accessible for super-admin, admin, editor and author.
* Teachers can receive self-certification badges (T1 to T6).

= 0.1.0 =
* Get Mozilla OpenBadges Certification available;
* Certification Page implemented.
* Administrator can send an email to student from admin panel. There is no gestion of user roles for the moment.
* More information in the JSON Files.

= 0.0.5 =
* Sending an email to receive the certification (email ok, but certification page not implemented yet).
* Custom Post with his information can be displayed on the user's ordPress website;
* 1 Custom Post per page;
* Translation possible to other language.

= 0.0.1 =
* Initial version;
* Creation of the Custom Type 'Badge School';
* Creation of the categories : 'Teacher Levels', 'Student Levels', 'Skills' and 'Tags'.
* Creation of the submenu 'CSV File Upload' which allowed a user to import a .csv file into a database table and to export a db table into a .csv file;


== Upgrade Notice ==

None