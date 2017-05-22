{*<pre>*}
{*{$features_all|var_dump}*}
{*{$category|var_dump}*}
{*</pre>*}

{capture name=path}{l s='Compare' mod='productcompare'}{/capture}
<table class="table-bordered table-compare">
    <thead>
        <th class="col-md-3">
        <div class="col-md-12">
            <div class="category_image">

            <a class="category_image" href="{$link->getCategoryLink($category->id, $category->link_rewrite.$id_lang)|escape:'html':'UTF-8'}">
                <img  class="replace-2x" src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}">
            </a>
            </div>
            <div class="col-md-12">
            <span class="category_name">
                {$category->name.$id_lang}
            </span>
            </div>
        </div>
        </th>
    {foreach from=$products item=product name=$products}

        <th class="col-md-3 product_container item" data-id-product="{$product.id_product}">
            <div class="col-md-6">
                <a href="{$product.link}">
                    <img class="product_image" src="{$link->getImageLink($product.link_rewrite, {$product.id_image}, 'cart_default')|escape:'html':'UTF-8'}">
                </a>
            </div>
            <div class="col-md-6">
                <button  class="close delete_prod_compare_cat" data-id-product ="{$product.id_product}">&times;</button>
                <a href="{$product.link}">
                    <span class="compare_name">{$product.name|truncate:40}</span>
                </a>
                <span class="compare_price">{convertPrice price=$product.price}</span>
            </div>
            <div class="clearfix"></div>
            <div class="pull-right">
            <div class="btn-qty">
                    <button class="decim_item">-</button>
                    <input name="input_339" value="1" pattern="{literal}[1-9][0-9]{0,}{/literal}" type="text" style="border: none;">
                    <button class="add_item">+</button>
                </div>
                <a class="button ajax_add_to_cart_button btn btn-default to_cart" href="https://aquamarket.ua/ru/cart?add=1&id_product={$product.id_product}" rel="nofollow" data-id-product-attribute="0" data-id-product="{$product.id_product}" data-minimal_quantity="1" value="1">
                </a>
                </div>
        </th>
    {/foreach}
    </thead>
    <tbody>
        {foreach from=$features_all item=feature name=$features_all}

        <tr class="feature_value">
            <td>{$feature.name}</td>
            {foreach from=$products item=product name=$products}
                <td  class="product_container" data-id-product="{$product.id_product}">{$product.features.{$feature.id_feature}.value}</td>
            {/foreach}
        </tr>

        {/foreach}
    </tbody>
</table>