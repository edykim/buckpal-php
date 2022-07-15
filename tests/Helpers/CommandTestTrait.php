<?php

namespace BuckPal\Tests\Helpers;

use ReflectionClass;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

trait CommandTestTrait
{
  protected function createCommandTest($commandClass, array $mocks): array
  {
    $ref = new ReflectionClass($commandClass);
    $attrs = $ref->getAttributes(AsCommand::class);

    if (count($attrs) > 1) {
      throw new \Error('Attribute "' . AsCommand::class . '" must not be repeated');
    }

    if (count($attrs) === 0) {
      throw new \Error('Attribute "' . AsCommand::class . '" must not be presented');
    }

    $args = $attrs[0]->getArguments();
    $name = $args['name'] ?? throw new RouteNotFoundException("$commandClass does not have a command name.");

    $mocks = array_map(fn ($mockClass) => $this->createMock($mockClass), $mocks);
    $command = new $commandClass(...$mocks);

    $app = new Application();
    $app->add($command);
    $command = $app->find($name);
    $commandTester = new CommandTester($command);

    return [$commandTester, $mocks];
  }
}
