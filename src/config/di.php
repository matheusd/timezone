<?php

class WebAppResourceNotFoundException extends Exception { }
class WebAppRouteNotFoundException extends Exception { }

class WebAppDIProvider implements Pimple\ServiceProviderInterface
{
    public function register(Pimple\Container $c)
    {
        include(__DIR__.'/../local/config.php');
        foreach ($env as $envk => $envval) {
            $c["config/$envk"] = $envval;
        }

        //name of the entry inside config/databases that will be used
        $c['dbConfigName'] = 'default';
    
        $c['routes'] = [
            '/' => 'route/index', 
            '/user/new',
            '/user/login',
            '/user/logout',
            '/user/menus',
            '/user/profile',
            '/user/{id}' => 'route/editUser',
            '/users',
            '/timezones',
            '/timezones/fromUser/{id}' => 'route/userTimezones',
            '/timezones/{id}' => 'route/editTimezone',
        ];

        $c['entityManager'] = function ($c) {
            $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
                    array(__DIR__."/../modules/Orm"), $c['config/devVersion']);
            $config->addEntityNamespace("tt", "MDTimezone\\Orm");
            $conn = $c['config/databases'][$c['dbConfigName']];
            if (isset($c['pdo'])) {                
                $conn['pdo'] = $c['pdo'];
            }
            return Doctrine\ORM\EntityManager::create($conn, $config);            
        };
        
        $c['dispatcher'] = function ($c) {
            $routes = $c['routes'];
            $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($routes) {
                foreach ($routes as $k => $v) {
                    if (is_int($k)) {
                        $k = $v;
                        $v = "route$v";
                    }
                    $r->addRoute('*', $k, $v);
                }                
            });  
            return $dispatcher;
        };
        
        $c['request'] = function ($c) {
            $req = Zend\Diactoros\ServerRequestFactory::fromGlobals(
                $_SERVER,
                $_GET,
                $_POST,
                $_COOKIE,
                $_FILES
            );     
            $c['logger']->notice('Started ' . $req->getMethod() . ' ' . $req->getUri()->getPath());
            return $req;
        };
        
        $c['resource'] = function ($c) {
            $dispatcher = $c['dispatcher'];
            $request = $c['request'];
            $uri = $request->getUri();
            $path = $uri->getPath();            
            if (preg_match("|^(.+)\..+$|", $path, $matches)) {
                //if path ends in .json, .html, etc, ignore it
                $path = $matches[1];
            }            
            $res = $dispatcher->dispatch('*', $path);
            if ($res[0] == FastRoute\Dispatcher::NOT_FOUND) {
                throw new WebAppRouteNotFoundException("Route '$path' not found on routing table"); 
            }            
            
            $reqParameters = $res[2];
            $c['requestParameters'] = $reqParameters;
            
            $entry = $res[1];            
            
            if (!isset($c[$entry])) {
                throw new WebAppResourceNotFoundException("Resource '$entry' not found on DI container");
            }
            
            $res = $c[$entry];
            $c['logger']->notice("Resource Selected ($entry): " . get_class($res));            
            return $res;
        };
        
        $c['response'] = function ($c) {            
            try {
                $resource = $c['resource'];
                $response = $resource->exec();                
                return $response;
            } catch (Exception $e) {
                return $c['handleException']($e);
            }
        };
        
        $c['templaterFactory'] = function ($c) {
            $temp = new MDTimezone\templater\SampleTemplaterFactory();
            $temp->globalContext = [
                'url' => $c['config/publicUrl'],
                'assetsUrl' => $c['config/assetsUrl'],
            ];
            $temp->request = $c['request'];
            return $temp;
        };
        
        $c['responseFactory'] = function ($c) {
            $respFactory = new Resourceful\ResponseFactory();
            $respFactory->templaterFactory = $c['templaterFactory'];
            return $respFactory;
        };
        
        $c['responseEmitter'] = function ($c) {
            return new Zend\Diactoros\Response\SapiEmitter();
        };
        
        $c['session'] = function ($c) {
            $sess = new Resourceful\SessionStorage("ExampleApp");
            $sess->startSession();            
            $c['logger']->notice('Starting session ' . $sess->sessionId() .
                (isset($sess['userId']) ? (" userId " . $sess['userId']) : ''));
            return $sess;
        };
        
        $c['logger'] = function ($c) {            
            $handler = new Monolog\Handler\ErrorLogHandler(Monolog\Handler\ErrorLogHandler::SAPI, Monolog\Logger::NOTICE);
            $formatter = new Monolog\Formatter\LineFormatter();
            $formatter->includeStacktraces(true);
            $handler->setFormatter($formatter);
            $log = new Monolog\Logger('webapp');
            $log->pushHandler($handler);                        
            return $log;
        };
        
        $c['validator'] = function ($c) {
            $builder = Symfony\Component\Validator\Validation::createValidatorBuilder();
            $builder->addMethodMapping('loadValidatorMetadata');
            return $builder->getValidator();
        };
        
        $c['handleException'] = $c->protect(function ($e) use ($c) {
            $c['logger']->error($e);
            
            $exceptionBuilder = new \Resourceful\Exception\ExceptionResponseBuilder();
            //$exceptionBuilder = new \MDTimezone\Exceptions\Control\ExceptionResource();
            $exceptionBuilder->includeStackTrace = $c['config/devVersion'];
            $exceptionBuilder->responseFactory = $c['responseFactory'];
            
            $request = null;
            try {
                $request = $c['request'];
            } catch (Exception $e) {
                //ignore and just use a null request
            }            
                        
            $resp = $exceptionBuilder->buildResponse($e, $request);                        
            return $resp;

        });        
 

        $mkres = function ($cls, $props=[]) use ($c) {   
            return function ($c) use ($cls, $props) {
                $res = new $cls();
                $res->request = $c['request'];
                $res->parameters = $c['requestParameters'];
                $res->responseFactory = $c['responseFactory'];
                $res->session = $c['session'];                
                $res->auth = $c['model/auth'];
                foreach ($props as $k => $v) {
                    $res->$k = $c[$v];
                }
                return $res;
            };
        };
        
        $mkmodel = function ($cls, $props=[]) use ($c) {
            return function ($c) use ($cls, $props) {
                $res = new $cls();
                $res->entityManager = $c['entityManager'];
                $res->validator = $c['validator'];
                foreach ($props as $k => $v) {
                    $res->$k = $c[$v];
                }
                return $res;
            };        
        };
        
        /************************
         *     Models
         ************************/
         $c['model/users'] = $mkmodel('MDTimezone\User\Model\Users');
         $c['model/auth'] = $mkmodel('MDTimezone\User\Model\Auth', ['session' => 'session']);
         $c['model/timezones'] = $mkmodel('MDTimezone\Timezones\Model\Timezones', []);
       
        
        /************************
         *     Resources
         ************************/
        $c['route/index'] = $mkres('MDTimezone\Home\Control\IndexResource');
        
        $c['route/user/new'] = $mkres('MDTimezone\User\Control\RegisterUserResource', 
                ['validator' => 'validator', 'users' => 'model/users']);
        $c['route/user/login'] = $mkres('MDTimezone\User\Control\LoginResource', 
                ['users' => 'model/users']);
        $c['route/user/logout'] = $mkres('MDTimezone\User\Control\LogoutResource');
        $c['route/user/profile'] = $mkres('MDTimezone\User\Control\ProfileResource',
                ['users' => 'model/users']);
        $c['route/user/menus'] = $mkres('MDTimezone\User\Control\MenusResource');        
        $c['route/users'] = $mkres('MDTimezone\User\Control\UserListingResource',
                ['users' => 'model/users']);
        $c['route/editUser'] = $mkres('MDTimezone\User\Control\EditUserResource',
                ['users' => 'model/users']);
        
        $c['route/timezones'] = $mkres('MDTimezone\Timezones\Control\TimezoneListingResource',
                ['timezones' => 'model/timezones']);
        $c['route/userTimezones'] = $mkres('MDTimezone\Timezones\Control\UserTimezoneListingResource',
                ['timezones' => 'model/timezones', 'users' => 'model/users']);
        $c['route/editTimezone'] = $mkres('MDTimezone\Timezones\Control\EditTimezoneResource',
                ['timezones' => 'model/timezones']);

    }
}