<?php
/**
 * Created by Yellow Heroes
 * File: Async.php
 * Date: 05/04/2019
 *
 * class Async is a wrapper to make AJAX calls easy.
 * Make a GET request or POST request (with data), and benefit from
 * asynchronous processing of the request.
 * The call requires:
 * 1. a URI (target script that processes the request)
 * 2. optionally data (POST request).
 *
 * example usage:
 * process.php is the target script that in a POST request,
 * receives data (key-value pairs) and returns processed data.
 *
 * In a GET request, process.php merely runs as a background process
 * asynchronously, there is no data received, only a message sent back
 * when the process is finished.
 *
 * POST data
 * $uri = "https://www.yoursite.com/process.php";
 * $data = ['firstname' => 'joHn', 'lastname' => 'bLaCk'];
 * $req = (new Async())->request($uri, $data);
 *
 *
 * GET data
 * $uri = "https://www.yoursite.com/process.php";
 * $req = (new Async())->request($uri);
 */

$uri = "http://localhost/z_other/scratchpad/co-routines/index.php";
$req = (new Async())->request($uri);

class Async
{
    public function __construct() {}

    public function request($uri = '', $data = []): void
    {
        /* POST - we use formData object to send data to target */
        $formDataScript = '';
        if (!empty($data)) {
            $method = 'POST'; // we force POST method when sending data
            $formDataScript = <<<HEREDOC
var formData = new FormData(); \r\n
HEREDOC;
            foreach ($data as $key => $value) {
                $formDataScript .= <<<HEREDOC
formData.append("$key", "$value"); \r\n
HEREDOC;
            }
        } else {
            $method = 'GET'; // when not sending data we use GET method
            $formDataScript = <<<HEREDOC
var formData = null; // set to null, we're not sending data
HEREDOC;
        }

        /*
         * the ajax call with "load" event-listener,
         * (which we baptised responseListener)
         *
         * the load event is fired when the whole page has loaded,
         * including all dependent resources
         */
        $asyncRequestHtml = <<<HEREDOC
<script>
  function responseListener() {
     console.log(this.responseText);
 }

 var oReq = new XMLHttpRequest(); // instantiate xhr object
 oReq.addEventListener("load", responseListener); // invoke function 'responseListener' when response is received
 oReq.open("$method", "$uri"); // prepare the AJAX request (method, uri) \r\n
 $formDataScript
 /* formData == null in case we're not sending data */
 oReq.send(formData); // launch the AJAX request, possibly with POST data
</script>\r\n\r\n
HEREDOC;

        echo $asyncRequestHtml; // echo the AJAX script
    }
}

