<?php

class FrontController extends FrontControllerCore {
    public function initContent() {
        parent::initContent();

        $categories = Category::getCategories($this->context->language->id, true, true, ' AND c.id_parent = 2 ');
        $categories = $categories[2];

        foreach ($categories as $index => $category) {
            $categories[$index] = $category['infos'];
        }

        $this->context->smarty->assign(array(
            'menu_categories' => $categories
        ));
    }
}