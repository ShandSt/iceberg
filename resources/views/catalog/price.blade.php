<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: DejaVu Sans;
        }
        h1, h2 {
            text-align: center;
        }
        img {
            max-width: 100px;
            max-height: 100px;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
            white-space: nowrap;
        }
        .product-image {
            width: 100px;
            margin-right: 15px;
        }
        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: separate;
        }
        td, th {
            padding: 8px;
            outline: none;
            margin: 0;
        }
        td {
            border-bottom: 1px solid #0392CC;
            border-top: 1px solid #0392CC;
        }
        tr:last-child > td {
            border-bottom: 2px solid #0392CC;
        }
        th {
            padding: 16px 8px;
            border-bottom: 1px solid #0392CC;
        }
        .category {
            background-color: #0392CC;
            color: #ffffff;
        }
        .old-price {
            text-decoration: line-through;
        }
    </style>
</head>
<body>
<h1>Айсберг</h1>
<h2>Прайс на: {{ $date }}</h2>
<table>
    <thead>
    <tr>
        <th colspan="2" class="text-left">Наименование</th>
        <th class="text-right">Цена</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr class="category">
            <td colspan="3">{{ $category->name }}</td>
        </tr>
        @foreach($category->products as $product)
            <tr>
                <td class="product-image text-center">
                    <img src="{{ asset($product->smallPhoto) }}">
                </td>
                <td class="text-left">{{ $product->name }}</td>
                <td class="text-right">
                    @if ($product->old_price && $product->old_price > $product->price)
                        <span class="old-price">{{ number_format($product->old_price, 0, '', '') }} руб.</span>
                        <span class="new-price">{{ number_format($product->price, 0, '', '') }} руб.</span>
                    @else
                        {{ number_format($product->price, 0, '', '') }} руб.
                    @endif
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
</body>
</html>
