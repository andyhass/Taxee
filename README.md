# Taxee

THIS REPOSITORY IS DEPRECATED. 

Taxee statistics are now maintained here: https://github.com/taxee/taxee-tax-statistics

----------

#### Your friendly neighborhod income tax data and calculation API.

This repository contains the source code for the Taxee REST API.  The hosted version of this API can be found on [taxee.io][1], along with documentation on the API endpoints.

This API is written in PHP (PHP5) and utilizes the [Slim Framework][2].  The data that drives Taxee (the tax data) is contained in static JSON files.  The GET requests detailed on [taxee.io][3] simply serve this static data.  The PUT request (the calculation endpoint) consumes some of this static JSON data, and then performs a few minor calculations using it.

## Usage

 1. [Install][4] the `composer` PHP dependency management tool.
 2. Clone/download this repository
 3. Run ``` php composer.phar install``` in the root of this repository to install the PHP dependencies.
 4. Start up a PHP server in the repository directory (Ex. ```php -S localhost:8000```)
 5. If everything went well, you should be able to load up 2014's federal tax data in your browser by going to http://localhost:8000/v1/federal/2014

## License

MIT

  [1]: http://taxee.io
  [2]: http://www.slimframework.com/
  [3]: http://taxee.io
  [4]: https://getcomposer.org/doc/00-intro
