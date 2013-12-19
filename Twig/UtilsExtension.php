<?php
/**
 * User: Dred
 * Date: 23.10.13
 * Time: 16:49
 */

namespace iKantam\UtilsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class UtilsExtension extends Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ikantam_utils';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            'absolute_url' => new Twig_SimpleFilter('absolute_url', [$this,'filterAbsoluteUrl']),
            'json_decode' => new Twig_SimpleFilter('json_decode', [$this,'filterJSONDecode']),
        ];
    }

    /**
     * {@inherited}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'fos_js_routes_options',
                [
                    $this,
                    'fosJsRoutesOptions'
                ],
                [
                    'is_safe' => [
                        'html'
                    ]
                ]
            ),
        ];
    }

    /**
     * Provide filter that add absolute url before string
     *
     * @param $url
     *
     * @return string
     */
    public function filterAbsoluteUrl($url)
    {
        $request = $this->container->get('request');
        $routerContext = $this->container->get('router')->getContext();

        $secure = $request->isSecure();

        $absoluteUrl = $routerContext->getScheme().'://'.$routerContext->getHost();

        if ($secure && !empty($routerContext->getHttpsPort()) && $routerContext->getHttpsPort() != 443) {
            $absoluteUrl .= ':'.$routerContext->getHttpsPort();
        } elseif (!$secure && !empty($routerContext->getHttpPort()) && $routerContext->getHttpPort() != 80) {
            $absoluteUrl .= ':'.$routerContext->getHttpPort();
        }

        return $absoluteUrl.$url;
    }

    /**
     * Implement json_decode to twig
     *
     * @param $json_string
     *
     * @return mixed
     */
    public function filterJSONDecode($json_string){
        return json_decode($json_string);
    }

    /**
     * Setup FOS JsRoutingBundle config for current request
     *
     * @return string
     */
    public function fosJsRoutesOptions()
    {
        $default_options = [
            'e' => '', // base_url
            'scheme' => '', //
            'host' => '', //
            'prefix' => '', // locale -???
        ];

        $confParameter = 'router.request_context.base_url';

        if ($this->container->hasParameter($confParameter)) {
            $default_options['e'] = $this->container->getParameter($confParameter);
        }

        $request = $this->container->get('request');

        if (empty($default_options['e'])) {
            $default_options['e'] = $request->getBaseUrl();
        }

        $default_options['scheme'] = $request->getScheme();

        $default_options['host'] = $request->getHost();

        $port = $request->getPort();

        if (!in_array($port, [80, 443])) {
            $default_options['host'] .= ':'.$port;
        }

        $default_options['prefix'] = $request->getLocale();

        $output = 'if (undefined != fos) {';
        $output .= '    fos.Router.j.b = '.json_encode($default_options);
        $output .= '}';

        return $output;
    }
}