<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Http\Request;
use ReflectionClass;
use ReflectionException;

abstract class ControllerBase {
    protected array $body = [];

    private array $modelBindingErrors = [];

    public function __construct() {
        $this->body = Request::getBody();
    }

    /**
     * {@see View::make()}
     */
    public function view(?string $view, ?object $model = null): View {
        return View::make($view, $model);
    }

    /**
     * Provides a very rudimentary way of binding the data in {@see ControllerBase::$body} to a provided model.
     *
     * At this time, the desired model to be hydrated must meet the following criteria:
     * - Constructor must be parameterless (including no promoted properties).
     * - All property types must be simple, built-in types (e.g., string, int, bool).
     *
     * @param string $classFQN  The fully qualified name of the desired model (i.e., MyClassName::class).
     *
     * @return object|null The hydrated model given no fatal errors, null otherwise.
     */
    public function bindData(string $classFQN): ?object {
        // Use the Reflection API to look into the class so that we can validate the binding.
        try {
            $reflectionModel = new ReflectionClass($classFQN);
            $model           = $reflectionModel->newInstance();
        } catch (ReflectionException $exception) {
            $this->modelBindingErrors['FatalErrors'][] = "Failed to instantiate an instance of the given class {$classFQN}";

            return null;
        }

        foreach ($reflectionModel->getProperties() as $property) {
            if (!$property->hasType()) {
                $this->modelBindingErrors['FatalErrors'][] = "All properties of class {$classFQN} must be type hinted.";

                break;
            }

            foreach ($this->body as $key => $value) {
                if ($property->getName() !== $key) {
                    continue;
                }

                $type      = $property->getType();
                $valueType = gettype($value);

                if (!$type->isBuiltin()) {
                    $this->modelBindingErrors['FatalErrors'][] = "Property {$property->getName()} in class {$reflectionModel->getName()} must be a built-in type.";

                    break;
                }

                if ($type->getName() !== $valueType) {
                    if ($type->getName() === 'bool') {
                        if ($value === 'true' || $value === 1) {
                            $property->setValue($model, true);
                        } elseif ($value === 'false' || $value === 0) {
                            $property->setValue($model, false);
                        }
                    }

                    if ($type->allowsNull()) {
                        $property->setValue($model, null);

                        $this->modelBindingErrors['NonFatalErrors'][] =
                            "Property {$property->getName()} in class {$reflectionModel->getName()} type did not match the received type and has been set to null.";

                        continue;
                    }

                    $this->modelBindingErrors['FatalErrors'][] =
                        "Property {$property->getName()} in class {$reflectionModel->getName()} type did not match the received type and does not allow for null.";

                    break;
                }

                $property->setValue($model, $value);
            }
        }

        return $model;
    }

    /**
     * If the model binding process recorded any Fatal Errors, this will return false.
     *
     * @return bool
     */
    public function isModelStateValid(): bool {
        return count($this->modelBindingErrors['FatalErrors']) === 0;
    }

    public function getFatalModelError(): ?string {
        return $this->modelBindingErrors['FatalErrors'][0] ?? null;
    }

    public function getFatalModelErrors(): ?array {
        return $this->modelBindingErrors['FatalErrors'];
    }

    public function getNonFatalModelError(): ?string {
        return $this->modelBindingErrors['FatalErrors'][0] ?? null;
    }

    public function getNonFatalModelErrors(): ?array {
        return $this->modelBindingErrors['FatalErrors'];
    }
}
