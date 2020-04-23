<?php

namespace Codebykyle\CalculatedField;

use Illuminate\Http\Request;
use Laravel\Nova\Element;
use Laravel\Nova\Fields\Field;

class ListenerField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'listener-field';
    
    /**
     * The type of the field to show on the form
     * @var string
     */
    public $type = 'number';

    /**
     * The event this fields listens for
     * @var string
     */
    protected $listensTo;

    /**
     * The function to call when input is detected
     * @var \Closure
     */
    public $calculateFunction;

    /***
     * ListenerField constructor.
     * @param $name
     * @param null $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->listensTo = 'broadcast-field-input';

        $this->calculateFunction = function ($values, Request $request) {
            return collect($values)->values()->sum();
        };
    }

    /**
     * Allows us to set the format of the number according to numeral.js
     * @param $broadcastChannel
     */
    public function numberFormat($format)
    {
        return $this->withMeta([
            'numberFormat' => $format
        ]);
    }
    
    /**
     * Set the type of the field (string, number)
     *
     * @param $type
     * @return Element
     */
    public function setType($type) : Element
    {
        return $this->withMeta([
            'type' => $type
        ]);
    }

    /**
     * The channel that the client side component listens to
     * @param $channel
     * @return $this
     */
    public function listensTo($channel) {
        $this->listensTo = $channel;
        return $this;
    }

    /***
     * The callback we want to call when the field has some input
     *
     * @param callable $calculateFunction
     * @return $this
     */
    public function calculateWith(callable $calculateFunction) {
        $this->calculateFunction = $calculateFunction;
        return $this;
    }

    /***
     * Serialize the field to JSON
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'listensTo' => $this->listensTo
        ], parent::jsonSerialize());
    }
}
