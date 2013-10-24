<?php
/**
 * User: Dred
 * Date: 23.10.13
 * Time: 16:49
 */

namespace iKantam\Bundles\UtilsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFilter;

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
     * Provide filter that add absolute url before string
     *
     * @param $url
     *
     * @return string
     */
    public function filterAbsoluteUrl($url)
    {
        $request = $this->container->get('request');

        $base_absolute_url = $request->getSchemeAndHttpHost();

        return $base_absolute_url.$url;
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
}