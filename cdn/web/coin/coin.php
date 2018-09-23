
<html>
    <head>
        <title>Đồng tiền ảo</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            table{
                margin: 0 auto;
                border-right: 1px solid #ccc;
            }
            table tr td, table tr th{
                border-bottom: 1px solid #ccc;
                margin:0;
                padding: 5px 10px;
                border-left: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th>Rank</th>
                <th>Coin</th>
                <th>Mã</th>
                <th>Giá VND</th>
                <th>Giá USD</th>
                <th>24H%</th>
                <th>M.CAP(USD)</th>
                <th>CIRCULATING SUPPLY</th>
               
                <th>CHART 7D</th>
            </tr>
            <tbody class="list">
                <?php include 'data.php'; ?>
            </tbody>
        </table>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        window.setInterval(function () {
            $.ajax({
                url: 'http://cdn.giataivuon.com/coin/data.php',
                type: 'get',
                success: function (data) {
                    $('.list').html(data);
                }

            });
        }, 3000);
    </script>
</html>