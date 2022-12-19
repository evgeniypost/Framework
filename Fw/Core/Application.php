<?php

namespace Fw\Core;

class Application
{
    private $pager;
    private $template = null;
    private const FOLDER_TEMPLATES = __DIR__ . "/../templates/";

    public function __construct()
    {
        $this->pager = Multiton::get(Page::class);
    }

    private function startBuffer()
    {
        ob_start();
    }

    private function endBuffer()
    {
        $contentPage = ob_get_contents();
        $replaces = $this->pager->getAllReplace();
        $contentPage = str_replace(array_keys($replaces), array_values($replaces), $contentPage);
        $this->restartBuffer();
        echo $contentPage;
    }

    public function restartBuffer()
    {
        ob_clean();
    }

    private function getTemplatePath()
    {
        $templatePath= Config::getValue("template");
        return $templatePath ?: "default";
    }

    public function header()
    {
        $this->startBuffer();
        require_once Application::FOLDER_TEMPLATES . $this->getTemplatePath() . "/header.php";
    }

    public function footer()
    {
        require_once Application::FOLDER_TEMPLATES . $this->getTemplatePath() . "/footer.php";
        $this->endBuffer();
    }

    public function getPager()
    {
        return $this->pager;
    }
}
