<?php

/**
 * @link      digilab.co.uk
 * @copyright Copyright (c) 2023 Dene Hewings
 */

namespace digilab\split\variables;

use digilab\split\Split;

/**
 * @author    gigiLab
 * @package   Split Delivery
 * @since     1
 */

class OrderfieldsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.twigvar.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.twigvar.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */

    public function getTheOrderFields()
    {
        $service = Split::getInstance()->service;
        return $service->orderfields;
    }
    public function getField($fieldhandle)
    {
        $service = Split::getInstance()->service;
        return $service->getField($fieldhandle);
    }
    public function getFieldName($fieldhandle)
    {
        $service = Split::getInstance()->service;
        return $service->getField($fieldhandle)->name;
    }
    public function getFieldDescription($fieldhandle)
    {
        $service = Split::getInstance()->service;
        return $service->getField($fieldhandle)->instructions;
    }
    public function getSettings()
    {
        $service = Split::getInstance()->service;
        return $service->settings;
    }
    public function getFieldHtml($fieldhandle)
    {
        $service = Split::getInstance()->service;
        return $service->getFieldHtml($fieldhandle);
    }
}
