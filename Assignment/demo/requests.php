<?php
/**
 * [apiCall description]
 * @param  [type] $APIKey      [description]
 * @param  [type] $url         [description]
 * @param  [type] $queryParams [description]
 * @return [type]              [description]
 */
function apiCall($APIKey, $url, $queryParams)
{
    $opts = array(
        'http' => array(
          'header'=>"Ocp-Apim-Subscription-Key: ${APIKey}"
        )
    );

    $context = stream_context_create($opts);

    return file_get_contents($query, false, $context);
}

/**
 * [ParallelGet description]
 */
class ParallelGet
{
    private $mh;
    private $urls;
    private $ch;

    /**
     * [__construct description]
     * @param [type] $urls   [description]
     * @param [type] $APIKey [description]
     */
    function __construct($urls, $APIKey)
    {
        // Create get requests for each URL
        $this->mh = curl_multi_init();
        $this->urls = $urls;
        $this->ch = array();
        $request_headers = array();
        $request_headers[] = "accept: application/json";
        $request_headers[] = "accept-encoding: gzip, deflate";
        $request_headers[] = "accept-language: en-US,en;q=0.8";
        $request_headers[] = "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36";
        $request_headers[] = "Ocp-Apim-Subscription-Key: ". $APIKey;

        foreach($urls as $i => $url)
        {
            $this->ch[$i] = curl_init($url);
            curl_setopt($this->ch[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->ch[$i], CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($this->ch[$i], CURLOPT_CAINFO, getcwd() . '\certs\ca-bundle.crt');
            curl_multi_add_handle($this->mh, $this->ch[$i]);

        }
    }
    /**
     * [execute description]
     * @return [type] [description]
     */
    function execute()
    {
        // Start performing the request
        do {
            $execReturnValue = curl_multi_exec($this->mh, $runningHandles);
        } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);

        // Loop and continue processing the request
        while ($runningHandles && $execReturnValue == CURLM_OK)
        {
            if (curl_multi_select($this->mh) != -1)
            {
                usleep(100);
            }

            do {
                $execReturnValue = curl_multi_exec($this->mh, $runningHandles);
            } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
        }

        // Check for any errors
        if ($execReturnValue != CURLM_OK)
        {
            trigger_error("Curl multi read error $execReturnValue\n", E_USER_WARNING);
        }
    }

    /**
     * [getResults description]
     * @return [type] [description]
     */
    function getResults()
    {
        // Extract the content
        foreach ($this->urls as $i => $url)
        {
            // Check for errors
            $curlError = curl_error($this->ch[$i]);

            if ($curlError == "")
            {
                $responseContent = curl_multi_getcontent($this->ch[$i]);
                $res[$i] = $responseContent;
            }
            else
            {
                return "Curl error on handle $i: $curlError\n";
            }
            // Remove and close the handle
            curl_multi_remove_handle($this->mh, $this->ch[$i]);
            curl_close($this->ch[$i]);
        }

        // Clean up the curl_multi handle
        curl_multi_close($this->mh);

        // Print the response data
        return $res;
    }
}
 ?>
