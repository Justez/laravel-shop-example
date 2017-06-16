<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        ul {
          list-style-type: none;
          margin-left: auto;
        }
        .container {
          margin-left: 10%;
        }
        .img-holder {
          width: 100%;
          max-height: 180px;
        }
        .img-container {
          max-width:100%;
          height:180px;
          margin:auto;
          display:block;
        }
        .thumbnail {
          width: 270px;
          height: 350px;
        }
        .cart-sidebar {
          border-color: lightgrey;
          border-width: thin;
          width: auto;
          height: auto;
        }
        .sidebar {
          border-color: lightgrey ;
          border-width: thin;
          margin-right: 10%;
          height: auto;
          margin-top: 3%;
        }
    </style>
</head>
<body>
    <div class="navbar navbar-inverse nav">
        <div class="navbar-inner">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="/">Store</a>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li class="divider-vertical"></li>
                        <!--<li><a href="/"><i class="icon-home icon-white"></i> Product List</a></li>-->
                    </ul>
                    <div class="pull-right">
                        <ul class="nav pull-right">
                            @if(!Auth::check())
                            <li><a href="/register">Sign Up<strong class="caret"></strong></a></li>
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">Sign In <strong class="caret"></strong></a>
                                    <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                                        <p>Please Login</a>
                                        <form action="/login" method="post" accept-charset="UTF-8">
                                            <input id="email" style="margin-bottom: 15px;" type="text" name="email" size="30" placeholder="email" />
                                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                            <input id="password" style="margin-bottom: 15px;" type="password" name="password" size="30" />
                                            <input class="btn btn-info" style="clear: left; width: 100%; height: 32px; font-size: 13px;" type="submit" name="commit" value="Sign In" />
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            @else
                                <li><a href="/checkout"><i class="icon-shopping-cart icon-white"></i> Items in cart: {{count(Session::get('cart'))}}</a></li>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{Auth::user()->name}} <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/orders"><i class="icon-envelope"></i> My Orders</a></li>
                                        <li class="divider"></li>
                                        <li><a href="/logout"><i class="icon-off"></i> Logout</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
        </div>
    </div>



    

    @yield('content')

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    $(function() {
        $('.dropdown-toggle').dropdown();
        $('.dropdown input, .dropdown label').click(function(e) {
            e.stopPropagation();
        });
    });
    @if(isset($error))
        alert("{{$error}}");
    @endif
    @if(Session::has('error'))
        alert("{{Session::get('error')}}");
    @endif
    @if(Session::has('message'))
        alert("{{Session::get('message')}}");
    @endif
    </script>
</body>
</html>
