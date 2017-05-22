<div class="product-rating">
    {$html}

    <div class="row">
        <div class="row">
            <div class="col-md-12">Дизайн</div>
        </div>
        <div class="row">
            <div class="product-rating-col col-md-4">Сорока-ворона</div>
            {if $avg_admin.design_rate == 0}
                <div class="product-rating-col col-md-3">Администратор еще не поставил оценку</div>
            {else}
                <div class="product-rating-col col-md-3 progressBar-design_a"></div>
            {/if}
        </div>
        <div class="row">
            <div class="product-rating-col col-md-4">{$avg_customer.cd} {if $avg_customer.cd == 0}покупателей{elseif $avg_customer.cd == 1} покупатель{elseif  $avg_customer.cd >= 2 && $avg_customer.cd <=5}покупателя{else}покупателей{/if}</div>
            <div class="product-rating-col col-md-3 progressBar-design"></div>
            {if $logged}
                <form method="post" action="" class="product-rating-col col-md-1">
                    {if $check.design_rate != null}
                    <span class="your-rate">{l s='Ваша оценка: '}{$check.design_rate}</span>{/if}
                    <select name="design_rate" class="rating-form-design form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <input value="Проголосовать" name="submit" type="submit"
                           class="rating-btn-design btn btn-secondary">
                </form>
            {else}
                <span>Голосовать могут только авторизированные пользователи</span>
            {/if}
        </div>
    </div>


    <div class="row">
        <div class="row">
            <div class="col-md-12">Функционал</div>
        </div>
        <div class="row">
            <span class="product-rating-col col-md-4">Сорока-ворона</span>
            {if $avg_admin.functionality_rate == 0}
                <div class="product-rating-col col-md-3">Администратор еще не поставил оценку</div>
            {else}
                <div class="product-rating-col col-md-3 progressBar-functionality_a"></div>
            {/if}
        </div>
        <div class="row">
            <span class="product-rating-col col-md-4">{$avg_customer.cf} {if $avg_customer.cf == 0}покупателей{elseif $avg_customer.cf == 1} покупатель{elseif  $avg_customer.cf >= 2 && $avg_customer.cf <=5}покупателя{else}покупателей{/if}</span>
            <span class="product-rating-col col-md-3 progressBar-functionality"></span>
            <input id="uf" hidden="hidden" value="{$avg_customer.func_rate|round:1}">
            {if $logged}
                <form method="post" action="" class="product-rating-col col-md-1">
                    {if $check.functionality_rate != null}
                    <span class="your-rate">{l s='Ваша оценка: '}{$check.functionality_rate}</span>
                    {/if}
                    <select name="functionality_rate" class="rating-form-functionality form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <input value="Проголосовать" name="submit" type="submit"
                           class="rating-btn-functionality btn btn-secondary">
                </form>
            {else}
                <span>Голосовать могут только авторизированные пользователи</span>
            {/if}
        </div>
    </div>
    <div class="row">
        <div class="row">
            <div class="col-md-12">Качество</div>
        </div>
        <div class="row">
            <span class="product-rating-col col-md-4">Сорока-ворона</span>
            {if $avg_admin.quality_rate == 0}
                <div class="product-rating-col col-md-3">Администратор еще не поставил оценку</div>
            {else}
                <div class="product-rating-col col-md-3 progressBar-quality_a"></div>
            {/if}
        </div>
        <div class="row">
            <span class="product-rating-col col-md-4">{$avg_customer.cq} {if $avg_customer.cq == 0}покупателей{elseif $avg_customer.cq == 1} покупатель{elseif  $avg_customer.cq >= 2 && $avg_customer.cq <=5}покупателя{else}покупателей{/if}</span>
            <span class="product-rating-col col-md-3 progressBar-quality"></span>
            {if $logged}
                <form method="post" action="" class="product-rating-col col-md-1">
                    {if $check.quality_rate != null}
                    <span class="your-rate">{l s='Ваша оценка: '}{$check.quality_rate}</span>
                    {/if}
                    <select name="quality_rate" class="rating-form-quality form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                    </select>
                    <input value="Проголосовать" name="submit" type="submit"
                           class="rating-btn-quality btn btn-secondary">
                </form>
            {else}
                <span>Голосовать могут только авторизированные пользователи</span>
            {/if}
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(function () {
            $(".progressBar-design_a").progressbar({
                value: {$avg_admin.design_rate|round:1},
                max: 10
            });
        });
        $(function () {
            $(".progressBar-design").progressbar({
                value: {$avg_customer.design_rate|round:1},
                max: 10
            });
        });
        $(function () {
            $(".progressBar-functionality_a").progressbar({
                value: {$avg_admin.functionality_rate|round:1},
                max: 10
            });
        });
        $(function () {
            $(".progressBar-functionality").progressbar({
                value: {$avg_customer.func_rate|round:1},
                max: 10
            });
        });
        $(function () {
            $(".progressBar-quality_a").progressbar({
                value: {$avg_admin.quality_rate|round:1},
                max: 10
            });
        });
        $(function () {
            $(".progressBar-quality").progressbar({
                value: {$avg_customer.quality_rate|round:1},
                max: 10
            });
        });
    });
</script>