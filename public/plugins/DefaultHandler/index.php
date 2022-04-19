<?php

class DefaultHandler extends \App\Plugin {
    public $pluginInfo = [
        "name" => "DefaultHandler",
        "description" => "Default plugin for pages with no other plugin.",
        "type" => \App\PluginType::DEFAULT,
        "version" => "1.0.0"
    ];

    function init(\App\App &$app, \App\Request $req, array $opts = []) {
        if (isset($opts["additional_template"]) && $this->templateExists($opts["additional_template"])) {
            $app->smarty->assign("additional_template", $app->smarty->fetch($opts["additional_template"]));
        }

        $app->title = $opts["title"];
        $app->content = $app->smarty->fetch($opts["path"]);
    }

    private function templateExists(?string $file): bool {
        return file_exists($file);
    }

    /**
     * Get the nav bar for the header.
     */
    function getNav(\App\App $app): string {
        // Filter nav items which are set to visible.
        $nav_items_filtered = array_filter($app->page_loader->routes, fn($x) => $x["visible"]);

        // Get the correct lang for nav items.
        $nav_items_lang = array_map(fn($x) =>
            array_merge($x, ["title" => $app->lang->get("nav:" . strtolower($x["title"]))
        ]), $nav_items_filtered);

        return $app->smarty->fetch("lib/templates/partials/nav.tpl", [
            "nav_items" => $nav_items_lang
        ]);
    }

    function getFooter(\App\App $app): string {
        return $app->smarty->fetch("lib/templates/partials/footer.tpl");
    }
}
