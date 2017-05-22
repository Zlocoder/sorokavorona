<div class="compare_product">
    {*<pre>{$count_products|var_dump}</pre>*}
    <a href="/{$lang_iso}/compare">
    <button class="comparebtn">Сравнение (<span class="total_compare">{$count_products.total}</span>) </button>
    </a>
    <div class="compare-content">
        {foreach from=$categories item=category name=categories}
        <a href="/{$lang_iso}/compare?id_category={$category.category->id}">{$category.category->name.$id_lang}
        <span class="count_compare" data-id-category = {$category.category->id}>({$count_products.{$category.category->id}})</span>
        </a>
                 <span>
                <button style="display:inline-block;margin-top: 9px;margin-right: 10px;font-size: 25px;" class="delete_cat_compare close" data-id-category = {$category.category->id}>&times;</button>
            </span>
            </span>

        {/foreach}
    </div>
</div>
