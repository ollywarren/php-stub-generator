<?php

namespace SebastiaanLuca\StubGenerator;

use RuntimeException;

class StubGenerator
{
    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var bool
     */
    protected $toString;

    /**
     * @param string $source
     * @param string $target
     */
    public function __construct(string $source, string $target = null)
    {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * @param array $replacements
     *
     * @throws \RuntimeException
     */
    public function render(array $replacements)
    {
        $contents = file_get_contents($this->source);

        // Standard replacements
        collect($replacements)->each(function (string $replacement, string $tag) use (&$contents) {
            $contents = str_replace($tag, $replacement, $contents);
        });

        if (!$this->toString) {
            if (file_exists($this->target)) {
                throw new RuntimeException('Cannot generate file. Target ' . $this->target . ' already exists.');
            }

            $path = pathinfo($this->target, PATHINFO_DIRNAME);

            if (! file_exists($path)) {
                mkdir($path, 0776, true);
            }

            file_put_contents($this->target, $contents);
        } else {
            return $contents;
        }
    }

    /**
     * Set response to string
     *
     * @return SebastiaanLuca\StubGenerator\StubGenerator
     */
    public function toString()
    {
        $this->toString = true;
        return $this;
    }
}
