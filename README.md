# Task
I have not added the authontication token here since when I apply it to my application it always redirects me to 
 $this->Flash->error('Invalid username or password');
 
 
The steps that I followed to add the authentication are:
 1) adding the authentication blugin using composer.
 2) adding authentication in View/Application.php
 
 
   //authentication
  use Authentication\AuthenticationService;
  use Authentication\AuthenticationServiceInterface;
  use Authentication\AuthenticationServiceProviderInterface;
  use Authentication\Identifier\IdentifierInterface;
  use Authentication\Middleware\AuthenticationMiddleware;
  use Cake\Routing\Router;
  use Psr\Http\Message\ServerRequestInterface;

// making my application implements Authentication
 class Application extends BaseApplication implements AuthenticationServiceProviderInterface{
   public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();
        $this->addPlugin('Authentication');
    }
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();
    
        // Define where users should be redirected to when they are not authenticated
        $service->setConfig([
            'unauthenticatedRedirect' => Router::url([
                    'prefix' => false,
                    'plugin' => null,
                    'controller' => 'Users',
                    'action' => 'login',
            ]),
            'queryParam' => 'redirect',
        ]);
    
        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',

        ];
        // Load the authenticators. Session should be first.
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => Router::url([
                'prefix' => false,
                'plugin' => null,
                'controller' => 'Users',
                'action' => 'login',
            ]),
        ]);
    
        // Load identifiers
        $service->loadIdentifier('Authentication.Password', compact('fields'));
    
        return $service;
    }
    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
    
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))

            ->add(new BodyParserMiddleware())
            ->add(new AuthenticationMiddleware($this))
            
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }
    }
    
 3) adding a login method in UsersController.php


     public function login()
    {
        $this->request->allowMethod(['get', 'post']);
    $result = $this->Authentication->getResult();
       
            if ($result->isValid()) {
                $target = $this->Authentication->getLoginRedirect() ?? '/home';
                return $this->redirect($target);
            } elseif ($this->request->is('post') && !$result->isValid()) {
                $this->Flash->error('Invalid username or password');
            }
                   
    }
    
4) adding the authontication combonant in AppController.php


 
   public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
    }
     public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login']);
    }
    
5) adding a Template for login
    
    <div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
        <?= $this->Form->control('password_Conformation',['type'=>'password']) ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
