<?php
namespace Wamoco\TwigTheme\Engine\Twig\Result;
use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;

class Page extends \Magento\Framework\Controller\AbstractResult
{
    /**
     * @var string
     */
    protected $template = null;

    /**
     * @var \Wamoco\TwigTheme\Engine\Twig
     */
    protected $twig;

    /**
     * @var \Wamoco\TwigTheme\Engine\Twig\Context
     */
    protected $templateContext;

    /**
     * __construct
     *
     * @param \Wamoco\TwigTheme\Engine\Twig $twig
     * @param \Wamoco\TwigTheme\Engine\View\ContextProvider $templateContext
     */
    public function __construct (
        \Wamoco\TwigTheme\Engine\Twig $twig,
        \Wamoco\TwigTheme\View\ContextProvider $templateContext
    ) {
        $this->twig = $twig;
        $this->templateContext = $templateContext;
    }

    /**
     * renderToString
     * @return string
     */
    public function renderToString()
    {
        if (!$this->template) {
            return "";
        }
        return $this->twig->render(
            $this->template,
            $this->templateContext->getAll()
        );
    }

    /**
     * setTemplate
     *
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * templateExists
     * @return bool
     */
    public function templateExists()
    {
        return $this->twig->templateExists($this->template);
    }

    /**
     * render
     *
     * @param HttpResponseInterface $response
     * @return $this
     */
    protected function render(HttpResponseInterface $response)
    {
        $response->setContent($this->renderToString());
        return $this;
    }
}
