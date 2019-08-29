# async
Async is a PHP wrapper class to allow for easy AJAX functionality

Make a GET or POST request (with data), through a PHP class, and benefit from asynchronous processing of the request.

A request requires:
  1. a URI (target script that processes the request)
  2. optionally data (POST request).
 
## Usage

### POST request - example
Process.php is the target script that in a POST request receives data (key-value pairs), and performs some operation on the data. Process.php could return some result or a message.
```php
$uri = "https://www.yoursite.com/process.php";
$data = ['firstname' => 'joHn', 'lastname' => 'bLaCk'];
$req = (new Async())->request($uri, $data);
```


### GET request - example
In a GET request, process.php merely runs as a 'background process' (asynchronously), there is no data sent to process.php. Process.php might send a message back when the process is finished.
```php
$uri = "https://www.yoursite.com/process.php";
$req = (new Async())->request($uri);
```
