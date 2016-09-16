# MD Timezone

A Web Application for managing Timezones.

This WebApp allows end users to manage a list of timezones they are interested in, 
and automatically see the current time on all of their timezones.

There are three levels of permission:

- Users can see only their own timezones
- Manager can see and manage users (but not user data)
- Admins can manage both Users and Managers **and** user's data
  
  
## Project Setup

This project uses PHP's composer package manager for code dependencies. After
downlading the project (via git or tarball), it can be setup by running the
following command:

`composer install`
  
This project has been developed and tested against PHP 7.0. It may need a few
corrections for running on older or newer versions of PHP.
  
## Configuration

Local configuration for the project is kept on the "src/local" folder. This 
folder is **not** kept in version control. 

The main config file is called "config.php" and sets up the running environment
for the project. A sample file with explanation for the entries is available
at the "src/config" directory.

## Database Setup

The database can be setup manually, by running the scripts inside the "db" 
folder, or it can be setup using the orm schema tool for doctrine, by using the
following command (at the root of the project tree):

`vendor/bin/doctrine orm:schema-tool:create`
  
The scripts inside the "db" directory are meant for use with PostgreSQL.  

A test database (for running the unit tests) can be setup by the same procedure
(either manually or by running the following command at the root of the test
tree - the "testing" folder):

`../vendor/bin/doctrine orm:schema-tool:create`

## Unit Testing

Unit and integration testing (in this particular project, "integration" means
testing the whole resource's call stack, including database access, except for 
the HTTP request phase of the processing) can be run by acessing the root test
directory and running:

`../vendor/bin/phpunit --bootstrap ../src/bootstrap.php Tests`
  
## Development Webserver

For local testing, PHP's embedded webserver may be used. To run it, access the
"src" directory for the project and run:

`php -S 0.0.0.0:8039 -t $PWD docroot/front.php`
  
## Production Virtual Host

To host a production environment, setup a new virtual host on your webserver and
configure a PHP/FPM front controller such that all requests are redirected to 
the file "docroot\front.php".

The "/assets/*" path may be put on a different vhost or use a rule to directly
redirect all requests to the "src/assets" folder of the project (such that 
static files don't have to go through PHP's front controller).

See an example of an nginx vhost configuration in the "docs" directory.
  
## Code Architecture

The general code architecture for the project is as follows:

The entry point of every request is the front controller script. It includes
the autoloader (bootstrap.php) and uses a dependency injection container to
obtain the response to the request and a response emitter, that will effectively
generate the body of the response.

All dependencies of the project are tracked by the DI container (file 
"config/di.php"). 

Every request to the webserver is routed to a resource (identified by an entry
in the DI container) based on its path.

The resource determines whether it can respond to the given HTTP method the web 
client is trying to emit to the resource. The resource then either throws an
exception (which will be converted to an HTTP error code) or responds with 
a response representation (either a JSON or HTML representation of the 
resource). 


## Sample API Client

A sample API client written in native PHP code (using the curl extension) can
be used to access the RESTful API.

It uses the same resources as a web client, but only sends/receives JSON 
objects.

Authentication is done by a session cookie which is saved between calls to the
client API.

Currently, only the login and self timezone listing are implemented (as a 
proof-of-concept) but other API calls can be easily written.

To run the sample API client, create a copy of "config.inc.sample" as 
"config.inc.local" on the "demoApiClient" folder, replacing the necessary config
variables and run:

`php login.php`
`php timezoneClient.php`
  
## Contact

This project was initially done by Matheus Degiovani. Reachable at:

md-timezone@matheusd.com

