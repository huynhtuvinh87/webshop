<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$url = 'https://www.vietcombank.com.vn/ExchangeRates/ExrateXML.aspx';
$xml = simpleXML_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);
$data = (array) $xml;
$array = (array) $data['Exrate'][18];
$usd = $array['@attributes']['Sell'];
$json = file_get_contents("https://api.coinmarketcap.com/v2/ticker?limit=50");
$data = json_decode($json, TRUE);
foreach ($data['data'] as $value) {
    $url = 'https://s2.coinmarketcap.com/static/img/coins/16x16/' . $value['id'] . '.png';
    //save_image($url, 'logo_' . $value['id'] . '.png');
    $url_chart = 'https://s2.coinmarketcap.com/generated/sparklines/web/7d/usd/' . $value['id'] . '.png';
    save_image($url_chart, 'chart_' . $value['id'] . '.png');
    ?> 

    <tr>
        <td><?= $value['rank'] ?></td>
        <td>
            <img src="http://cdn.giataivuon.com/coin/logo_<?= $value['id'] ?>.png">
            <?= $value['name'] ?>
        </td>
        <td><?= $value['symbol'] ?></td>
        <td><?= number_format($value['quotes']['USD']['price'] * $usd, 0, '', '.') ?></td>
        <td><?= $value['quotes']['USD']['price'] ?></td>
        <td>
            <p class="<?= $value['quotes']['USD']['percent_change_24h'] > 0 ? "text-primary" : "text-danger" ?>"><?= $value['quotes']['USD']['percent_change_24h'] ?>%</p>
        </td>
        <td><?= number_format($value['quotes']['USD']['market_cap'], 0, '', '.') ?></td>
        <td><?= number_format($value['circulating_supply'], 0, '', '.') ?></td>

        <td><img src="http://cdn.giataivuon.com/coin/chart_<?= $value['id'] ?>.png"></td>
    </tr>
    <?php
}

function save_image($inPath, $outPath) {
    $in = fopen($inPath, "rb");
    $out = fopen($outPath, "wb");
    while ($chunk = fread($in, 8192)) {
        fwrite($out, $chunk, 8192);
    }
    fclose($in);
    fclose($out);
}
?>