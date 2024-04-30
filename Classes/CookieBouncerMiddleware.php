<?php

declare(strict_types=1);

namespace Sitegeist\CookieBouncer;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Neos\FLow\Annotations as Flow;

class CookieBouncerMiddleware implements MiddlewareInterface
{

    /**
     * @var string
     * @Flow\InjectConfiguration(package="Neos.Flow", path="session.name")
     */
    protected string $sessionCookieName;

    /**
     * @var string[]
     * @Flow\InjectConfiguration(path="allowPatterns")
     */
    protected array $allowPatterns;

    /**
     * @var string[]
     * @Flow\InjectConfiguration(path="denyPatterns")
     */
    protected array $denyPatterns;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookiesParams = $request->getCookieParams();
        $cookiesParamNames = array_keys($cookiesParams);

        $requireCookieReset = false;
        foreach ($cookiesParamNames as $cookiesParamName) {
            // session cookie must be allowed
            if ($cookiesParamName == $this->sessionCookieName) {
                continue;
            }

            // other cookies may be allowed by pattern
            $isAllowed = false;
            foreach ($this->allowPatterns as $allowPattern) {
                if (fnmatch($allowPattern, $cookiesParamName)) {
                    $isAllowed = true;
                    break;
                }
            }
            if ($isAllowed === true) {
                continue;
            }

            // if not they may be forbidden which allows removal
            foreach ($this->denyPatterns as $denyPattern) {
                if (fnmatch($denyPattern, $cookiesParamName)) {
                    unset($cookiesParams[$cookiesParamName]);
                    $requireCookieReset = true;
                    break;
                }
            }
        }

        if ($requireCookieReset === true) {
            $request = $request->withCookieParams($cookiesParams);
        }

        return $handler->handle($request);
    }
}
