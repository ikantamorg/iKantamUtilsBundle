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

        $port = $secure ? $routerContext->getHttpsPort(): $routerContext->getHttpPort();

        if (!empty($port) && (($secure && $port != 443) || (!$secure && $port != 80))) {
            $absoluteUrl .= ':'.$port;
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
    public function filterJSONDecode($jsonString){
        return json_decode($jsonString);
    }

    /**
     * Setup FOS JsRoutingBundle config for current request
     *
     * @return string
     */
    public function fosJsRoutesOptions()
    {

        $request = $this->container->get('request');

        $options = [
            'e' => $request->getBaseUrl(), // base_url
            'scheme' => $request->getScheme(), //
            'host' => $request->getHost(), //
            'prefix' => $request->getLocale(), // locale -???
        ];


        $port = $request->getPort();

        if (!in_array($port, [80, 443])) {
            $options['host'] .= ':'.$port;
        }

        /*$confParameter = 'router.request_context.base_url';
        if ($this->container->hasParameter($confParameter)) {
            $default_options['e'] = $this->container->getParameter($confParameter);
        }*/

        $output = 'if (undefined != fos) {';
        $output .= '    fos.Router.j.b = '.json_encode($options);
        $output .= '}';

        return $output;
    }
}