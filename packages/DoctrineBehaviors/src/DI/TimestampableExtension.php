<?php

declare(strict_types=1);

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\ORM\Timestampable\TimestampableSubscriber;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


final class TimestampableExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'isRecursive' => TRUE,
		'trait' => Timestampable::class,
		'dbFieldType' => 'datetime',
	];


	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setClass(TimestampableSubscriber::class, [
				'@' . $this->getClassAnalyzer()->getClass(),
				$config['isRecursive'],
				$config['trait'],
				$config['dbFieldType'],
			])
			->setAutowired(FALSE)
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	/**
	 * @throws AssertionException
	 */
	private function validateConfigTypes(array $config)
	{
		Validators::assertField($config, 'isRecursive', 'bool');
		Validators::assertField($config, 'trait', 'type');
		Validators::assertField($config, 'dbFieldType', 'string');
	}

}
