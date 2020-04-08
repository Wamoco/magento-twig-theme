<?php
namespace Wamoco\TwigTheme\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class: ScrapeCommand
 *
 * php bin/magento wamoco:theme:scrape
 *
 * @see Command
 */
class ScrapeCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('wamoco:theme:scrape');
        $this->setDescription('tbd');
        $this->addOption(
            "file",
            null,
            InputOption::VALUE_REQUIRED,
            'import file'
        );


        /* $this->baseUrl = "meyer-fachgrosshandel.de"; */
        /* $this->webPath = "/var/www/html/Source/src/Wamoco/theme-meyer/Wamoco_TwigTheme/web"; */
        /* $this->templateFile = "/var/www/html/Source/src/Wamoco/theme-meyer/Wamoco_TwigTheme/web/templates/base.html.twig"; */
        /* $this->shouldDownload = false; */
        /* $this->shouldReplace = false; */
        $this->baseUrl = "https://drinkfju.de";
        $this->webPath = "/var/www/html/Source/src/Wamoco/theme-drinkfju/Wamoco_TwigTheme/web";
        $this->templateFile = "/var/www/html/Source/src/Wamoco/theme-drinkfju/Wamoco_TwigTheme/web/templates/pages/custom/gallery.html.twig";
        $this->shouldDownload = true;
        $this->shouldReplace = true;
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->templateFile)) {
            $output->writeln('<error>file not found</error>');
            return;
        }

        $content = file_get_contents($this->templateFile);
        $result = [];
        preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $content, $result, PREG_PATTERN_ORDER);
        $result = $result[0];

        foreach ($result as $url) {
            if (strpos($url, $this->baseUrl) !== false) {
                $url = $this->cleanupUrl($url);

                if ($this->shouldReplace) {
                    $replacement = $this->getReplacement($url);
                    echo "[REPLACE] " . $url . " -> " . $replacement . "\n";

                    $content = str_replace($url, $replacement, $content);
                }

                if ($this->shouldDownload && $this->isDownloadableAsset($url)) {
                    echo "[DOWNLOAD] " . $url . "\n";
                    $this->downloadAsset($url);
                }
            }
        }

        if ($this->shouldReplace) {
            file_put_contents($this->templateFile, $content);
        }
    }

    protected function cleanupUrl($url)
    {
        if (strpos($url, "?") !== false) {
            return substr($url, 0, strpos($url, "?"));
        }
        return $url;
    }

    protected function removeBaseUrl($url)
    {
        $pos = strpos($url, $this->baseUrl);
        return substr($url, $pos+strlen($this->baseUrl));
    }

    protected function getReplacement($url)
    {
        $functionName = $this->isDownloadableAsset($url) ? "getAssetUrl" : "getUrl";
        $newUrl = $this->removeBaseUrl($url);
        $newUrl = strlen($newUrl) == 0 ? '/' : $newUrl;
        return sprintf("{{%s(\"%s\")}}", $functionName, $newUrl);
    }

    protected function isDownloadableAsset($url)
    {
        $extensions = ["js", "css", "jpeg", "jpg", "png"];
        foreach ($extensions as $ext) {
            if (strpos($url, "." . $ext) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function downloadAsset($url)
    {
        $chdir = "cd $this->webPath";
        $dirname = dirname($this->removeBaseUrl($url));

        if (substr($dirname, 0, 1) == '/') {
            $dirname = substr($dirname, 1);
        }
        $filename = basename($this->removeBaseUrl($url));


        $commands = [
            "mkdir -p $dirname",
            "wget $url",
            "mv $filename $dirname"
        ];

        foreach ($commands as $cmd) {
            $cmd = sprintf("%s && %s", $chdir, $cmd);
            shell_exec($cmd);
        }
    }
}
