<?php
class RegistryModel{
    private $_version = 'v2';
    private $_timeout = 30;

    protected $endpoint;

    public function __construct(){
        $registry_config = C('REGISTRY_CONFIG');
        $host = $registry_config['host'];
        $port = $registry_config['port'];
        $this->endpoint = $host.":".$port;
    }

    //获取镜像列表
    public function getList($path = '_catalog'){
        $rep = $this->_do_request('GET', $path);
        if ($rep) {
            $rep = json_decode($rep,true);
            return $rep;
        } else {
            return false;
        }

    }

    public function _do_request($method, $path, $headers = NULL, $body= NULL) {
        $uri = $this->endpoint."/{$this->_version}/{$path}";
        $ch = curl_init("http://{$uri}");
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method == 'PUT' || $method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else {
            curl_setopt($ch, CURLOPT_POST, 0);
        }

         if ($method == 'GET') {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }

        if ($method == 'HEAD') {
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $header_string = '';
        $body = '';

        if ($method == 'GET') {
            $header_string = '';
            $body = $response;
        }
        else {
            list($header_string, $body) = explode("\r\n\r\n", $response, 2);
        }

        if ($http_code == 200) {
             if ($method == 'GET') {
                return $body;
            }
            else {
                //$data = $this->_getHeadersData($header_string);
                //return count($data) > 0 ? $data : true;
            }
        } else {
            return false;

        }
    }



}













?>