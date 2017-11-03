<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\Tests\Fragment\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Fragment\ContentElement\DefaultContentElementRenderer;
use Contao\CoreBundle\Fragment\FragmentRegistry;
use Contao\CoreBundle\Fragment\FragmentRegistryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

class DefaultContentElementRendererTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $renderer = $this->mockRenderer();

        $this->assertInstanceOf('Contao\CoreBundle\Fragment\ContentElement\DefaultContentElementRenderer', $renderer);
    }

    public function testSupportsContentModels(): void
    {
        $this->assertTrue($this->mockRenderer()->supports(new ContentModel()));
    }

    public function testRendersContentModels(): void
    {
        $attributes = [
            'contentModel' => 42,
            'inColumn' => 'main',
            'scope' => 'scope',
        ];

        $expectedControllerReference = new ControllerReference('test', $attributes);
        $fragment = new \stdClass();

        $options = [
            'tag' => 'contao.fragment.content_element',
            'type' => 'test',
            'controller' => 'test',
            'category' => 'text',
        ];

        $registry = new FragmentRegistry();
        $registry->addFragment('contao.fragment.content_element.identifier', $fragment, $options);

        $handler = $this->createMock(FragmentHandler::class);

        $handler
            ->expects($this->once())
            ->method('render')
            ->with($this->equalTo($expectedControllerReference))
        ;

        $model = new ContentModel();
        $model->setRow(['id' => 42, 'type' => 'identifier']);

        $renderer = $this->mockRenderer($registry, $handler);
        $renderer->render($model, 'main', 'scope');
    }

    /**
     * Mocks a default content element renderer.
     *
     * @param FragmentRegistryInterface|null $registry
     * @param FragmentHandler|null           $handler
     *
     * @return DefaultContentElementRenderer
     */
    private function mockRenderer(FragmentRegistryInterface $registry = null, FragmentHandler $handler = null): DefaultContentElementRenderer
    {
        if (null === $registry) {
            $registry = new FragmentRegistry();
        }

        if (null === $handler) {
            $handler = $this->createMock(FragmentHandler::class);
        }

        return new DefaultContentElementRenderer($registry, $handler, new RequestStack());
    }
}
