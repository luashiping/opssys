<?php
class DockerModel{
    private static $docker = null;
    private static $manager = null;
    private static $Imagemanager = null;
    
    public function __construct(){
        $swarm_config = C('SWARM_CONFIG');
        $host = implode(':',$swarm_config);
        $client = new Docker\Http\DockerClient(array(),$host);
        self::$docker = new Docker\Docker($client);
        self::$manager = self::$docker->getContainerManager();
        self::$Imagemanager = self::$docker->getImageManager();
        
    }
    
    //start container
    public function start($id) {
        $container = self::$manager->find($id);
        try {
            self::$manager->start($container);
        } catch (Exception $e) {
            return $e->getMessage();   
        }     
    }
    
    //stop container
    public function stop($id) {
        $container = self::$manager->find($id);
        try {
            self::$manager->stop($container,5);
        } catch (Exception $e) {
            return $e->getMessage();   
        }    
    }
    
    //remove container
    public function remove($ids) {
        foreach($ids as $id) {
            $container = self::$manager->find($id);
            try {
                self::$manager->remove($container,$volumes,true);
            } catch (Exception $e) {
                return $e->getMessage();   
            }   
        } 
    }
    //list container
    public function lists() {
        $containers = array();
        $params['all'] = 1; 
        foreach (self::$manager->findAll($params) as $container) {
            $container_single = $container->getData();
            if(preg_match('/^Up/',$container_single['Status'])){
                $container_single['Zt'] = 1;
            }
            $containers[] = $container_single;
        }
        return $containers;
    }
    
    //return container info
    public function info($id) {
        $container = self::$manager->find($id);
        return $container->getRuntimeInformations();
    }

   
    //get image id
    public function FetchImageid($image_name,$tag) {
        $image = self::$Imagemanager->find($image_name,$tag);
        return $image->getId();
    }

          
    public function exec($containerName) {
        $container = self::$manager->find($containerName);
        $execid = $manager->exec($container, ["/bin/bash", "-c", "ls /var/www/html"]);
        $response = $manager->execstart($execid);

        print_r("Result= <" . $response->getBody()->__toString() . ">\n");

    }
  
  
  
}


?>