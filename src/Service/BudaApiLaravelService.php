<?php
namespace Sdtech\LaravelBuda\Service;


class BudaApiLaravelService
{
    private $baseUrl = '';
    private $version = '';

    public function __construct()
    {
        $this->baseUrl = config('budaLaravel.BUDA_API_BASE_URL');
        $this->version = config('budaLaravel.BUDA_API_VERSION');
        $this->apiKey = config('budaLaravel.BUDA_API_KEY');
        $this->apiSecret = config('budaLaravel.BUDA_API_SECRET');
    }


    private function is_setup() {
        return (!empty($this->private_key) && !empty($this->public_key));
    }
    /**
     * call the coin payment api <br />
     * @param cmd The end point of api.
     * @param req The request of array.
     */
    private function api_call($cmd,$type, $req = array()) {

        $header = array();
        $header[] = 'Content-type: application/json; charset=utf-8';
        $header[] = 'Accept: application/json';

        // Generate the query string
        $post_data = json_encode($req);


        // Create cURL handle and initialize (if needed)
        $url = $this->baseUrl.$this->version.$cmd;

        $crl = curl_init($url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLINFO_HEADER_OUT, true);
        if($type == 'POST') {
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        }


        // Set HTTP Header for POST request
        curl_setopt($crl, CURLOPT_HTTPHEADER, $header
        );

        // Submit the POST request
        $data = curl_exec($crl);
//        dd($url,$data);
        if ($data !== FALSE) {
            if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
                $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
            } else {
                $dec = json_decode($data, TRUE);
            }
            if ($dec !== NULL && count($dec)) {
                return $dec;
            } else {
                // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
                return array('error' => 'Unable to parse JSON result ('.json_last_error().')');
            }
        } else {
            return array('error' => 'cURL error: '.curl_error($crl));
        }
    }

    /**
     * Gets the current market rate with market id
     * The ticker allows you to see the current state of a certain market.
     * The response to this call delivers the best buy and sell offers ( bidand ask), as well as the price of the last transaction ( last_price) for the requested market.
     * It also includes information such as daily volume and how much the price has changed in the last 24 hours.
     * @param marketId The ID of the market (Ex: btc-clp, eth-btc, etc).
     */
    public function getMarketData($marketId = null) {
        if(is_null($marketId)) {
            return $this->api_call('/markets','GET');
        } else {
            return $this->api_call('/markets/'.$marketId.'/ticker','GET');
        }
    }

    /**
     * Gets the current market volume with market id
     * This call allows access to information on the volume traded in a certain market,
     * where ask_volumeit represents the amount traded in sales orders and bid_volumecorresponds to the amount traded in purchase orders.
     * @param marketId The ID of the market (Ex: btc-clp, eth-btc, etc).
     */
    public function getMarketVolume($marketId) {
        return $this->api_call('/markets/'.$marketId.'/volume','GET');
    }

    /**
     * Get the list of all the orders that are active in the selected market.
     * @param marketId The ID of the market (Ex: btc-clp, eth-btc, etc).
     */
    public function getMarketOrderBook($marketId) {
        return $this->api_call('/markets/'.$marketId.'/order_book','GET');
    }

    /**
     * Obtain the list of the most recent transactions of the indicated market.
     * @param marketId The ID of the market (Ex: btc-clp, eth-btc, etc).
     */
    public function getMarketTrade($marketId,$limit=50) {
        return $this->api_call('/markets/'.$marketId.'/trades?limit='.$limit,'GET');
    }



}
