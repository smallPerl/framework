<?php

declare(strict_types=1);

/*
 * This file is part of eelly package.
 *
 * (c) eelly.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eelly\Console\Command;

use Eelly\Di\InjectionAwareInterface;
use Eelly\Di\Traits\InjectableTrait;
use Phalcon\Events\EventsAwareInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 缓存刷新.
 *
 * @author hehui<hehui@eelly.net>
 */
class FlushCacheCommand extends SymfonyCommand implements InjectionAwareInterface, EventsAwareInterface
{
    use InjectableTrait;

    protected function configure(): void
    {
        $this->setName('flush-cache')
            ->setDescription('Flush cahce')
            ->setHelp('flush cache');
        $this->addArgument('module', InputArgument::REQUIRED, 'Module name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $module = $input->getArgument('module');

        $module = [
            'className' => ucfirst($module).'\\Module',
        ];
        $di = $this->di;
        $moduleObject = $di->get($module['className']);
        $moduleObject->registerAutoloaders($di);
        $moduleObject->registerServices($di);
        $di->get('cache')->flush();
    }
}
