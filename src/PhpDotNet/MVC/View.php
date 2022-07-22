<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Exceptions\Common\DirectoryNotFoundException;
use PhpDotNet\Exceptions\View\LayoutNotFoundException;
use PhpDotNet\Exceptions\View\LayoutPathDoesNotExistException;
use PhpDotNet\Exceptions\View\ViewNotFoundException;

final class View {
    private static string $viewDir;
    private static string $layoutPath = '';

    /**
     * Instantiates a new {@see View}.
     *
     * @param string      $view                Path to the view to render.
     * @param object|null $model               The view model for building the view.
     * @param bool        $useDefaultViewPath  Configures whether to locate the view from the default view path.
     */
    public function __construct(private readonly string $view, private readonly ?object $model = null, private readonly bool $useDefaultViewPath = true) {}

    /**
     * Statically instantiates a new {@see View}.
     *
     * @param string|null $view                Path to the view to render.
     * @param object|null $model               The view model for building the view.
     * @param bool        $useDefaultViewPath  Configures whether to locate the view from the default view path.
     *
     * @return View
     */
    public static function make(?string $view = null, ?object $model = null, bool $useDefaultViewPath = true): self {
        // if ($view === null) {
        //     $view = InsightsUtil::getCallingMethodName();
        // }
        // $controller = InsightsUtil::getCallingClassName();
        // WebApplication::$app->logger->info('View caller: {caller}', ['caller' => $callingMethodName]);
        // WebApplication::$app->logger->info('View caller: {caller}', ['caller' => $callingClassName]);
        return new View($view, $model, $useDefaultViewPath);
    }

    /**
     * Sets the path to locate views.
     *
     * @param string $viewDir
     *
     * @return void
     * @throws DirectoryNotFoundException
     */
    public static function setViewDir(string $viewDir): void {
        $viewDir = realpath($viewDir);

        if ($viewDir === false || !is_dir($viewDir)) {
            throw new DirectoryNotFoundException('The directory specified for the Views does not exist.');
        }

        self::$viewDir = $viewDir;
    }

    /**
     * Attempts to set the layout path.
     *
     * @param string $layoutPath
     *
     * @return void
     * @throws LayoutPathDoesNotExistException
     */
    public static function setLayoutPath(string $layoutPath): void {
        $layoutPath = realpath($layoutPath);

        if ($layoutPath === false || !is_file($layoutPath)) {
            throw new LayoutPathDoesNotExistException();
        }

        self::$layoutPath = $layoutPath;
    }

    /**
     * Calls {@see View::render()}.
     *
     * @return string
     * @throws ViewNotFoundException
     * @throws LayoutNotFoundException
     * @throws DirectoryNotFoundException
     */
    public function __toString(): string {
        return $this->render();
    }

    /**
     * Builds the {@see View} with the layout and returns it as a string.
     *
     * @return string
     * @throws ViewNotFoundException
     * @throws LayoutNotFoundException
     * @throws DirectoryNotFoundException
     */
    public function render(): string {
        if (empty(self::$viewDir)) {
            throw new DirectoryNotFoundException('Could not find views directory while attempting to render view.');
        }

        if (!empty(self::$layoutPath)) {
            $layoutPath = self::$layoutPath;
        } else {
            $layoutPath = self::$viewDir . '/Shared/Layout.phtml';
        }

        if (!is_file($layoutPath)) {
            throw new LayoutNotFoundException();
        }

        if ($this->useDefaultViewPath) {
            $viewPath = self::$viewDir . '/' . $this->view . '.phtml';
        } else {
            $viewPath = $this->view;
        }

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        $model = $this->model;

        ob_start();

        include $layoutPath;

        $layout = (string)ob_get_clean();

        ob_start();

        include $viewPath;

        $view = (string)ob_get_clean();

        return str_replace('{{RenderContent}}', $view, $layout);
    }

    /**
     * Builds the {@see View} without the layout and returns it as a string.
     *
     * @return string
     * @throws DirectoryNotFoundException
     * @throws ViewNotFoundException
     */
    public function renderPartial(): string {
        if (empty(self::$viewDir)) {
            throw new DirectoryNotFoundException('Could not find views directory while attempting to render view.');
        }

        if ($this->useDefaultViewPath) {
            $viewPath = self::$viewDir . '/' . $this->view . '.phtml';
        } else {
            $viewPath = $this->view;
        }

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        $model = $this->model;

        ob_start();

        include $viewPath;

        return (string)ob_get_clean();
    }

    // /**
    //  * Attempts to find the path for the given view file.
    //  *
    //  * @param string $view  The view file (e.g., /View.phtml').
    //  *
    //  * @return string
    //  */
    // private function resolveViewPath(string $view): string {
    //     $viewDir  = scandir(self::$viewDir);
    //     $viewPath = self::$viewDir . $view;
    //
    //     if (!file_exists($viewPath)) {
    //         foreach ($viewDir as $item) {
    //             if (is_dir($item)) {
    //                 $viewPath = $item . $view;
    //                 if (file_exists($viewPath)) {
    //                     break;
    //                 }
    //
    //                 $viewDir = scandir($item);
    //
    //                 foreach ($viewDir as $item2) {
    //                     if (is_dir($item2)) {
    //                         $viewPath = $item2 . $view;
    //                         if (file_exists($viewPath)) {
    //                             break;
    //                         }
    //
    //                         $viewDir = scandir($item2);
    //
    //                         foreach ($viewDir as $item3) {
    //                             if (is_dir($item3)) {
    //                                 $viewPath = $item3 . $view;
    //                                 if (file_exists($viewPath)) {
    //                                     break;
    //                                 }
    //
    //                                 $viewDir = scandir($item3);
    //
    //                                 foreach ($viewDir as $item4) {
    //                                     if (is_dir($item4)) {
    //                                         $viewPath = $item4 . $view;
    //                                         if (file_exists($viewPath)) {
    //                                             break;
    //                                         }
    //
    //                                         $viewDir = scandir($item4);
    //
    //                                         foreach ($viewDir as $item5) {
    //                                             if (is_dir($item5)) {
    //                                                 $viewPath = $item5 . $view;
    //                                                 if (file_exists($viewPath)) {
    //                                                     break;
    //                                                 }
    //                                             }
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //
    //             }
    //         }
    //     }
    //
    //     return $viewPath;
    // }
}
