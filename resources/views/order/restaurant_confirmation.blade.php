<!DOCTYPE html>
<html>
<head>
    <meta content="width=device-width,initial-scale=1" name=viewport>
    <title>Order Confirmation ?</title>
    <style>
        .myBtn{
            padding: 20px;
            width: 100%;
            height: 100%;
            border: 5px solid #ccc;
            border-radius: 10%;
            background: #348F50; /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #348F50 , #56B4D3); /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #348F50 , #56B4D3); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }

        #form{
            width: 30%;
            height: 20%;
            margin: 40% 40%;
        }

        @media(max-width: 768px)
        {
            #form{
                width: 100%;
                height: 20%;
                margin: 40% 0;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
</head>
<body onload="init()">
    <center>
    <form style="" action="{{ $route }}" id="form" method="post">
        <fieldset>
            <legend>Order Confirmation</legend>
            <input id="myBtn" type="submit" value="Confirm Order!"  class="myBtn" />
        </fieldset>
    </form>
    </center>
</body>
<script>
    function init()
    {
        document.getElementById('myBtn').classList.add('tada');
    }
</script>
</html>