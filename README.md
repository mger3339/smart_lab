# SmartLab

## Install instructions

Live instances of this applicagtion run on a Linux / Apache webserver environment, running PHP at a version higher than 5.3. It is highly recommended that local installs are run within the same environment. Your local PHP version must be higher than 5.3.

**Downloaded the framework to your local server take the following steps:**

1. Duplicate the following files: (leave these original ones intact)
`index.php-default`
`application/config/database.php-default`
`application/config/email_notification.php-default`
... and remove the '-default' from the filename so you have:
`index.php`
`application/config/database.php`
`application/config/email_notification.php`

2. In index.php make sure that `ENVIRONMENT` is set to `'development'` on line 56.

3. Set your database connection settings in `application/config/database.php`.

4. To set passwords easily for user accounts, PHP will need to be able to send emails using the reset password functionality. If the local PHP environemt does not have the means to do this, the application can be configured to use an SMTP server such as Google Mail (a google mail account will be needed) to send emails. To do this, in `application/config/email_notification.php`, set the `'email_notification_use_smtp'` to `TRUE` along with the other settings.

5. Make sure that the following directories have write permissions:
`_/cache`
`application/sessions`

6. Database manipulation is done via migrations, and migration files are found in `application/migrations`. Before running the framework you should set your local DB config settings and the run the migrate controller to set up your database. This is done via the command line:
`cd` to your local `index.php` file and then run
`php index.php migrate`
... and you should get a message saying that the schema changes have been made successfully.

7. The Super-admin (where 'clients' can be managed) and clients are distinguished and accessed via subdomains. This means that you will have to set up a subdomain for Super-admin, which will be accessed like this example:
`http://super-admin.smartlab.local`
On a local setup this normally involves modifying your local apache server http docs / setup.
Once this is set up locally you should be able to get to the Super-admin login page.

8. In Super-admin 'clients' will need to be set up. To get a client working, a local subdomain will also have to set up for the client - for example:
`http://my-client.smartlab.local`
... where `my-client` is the client slug.
Clients can also be set up to run under their own domain names, set in the 'Domain name' field. This means that a client can also be accessed locally by setting up a local domain such as `http://www.my-client.local`.
Once you a client is set up test users will need to be set up for the client, and set their passwords will need to be set using the process described above, albiet via the cliet's own login page. Note:
  - Different email addresses must be used for different users under a single client.
  - A Super-admin user can log into any client as a client admin user.
  - Simultaneous logins are not possible, so if a login is performed using an account that is already logged in, the already-logged-in account will be logged out.


