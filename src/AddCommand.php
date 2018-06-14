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

class AddCommand extends Command
{
    /**
     * 添加命令描述
     */
    protected function configure()
    {
        $this
            ->setName('template:add')
            ->setDescription('添加 github 项目模板')
            ->addArgument('template', InputArgument::REQUIRED, '模板名, 例如 cola')
            ->addArgument('url', InputArgument::REQUIRED, '模板 github 地址, 例如 https://github.com/zhangxiangliang/cola');
    }

    /**
     * 执行命令
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $template = $input->getArgument('template');

        $templates = templates();
        $templates[$template] = $url;

        $this->writeConfigureFile($templates);
        $output->writeln('<comment>模板已经添加完成！</comment>');
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
