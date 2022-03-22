<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;

use App\Middleware\CompanyMiddleware;
use App\Middleware\ConvertGetRequestParams;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        $this->addPlugin('Muffin/Trash');

        $this->addPlugin('ADmad/Sequence');

        $this->addPlugin('Queue', ['routes' => true]);

        $this->addPlugin('Duplicatable');

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');

        if (!empty(env('SENTRY_DSN'))) {
            $this->addPlugin('Monitor', ['bootstrap' => true, 'routes' => true]);
        }

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        $this->addPlugin('Cors');
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))
            // Allows parsing JSON request bodies
            ->add(new BodyParserMiddleware())
            ->add(
                new AssetMiddleware(
                    [
                        'cacheTime' => Configure::read('Asset.cacheTime'),
                    ]
                )
            )
            ->add(new RoutingMiddleware($this, '_cake_routes_'))
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this, [
                'requireAuthorizationCheck' => false,
            ]))
            ->add(new CompanyMiddleware())
            ->add(new ConvertGetRequestParams());

        return $middlewareQueue;
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        $fields = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'email',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password',
        ];

        // Load identifiers
        $service->loadIdentifier('Authentication.Password', compact('fields'));

        $service->loadIdentifier('Authentication.JwtSubject', [
            'returnPayload' => false,
            'secretKey' => Configure::read('Security.cookieSalt'),
        ]);

        $service->loadAuthenticator('Authentication.Form', [
            'fields' => $fields,
            'loginUrl' => '/api/v1/users/login',
        ]);

        $service->loadAuthenticator('Authentication.Jwt', [
            'returnPayload' => false,
        ]);

        return $service;
    }

    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $ormResolver = new OrmResolver();

        return new AuthorizationService($ormResolver);
    }

    /**
     * Bootrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {

        try {
            $this->addPlugin('Bake');
            $this->addPlugin('Migrations');
            $this->addPlugin('IdeHelper');
            $this->addPlugin('Devin345458/Api');
            $this->addPlugin('IdeHelper');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }
    }
}
