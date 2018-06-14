<?php

namespace NewOne\Cola\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    /**
     * 添加命令描述
     */
    protected function configure()
    {
        $this
            ->setName('template:list')
            ->setDescription('列出可用模板');
    }

    /**
     * 执行命令
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $templates = templates();
        $output->writeln('<info>可用模板如下: </info>');
        foreach ($templates as $key => $value) {
           $output->writeln("<comment>{$key} : {$value}</comment>");
        }
    }
}
