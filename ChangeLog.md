# Changes Logs v1.2.2

## Tue, July 02 2024

- Added `asset()` function in `Functions.php` file, and can distinguish to start path from relative path or root path
- `RouteController.php` in `slides_include()` function.
- Removed `::view` and `::root` string and it's functions.
- Added `__ROOT__` constant
- Updated slides_include file to auto clone a file to a generated file.
- Can now use normal relative path in `slides_include()` function.
- Improved in Route Map function, can now use `name()` method in any position.
- Updated Request URL to match both uppercase and lowercase
- Renamed `/routes/route.php` to `/routes/web.php`
- Added named route function to normal route method `POST`, `GET`, `PATCH`, `PUT`, `DELETE`, `VIEW`.

## Tue, July 09 2024

- Change all file names to CamelCase
- Added configuration for Console
- Added Console template for Controller, ApiController, Middleware.

## Thursday, July 11 2024

- Added Authorization method for getting Basic and Bearer token.

## Saturday, July 13 2024

- Completed API Controller function.
- Completed middleware function.

## Tuesday, July 16 2024

- Worked on controller and Middleware
- Added mapping method in Api controller
- Api controller now accepts naming route in mapping method.

## Friday, July 19 2024

- Make API `define()` method now working with `route()` method
- Work on `route()` so when using `define()` with `route()` they can pass second parameter in `route()` as controller method for the defined route
- Added more methods to `Request` class
- Added documentation to each methods in Request class and interface with Api class and interface
- Added more version methods manually.
