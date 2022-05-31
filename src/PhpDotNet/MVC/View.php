<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Exceptions\ViewNotFoundException;
use stdClass;

final class View {
    private static string $viewPath;

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
     * @param string $viewPath
     *
     * @return void
     */
    public static function setViewPath(string $viewPath): void {
        self::$viewPath = $viewPath;
    }

    /**
     * Builds the {@see View} and returns it as a string to be echoed.
     *
     * @return string
     * @throws ViewNotFoundException
     */
    public function render(): string {
        if ($this->useDefaultViewPath) {
            $viewPath = self::$viewPath . $this->view . '.phtml';

            if (!file_exists($viewPath)) {
                $viewPath = self::$viewPath . $this->view . '.php';
                if (!file_exists($viewPath)) {
                    throw new ViewNotFoundException();
                }
            }
        } else {
            $viewPath = $this->view . '.phtml';

            if (!file_exists($viewPath)) {
                $viewPath = $this->view . '.php';
                if (!file_exists($viewPath)) {
                    throw new ViewNotFoundException();
                }
            }
        }

        ob_start();

        include $viewPath;

        return (string)ob_get_clean();
    }

    /**
     * Calls {@see View::render()}.
     *
     * @return string
     * @throws ViewNotFoundException
     */
    public function __toString(): string {
        return $this->render();
    }
}
