# Ratchet HTTP Server

A proof of concept web server made using [Ratchet](http://socketo.me/).

Ratchet was designed primarily to deal with WebSockets but contains an HTTP Server component. Using this
component I present a proof of concept web server which will accept requests, return an appropriate
response and then close the connection.

Usage
------
- Clone the repository:
`git clone https://github.com/andyexeter/ratchet-http-server`
- Install required dependencies: `composer install`
- Run the server: `php bin/server.php`
- View the home page in your browser: <http://localhost:8080> (This link will only work if you're using the default configuration in `app/config.php`)

License
--------
[GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)
