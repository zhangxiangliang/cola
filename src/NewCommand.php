<?php

namespace NewOne\Cola\Console;

use ZipArchive;
use GuzzleHttp\Client;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    /**
     * 添加命令描述
     */
    protected function configure()
    {
        $this
            ->setName('template:new')
            ->setDescription('选择一个代码模板，快速创建代码基础目录结构')
            ->addArgument('template', InputArgument::REQUIRED, '模板名, 例如 cola')
            ->addArgument('name', InputArgument::OPTIONAL, '项目文件名, 例如 cola');
    }

    /**
     * 执行命令
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $template = $input->getArgument('template');
        $directory = ($input->getArgument('name')) ? getcwd().'/'.$input->getArgument('name') : getcwd();

        $templatesPath = templates();
        if (!isset($templatesPath[$template])) {
            throw new RuntimeException('该模板不存在');
        }

        if (! $input->getOption('force')) {
            $this->verifyApplicationDoesntExist($directory);
        }

        $output->writeln('<info>创建模板中 ...</info>');

        $templatePath = $templatesPath[$template];
        $zipFile = $this->makeFilename();
        $this->download($templatePath, $zipFile)
             ->extract($zipFile, $directory)
             ->cleanUp($zipFile);

        $output->writeln('<comment>模板已经创建完成！</comment>');
    }

    /**
     * 验证 文件夹 或 文件是否存在
     */
    protected function verifyApplicationDoesntExist($directory)
    {
        if ((is_dir($directory) || is_file($directory)) && $directory != getcwd()) {
            throw new RuntimeException('该文件名已存在');
        }
    }

    /**
     * 创建一个随机模板名
     */
    protected function makeFilename()
    {
        return '/tmp/cola_'.md5(time().uniqid()).'.zip';
    }

    /**
     * 下载模板
     */
    protected function download($templatePath, $zipFile)
    {
        $response = (new Client)->get("$templatePath" . '/archive/master.zip');
        file_put_contents($zipFile, $response->getBody());
        return $this;
    }

    /**
     * 解压模板文件
     */
    protected function extract($zipFile, $directory)
    {
        $archive = new ZipArchive;

        $archive->open($zipFile);

        $name = $archive->getNameIndex(0);
        $archive->extractTo('/tmp/');
        $archive->close();

        @rename("/tmp/{$name}", $directory);

        return $this;
    }

    /**
     * 修改文件权限 并 清理文件
     */
    protected function cleanUp($zipFile)
    {

        @chmod($zipFile, 0777);

        @unlink($zipFile);

        return $this;
    }

     /**
     * 获取 composer 命令
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" composer.phar';
        }

        return 'composer';
    }
}
