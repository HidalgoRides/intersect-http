<?php

namespace Intersect\Http\Response\Handlers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Intersect\Http\Response\Response;
use Intersect\Http\Response\TwigResponse;
use Intersect\Http\Response\Handlers\ResponseHandler;

/**
 * composer dependencies required
 * "twig/twig": "^2.0",
 */
class TwigResponseHandler implements ResponseHandler {

    private $configs;
    private $templatesPath;

    public function __construct($templatesPath, $configs = [])
    {
        $this->templatesPath = $templatesPath;
        $this->configs = $configs;
    }

    public function canHandle(Response $response)
    {
        return ($response instanceof TwigResponse);
    }

    /**
     * @param TwigResponse $response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(Response $response)
    {
        $loader = new FilesystemLoader($this->templatesPath);

        $options = (array_key_exists('options', $this->configs) ? $this->configs['options'] : []);
        $extensions = (array_key_exists('extensions', $this->configs) ? $this->configs['extensions'] : []);

        $twig = new Environment($loader, $options);

        foreach ($extensions as $extension)
        {
            $extensionInstace = new $extension();
            if ($extensionInstace instanceof \Twig_ExtensionInterface)
            {
                $twig->addExtension($extensionInstace);
            }
        }

        echo $twig->render($response->getTemplateFile(), $response->getData());
    }

}