=== Plugin Name ===
Contributors: brasadesign, matheusgimenez
Donate link: http://brasa.art.br
Tags: git, github, theme sync, github deploy, deploy, deploy github
Requires at least: 4.5
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Deploy our theme hosted on GitHub automatically whenever you have a commit.

== Description ==

Deploy our theme hosted on GitHub automatically whenever you have a commit on a specific branch. Simple, easy, free.

How to use:

To use the plugin download it by the WordPress panel or upload via FTP in the wp-content folder /plugins . Then activate it.

When the plugin is enabled check make sure that the theme is ready in the wp-content folder /themes . The name of the theme folder must match the address on GitHub. Example:

Link GitHub: http://github.com/wpbrasil/odin/ in this case, the folder name would be /odin.

Once verified these steps, go to your repository on GitHub, then enter the Settings page of the repository within that page, click the link Webhooks & services.

Within this page, click Add webhook. A password confirmation box will open, confirm and a form will appear:

In Payload URL complete with:
http://SITE_ADDRESS/wp-admin/admin-ajax.php?action=brasa_deploy

In Content-type, leave the default selection (application/json).

In the field Secret, use a strong password, consider using a password generator.

Mark webhook to Active and save. Now we can return to the WordPress dashboard.

In the WordPress dashboard, go to the Tools menu -> Brasa Theme Deploy.

On this page, fill Secret with the password you used earlier and also the branch you want to synchronize.

Save and the next time you give a push to that branch, your site will be updated automatically.

== Installation ==

To use the plugin download it by the WordPress panel or upload via FTP in the wp-content folder /plugins . Then activate it.

When the plugin is enabled check make sure that the theme is ready in the wp-content folder /themes . The name of the theme folder must match the address on GitHub. Example:

Link GitHub: http://github.com/wpbrasil/odin/ in this case, the folder name would be /odin.

Once verified these steps, go to your repository on GitHub, then enter the Settings page of the repository within that page, click the link Webhooks & services.

Within this page, click Add webhook. A password confirmation box will open, confirm and a form will appear:

In Payload URL complete with:
http://SITE_ADDRESS/wp-admin/admin-ajax.php?action=brasa_deploy

In Content-type, leave the default selection (application/json).

In the field Secret, use a strong password, consider using a password generator.

Mark webhook to Active and save. Now we can return to the WordPress dashboard.

In the WordPress dashboard, go to the Tools menu -> Brasa Theme Deploy.

On this page, fill Secret with the password you used earlier and also the branch you want to synchronize.

Save and the next time you give a push to that branch, your site will be updated automatically.

== Frequently Asked Questions ==

= Works with GitLab or another Git repository? =

No. Only on GitHub

== Changelog ==

= 0.1 =
First version
