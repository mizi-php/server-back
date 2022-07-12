<?php

namespace Mizi;

use Mizi\View\TraitViewCurrent;
use Mizi\View\TraitViewLoad;
use Mizi\View\TraitViewMap;
use Mizi\View\TraitViewPrepare;

class View
{
    use TraitViewCurrent;
    use TraitViewLoad;
    use TraitViewMap;
    use TraitViewPrepare;

    static function render(string|array $viewRef = '.', array $prepare = []): string
    {
        if (is_array($viewRef)) {
            $prepare = $viewRef;
            $viewRef = '.';
        }

        if (self::map($viewRef)) {

            self::setCurrent($viewRef, $prepare);

            $prepare = [
                ...self::prepare(),
                ...self::getCurrentPrepare()
            ];

            $content = self::load_content($viewRef, $prepare);
            $script = self::load_script($viewRef, $prepare);
            $style = self::load_style($viewRef, $prepare);

            $incorp = [];

            if (strpos($content, '[#this.script]') !== false) {
                $incorp['this.script'] = $script;
                $script = '';
            }

            if (strpos($content, '[#this.style]') !== false) {
                $incorp['this.style'] = $style;
                $style = '';
            }

            if (!empty($incorp))
                $content = prepare($content, $incorp);

            $content = "$style$content$script";

            self::setCurrent();
        }

        return $content ?? '';
    }
}