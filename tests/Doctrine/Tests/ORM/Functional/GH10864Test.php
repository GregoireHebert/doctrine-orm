<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\OrmFunctionalTestCase;

/**
 * @group GH10864
 */
class GH10864Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchemaForModels(
            GH10864Entity::class
        );
    }

    public function testInsertionOrderIsParentThenChildren(): void
    {
        $child = new GH10864Entity();
        $child->name = 'child';

        $parent = new GH10864Entity();
        $parent->name = 'parent';
        $parent->child = $child;

        $this->_em->persist($parent);
        $this->_em->flush();

        self::assertEquals(1, $parent->getId(), "Parent should be inserted first but his Id is {$parent->getId()}");
        self::assertEquals(2, $child->getId(), 'Child should be inserted second');
    }
}

/**
 * @ORM\Entity
 */
class GH10864Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * ORM\Column(name="name", type="string", length=30)]
     */
    public $name;

    /**
     * @ORM\ManyToOne(targetEntity="GH10864Entity", cascade={"persist"})
     *
     * @var self
     */
    public $child;

    public function getId()
    {
        return $this->id;
    }
}
