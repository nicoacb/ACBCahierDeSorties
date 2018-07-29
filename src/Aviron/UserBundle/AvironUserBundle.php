<?php

namespace Aviron\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AvironUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
