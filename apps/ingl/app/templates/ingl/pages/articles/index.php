<?php

$engine->pages->load_pages("articles", "article_categories");
switch ($engine->sef->sef_params["task"]) {
    case "view":
        require_once _APP_DIR_ . "templates/" . $this->engine->settings->general->template
            . "/pages/articles/views/articleView.php";
        require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
            . "/pages/articles/controllers/viewController.php";
        require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
            . "/pages/articles/models/modelViewArticle.php";
        $model= new modelViewArticle();
        $controller = new viewController($model);
        break;
    case "list":
        if (isset($this->engine->sef->sef_params["route_more"])) {
            $this->engine->sef->sef_params["task"] = "view";
            require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
                . "/pages/articles/controllers/viewController.php";
            require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
                . "/pages/articles/models/modelViewArticle.php";
            require_once _APP_DIR_ . "templates/" . $this->engine->settings->general->template
                . "/pages/articles/views/articleView.php";
            $model = new modelViewArticle();
            $controller = new viewController($model);
        } else {
            // Require once
            require_once _APP_DIR_ . "templates/" . $this->engine->settings->general->template
                . "/pages/articles/views/articlesListView.php";
            require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
                . "/pages/articles/models/modelListArticles.php";
            require_once _APP_DIR_ . "templates/" . $engine->settings->general->template
                . "/pages/articles/controllers/listController.php";
            $model = new modelListArticles();
            $controller = new listController($model);
        }
        break;
}