<?php

declare(strict_types=1);

namespace Zenify\DoctrineBehaviors\DI;

use Kdyby\Events\DI\EventsExtension;
use Knp\DoctrineBehaviors\Model\Tree\Node;
use Knp\DoctrineBehaviors\ORM\Tree\TreeSubscriber;
use Nette\Utils\AssertionException;
use Nette\Utils\Validators;


final class TreeExtension extends AbstractBehaviorExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'isRecursive' => TRUE,
		'nodeTrait' => Node::class
	];


	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$this->validateConfigTypes($config);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setClass(TreeSubscriber::class, [
				'@' . $this->getClassAnalyzer()->getClass(),
				$config['isRecursive'],
				$config['nodeTrait']
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
		Validators::assertField($config, 'nodeTrait', 'type');
	}

}
