<div class="widget">
    <h3><?php echo $this->settings["title"]; ?></h3>
    <div class="widget-content">
        <ul>
            <?php
            foreach ($this->articles as $val){

                $article = $val["article"];
                $class = "";

                if (isset($this->engine->sef->sef_params["route_more"])){
                    $exp = explode("/",$this->engine->sef->sef_params["url"]);
                    if ($article->getAlias()==$exp[count($exp)-2]&&$this->engine->sef->sef_params["page"]=="articles"){
                        $class = "class=\"selected\"";
                    }
                }
                else if ($this->engine->sef->sef_params["page"]=="articles"
                    && $this->engine->sef->sef_params["id"]==$article->getID() &&$this->engine->sef->sef_params["task"]=="view" ){

                    $class = "class=\"selected\"";
                }
            ?>
            <li>
                <a href="<?php echo $article->getElementFromData("link"); ?>" <?php echo $class; ?>><?php echo $article->getTitle(); ?></a>
            </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>