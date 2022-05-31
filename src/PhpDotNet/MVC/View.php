<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Exceptions\View\LayoutNotFoundException;
use PhpDotNet\Exceptions\View\LayoutPathDoesNotExistException;
use PhpDotNet\Exceptions\View\ViewDirDoesNotExistException;
use PhpDotNet\Exceptions\View\ViewNotFoundException;
use stdClass;

final class View {
    private static string $viewDir;
    private static string $layoutPath = '';

    /**
     * Instantiates a new {@see View}.
     *
     * @param string        $view                Path to the view to render.
     * @param stdClass|null $model               The view model for building the view.
     * @param bool          $useDefaultViewPath  Configures whether to use locate the view from the default view path.
     *
     * @noinspection PhpPropertyOnlyWrittenInspection
     */
    public function __construct(private readonly string $view, private readonly ?stdClass $model = null, private readonly bool $useDefaultViewPath = true) {}

    /**
     * Statically instantiates a new {@see View}.
     *
     * @param string        $view                Path to the view to render.
     * @param stdClass|null $model               The view model for building the view.
     * @param bool          $useDefaultViewPath  Configures whether to use locate the view from the default view path.
     *
     * @return View
     */
    public static function make(string $view, ?stdClass $model = null, bool $useDefaultViewPath = true): self {
        return new View($view, $model, $useDefaultViewPath);
    }

    /**
     * Sets the path to locate views.
     *
     * @param string $viewDir
     *
     * @return void
     * @throws ViewDirDoesNotExistException
     */
    public static function setViewDir(string $viewDir): void {
        $viewDir = realpath($viewDir);

        if ($viewDir === false) {
            throw new ViewDirDoesNotExistException();
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

        if ($layoutPath === false) {
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
     */
    public function __toString(): string {
        return $this->render();
    }

    /**
     * Builds the {@see View} and returns it as a string to be echoed.
     *
     * @return string
     * @throws ViewNotFoundException
     * @throws LayoutNotFoundException
     */
    public function render(): string {
        if (!empty(self::$layoutPath)) {
            $layoutPath = self::$layoutPath;
        } else {
            $layoutPath = self::$viewDir . '/Shared/Layout.phtml';
        }

        if (!file_exists($layoutPath)) {
            throw new LayoutNotFoundException();
        }

        if ($this->useDefaultViewPath) {
            $view     = '/' . $this->view . '.phtml';
            $viewPath = $this->resolveViewPath($view);
        } else {
            $viewPath = $this->view;
        }

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        ob_start();

        include $layoutPath;

        $layout = (string)ob_get_clean();

        ob_start();

        include $viewPath;

        $view = (string)ob_get_clean();

        return str_replace('{{content}}', $view, $layout);
    }

    /**
     * Attempts to find the path for the given view file.
     *
     * @param string $view  The view file (e.g., /View.phtml').
     *
     * @return string
     */
    private function resolveViewPath(string $view): string {
        $viewDir  = scandir(self::$viewDir);
        $viewPath = self::$viewDir . $view;

        if (!file_exists($viewPath)) {
            foreach ($viewDir as $item) {
                if (is_dir($item)) {
                    $viewPath = $item . $view;
                    if (file_exists($viewPath)) {
                        break;
                    }

                    $viewDir = scandir($item);

                    foreach ($viewDir as $item2) {
                        if (is_dir($item2)) {
                            $viewPath = $item2 . $view;
                            if (file_exists($viewPath)) {
                                break;
                            }

                            $viewDir = scandir($item2);

                            foreach ($viewDir as $item3) {
                                if (is_dir($item3)) {
                                    $viewPath = $item3 . $view;
                                    if (file_exists($viewPath)) {
                                        break;
                                    }

                                    $viewDir = scandir($item3);

                                    foreach ($viewDir as $item4) {
                                        if (is_dir($item4)) {
                                            $viewPath = $item4 . $view;
                                            if (file_exists($viewPath)) {
                                                break;
                                            }

                                            $viewDir = scandir($item4);

                                            foreach ($viewDir as $item5) {
                                                if (is_dir($item5)) {
                                                    $viewPath = $item5 . $view;
                                                    if (file_exists($viewPath)) {
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }

        return $viewPath;
    }
}
