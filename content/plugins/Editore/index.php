<?php

class Editore {
    function init(\App\App &$app, $res, array $opts = []) {
        $app->addCSS("content/assets/styles/kernel.css");
        $app->addCSS("content/plugins/Editor/style.css");

        if (empty($res->params)) {
            $app->title = "Editore";

            $page_loader = new \App\PageLoader();

            $app->content = $app->smarty->fetch(__DIR__ . "/editor.tpl", [
                "posts" => \App\PluginLoader::loadPlugin($app, "BlogPosts", [], ["template" => true]),
                "plugins" => \App\PluginLoader::getPluginsList(),
                "pages" => $page_loader->loadPages($app)
            ]);
        } else {
            $this->blogPostEdit($app, $res);
        }
    }

    /**
     * Edit a particular blog post.
     */

    function blogPostEdit(\App\App &$app, $res) {
        require_once("content/plugins/BlogPosts/Blog.php");

        $blog = new \Plugin\Blog();

        $blog->loadPosts();

        $post = $blog->posts[$res->params["id"]];

        $app->title = $post->get("title");
        $app->description = substr(strip_tags($post->get("content")), 0, 150) . " ...";
        
        $app->content = $app->smarty->fetch(__DIR__ . "/edit_post.tpl", [
            "post" => $post
        ]);
    }
}
