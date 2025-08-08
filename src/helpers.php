<?php

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

if (! function_exists('html_classes')) {
    /**
     * Generate a class attribute string from the given classes list.
     *
     * @param string|int|array|null ...$classList
     */
    function html_classes(...$classList): HtmlString
    {
        $classes = [];
        foreach ($classList as $arg) {
            switch (gettype($arg)) {
                case 'string':
                case 'integer':
                    $classes[] = $arg;
                    break;
                case 'array':
                    $classes = $arg ? array_merge($classes, $arg) : $classes;
                    break;
                case 'NULL':
                    break;
                default:
                    throw new \RuntimeException(
                        'Classes should be strings, integers, arrays or null: ' . gettype($arg) . ' given.'
                    );
            }
        }
        $classes = array_map('trim', array_filter(Arr::flatten($classes)));

        return new HtmlString($classes ? ' class="' . implode(' ', $classes) . '"' : '');
    }
}

if (! function_exists('html_attributes')) {
    /**
     * Generate an attributes string from the given attributes list.
     *
     * @param string|array|null ...$attributesList
     */
    function html_attributes(...$attributesList): HtmlString
    {
        $attributes = html_helper_build_attributes(...$attributesList);
        $html = html_helper_build_html_string($attributes);

        return new HtmlString($html);
    }

    /**
     * @param string|array|null ...$attributesList
     */
    function html_helper_build_attributes(...$attributesList): array
    {
        $attributes = [];
        foreach ($attributesList as $arg) {
            switch (gettype($arg)) {
                case 'string':
                    $attributes[] = $arg;
                    break;
                case 'array':
                    html_helper_build_attribute_from_array($arg, $attributes);
                    break;
                case 'NULL':
                    break;
                default:
                    throw new \RuntimeException(
                        'The given attributes arguments should be strings or arrays: ' . gettype($arg) . ' type given.'
                    );
            }
        }

        return array_map('trim', array_filter($attributes));
    }

    function html_helper_build_attribute_from_array(array $attribute, array &$attributes): void
    {
        foreach ($attribute as $key => $value) {
            if (is_array($value)) {
                if (is_string($key)) {
                    $attributes[] = $key;
                }
                html_helper_build_attribute_from_array($value, $attributes);
                continue;
            }
            if (is_string($key) && $value) {
                $attributes[$key] = $value;
                continue;
            }
            if ($value) {
                $attributes[] = $value;
                continue;
            }
            $attributes[] = $key;
        }
    }

    function html_helper_build_html_string(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $key => $attribute) {
            $spacer = $html ? ' ' : '';
            if ($key && is_string($key)) {
                $html .= $spacer . $key . ($attribute ? '="' . $attribute . '"' : '');
            } else {
                $html .= $spacer . $attribute;
            }
        }

        return ($html ? ' ' : '') . $html;
    }
}
