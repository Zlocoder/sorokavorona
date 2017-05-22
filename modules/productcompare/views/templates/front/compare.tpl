
{capture name=path}{l s='Compare' mod='productcompare'}{/capture}
{foreach from=$categories item=category name=categories}
    {if $category.compare_products}
        <div class="row category_container" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
            <div class="col-md-12">
                <h2 class="page-heading">
                <span class="cat-name">{$category.category->name.$id_lang}</span>
                </h2>
                <button type="button" class="delete_cat_compare btn btn-default compare_button" data-id-category = {$category.category->id}>{l s='delete category' mod='productcompare'}</button>
            </div>
            {foreach from=$category.compare_products item=product name=$category.compare_products}
                {*<pre>{$category|var_dump}</pre>*}
                {*<button class="delete_cat_compare">x</button>*}
                {*{$product|var_dump}*}
                <div class="col-md-4 product_container">
                    <button class="delete_prod_compare close" data-id-product = {$product->id}>&times;</button>
                    {assign var = "url"  value = $link->getImageLink($product->link_rewrite.$id_lang, $images.{$product->id}.id_image, 'home_default')|escape:'html':'UTF-8'}
                    <div class="left-block">
                        <a href="{$product->getLink()}">
                            <img class="compare_img" src="{$url}">
                        </a>
                    </div>
                    <div class="right-block">
                        <div class="product-name">
                            <a href="{$product->getLink()}">
                                <span class="compare_name"> {$product->name.$id_lang}</span>
                                </a>
                        </div>
                        <div class="content_price">
                            <span class="compare_price">{convertPrice price=$product->price}</span>
                        </div>
                    </div>
                </div>
            {/foreach}
            <div class="clearfix"></div>
            <div style="float:left;">
            <a type="button" class="compare_link btn btn-success" href="?id_category={$category.category->id}">{l s='link to compere' mod='productcompare'}</a>
            </div>
        </div>
    {/if}
{/foreach}
<div class="row">
    <a type="button" class="compare_link btn btn-success" href="?id_category=all">{l s='compare all' mod='productcompare'}</a>
</div>