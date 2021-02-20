<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    /**
     * @return int|null
     */
    protected function itemPerPage(): ?int
    {
        $itemPerPage = request()->get("per_page");
        return (int)$itemPerPage;
    }
}
