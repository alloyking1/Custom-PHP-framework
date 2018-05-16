<?php 
    /**
     * App Core Class
     * creats URL and Loads core controllers
     * URL Format - /controller/mathod/params
     * smile.. lol
     */
    class Core {
        //declear parameters
        protected $currentController = 'pages';
        protected $currentMethod = 'index';
        protected $params = [];

        //call to getUrl 
        public function __construct(){
            //print_r($this->getUrl());
            $url =  $this->getUrl();
            //look in controller if url exist
            //ucwords change first letter to upper case
            if(file_exists('../app/controllers/'. ucwords($url[0]).'.php')){
                //if exist, set as controller
                $this->currentController = ucwords($url[0]);
                //unset 0 index
                unset($url[0]);
            }

            //require the controller
            require_once '../app/controllers/'. $this->currentController. '.php';
            //instantiate the controller class
            $this->currentController = new $this->currentController;

            //check for second part of url
            if(isset($url[1])){
                //print_r($url[1]);
                //check to see if the method exist in controller
                if(method_exists($this->currentController, $url[1])){
                    //put in current method
                  $this->currentMethod = $url[1];
                  unset($url[1]);
                }
            }

            //get parms if any
            $this->params = $url ? array_values($url) : [];

            //call a callbck with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        }

        //creat method getUrl
        public function getUrl(){
            //if $_GET is true
            if(isset($_GET['url'])){
                //trim to remove '/'
                $url = rtrim($_GET['url'], '/');
                //filter variables to remove any character a url should not have  
                $url = filter_var($url, FILTER_SANITIZE_URL);
                //break into and array
                $url = explode('/', $url);
                //return array
                return $url;
            }
        }
    }

?>
