

<table class="table table-striped">
    <tr>
        <td>Название</td>
        <td>{{ $product->title }}</td>
    </tr>
    <tr>
        <td>Артикул</td>
        <td>{{ $product->vendor_code }}</td>
    </tr>
    <tr>
        <td>Цена</td>
        <td>{{ $product->price }}</td>
    </tr>
    <tr>
        <td>Цена со скидкой</td>
        <td>{{ $product->price_with_discount }}</td>
    </tr>
    <tr>
        <td>Дата начала скидки</td>
        <td>{{ $product->discount_start ? $product->discount_start->format('d.m.Y') : null  }}</td>
    </tr>
    <tr>
        <td>Дата конца скидки</td>
        <td>{{ $product->discount_finish ? $product->discount_finish->format('d.m.Y') : null }}</td>
    </tr>
    <tr>
        <td>Описание скидки</td>
        <td>{{ $product->discount_type }}</td>
    </tr>
    <tr>
        <td>Скидка</td>
        <td>{{ $product->discount }}</td>
    </tr>
    <tr>
        <td>Торговая марка</td>
        <td>{{ $product->trademark }}</td>
    </tr>
    <tr>
        <td>Масса</td>
        <td>{{ $product->weight }}</td>
    </tr>
    <tr>
        <td>Единица измерения</td>
        <td>{{ $product->unit }}</td>
    </tr>
    <tr>
        <td>Состав</td>
        <td>{{ $product->structure }}</td>
    </tr>
    <tr>
        <td>Белки</td>
        <td>{{ $product->proteins }}</td>
    </tr>
    <tr>
        <td>Углеводы</td>
        <td>{{ $product->carbohydrates }}</td>
    </tr>
    <tr>
        <td>Жиры</td>
        <td>{{ $product->fats }}</td>
    </tr>
    <tr>
        <td>Энергетическая ценность на 100 грамм</td>
        <td>{{ $product->calories }}</td>
    </tr>
    <tr>
        <td>Магазин</td>
        <td>{{ $product->shop->title }}</td>
    </tr>
    <tr>
        <td>Описание</td>
        <td>{{ $product->description }}</td>
    </tr>
</table>