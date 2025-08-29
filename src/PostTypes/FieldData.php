<?php

namespace Evolutio\PostTypes;

use InvalidArgumentException;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case PHONE = 'phone';
    case EMAIL = 'email';
    case STATUS = 'status';
}

class FieldData
{

    private string $name;
    private FieldType $type;
    private string $label;
    private string $placeholder;
    private int $max_size;
	private bool $required;

    public function __construct(string $name, FieldType $type, string $label, string $placeholder, int $max_size, bool $required = true)
    {
        if ($max_size < 0) {
            throw new InvalidArgumentException("max_size must be a positive integer.");
        }

        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->max_size = $max_size;
		$this->required = $required;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): FieldType
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getMaxSize(): int
    {
        return $this->max_size;
    }

	public function isRequired(): bool
	{
		return $this->required;
	}
}
