<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation ?</title>
</head>
<body>
    <form action="{{ $route }}" id="form" method="post">
        <fieldset>
            <legend>Order Confirmation</legend>
            Please enter last four digits of {{ substr($mobile, 0, 2) }}xxx{{ substr($mobile, 5, 1) }}<input type="text" name="digit" onkeyup="if(this.value.toString().length == 4) document.getElementById('form').submit();" />
        </fieldset>
    </form>

</body>
</html>