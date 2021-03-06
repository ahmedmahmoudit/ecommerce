<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>ITC Promotions</title>
<link id="" rel="shortcut icon" href="/images/favicon.ico" />


<!-- Google Font face -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet" />
<!-- Bootstrap -->
<link rel="stylesheet" href="/css/bootstrap.css" />
<!-- SLick Slider -->
<link rel="stylesheet" href="/css/slick.css" />
<link rel="stylesheet" href="/css/slick-theme.css"/>
<!-- Font face -->
<link rel="stylesheet" href="/css/fonts.css" />
<!-- custom input -->
<link rel="stylesheet" href="/css/custom-input.css" />

<!-- select2 -->
<link rel="stylesheet" href="/css/select2.css">

<!-- rating style -->
<link rel="stylesheet" href="/css/rateit.css">

<!-- custom input css arabic  -->
{{--<link rel="stylesheet" href="/css/custom-input-ar.css">--}}

<!-- custom style -->
<link rel="stylesheet" href="/css/style.css" />
<link rel="stylesheet" href="/css/custom-dev-style.css"/>

<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<style>
    .product-listing .prod-bx .data{
        position: relative;
        /* overflow: hidden; */
    }
    .product-listing .prod-bx strong.sku{
        font-size: 12px;
        position: absolute;
        top: 0px;
        left: 0px;
        right: 0;
        background: rgba(249, 156, 28);
        padding: 5px;
        color: #fff;
        box-shadow:0px -5px 10px rgba(0, 0, 0, 0.35);
        transform: translateY(0px);
        transition: 0.5s;
        /* display: none; */
        opacity: 0;
        text-align: center;
    }
    .product-listing .prod-bx:hover strong.sku{
        transform: translateY(-35px);
        transition: 0.2s;
        /* display: block; */
        opacity: 1;
    }
</style>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
