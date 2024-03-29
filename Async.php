<?php
/*
 * Created by Yellow Heroes
 * File: Async.php
 * Date: 05/04/2019
 *
 * class Async is a wrapper to make AJAX calls easy.
 * Make a GET request or POST request (with data), and benefit from
 * asynchronous processing of the request.
 */
namespace yellowheroes\async;

class Async
{
    public function __construct() {}

    /**
     * @param string $uri   : the php script that processes the request
     * @param array  $data  : data to be processed(optional) - if empty $_GET, otherwise $_POST
     */
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

        echo $asyncRequestHtml; // echo the AJAX script (i.e. run the async request)
    }
}

