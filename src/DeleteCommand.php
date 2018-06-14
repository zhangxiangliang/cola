<?php

namespace NewOne\Cola\Console;

use GuzzleHttp\Client;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DeleteCommand extends Command
{
    /**
     * 添加命令描述
     */
    protected function configure()
    {
        $this
            ->setName('template:delete')
            ->setDescription('删除 github 项目模板')
            ->addArgument('template', InputArgument::REQUIRED, '模板名, 例如 cola');
    }

    /**
     * 执行命令
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = $input->getArgument('template');

        $templates = templates();
        unset($templates[$template]);

        $this->writeConfigureFile($templates);
        $output->writeln('<comment>模板删除成功！</comment>');
    }

    /**
     * 获取配置文件名
     */
    protected function ConfigureFileName()
    {
        return getenv("HOME") . "/.cola";
    }

    /**
     * 添加新的模板到配置文件中
     */
    protected function writeConfigureFile($templates)
    {
        $config = @fopen($this->ConfigureFileName(), "w");

        foreach ($templates as $template => $url) {
            @fwrite($config, "{$template}={$url}\n");
        }

        @fclose($config);
    }
}
