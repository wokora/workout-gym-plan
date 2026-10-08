<?php

namespace App\Http\View\Composers;

use App\Main\SidebarPanel;
use Illuminate\View\View;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!is_null(request()->route())) {
            $pageName = request()->route()->getName() ?? '';
            $routePrefix = explode('.', $pageName)[0] ?? '';

            switch ($routePrefix) {
                case 'workout':
                case 'exercise':
                case 'body':
                case 'muscle-groups':
                    $view->with('sidebarMenu', SidebarPanel::workouts());
                    break;
                case 'meal':
                case 'food':
                    $view->with('sidebarMenu', SidebarPanel::meals());
                    break;
                case 'vital':
                    $view->with('sidebarMenu', SidebarPanel::vitals());
                    break;
                default:
                    $view->with('sidebarMenu', SidebarPanel::dashboard());
            }

            $view->with('allSidebarItems', SidebarPanel::all());
            $view->with('pageName', $pageName);
            $view->with('routePrefix', $routePrefix);
        } else {
            $view->with('sidebarMenu', SidebarPanel::dashboard());
            $view->with('allSidebarItems', SidebarPanel::all());
            $view->with('pageName', 'index');
            $view->with('routePrefix', '');
        }
    }
}
